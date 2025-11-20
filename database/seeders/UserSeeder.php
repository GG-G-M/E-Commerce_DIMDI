<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create or Update Admin User
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'phone' => '0912-345-6789',
                'address' => 'Admin Building',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
                'country' => 'Philippines',
                'is_active' => true,
            ]
        );

        // Create or Update Delivery User
        User::updateOrCreate(
            ['email' => 'delivery@example.com'],
            [
                'first_name' => 'Juan',
                'last_name' => 'Delivery',
                'password' => Hash::make('12345678'),
                'role' => 'delivery',
                'phone' => '0917-123-4567',
                'address' => '123 Delivery Street',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
                'country' => 'Philippines',
                'vehicle_type' => 'Motorcycle',
                'vehicle_number' => 'MC-1234',
                'license_number' => 'DL-123456',
                'is_active' => true,
            ]
        );

        // Create or Update Your Customer User
        User::updateOrCreate(
            ['email' => 'gilgregenemantilla@gmail.com'],
            [
                'first_name' => 'Gilgre Gene',
                'middle_name' => 'G',
                'last_name' => 'Mantilla',
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'phone' => '123-456-7890',
                'address' => 'Bankal Skibidi',
                'city' => 'Davao City',
                'state' => 'Davao State',
                'zip_code' => '12345',
                'country' => 'Philippines',
                'is_active' => true,
            ]
        );

        $this->command->info('Users created/updated successfully!');
        $this->command->info('Admin: admin@example.com / 12345678');
        $this->command->info('Delivery: delivery@example.com / 12345678');
        $this->command->info('Customer: gilgregenemantilla@gmail.com / 12345678');
    }
}