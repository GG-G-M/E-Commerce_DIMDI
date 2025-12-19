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
            ['email' => 'maria.garcia@gmail.com', 'first_name' => 'Maria', 'last_name' => 'Garcia', 'phone' => '09161234567'],
            ['email' => 'carlos.reyes@gmail.com', 'first_name' => 'Carlos', 'last_name' => 'Reyes', 'phone' => '09171234567'],
            ['email' => 'anna.santos@gmail.com', 'first_name' => 'Anna', 'last_name' => 'Santos', 'phone' => '09181234567'],
            ['email' => 'miguel.torres@gmail.com', 'first_name' => 'Miguel', 'last_name' => 'Torres', 'phone' => '09191234567'],
            ['email' => 'rosa.cruz@gmail.com', 'first_name' => 'Rosa', 'last_name' => 'Cruz', 'phone' => '09201234567'],
            ['email' => 'john.santos@gmail.com', 'first_name' => 'John', 'last_name' => 'Santos', 'phone' => '09211234567'],
            ['email' => 'teresa.mendez@gmail.com', 'first_name' => 'Teresa', 'last_name' => 'Mendez', 'phone' => '09221234567'],
            ['email' => 'david.morales@gmail.com', 'first_name' => 'David', 'last_name' => 'Morales', 'phone' => '09231234567'],
            ['email' => 'elena.diaz@gmail.com', 'first_name' => 'Elena', 'last_name' => 'Diaz', 'phone' => '09241234567'],
            ['email' => 'ramon.flores@gmail.com', 'first_name' => 'Ramon', 'last_name' => 'Flores', 'phone' => '09251234567'],
            ['email' => 'patricia.lopez@gmail.com', 'first_name' => 'Patricia', 'last_name' => 'Lopez', 'phone' => '09261234567'],
            ['email' => 'fernando.rivera@gmail.com', 'first_name' => 'Fernando', 'last_name' => 'Rivera', 'phone' => '09271234567'],
            ['email' => 'isabel.rojas@gmail.com', 'first_name' => 'Isabel', 'last_name' => 'Rojas', 'phone' => '09281234567'],
            ['email' => 'jose.vargas@gmail.com', 'first_name' => 'Jose', 'last_name' => 'Vargas', 'phone' => '09291234567'],
            ['email' => 'sophia.gutierrez@gmail.com', 'first_name' => 'Sophia', 'last_name' => 'Gutierrez', 'phone' => '09301234567'],
            ['email' => 'lucas.herrera@gmail.com', 'first_name' => 'Lucas', 'last_name' => 'Herrera', 'phone' => '09311234567'],
            ['email' => 'carolina.medina@gmail.com', 'first_name' => 'Carolina', 'last_name' => 'Medina', 'phone' => '09321234567'],
            ['email' => 'antonio.perez@gmail.com', 'first_name' => 'Antonio', 'last_name' => 'Perez', 'phone' => '09331234567'],
            ['email' => 'veronica.silva@gmail.com', 'first_name' => 'Veronica', 'last_name' => 'Silva', 'phone' => '09341234567'],
            ['email' => 'marcos.ortiz@gmail.com', 'first_name' => 'Marcos', 'last_name' => 'Ortiz', 'phone' => '09351234567'],
            ['email' => 'lucia.campos@gmail.com', 'first_name' => 'Lucia', 'last_name' => 'Campos', 'phone' => '09361234567'],
            ['email' => 'sergio.ramirez@gmail.com', 'first_name' => 'Sergio', 'last_name' => 'Ramirez', 'phone' => '09371234567'],
            ['email' => 'camila.castillo@gmail.com', 'first_name' => 'Camila', 'last_name' => 'Castillo', 'phone' => '09381234567'],
            ['email' => 'ricardo.moreno@gmail.com', 'first_name' => 'Ricardo', 'last_name' => 'Moreno', 'phone' => '09391234567'],
            ['email' => 'mariana.guzman@gmail.com', 'first_name' => 'Mariana', 'last_name' => 'Guzman', 'phone' => '09401234567'],
            ['email' => 'gabriel.jimenez@gmail.com', 'first_name' => 'Gabriel', 'last_name' => 'Jimenez', 'phone' => '09411234567'],
            ['email' => 'alejandra.romero@gmail.com', 'first_name' => 'Alejandra', 'last_name' => 'Romero', 'phone' => '09421234567'],
            ['email' => 'pablo.delgado@gmail.com', 'first_name' => 'Pablo', 'last_name' => 'Delgado', 'phone' => '09431234567'],
            ['email' => 'daniela.fuentes@gmail.com', 'first_name' => 'Daniela', 'last_name' => 'Fuentes', 'phone' => '09441234567'],
            ['email' => 'andres.santos@gmail.com', 'first_name' => 'Andres', 'last_name' => 'Santos', 'phone' => '09451234567'],
            ['email' => 'valentina.iglesias@gmail.com', 'first_name' => 'Valentina', 'last_name' => 'Iglesias', 'phone' => '09461234567'],
            ['email' => 'francisco.peña@gmail.com', 'first_name' => 'Francisco', 'last_name' => 'Peña', 'phone' => '09471234567'],
            ['email' => 'marina.salazar@gmail.com', 'first_name' => 'Marina', 'last_name' => 'Salazar', 'phone' => '09481234567'],
            ['email' => 'diego.espinoza@gmail.com', 'first_name' => 'Diego', 'last_name' => 'Espinoza', 'phone' => '09491234567'],
            ['email' => 'natalia.carrillo@gmail.com', 'first_name' => 'Natalia', 'last_name' => 'Carrillo', 'phone' => '09501234567'],
            ['email' => 'victor.gallardo@gmail.com', 'first_name' => 'Victor', 'last_name' => 'Gallardo', 'phone' => '09511234567'],
            ['email' => 'jessica.navarro@gmail.com', 'first_name' => 'Jessica', 'last_name' => 'Navarro', 'phone' => '09521234567'],
            ['email' => 'enrique.molina@gmail.com', 'first_name' => 'Enrique', 'last_name' => 'Molina', 'phone' => '09531234567'],
            ['email' => 'laura.acosta@gmail.com', 'first_name' => 'Laura', 'last_name' => 'Acosta', 'phone' => '09541234567'],
            ['email' => 'rocky@gmail.com', 'first_name' => 'Rocky', 'last_name' => 'Adaya', 'phone' => '09551234567'],
            ['email' => 'gilgregenemantilla@gmail.com', 'first_name' => 'Gil Gregor', 'last_name' => 'Nemantilla', 'phone' => '09561234567'],
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
        $this->command->info('👥 Customers: 41 customers created (including gilgregenemantilla@gmail.com)');
        $this->command->info('📦 Warehouse Staff: 4 staff created');
        $this->command->info('🔍 Stock Checkers: 2 checkers created');
    }
}