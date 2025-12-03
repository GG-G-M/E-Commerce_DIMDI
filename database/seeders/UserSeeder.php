<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ------------------------------
        // Create Delivery User
        // ------------------------------
        User::updateOrCreate(
            ['email' => 'delivery@example.com'],
            [
                'first_name' => 'Juan',
                'middle_name' => null,
                'last_name' => 'Delivery',
                'password' => Hash::make('12345678'),
                'role' => 'delivery',
                'phone' => '0917-123-4567',

                // ✅ Correct address fields
                'region' => 'Davao Region',
                'province' => 'Davao del Sur',
                'city' => 'Davao City',
                'barangay' => 'Barangay 2',
                'street_address' => '123 Delivery Street',
                'country' => 'Philippines',

                'is_archived' => false,
                'vehicle_type' => 'Motorcycle',
                'vehicle_number' => 'MC-1234',
                'license_number' => 'DL-123456',
                'is_active' => true,
            ]
        );

        // ------------------------------
        // Create Customer Users
        // ------------------------------
        $customers = [
            [
                'email' => 'gilgregenemantilla@gmail.com',
                'first_name' => 'Gilgre Gene',
                'middle_name' => 'G',
                'last_name' => 'Mantilla',
                'phone' => '123-456-7890',
                'region' => 'Davao Region',
                'province' => 'Davao del Sur',
                'city' => 'Davao City',
                'barangay' => 'Bankal',
                'street_address' => 'Bankal Skibidi',
            ],
            [
                'email' => 'rocky@gmail.com',
                'first_name' => 'Rocky',
                'middle_name' => 'B.',
                'last_name' => 'Adaya',
                'phone' => '123-456-7890',
                'region' => 'Davao Region',
                'province' => 'Davao del Sur',
                'city' => 'Davao City',
                'barangay' => 'Catalunan Grande',
                'street_address' => 'Catalunan Grande',
            ],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'first_name' => $customer['first_name'],
                    'middle_name' => $customer['middle_name'] ?? null,
                    'last_name' => $customer['last_name'],
                    'password' => Hash::make('12345678'),
                    'role' => 'customer',
                    'phone' => $customer['phone'],
                    'region' => $customer['region'],
                    'province' => $customer['province'],
                    'city' => $customer['city'],
                    'barangay' => $customer['barangay'],
                    'street_address' => $customer['street_address'],
                    'country' => 'Philippines',
                    'is_archived' => false,
                    'vehicle_type' => null,
                    'vehicle_number' => null,
                    'license_number' => null,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Customer users created/updated successfully!');
    }
}
