<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelDesignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ModelDesign::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', true);
            } elseif ($request->status == 'inactive') {
                $query->where('status', false);
            }
        }

        // Sort
        if ($request->filled('sort')) {
            if ($request->sort == 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort == 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($request->sort == 'name_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($request->sort == 'name_desc') {
                $query->orderBy('name', 'desc');
            }
        } else {
            $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
        }

        $modelDesigns = $query->paginate(10)->withQueryString();

        return view('creative-ai.model-designs.index', compact('modelDesigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('creative-ai.model-designs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Ensure storage directory exists
                if (!Storage::disk('public')->exists('model-designs')) {
                    Storage::disk('public')->makeDirectory('model-designs');
                }
                $imagePath = $request->file('image')->store('model-designs', 'public');
                $validated['image'] = $imagePath;
            }

            // Handle status checkbox (if not present, default to false)
            $validated['status'] = $request->has('status') && ($request->status == 'on' || $request->status == '1') ? true : false;
            
            // Set default sort_order if not provided
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            ModelDesign::create($validated);

            return redirect()->route('admin.creative-ai.model-designs.index')
                ->with('success', 'Model Design created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create model design: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ModelDesign $modelDesign)
    {
        return view('creative-ai.model-designs.show', compact('modelDesign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModelDesign $modelDesign)
    {
        return view('creative-ai.model-designs.edit', compact('modelDesign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModelDesign $modelDesign)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Ensure storage directory exists
                if (!Storage::disk('public')->exists('model-designs')) {
                    Storage::disk('public')->makeDirectory('model-designs');
                }
                // Delete old image if exists
                if ($modelDesign->image && Storage::disk('public')->exists($modelDesign->image)) {
                    Storage::disk('public')->delete($modelDesign->image);
                }
                $imagePath = $request->file('image')->store('model-designs', 'public');
                $validated['image'] = $imagePath;
            }

            // Handle status checkbox
            $validated['status'] = $request->has('status') && $request->status == 'on' ? true : false;
            
            // Set default sort_order if not provided
            $validated['sort_order'] = $validated['sort_order'] ?? $modelDesign->sort_order;

            $modelDesign->update($validated);

            return redirect()->route('admin.creative-ai.model-designs.index')
                ->with('success', 'Model Design updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update model design: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModelDesign $modelDesign)
    {
        // Delete image if exists
        if ($modelDesign->image && Storage::disk('public')->exists($modelDesign->image)) {
            Storage::disk('public')->delete($modelDesign->image);
        }

        $modelDesign->delete();

        return redirect()->route('admin.creative-ai.model-designs.index')
            ->with('success', 'Model Design deleted successfully.');
    }
}
