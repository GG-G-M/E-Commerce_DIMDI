<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Check if super admin already exists
        if (!User::where('email', 'superadmin@system.com')->exists()) {
            User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@system.com',
                'password' => Hash::make('SuperAdmin@123'), // Change this in production!
                'role' => User::ROLE_SUPER_ADMIN,
                'phone' => '0000000000',
                'is_active' => true,
            ]);
            $this->command->info('Super admin account created!');
            $this->command->info('Email: superadmin@system.com');
            $this->command->info('Password: SuperAdmin@123');
        }
    }
}