<?php

namespace Database\Seeders;

use App\Models\Delivery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DeliverySeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating delivery personnel that match UserSeeder...');
        
        // First, get delivery users from users table
        $deliveryUsers = DB::table('users')
            ->where('role', 'delivery')
            ->get();
        
        if ($deliveryUsers->isEmpty()) {
            $this->command->warn('No delivery users found in users table. Creating default ones...');
            
            // Create default deliveries that match UserSeeder
            $deliveries = [
                [
                    'name' => 'Juan Delivery',
                    'email' => 'delivery1@example.com',
                    'phone' => '0917-123-4567',
                    'vehicle_type' => 'Motorcycle',
                    'vehicle_number' => 'MC-1234',
                    'license_number' => 'DL-123456',
                    'password' => Hash::make('12345678'),
                    'is_active' => true,
                ],
                [
                    'name' => 'Pedro Santos',
                    'email' => 'delivery2@example.com',
                    'phone' => '0918-234-5678',
                    'vehicle_type' => 'Motorcycle',
                    'vehicle_number' => 'MC-2345',
                    'license_number' => 'DL-234567',
                    'password' => Hash::make('12345678'),
                    'is_active' => true,
                ],
                [
                    'name' => 'Maria Gonzales',
                    'email' => 'delivery3@example.com',
                    'phone' => '0919-345-6789',
                    'vehicle_type' => 'Van',
                    'vehicle_number' => 'VAN-3456',
                    'license_number' => 'DL-345678',
                    'password' => Hash::make('12345678'),
                    'is_active' => true,
                ],
                [
                    'name' => 'Antonio Reyes',
                    'email' => 'delivery4@example.com',
                    'phone' => '0920-456-7890',
                    'vehicle_type' => 'Motorcycle',
                    'vehicle_number' => 'MC-4567',
                    'license_number' => 'DL-456789',
                    'password' => Hash::make('12345678'),
                    'is_active' => true,
                ],
                [
                    'name' => 'Luis Cruz',
                    'email' => 'delivery5@example.com',
                    'phone' => '0921-567-8901',
                    'vehicle_type' => 'Bicycle',
                    'vehicle_number' => 'BC-5678',
                    'license_number' => 'DL-567890',
                    'password' => Hash::make('12345678'),
                    'is_active' => true,
                ],
            ];
        } else {
            // Create deliveries based on users table
            $deliveries = [];
            foreach ($deliveryUsers as $index => $user) {
                $vehicleInfo = $this->getVehicleInfo($user->first_name, $index);
                
                $deliveries[] = [
                    'user_id' => $user->id,
                    'name' => trim($user->first_name . ' ' . $user->last_name),
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'vehicle_type' => $user->vehicle_type ?? $vehicleInfo['type'],
                    'vehicle_number' => $user->vehicle_number ?? $vehicleInfo['number'],
                    'license_number' => $user->license_number ?? $vehicleInfo['license'],
                    'password' => $user->password ?? Hash::make('12345678'),
                    'is_active' => true,
                ];
            }
        }
        
        // Create or update deliveries
        $created = 0;
        $updated = 0;
        
        foreach ($deliveries as $delivery) {
            $existing = Delivery::where('email', $delivery['email'])->first();
            
            if ($existing) {
                $existing->update($delivery);
                $updated++;
            } else {
                Delivery::create($delivery);
                $created++;
            }
        }
        
        $this->command->info("✅ Delivery personnel synced!");
        $this->command->info("📊 Created: {$created} | Updated: {$updated}");
        $this->command->info("🚚 Total deliveries: " . Delivery::count());
        
        // Show login info
        $sampleDeliveries = Delivery::take(3)->get();
        $this->command->info("\n📋 Delivery Login Information:");
        $this->command->info("   (Use the same email/password as user account)");
        foreach ($sampleDeliveries as $delivery) {
            $this->command->info("   👤 {$delivery->name} | 📧 {$delivery->email} | 🔑 12345678");
        }
    }
    
    private function getVehicleInfo($name, $index)
    {
        $nameHash = crc32($name . $index);
        
        $vehicleTypes = ['Motorcycle', 'Bicycle', 'Van', 'Car', 'Tricycle'];
        $type = $vehicleTypes[$nameHash % count($vehicleTypes)];
        
        $prefixes = [
            'Motorcycle' => 'MC',
            'Bicycle' => 'BC',
            'Van' => 'VAN',
            'Car' => 'CAR',
            'Tricycle' => 'TC'
        ];
        
        $prefix = $prefixes[$type] ?? 'VEH';
        
        return [
            'type' => $type,
            'number' => $prefix . '-' . str_pad(($nameHash % 9999) + 1000, 4, '0', STR_PAD_LEFT),
            'license' => 'DL-' . str_pad(($nameHash % 999999) + 100000, 6, '0', STR_PAD_LEFT)
        ];
    }
}