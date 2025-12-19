<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // ------------------------------
        // Create Admin User
        // ------------------------------
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'G',
                'middle_name' => 'G',
                'last_name' => 'M',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'phone' => '123-456-7890',

                // ✅ Correct address fields
                'region' => 'Admin Region',
                'province' => 'Admin Province',
                'city' => 'Admin City',
                'barangay' => 'Admin Barangay',
                'street_address' => 'Admin Street Address',
                'country' => 'Admin Country',

                'is_archived' => false,
                'vehicle_type' => null,
                'vehicle_number' => null,
                'license_number' => null,
                'is_active' => true,
            ]
        );

        $this->command->info('Admin user created/updated successfully!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: 12345678');
    }
}
