<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Style;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductType::query()->with('category.industry');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('industry_id')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('industry_id', $request->industry_id);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', true);
            } elseif ($request->status == 'inactive') {
                $query->where('status', false);
            }
        }

        $productTypes = $query->orderBy('name')->paginate(10)->withQueryString();
        $industries = Industry::where('status', true)->orderBy('name')->get();
        // Just empty collection for categories initially if no industry selected
        $categories = collect();

        if ($request->filled('industry_id')) {
            $categories = Category::where('industry_id', $request->industry_id)
                ->where('status', true)
                ->orderBy('name')
                ->get();
        }

        return view('creative-ai.product-types.index', compact('productTypes', 'industries', 'categories'));
    }

    public function create()
    {
        $industries = Industry::where('status', true)->orderBy('name')->get();
        return view('creative-ai.product-types.create', compact('industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        $productType = ProductType::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'status' => $request->has('status'),
        ]);

        // Automatically create the Style configuration
        $category = Category::find($request->category_id);
        Style::firstOrCreate([
            'industry_id' => $category->industry_id,
            'category_id' => $category->id,
            'product_type_id' => $productType->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Product Type created successfully.']);
        }

        return redirect()->route('admin.creative-ai.product-types.index')
            ->with('success', 'Product Type created successfully.');
    }

    public function edit(ProductType $productType)
    {
        $productType->load('category.industry');
        $industries = Industry::where('status', true)->orderBy('name')->get();
        $categories = Category::where('industry_id', $productType->category->industry_id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('creative-ai.product-types.edit', compact('productType', 'industries', 'categories'));
    }

    public function update(Request $request, ProductType $productType)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        $productType->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.creative-ai.product-types.index')
            ->with('success', 'Product Type updated successfully.');
    }

    public function destroy(ProductType $productType)
    {
        $productType->delete();

        return redirect()->route('admin.creative-ai.product-types.index')
            ->with('success', 'Product Type deleted successfully.');
    }

    // AJAX method to get product types by category
    public function getByCategory($categoryId)
    {
        $productTypes = ProductType::where('category_id', $categoryId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return response()->json($productTypes);
    }
}
