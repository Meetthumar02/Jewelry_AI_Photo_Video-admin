<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function index(Request $request)
    {
        $query = Industry::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', true);
            } elseif ($request->status == 'inactive') {
                $query->where('status', false);
            }
        }

        $industries = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('creative-ai.industries.index', compact('industries'));
    }

    public function create()
    {
        return view('creative-ai.industries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        Industry::create([
            'name' => $request->name,
            'status' => $request->has('status'),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Industry created successfully.']);
        }

        return redirect()->route('admin.creative-ai.industries.index')
            ->with('success', 'Industry created successfully.');
    }

    public function edit(Industry $industry)
    {
        return view('creative-ai.industries.edit', compact('industry'));
    }

    public function update(Request $request, Industry $industry)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        $industry->update([
            'name' => $request->name,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.creative-ai.industries.index')
            ->with('success', 'Industry updated successfully.');
    }

    public function destroy(Industry $industry)
    {
        $industry->delete();

        return redirect()->route('admin.creative-ai.industries.index')
            ->with('success', 'Industry deleted successfully.');
    }
}
