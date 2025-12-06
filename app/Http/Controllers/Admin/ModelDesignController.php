<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
                $file = $request->file('image');
                $originalName = $file->getClientOriginalName();
                $modelDesignName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $validated['name']);
                
                // Create directory path: public/upload/Model Design/Model Design Name/
                $uploadPath = public_path('upload/Model Design/' . $modelDesignName);
                
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

            $oldModelDesignName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $modelDesign->name);
            $newModelDesignName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $validated['name']);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $originalName = $file->getClientOriginalName();
                
                // Delete old image if exists
                if ($modelDesign->image) {
                    $oldImagePath = public_path('upload/Model Design/' . $oldModelDesignName . '/' . $modelDesign->image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
                
                // Create directory path: public/upload/Model Design/Model Design Name/
                $uploadPath = public_path('upload/Model Design/' . $newModelDesignName);
                
                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                // Move file to the new location
                $file->move($uploadPath, $originalName);
                
                // Store only the original filename in database
                $validated['image'] = $originalName;
            } elseif ($modelDesign->image && $oldModelDesignName != $newModelDesignName) {
                // Name changed but no new image - move existing image to new directory
                $oldImagePath = public_path('upload/Model Design/' . $oldModelDesignName . '/' . $modelDesign->image);
                $newImagePath = public_path('upload/Model Design/' . $newModelDesignName);
                
                if (File::exists($oldImagePath)) {
                    // Create new directory if it doesn't exist
                    if (!File::exists($newImagePath)) {
                        File::makeDirectory($newImagePath, 0755, true);
                    }
                    
                    // Move image to new location
                    File::move($oldImagePath, $newImagePath . '/' . $modelDesign->image);
                    
                    // Delete old directory if empty
                    if (File::exists(public_path('upload/Model Design/' . $oldModelDesignName)) && count(File::files(public_path('upload/Model Design/' . $oldModelDesignName))) == 0) {
                        File::deleteDirectory(public_path('upload/Model Design/' . $oldModelDesignName));
                    }
                }
                // Image name stays the same in database
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
        if ($modelDesign->image) {
            $modelDesignName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $modelDesign->name);
            $imagePath = public_path('upload/Model Design/' . $modelDesignName . '/' . $modelDesign->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            
            // Delete directory if empty
            $modelDesignDir = public_path('upload/Model Design/' . $modelDesignName);
            if (File::exists($modelDesignDir) && count(File::files($modelDesignDir)) == 0) {
                File::deleteDirectory($modelDesignDir);
            }
        }

        $modelDesign->delete();

        return redirect()->route('admin.creative-ai.model-designs.index')
            ->with('success', 'Model Design deleted successfully.');
    }
}
