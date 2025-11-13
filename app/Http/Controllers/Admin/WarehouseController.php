<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of warehouses with optional search and status filters
     */
    public function index(Request $request)
    {
        $query = Warehouse::query();

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Status filter: active (default) or archived
        if ($request->status === 'archived') {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }

        $perPage = $request->get('per_page', 10);
        $warehouses = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->query());

        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Store a newly created warehouse
     */
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
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * Update the specified warehouse
     */
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

    /**
     * Archive a warehouse (soft delete)
     */
    public function archive($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->archive(); // Calls a model method for clarity

        return response()->json(['success' => true]);
    }

    /**
     * Unarchive a warehouse
     */
    public function unarchive($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->unarchive(); // Calls a model method for clarity

        return response()->json(['success' => true]);
    }

    /**
     * Permanently delete a warehouse
     */
    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return response()->json(['success' => true]);
    }
}
