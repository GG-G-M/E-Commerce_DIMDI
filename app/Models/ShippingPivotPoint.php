<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingPivotPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address',
        'is_active'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean'
    ];

    public function shippingZones()
    {
        return $this->hasMany(ShippingZone::class, 'pivot_point_id');
    }

    /**
     * Calculate shipping fee based on distance from this pivot point
     * Uses Haversine formula to calculate great-circle distance
     */
    public function calculateShippingFee($customerLatitude, $customerLongitude)
    {
        $distance = $this->haversineDistance(
            $this->latitude,
            $this->longitude,
            $customerLatitude,
            $customerLongitude
        );

        // Find the appropriate shipping zone based on distance
        $zone = $this->shippingZones()
            ->where('is_active', true)
            ->whereRaw('? BETWEEN min_distance AND max_distance', [$distance])
            ->first();

        return [
            'distance' => round($distance, 2),
            'fee' => $zone ? (float)$zone->shipping_fee : null,
            'zone' => $zone ? $zone->zone_name : null,
            'zone_id' => $zone ? $zone->id : null
        ];
    }

    /**
     * Haversine formula to calculate distance between two coordinates in kilometers
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }
}
