<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Remove the __construct() method entirely for now
    // We'll add authorization checks in each method
    
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        $roles = [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_DELIVERY => 'Delivery Staff',
            User::ROLE_CUSTOMER => 'Customer',
        ];
        
        // Add statistics
        $totalUsers = User::count();
        $adminCount = User::where('role', User::ROLE_ADMIN)->count();
        $deliveryCount = User::where('role', User::ROLE_DELIVERY)->count();
        $activeCount = User::where('is_active', true)->count();
        
        return view('superadmin.users.index', compact('users', 'roles', 'totalUsers', 'adminCount', 'deliveryCount', 'activeCount'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        $roles = [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_DELIVERY => 'Delivery Staff',
            User::ROLE_CUSTOMER => 'Customer',
        ];
        
        return view('superadmin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    // In your SuperAdmin UserController
public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string|in:super_admin,admin,delivery,customer',
        'phone' => 'nullable|string|max:20',
        
        // Address fields (optional)
        'street_address' => 'nullable|string|max:255',
        'barangay' => 'nullable|string|max:100',
        'city' => 'nullable|string|max:100',
        'province' => 'nullable|string|max:100',
        'region' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
        
        // Delivery specific fields
        'vehicle_type' => 'nullable|required_if:role,delivery|string|max:50',
        'vehicle_number' => 'nullable|required_if:role,delivery|string|max:50',
        'license_number' => 'nullable|required_if:role,delivery|string|max:50',
    ]);

    // Create user
    $user = User::create([
        'first_name' => $validated['first_name'],
        'middle_name' => $request->middle_name,
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'phone' => $validated['phone'],
        
        // Address fields
        'street_address' => $validated['street_address'] ?? null,
        'barangay' => $validated['barangay'] ?? null,
        'city' => $validated['city'] ?? null,
        'province' => $validated['province'] ?? null,
        'region' => $validated['region'] ?? null,
        'country' => $validated['country'] ?? null,
        
        // Delivery fields
        'vehicle_type' => $validated['vehicle_type'] ?? null,
        'vehicle_number' => $validated['vehicle_number'] ?? null,
        'license_number' => $validated['license_number'] ?? null,
        'is_active' => $request->has('is_active'),
    ]);

    return redirect()->route('superadmin.users.index')
        ->with('success', 'User created successfully!');
}
    public function show(User $user)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        return view('superadmin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        $roles = [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_DELIVERY => 'Delivery Staff',
            User::ROLE_CUSTOMER => 'Customer',
        ];
        
        return view('superadmin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        // Prevent editing super admins unless you're a super admin editing yourself or another super admin
        if ($user->isSuperAdmin() && auth()->user()->id !== $user->id) {
            return redirect()->back()->with('error', 'Cannot edit other super admin accounts.');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:' . implode(',', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN, User::ROLE_DELIVERY, User::ROLE_CUSTOMER]),
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        // Prevent deleting super admins or yourself
        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete super admin account.');
        }
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot delete your own account.');
        }

        $user->delete();
        
        return redirect()->route('superadmin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password reset successfully!');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot deactivate your own account.');
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully!");
    }

    /**
     * Bulk activate users.
     */
    public function bulkActivate(Request $request)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return redirect()->back()->with('error', 'No users selected.');
        }

        // Remove current user from the list
        $userIds = array_diff($userIds, [auth()->id()]);
        
        User::whereIn('id', $userIds)->update(['is_active' => true]);
        
        return redirect()->back()->with('success', 'Selected users activated successfully!');
    }

    /**
     * Bulk deactivate users.
     */
    public function bulkDeactivate(Request $request)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return redirect()->back()->with('error', 'No users selected.');
        }

        // Remove current user from the list
        $userIds = array_diff($userIds, [auth()->id()]);
        
        User::whereIn('id', $userIds)->update(['is_active' => false]);
        
        return redirect()->back()->with('success', 'Selected users deactivated successfully!');
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return redirect()->back()->with('error', 'No users selected.');
        }

        // Remove current user and super admins from the list
        $users = User::whereIn('id', $userIds)
            ->where('id', '!=', auth()->id())
            ->where('role', '!=', User::ROLE_SUPER_ADMIN)
            ->get();
        
        $count = $users->count();
        foreach ($users as $user) {
            $user->delete();
        }
        
        return redirect()->back()->with('success', "{$count} users deleted successfully!");
    }
}