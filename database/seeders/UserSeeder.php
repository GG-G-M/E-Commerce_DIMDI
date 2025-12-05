<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    
    public function run()
    {
        $faker = Faker::create();

        // Predefined Philippine addresses for realistic data
        $philippineAddresses = [
            ['region' => 'NCR', 'province' => 'Metro Manila', 'city' => 'Manila', 'barangay' => 'Ermita'],
            ['region' => 'NCR', 'province' => 'Metro Manila', 'city' => 'Quezon City', 'barangay' => 'Cubao'],
            ['region' => 'NCR', 'province' => 'Metro Manila', 'city' => 'Makati', 'barangay' => 'Bel-Air'],
            ['region' => 'NCR', 'province' => 'Metro Manila', 'city' => 'Pasig', 'barangay' => 'Ortigas Center'],
            ['region' => 'NCR', 'province' => 'Metro Manila', 'city' => 'Taguig', 'barangay' => 'Bonifacio Global City'],
            ['region' => 'Region IV-A', 'province' => 'Cavite', 'city' => 'Dasmarinas', 'barangay' => 'Salitran'],
            ['region' => 'Region IV-A', 'province' => 'Cavite', 'city' => 'Bacoor', 'barangay' => 'Molino'],
            ['region' => 'Region IV-A', 'province' => 'Laguna', 'city' => 'Santa Rosa', 'barangay' => 'Balibago'],
            ['region' => 'Region IV-A', 'province' => 'Laguna', 'city' => 'Calamba', 'barangay' => 'Pansol'],
            ['region' => 'Region IV-A', 'province' => 'Laguna', 'city' => 'San Pedro', 'barangay' => 'United Bayanihan'],
            ['region' => 'Region III', 'province' => 'Bulacan', 'city' => 'Meycauayan', 'barangay' => 'Camalig'],
            ['region' => 'Region III', 'province' => 'Bulacan', 'city' => 'Marilao', 'barangay' => 'Loma de Gato'],
            ['region' => 'Region III', 'province' => 'Pampanga', 'city' => 'Angeles', 'barangay' => 'Balibago'],
            ['region' => 'Region VII', 'province' => 'Cebu', 'city' => 'Cebu City', 'barangay' => 'Lahug'],
            ['region' => 'Region VII', 'province' => 'Cebu', 'city' => 'Mandaue', 'barangay' => 'Tipolo'],
            ['region' => 'Region XI', 'province' => 'Davao del Sur', 'city' => 'Davao City', 'barangay' => 'Buhangin'],
            ['region' => 'Region XI', 'province' => 'Davao del Sur', 'city' => 'Davao City', 'barangay' => 'Matina'],
            ['region' => 'Region X', 'province' => 'Misamis Oriental', 'city' => 'Cagayan de Oro', 'barangay' => 'Carmen'],
            ['region' => 'Region VI', 'province' => 'Iloilo', 'city' => 'Iloilo City', 'barangay' => 'Mandurriao'],
            ['region' => 'Region V', 'province' => 'Albay', 'city' => 'Legazpi', 'barangay' => 'Bitano'],
        ];

        // Philippine street names for more realistic addresses
        $streetNames = [
            'Rizal Street', 'Bonifacio Street', 'Aguinaldo Street', 'Quezon Avenue', 'Roxas Boulevard',
            'Mabini Street', 'Luna Street', 'Del Pilar Street', 'Legazpi Street', 'Magallanes Street',
            'Katipunan Avenue', 'Commonwealth Avenue', 'EDSA', 'C-5 Road', 'Ortigas Avenue',
            'Shaw Boulevard', 'Taft Avenue', 'Ayala Avenue', 'Buendia Avenue', 'Gil Puyat Avenue',
            'Marcos Highway', 'Sumulong Highway', 'Nagtahan Road', 'Quirino Highway', 'Mindanao Avenue'
        ];

        // ------------------------------
        // Create Admin User
        // ------------------------------
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Admin',
                'middle_name' => null,
                'last_name' => 'User',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'phone' => '0917-000-0001',
                'region' => 'NCR',
                'province' => 'Metro Manila',
                'city' => 'Manila',
                'barangay' => 'Ermita',
                'street_address' => '123 Admin Building, Rizal Park',
                'country' => 'Philippines',
                'is_archived' => false,
                'vehicle_type' => null,
                'vehicle_number' => null,
                'license_number' => null,
                'is_active' => true,
            ]
        );

        // ------------------------------
        // Create Delivery Users
        // ------------------------------
        $deliveryUsers = [
            [
                'email' => 'delivery1@example.com',
                'first_name' => 'Juan',
                'last_name' => 'Delivery',
                'phone' => '0917-123-4567',
                'vehicle_type' => 'Motorcycle',
                'vehicle_number' => 'MC-1234',
                'license_number' => 'DL-123456',
            ],
            [
                'email' => 'delivery2@example.com',
                'first_name' => 'Pedro',
                'last_name' => 'Santos',
                'phone' => '0918-234-5678',
                'vehicle_type' => 'Motorcycle',
                'vehicle_number' => 'MC-2345',
                'license_number' => 'DL-234567',
            ],
            [
                'email' => 'delivery3@example.com',
                'first_name' => 'Maria',
                'last_name' => 'Gonzales',
                'phone' => '0919-345-6789',
                'vehicle_type' => 'Van',
                'vehicle_number' => 'VAN-3456',
                'license_number' => 'DL-345678',
            ],
            [
                'email' => 'delivery4@example.com',
                'first_name' => 'Antonio',
                'last_name' => 'Reyes',
                'phone' => '0920-456-7890',
                'vehicle_type' => 'Motorcycle',
                'vehicle_number' => 'MC-4567',
                'license_number' => 'DL-456789',
            ],
            [
                'email' => 'delivery5@example.com',
                'first_name' => 'Luis',
                'last_name' => 'Cruz',
                'phone' => '0921-567-8901',
                'vehicle_type' => 'Bicycle',
                'vehicle_number' => 'BC-5678',
                'license_number' => 'DL-567890',
            ],
        ];

        foreach ($deliveryUsers as $delivery) {
            $address = $philippineAddresses[array_rand($philippineAddresses)];
            User::updateOrCreate(
                ['email' => $delivery['email']],
                [
                    'first_name' => $delivery['first_name'],
                    'middle_name' => null,
                    'last_name' => $delivery['last_name'],
                    'password' => Hash::make('12345678'),
                    'role' => 'delivery',
                    'phone' => $delivery['phone'],
                    'region' => $address['region'],
                    'province' => $address['province'],
                    'city' => $address['city'],
                    'barangay' => $address['barangay'],
                    'street_address' => $faker->buildingNumber() . ' ' . $streetNames[array_rand($streetNames)],
                    'country' => 'Philippines',
                    'is_archived' => false,
                    'vehicle_type' => $delivery['vehicle_type'],
                    'vehicle_number' => $delivery['vehicle_number'],
                    'license_number' => $delivery['license_number'],
                    'is_active' => true,
                ]
            );
        }

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

        foreach ($customers as $index => $customer) {
            // Get address from our predefined list (cycling through them)
            $address = $philippineAddresses[$index % count($philippineAddresses)];
            
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'first_name' => $customer['first_name'],
                    'middle_name' => $faker->firstName(), // Random middle name
                    'last_name' => $customer['last_name'],
                    'password' => Hash::make('12345678'),
                    'role' => 'customer',
                    'phone' => $customer['phone'],
                    'region' => $address['region'],
                    'province' => $address['province'],
                    'city' => $address['city'],
                    'barangay' => $address['barangay'],
                    'street_address' => $faker->buildingNumber() . ' ' . $streetNames[array_rand($streetNames)] . ', ' . 
                                      $faker->secondaryAddress(),
                    'country' => 'Philippines',
                    'is_archived' => false,
                    'vehicle_type' => null,
                    'vehicle_number' => null,
                    'license_number' => null,
                    'is_active' => true,
                ]
            );
        }

        // ------------------------------
        // Create Warehouse Staff Users
        // ------------------------------
        $warehouseStaff = [
            ['email' => 'warehouse1@example.com', 'first_name' => 'Roberto', 'last_name' => 'Lim', 'phone' => '09661234567'],
            ['email' => 'warehouse2@example.com', 'first_name' => 'Sofia', 'last_name' => 'Tan', 'phone' => '09671234567'],
            ['email' => 'warehouse3@example.com', 'first_name' => 'Miguel', 'last_name' => 'Chua', 'phone' => '09681234567'],
            ['email' => 'warehouse4@example.com', 'first_name' => 'Isabella', 'last_name' => 'Sy', 'phone' => '09691234567'],
        ];

        foreach ($warehouseStaff as $staff) {
            $address = $philippineAddresses[array_rand($philippineAddresses)];
            User::updateOrCreate(
                ['email' => $staff['email']],
                [
                    'first_name' => $staff['first_name'],
                    'middle_name' => null,
                    'last_name' => $staff['last_name'],
                    'password' => Hash::make('12345678'),
                    'role' => 'warehouse_staff',
                    'phone' => $staff['phone'],
                    'region' => $address['region'],
                    'province' => $address['province'],
                    'city' => $address['city'],
                    'barangay' => $address['barangay'],
                    'street_address' => $faker->buildingNumber() . ' ' . $streetNames[array_rand($streetNames)],
                    'country' => 'Philippines',
                    'is_archived' => false,
                    'vehicle_type' => null,
                    'vehicle_number' => null,
                    'license_number' => null,
                    'is_active' => true,
                ]
            );
        }

        // ------------------------------
        // Create Stock Checker Users
        // ------------------------------
        $stockCheckers = [
            ['email' => 'checker1@example.com', 'first_name' => 'Alfredo', 'last_name' => 'Ramos', 'phone' => '09701234567'],
            ['email' => 'checker2@example.com', 'first_name' => 'Corazon', 'last_name' => 'Aquino', 'phone' => '09711234567'],
        ];

        foreach ($stockCheckers as $checker) {
            $address = $philippineAddresses[array_rand($philippineAddresses)];
            User::updateOrCreate(
                ['email' => $checker['email']],
                [
                    'first_name' => $checker['first_name'],
                    'middle_name' => null,
                    'last_name' => $checker['last_name'],
                    'password' => Hash::make('12345678'),
                    'role' => 'stock_checker',
                    'phone' => $checker['phone'],
                    'region' => $address['region'],
                    'province' => $address['province'],
                    'city' => $address['city'],
                    'barangay' => $address['barangay'],
                    'street_address' => $faker->buildingNumber() . ' ' . $streetNames[array_rand($streetNames)],
                    'country' => 'Philippines',
                    'is_archived' => false,
                    'vehicle_type' => null,
                    'vehicle_number' => null,
                    'license_number' => null,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✅ Users created successfully!');
        $this->command->info('👑 Admin: admin@gmail.com / 12345678');
        $this->command->info('🚚 Delivery Personnel: delivery1@example.com / 12345678');
        $this->command->info('👥 Customers: 40 customers created');
        $this->command->info('📦 Warehouse Staff: 4 staff created');
        $this->command->info('🔍 Stock Checkers: 2 checkers created');
    }
}