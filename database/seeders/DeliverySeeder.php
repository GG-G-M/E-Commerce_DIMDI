<?php

namespace Database\Seeders;

use App\Models\Delivery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeliverySeeder extends Seeder
{
    public function run()
    {
        $deliveries = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delaCruz@delivery.local',
                'phone' => '09171234567',
                'vehicle_type' => 'Motorcycle',
                'vehicle_number' => 'MC-001',
                'license_number' => 'DL-MC-001',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@delivery.local',
                'phone' => '09172345678',
                'vehicle_type' => 'Tricycle',
                'vehicle_number' => 'TC-002',
                'license_number' => 'DL-TC-002',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ],
            [
                'name' => 'Pedro Garcia',
                'email' => 'pedro.garcia@delivery.local',
                'phone' => '09173456789',
                'vehicle_type' => 'Van',
                'vehicle_number' => 'VN-003',
                'license_number' => 'DL-VN-003',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ],
        ];
        
        foreach ($deliveries as $delivery) {
            Delivery::firstOrCreate(
                ['email' => $delivery['email']],
                $delivery
            );
        }
        
        $this->command->info('Delivery personnel created successfully!');
    }
}
