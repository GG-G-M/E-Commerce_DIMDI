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

        User::updateOrCreate(
            ['email' => 'rocky@gmail.com'],
            [
                'first_name' => 'Rocky',
                'middle_name' => 'B.',
                'last_name' => 'Adaya',
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'phone' => '123-456-7890',
                'address' => 'Catalunan Grande',
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

        // Create additional customer users for orders and ratings
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
            ['email' => 'felipe.dominguez@gmail.com', 'first_name' => 'Felipe', 'last_name' => 'Dominguez', 'phone' => '09551234567'],
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
                    'address' => rand(100, 999) . ' ' . ['Main', 'Oak', 'Elm', 'Pine', 'Maple'][rand(0, 4)] . ' Street',
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
        }

        $this->command->info('Users created/updated successfully!');
        $this->command->info('Admin: admin@example.com / 12345678');
        $this->command->info('Delivery: delivery@example.com / 12345678');
        $this->command->info('Customer: gilgregenemantilla@gmail.com / 12345678');
        $this->command->info('Additional 40 customers created for 5-month operations');
    }
}