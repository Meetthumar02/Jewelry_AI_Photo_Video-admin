<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShootType;
use Illuminate\Http\Request;

class ShootTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = ShootType::query();

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

        $shootTypes = $query->orderBy('name')->paginate(10);

        return view('creative-ai.shoot-types.index', compact('shootTypes'));
    }

    public function create()
    {
        return view('creative-ai.shoot-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        ShootType::create([
            'name' => $request->name,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.creative-ai.shoot-types.index')
            ->with('success', 'Shoot Type created successfully.');
    }

    public function edit(ShootType $shootType)
    {
        return view('creative-ai.shoot-types.edit', compact('shootType'));
    }

    public function update(Request $request, ShootType $shootType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        $shootType->update([
            'name' => $request->name,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.creative-ai.shoot-types.index')
            ->with('success', 'Shoot Type updated successfully.');
    }

    public function destroy(ShootType $shootType)
    {
        $shootType->delete();

        return redirect()->route('admin.creative-ai.shoot-types.index')
            ->with('success', 'Shoot Type deleted successfully.');
    }
}
