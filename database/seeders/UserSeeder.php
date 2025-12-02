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
                'is_archived' => false,
                'vehicle_type' => null,
                'vehicle_number' => null,
                'license_number' => null,
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
                'is_archived' => false,
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
                'is_archived' => false,
                'vehicle_type' => null,
                'vehicle_number' => null,
                'license_number' => null,
                'is_active' => true,
            ]
        );

        // Create additional customer users for orders and ratings
        $customers = [
            [
                'email' => 'maria.garcia@gmail.com',
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'phone' => '09161234567',
                'address' => '456 Market Street',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'carlos.reyes@gmail.com',
                'first_name' => 'Carlos',
                'last_name' => 'Reyes',
                'phone' => '09171234567',
                'address' => '789 Business Ave',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'anna.santos@gmail.com',
                'first_name' => 'Anna',
                'last_name' => 'Santos',
                'phone' => '09181234567',
                'address' => '321 Residential Road',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'miguel.torres@gmail.com',
                'first_name' => 'Miguel',
                'last_name' => 'Torres',
                'phone' => '09191234567',
                'address' => '654 Commerce Lane',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'rosa.cruz@gmail.com',
                'first_name' => 'Rosa',
                'last_name' => 'Cruz',
                'phone' => '09201234567',
                'address' => '987 Shopping District',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'john.santos@gmail.com',
                'first_name' => 'John',
                'last_name' => 'Santos',
                'phone' => '09211234567',
                'address' => '135 Retail Plaza',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'teresa.mendez@gmail.com',
                'first_name' => 'Teresa',
                'last_name' => 'Mendez',
                'phone' => '09221234567',
                'address' => '246 Downtown Mall',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'david.morales@gmail.com',
                'first_name' => 'David',
                'last_name' => 'Morales',
                'phone' => '09231234567',
                'address' => '369 Uptown Center',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'elena.diaz@gmail.com',
                'first_name' => 'Elena',
                'last_name' => 'Diaz',
                'phone' => '09241234567',
                'address' => '482 Eastside Complex',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
            [
                'email' => 'ramon.flores@gmail.com',
                'first_name' => 'Ramon',
                'last_name' => 'Flores',
                'phone' => '09251234567',
                'address' => '573 Westside Boulevard',
                'city' => 'Davao City',
                'state' => 'Davao del Sur',
                'zip_code' => '8000',
            ],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'password' => Hash::make('12345678'),
                    'role' => 'customer',
                    'phone' => $customer['phone'],
                    'address' => $customer['address'],
                    'city' => $customer['city'],
                    'state' => $customer['state'],
                    'zip_code' => $customer['zip_code'],
                    'country' => 'Philippines',
                    'is_archived' => false,
                    'vehicle_type' => null,
                    'vehicle_number' => null,
                    'license_number' => null,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Users created/updated successfully!');
        $this->command->info('Admin: admin@example.com / 12345678');
        $this->command->info('Delivery: delivery@example.com / 12345678');
        $this->command->info('Customer: gilgregenemantilla@gmail.com / 12345678');
        $this->command->info('Additional 10 customers created for orders and ratings');
    }
}