<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StyleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Style::query();

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

        $styles = $query->paginate(10)->withQueryString();

        return view('creative-ai.styles.index', compact('styles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('creative-ai.styles.create');
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
                if (!Storage::disk('public')->exists('styles')) {
                    Storage::disk('public')->makeDirectory('styles');
                }
                $imagePath = $request->file('image')->store('styles', 'public');
                $validated['image'] = $imagePath;
            }

            // Handle status checkbox (if not present, default to false)
            $validated['status'] = $request->has('status') && ($request->status == 'on' || $request->status == '1') ? true : false;
            
            // Set default sort_order if not provided
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            Style::create($validated);

            return redirect()->route('admin.creative-ai.styles.index')
                ->with('success', 'Style created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create style: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Style $style)
    {
        return view('creative-ai.styles.show', compact('style'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Style $style)
    {
        return view('creative-ai.styles.edit', compact('style'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Style $style)
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
                if (!Storage::disk('public')->exists('styles')) {
                    Storage::disk('public')->makeDirectory('styles');
                }
                // Delete old image if exists
                if ($style->image && Storage::disk('public')->exists($style->image)) {
                    Storage::disk('public')->delete($style->image);
                }
                $imagePath = $request->file('image')->store('styles', 'public');
                $validated['image'] = $imagePath;
            }

            // Handle status checkbox
            $validated['status'] = $request->has('status') && $request->status == 'on' ? true : false;
            
            // Set default sort_order if not provided
            $validated['sort_order'] = $validated['sort_order'] ?? $style->sort_order;

            $style->update($validated);

            return redirect()->route('admin.creative-ai.styles.index')
                ->with('success', 'Style updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update style: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Style $style)
    {
        // Delete image if exists
        if ($style->image && Storage::disk('public')->exists($style->image)) {
            Storage::disk('public')->delete($style->image);
        }

        $style->delete();

        return redirect()->route('admin.creative-ai.styles.index')
            ->with('success', 'Style deleted successfully.');
    }
}
