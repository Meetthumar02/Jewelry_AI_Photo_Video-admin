<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Industry;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()->with('industry');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('industry_id')) {
            $query->where('industry_id', $request->industry_id);
        }

        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', true);
            } elseif ($request->status == 'inactive') {
                $query->where('status', false);
            }
        }

        $categories = $query->orderBy('name')->paginate(10)->withQueryString();
        $industries = Industry::where('status', true)->orderBy('name')->get();

        return view('creative-ai.categories.index', compact('categories', 'industries'));
    }

    public function create()
    {
        $industries = Industry::where('status', true)->orderBy('name')->get();
        return view('creative-ai.categories.create', compact('industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'industry_id' => 'required|exists:industries,id',
            'name' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        Category::create([
            'industry_id' => $request->industry_id,
            'name' => $request->name,
            'status' => $request->has('status'),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Category created successfully.']);
        }

        return redirect()->route('admin.creative-ai.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $industries = Industry::where('status', true)->orderBy('name')->get();
        return view('creative-ai.categories.edit', compact('category', 'industries'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'industry_id' => 'required|exists:industries,id',
            'name' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        $category->update([
            'industry_id' => $request->industry_id,
            'name' => $request->name,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.creative-ai.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.creative-ai.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    // AJAX method to get categories by industry
    public function getByIndustry($industryId)
    {
        $categories = Category::where('industry_id', $industryId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }
}
