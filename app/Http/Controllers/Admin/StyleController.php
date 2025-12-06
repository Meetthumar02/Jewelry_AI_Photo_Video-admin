<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Style;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
                $file = $request->file('image');
                $originalName = $file->getClientOriginalName();
                $styleName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $validated['name']);
                
                // Create directory path: public/upload/Style/Style Name/
                $uploadPath = public_path('upload/Style/' . $styleName);
                
                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                // Move file to the new location
                $file->move($uploadPath, $originalName);
                
                // Store only the original filename in database
                $validated['image'] = $originalName;
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

            $oldStyleName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $style->name);
            $newStyleName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $validated['name']);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $originalName = $file->getClientOriginalName();
                
                // Delete old image if exists
                if ($style->image) {
                    $oldImagePath = public_path('upload/Style/' . $oldStyleName . '/' . $style->image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
                
                // Create directory path: public/upload/Style/Style Name/
                $uploadPath = public_path('upload/Style/' . $newStyleName);
                
                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                // Move file to the new location
                $file->move($uploadPath, $originalName);
                
                // Store only the original filename in database
                $validated['image'] = $originalName;
            } elseif ($style->image && $oldStyleName != $newStyleName) {
                // Name changed but no new image - move existing image to new directory
                $oldImagePath = public_path('upload/Style/' . $oldStyleName . '/' . $style->image);
                $newImagePath = public_path('upload/Style/' . $newStyleName);
                
                if (File::exists($oldImagePath)) {
                    // Create new directory if it doesn't exist
                    if (!File::exists($newImagePath)) {
                        File::makeDirectory($newImagePath, 0755, true);
                    }
                    
                    // Move image to new location
                    File::move($oldImagePath, $newImagePath . '/' . $style->image);
                    
                    // Delete old directory if empty
                    if (File::exists(public_path('upload/Style/' . $oldStyleName)) && count(File::files(public_path('upload/Style/' . $oldStyleName))) == 0) {
                        File::deleteDirectory(public_path('upload/Style/' . $oldStyleName));
                    }
                }
                // Image name stays the same in database
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
        if ($style->image) {
            $styleName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $style->name);
            $imagePath = public_path('upload/Style/' . $styleName . '/' . $style->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            
            // Delete directory if empty
            $styleDir = public_path('upload/Style/' . $styleName);
            if (File::exists($styleDir) && count(File::files($styleDir)) == 0) {
                File::deleteDirectory($styleDir);
            }
        }

        $style->delete();

        return redirect()->route('admin.creative-ai.styles.index')
            ->with('success', 'Style deleted successfully.');
    }
}
