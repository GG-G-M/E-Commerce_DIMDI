<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockChecker;
use Illuminate\Http\Request;

class StockCheckerController extends Controller
{
    /**
     * Display a listing of stock checkers with optional search and status filters
     */
    public function index(Request $request)
    {
        $query = StockChecker::query();

        // Search by first name, middle name, last name, or contact
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('firstname', 'like', "%{$request->search}%")
                  ->orWhere('middlename', 'like', "%{$request->search}%")
                  ->orWhere('lastname', 'like', "%{$request->search}%")
                  ->orWhere('contact', 'like', "%{$request->search}%");
            });
        }

        // Status filter: active (default) or archived
        if ($request->status === 'archived') {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }

        $perPage = $request->get('per_page', 10);
        $stockCheckers = $query->orderBy('created_at', 'desc')
                               ->paginate($perPage)
                               ->appends($request->query());

        return view('admin.stock_checkers.index', compact('stockCheckers'));
    }

    /**
     * Store a newly created stock checker
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname'  => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname'   => 'required|string|max:255',
            'contact'    => 'nullable|string|max:50',
            'address'    => 'nullable|string|max:255',
        ]);

        $checker = StockChecker::create([
            'firstname'   => $request->firstname,
            'middlename'  => $request->middlename,
            'lastname'    => $request->lastname,
            'contact'     => $request->contact,
            'address'     => $request->address,
            'is_archived' => false,
        ]);

        return response()->json([
            'success' => true,
            'checker' => $checker,
        ]);
    }

    /**
     * Update the specified stock checker
     */
    public function update(Request $request, $id)
    {
        $checker = StockChecker::findOrFail($id);

        $request->validate([
            'firstname'  => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname'   => 'required|string|max:255',
            'contact'    => 'nullable|string|max:50',
            'address'    => 'nullable|string|max:255',
        ]);

        $checker->update([
            'firstname'  => $request->firstname,
            'middlename' => $request->middlename,
            'lastname'   => $request->lastname,
            'contact'    => $request->contact,
            'address'    => $request->address,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Archive a stock checker
     */
    public function archive($id)
    {
        $checker = StockChecker::findOrFail($id);
        $checker->update(['is_archived' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Unarchive a stock checker
     */
    public function unarchive($id)
    {
        $checker = StockChecker::findOrFail($id);
        $checker->update(['is_archived' => false]);

        return response()->json(['success' => true]);
    }

    /**
     * Permanently delete a stock checker
     */
    public function destroy($id)
    {
        $checker = StockChecker::findOrFail($id);
        $checker->delete();

        return response()->json(['success' => true]);
    }
}
