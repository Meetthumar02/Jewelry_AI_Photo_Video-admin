<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Style;
use App\Models\Industry;
use App\Models\Category;
use App\Models\ProductType;
use Illuminate\Http\Request;

class StyleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Style::query()->with(['industry', 'category', 'productType']);

        // Search/Filter functionality
        if ($request->filled('industry_id')) {
            $query->where('industry_id', $request->industry_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        $styles = $query->latest()->paginate(10)->withQueryString();
        $industries = Industry::where('status', true)->orderBy('name')->get();

        return view('creative-ai.styles.index', compact('styles', 'industries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $industries = Industry::where('status', true)->orderBy('name')->get();
        return view('creative-ai.styles.create', compact('industries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'industry_id' => 'required|exists:industries,id',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
        ]);

        // Check if combination already exists
        $exists = Style::where('industry_id', $request->industry_id)
            ->where('category_id', $request->category_id)
            ->where('product_type_id', $request->product_type_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'This style combination already exists.']);
        }

        Style::create($request->all());

        return redirect()->route('admin.creative-ai.styles.index')
            ->with('success', 'Style configuration created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Style $style)
    {
        $style->load(['industry', 'category', 'productType']);
        return view('creative-ai.styles.show', compact('style'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Style $style)
    {
        $industries = Industry::where('status', true)->orderBy('name')->get();
        $categories = Category::where('industry_id', $style->industry_id)
            ->where('status', true)
            ->orderBy('name')
            ->get();
        $productTypes = ProductType::where('category_id', $style->category_id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('creative-ai.styles.edit', compact('style', 'industries', 'categories', 'productTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Style $style)
    {
        $request->validate([
            'industry_id' => 'required|exists:industries,id',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
        ]);

        // Check if combination already exists (excluding current)
        $exists = Style::where('industry_id', $request->industry_id)
            ->where('category_id', $request->category_id)
            ->where('product_type_id', $request->product_type_id)
            ->where('id', '!=', $style->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'This style combination already exists.']);
        }

        $style->update($request->all());

        return redirect()->route('admin.creative-ai.styles.index')
            ->with('success', 'Style configuration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Style $style)
    {
        $style->delete();

        return redirect()->route('admin.creative-ai.styles.index')
            ->with('success', 'Style configuration deleted successfully.');
    }
}
