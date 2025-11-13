<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::query();

        // Search filter by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->status === 'archived') {
            $query->where('is_archived', true);
        } else {
            // Default or 'active'
            $query->where('is_archived', false);
        }

        $perPage = $request->get('per_page', 10);
        $warehouses = $query->paginate($perPage)->appends($request->query());

        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name',
        ]);

        $warehouse = Warehouse::create([
            'name' => $request->name,
            'is_archived' => false,
        ]);

        return response()->json([
            'success' => true,
            'warehouse' => $warehouse
        ]);
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name,' . $warehouse->id,
        ]);

        $warehouse->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true]);
    }

    public function archive($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->is_archived = true;
        $warehouse->save();

        return response()->json(['success' => true]);
    }

    public function unarchive($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->is_archived = false;
        $warehouse->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return response()->json(['success' => true]);
    }
}
