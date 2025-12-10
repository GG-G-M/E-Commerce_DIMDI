<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->orderBy('created_at', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->status === 'archived') {
            $query->where('is_archived', true);
        } elseif ($request->status === 'active') {
            // Default or 'active'
            $query->where('is_archived', false);
        }
        // If status is 'all' or not specified, don't apply status filter (show all)


        $perPage = $request->get('per_page', 10);
        $customers = $query->paginate($perPage)->appends($request->query());

        return view('admin.customers.index', compact('customers'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'role'        => 'customer',
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'phone'       => $request->phone,
            'street_address' => $request->street_address,
            'barangay'    => $request->barangay,
            'city'        => $request->city,
            'province'    => $request->province,
            'region'      => $request->region,
            'country'     => $request->country,
        ]);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'street_address' => 'nullable|string',
            'barangay' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'region' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json(['success' => true]);
    }

    public function archive($id)
    {
        $customer = User::findOrFail($id);
        $customer->is_archived = true;
        $customer->save();

        return response()->json(['success' => true]);
    }

    public function unarchive($id)
    {
        $customer = User::findOrFail($id);
        $customer->is_archived = false;
        $customer->save();

        return response()->json(['success' => true]);
    }

}
