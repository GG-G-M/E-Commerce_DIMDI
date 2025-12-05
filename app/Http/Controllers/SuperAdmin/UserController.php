<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Add this import
use Illuminate\Support\Facades\Log; // Add this import

class UserController extends Controller
{
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
    public function store(Request $request)
    {
        // Check if user is super admin
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        
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

        // Start database transaction to ensure both inserts succeed or fail together
        DB::beginTransaction();
        
        try {
            // 1. Create user in users table
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
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            // 2. If role is delivery, ALSO create entry in deliveries table
            if ($validated['role'] === 'delivery') {
                // Check if email already exists in deliveries table
                $existingDelivery = DB::table('deliveries')->where('email', $user->email)->first();
                
                if ($existingDelivery) {
                    // Update existing entry
                    DB::table('deliveries')
                        ->where('id', $existingDelivery->id)
                        ->update([
                            'name' => $user->first_name . ' ' . $user->last_name,
                            'phone' => $user->phone,
                            'vehicle_type' => $user->vehicle_type,
                            'vehicle_number' => $user->vehicle_number,
                            'license_number' => $user->license_number,
                            'password' => $user->password, // Same password hash
                            'is_active' => $user->is_active,
                            'updated_at' => now(),
                        ]);
                    
                    Log::info("Updated existing deliveries table entry for: {$user->email}");
                } else {
                    // Create new entry
                    DB::table('deliveries')->insert([
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'vehicle_type' => $user->vehicle_type,
                        'vehicle_number' => $user->vehicle_number,
                        'license_number' => $user->license_number,
                        'password' => $user->password, // Same password hash
                        'is_active' => $user->is_active,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    Log::info("Created deliveries table entry for new delivery staff: {$user->email}");
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('superadmin.users.index')
                ->with('success', 'User created successfully!');
                
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            
            Log::error('Error creating user: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user.
     */
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
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Start transaction for consistency
        DB::beginTransaction();
        
        try {
            // Update user in users table
            $user->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role' => $request->role,
                'phone' => $request->phone,
                'is_active' => $request->boolean('is_active'),
                'vehicle_type' => $request->vehicle_type,
                'vehicle_number' => $request->vehicle_number,
                'license_number' => $request->license_number,
            ]);

            // If role is delivery, also update deliveries table
            if ($request->role === 'delivery') {
                $existingDelivery = DB::table('deliveries')->where('email', $user->email)->first();
                
                if ($existingDelivery) {
                    DB::table('deliveries')
                        ->where('id', $existingDelivery->id)
                        ->update([
                            'name' => $user->first_name . ' ' . $user->last_name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'vehicle_type' => $user->vehicle_type,
                            'vehicle_number' => $user->vehicle_number,
                            'license_number' => $user->license_number,
                            'is_active' => $user->is_active,
                            'updated_at' => now(),
                        ]);
                } else {
                    // Create entry if it doesn't exist
                    DB::table('deliveries')->insert([
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'vehicle_type' => $user->vehicle_type,
                        'vehicle_number' => $user->vehicle_number,
                        'license_number' => $user->license_number,
                        'password' => $user->password,
                        'is_active' => $user->is_active,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // If role changed from delivery to something else, remove from deliveries table
                DB::table('deliveries')->where('email', $user->email)->delete();
            }

            DB::commit();

            return redirect()->route('superadmin.users.index')
                ->with('success', 'User updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating user: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating user: ' . $e->getMessage());
        }
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

        // Start transaction
        DB::beginTransaction();
        
        try {
            // Also delete from deliveries table if exists
            DB::table('deliveries')->where('email', $user->email)->delete();
            
            // Delete user
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('superadmin.users.index')
                ->with('success', 'User deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
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

        // Start transaction
        DB::beginTransaction();
        
        try {
            $newPassword = Hash::make($request->password);
            
            // Update password in users table
            $user->update([
                'password' => $newPassword,
            ]);

            // Also update in deliveries table if exists
            DB::table('deliveries')
                ->where('email', $user->email)
                ->update([
                    'password' => $newPassword,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Password reset successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error resetting password: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error resetting password: ' . $e->getMessage());
        }
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

        // Start transaction
        DB::beginTransaction();
        
        try {
            $newStatus = !$user->is_active;
            
            // Update status in users table
            $user->update([
                'is_active' => $newStatus,
            ]);

            // Also update in deliveries table if exists
            DB::table('deliveries')
                ->where('email', $user->email)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);

            DB::commit();

            $status = $newStatus ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "User {$status} successfully!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error toggling user status: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error updating user status: ' . $e->getMessage());
        }
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
        
        // Get users to activate
        $users = User::whereIn('id', $userIds)->get();
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            foreach ($users as $user) {
                // Update user
                $user->update(['is_active' => true]);
                
                // Also update deliveries table if exists
                DB::table('deliveries')
                    ->where('email', $user->email)
                    ->update([
                        'is_active' => true,
                        'updated_at' => now(),
                    ]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Selected users activated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error bulk activating users: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error activating users: ' . $e->getMessage());
        }
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
        
        // Get users to deactivate
        $users = User::whereIn('id', $userIds)->get();
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            foreach ($users as $user) {
                // Update user
                $user->update(['is_active' => false]);
                
                // Also update deliveries table if exists
                DB::table('deliveries')
                    ->where('email', $user->email)
                    ->update([
                        'is_active' => false,
                        'updated_at' => now(),
                    ]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Selected users deactivated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error bulk deactivating users: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error deactivating users: ' . $e->getMessage());
        }
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
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            foreach ($users as $user) {
                // Delete from deliveries table first
                DB::table('deliveries')->where('email', $user->email)->delete();
                
                // Delete user
                $user->delete();
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', "{$count} users deleted successfully!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error bulk deleting users: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error deleting users: ' . $e->getMessage());
        }
    }
}