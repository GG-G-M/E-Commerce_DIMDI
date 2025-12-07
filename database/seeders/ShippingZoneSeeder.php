<?php

namespace Database\Seeders;

use App\Models\ShippingPivotPoint;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main pivot point - Davao
        $mainPivot = ShippingPivotPoint::firstOrCreate([
            'name' => 'Main Warehouse - Davao'
        ], [
            'latitude' => 7.0658826316964864, // approximate coordinates for Davao City
            'longitude' => 125.60761748482355,
            'address' => 'Dimdi Centre, Asaje II Building, San Pedro Street, Davao City, Philippines',
            'is_active' => true
        ]);

        // Optional: Branch pivot point - Manila
        $manilaPivot = ShippingPivotPoint::firstOrCreate([
            'name' => 'Branch Warehouse - Manila'
        ], [
            'latitude' => 14.5995,
            'longitude' => 120.9842,
            'address' => 'Manila, Philippines',
            'is_active' => true
        ]);

        // Optional: Branch pivot point - Cebu
        $cebuPivot = ShippingPivotPoint::firstOrCreate([
            'name' => 'Branch Warehouse - Cebu'
        ], [
            'latitude' => 10.3157,
            'longitude' => 123.8854,
            'address' => 'Cebu, Philippines',
            'is_active' => true
        ]);

        // Shipping zones (same structure for all pivots)
        $zones = [
            [
                'zone_name' => 'Local',
                'min_distance' => 0,
                'max_distance' => 100,
                'shipping_fee' => 100,
                'description' => 'Local delivery within 15km'
            ],
            [
                'zone_name' => 'Metro',
                'min_distance' => 100.01,
                'max_distance' => 200,
                'shipping_fee' => 150,
                'description' => 'Metro area (15-50km)'
            ],
            [
                'zone_name' => 'Provincial',
                'min_distance' => 200.01,
                'max_distance' => 300,
                'shipping_fee' => 200,
                'description' => 'Provincial delivery (50-150km)'
            ],
            [
                'zone_name' => 'Far Provincial',
                'min_distance' => 300.01,
                'max_distance' => 500,
                'shipping_fee' => 350,
                'description' => 'Far provincial (150-500km)'
            ]
        ];

        // Assign zones to all pivot points
        foreach ([$mainPivot, $manilaPivot, $cebuPivot] as $pivot) {
            foreach ($zones as $zone) {
                ShippingZone::firstOrCreate([
                    'pivot_point_id' => $pivot->id,
                    'zone_name' => $zone['zone_name']
                ], [
                    'min_distance' => $zone['min_distance'],
                    'max_distance' => $zone['max_distance'],
                    'shipping_fee' => $zone['shipping_fee'],
                    'description' => $zone['description'],
                    'is_active' => true
                ]);
            }
        }
    }
}
