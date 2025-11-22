<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers with optional search and status filters
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search by name or contact_person
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('contact_person', 'like', "%{$request->search}%");
            });
        }

        // Status filter: active (default) or archived
        if ($request->status === 'archived') {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }

        $perPage = $request->get('per_page', 10);
        $suppliers = $query->orderBy('created_at', 'desc')
                           ->paginate($perPage)
                           ->appends($request->query());

        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Store a newly created supplier
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255|unique:suppliers,name',
            'contact'        => 'nullable|string|max:50',
            'address'        => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create([
            'name'           => $request->name,
            'contact'        => $request->contact,
            'address'        => $request->address,
            'contact_person' => $request->contact_person,
            'is_archived'    => false,
        ]);

        return response()->json([
            'success'  => true,
            'supplier' => $supplier,
        ]);
    }

    /**
     * Update the specified supplier
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'contact'        => 'nullable|string|max:50',
            'address'        => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $supplier->update([
            'name'           => $request->name,
            'contact'        => $request->contact,
            'address'        => $request->address,
            'contact_person' => $request->contact_person,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Archive a supplier
     */
    public function archive($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update(['is_archived' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Unarchive a supplier
     */
    public function unarchive($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update(['is_archived' => false]);

        return response()->json(['success' => true]);
    }

    /**
     * Permanently delete a supplier
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json(['success' => true]);
    }
}
