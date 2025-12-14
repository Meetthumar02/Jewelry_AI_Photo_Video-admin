<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelDesign;
use App\Models\Industry;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\ShootType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelDesignController extends Controller
{
    public function index(Request $request)
    {
        $query = ModelDesign::query()->with(['industry', 'category', 'productType', 'shootType']);

        if ($request->filled('industry_id')) {
            $query->where('industry_id', $request->industry_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        if ($request->filled('shoot_type_id')) {
            $query->where('shoot_type_id', $request->shoot_type_id);
        }

        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', true);
            } elseif ($request->status == 'inactive') {
                $query->where('status', false);
            }
        }

        $modelDesigns = $query->latest()->paginate(12);
        $industries = Industry::where('status', true)->orderBy('name')->get();
        $shootTypes = ShootType::where('status', true)->orderBy('name')->get();

        $categories = collect();
        $productTypes = collect();

        if ($request->filled('industry_id')) {
            $categories = Category::where('industry_id', $request->industry_id)
                ->where('status', true)
                ->orderBy('name')
                ->get();
        }

        if ($request->filled('category_id')) {
            $productTypes = ProductType::where('category_id', $request->category_id)
                ->where('status', true)
                ->orderBy('name')
                ->get();
        }

        return view('creative-ai.model-designs.index', compact('modelDesigns', 'industries', 'categories', 'productTypes', 'shootTypes'));
    }

    public function create()
    {
        $industries = Industry::where('status', true)->orderBy('name')->get();
        $shootTypes = ShootType::where('status', true)->orderBy('name')->get();

        return view('creative-ai.model-designs.create', compact('industries', 'shootTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'industry_id' => 'required|exists:industries,id',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'shoot_type_id' => 'required|exists:shoot_types,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'nullable',
        ]);

        $imageRelativePath = null;
        if ($request->hasFile('image')) {
            // Fetch related names
            $industry = Industry::findOrFail($request->industry_id);
            $category = Category::findOrFail($request->category_id);
            $productType = ProductType::findOrFail($request->product_type_id);
            $shootType = ShootType::findOrFail($request->shoot_type_id);

            // Replace spaces with underscores
            $industryName = str_replace(' ', '_', $industry->name);
            $categoryName = str_replace(' ', '_', $category->name);
            $productTypeName = str_replace(' ', '_', $productType->name);
            $shootTypeName = str_replace(' ', '_', $shootType->name);

            // Create hierarchical directory path
            $directoryPath = "upload/{$industryName}/{$categoryName}/{$productTypeName}/{$shootTypeName}";
            $fullDirectoryPath = public_path($directoryPath);

            // Create directories if they don't exist
            if (!file_exists($fullDirectoryPath)) {
                mkdir($fullDirectoryPath, 0755, true);
            }

            // Generate unique filename
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move image to hierarchical directory
            $image->move($fullDirectoryPath, $imageName);

            // Store relative path in database
            $imageRelativePath = "{$directoryPath}/{$imageName}";
        }

        ModelDesign::create([
            'industry_id' => $request->industry_id,
            'category_id' => $request->category_id,
            'product_type_id' => $request->product_type_id,
            'shoot_type_id' => $request->shoot_type_id,
            'image' => $imageRelativePath,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.creative-ai.model-designs.index')
            ->with('success', 'Model Design created successfully.');
    }

    public function show(ModelDesign $modelDesign)
    {
        $modelDesign->load(['industry', 'category', 'productType', 'shootType']);
        return view('creative-ai.model-designs.show', compact('modelDesign'));
    }

    public function edit(ModelDesign $modelDesign)
    {
        $modelDesign->load(['industry', 'category', 'productType', 'shootType']);
        $industries = Industry::where('status', true)->orderBy('name')->get();
        $categories = Category::where('industry_id', $modelDesign->industry_id)
            ->where('status', true)
            ->orderBy('name')
            ->get();
        $productTypes = ProductType::where('category_id', $modelDesign->category_id)
            ->where('status', true)
            ->orderBy('name')
            ->get();
        $shootTypes = ShootType::where('status', true)->orderBy('name')->get();

        return view('creative-ai.model-designs.edit', compact('modelDesign', 'industries', 'categories', 'productTypes', 'shootTypes'));
    }

    public function update(Request $request, ModelDesign $modelDesign)
    {
        $request->validate([
            'industry_id' => 'required|exists:industries,id',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'shoot_type_id' => 'required|exists:shoot_types,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'nullable',
        ]);

        $imageRelativePath = $modelDesign->image;
        if ($request->hasFile('image')) {
            // Delete old image using the full stored path
            if ($modelDesign->image && file_exists(public_path($modelDesign->image))) {
                unlink(public_path($modelDesign->image));
            }

            // Fetch related names
            $industry = Industry::findOrFail($request->industry_id);
            $category = Category::findOrFail($request->category_id);
            $productType = ProductType::findOrFail($request->product_type_id);
            $shootType = ShootType::findOrFail($request->shoot_type_id);

            // Replace spaces with underscores
            $industryName = str_replace(' ', '_', $industry->name);
            $categoryName = str_replace(' ', '_', $category->name);
            $productTypeName = str_replace(' ', '_', $productType->name);
            $shootTypeName = str_replace(' ', '_', $shootType->name);

            // Create hierarchical directory path
            $directoryPath = "upload/{$industryName}/{$categoryName}/{$productTypeName}/{$shootTypeName}";
            $fullDirectoryPath = public_path($directoryPath);

            // Create directories if they don't exist
            if (!file_exists($fullDirectoryPath)) {
                mkdir($fullDirectoryPath, 0755, true);
            }

            // Generate unique filename
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move image to hierarchical directory
            $image->move($fullDirectoryPath, $imageName);

            // Store relative path in database
            $imageRelativePath = "{$directoryPath}/{$imageName}";
        }

        $modelDesign->update([
            'industry_id' => $request->industry_id,
            'category_id' => $request->category_id,
            'product_type_id' => $request->product_type_id,
            'shoot_type_id' => $request->shoot_type_id,
            'image' => $imageRelativePath,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.creative-ai.model-designs.index')
            ->with('success', 'Model Design updated successfully.');
    }

    public function destroy(ModelDesign $modelDesign)
    {
        // Delete image using the full stored path
        if ($modelDesign->image && file_exists(public_path($modelDesign->image))) {
            unlink(public_path($modelDesign->image));
        }

        $modelDesign->delete();

        return redirect()->route('admin.creative-ai.model-designs.index')
            ->with('success', 'Model Design deleted successfully.');
    }
}
