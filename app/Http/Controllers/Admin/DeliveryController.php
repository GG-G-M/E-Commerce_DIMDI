<?php
// app/Http\Controllers\Admin\DeliveryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DeliveryController extends Controller
{
    public function index()
    {
        // Change this line from get() to paginate()
        $deliveries = User::where('role', 'delivery')->latest()->paginate(10);
        return view('admin.deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        return view('admin.deliveries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_number' => $request->vehicle_number,
            'license_number' => $request->license_number,
            'password' => Hash::make($request->password),
            'role' => 'delivery', 
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Delivery person created successfully.');
    }

    public function show(User $delivery)
    {
        
        if ($delivery->role !== 'delivery') {
            abort(404);
        }
        
        return view('admin.deliveries.show', compact('delivery'));
    }

    public function edit(User $delivery)
    {
        if ($delivery->role !== 'delivery') {
            abort(404);
        }

        return view('admin.deliveries.edit', compact('delivery'));
    }

    public function update(Request $request, User $delivery)
    {
        if ($delivery->role !== 'delivery') {
            abort(404);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($delivery->id),
            ],
            'phone' => 'required|string|max:20',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_number' => $request->vehicle_number,
            'license_number' => $request->license_number,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $delivery->update($updateData);

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Delivery person updated successfully.');
    }

    public function destroy(User $delivery)
    {
        if ($delivery->role !== 'delivery') {
            abort(404);
        }

        $delivery->delete();

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Delivery person deleted successfully.');
    }

    public function toggleStatus(User $delivery)
    {
        if ($delivery->role !== 'delivery') {
            abort(404);
        }

        $delivery->update([
            'is_active' => !$delivery->is_active
        ]);

        $status = $delivery->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Delivery person {$status} successfully.");
    }
}