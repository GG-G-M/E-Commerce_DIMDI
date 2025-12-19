<?php

namespace App\Services;

use App\Models\ShippingPivotPoint;

class ShippingCalculationService
{
    /**
     * Calculate shipping fee based on customer address and distance from pivot point
     * 
     * @param string $customerLatitude GPS latitude of delivery address
     * @param string $customerLongitude GPS longitude of delivery address
     * @return array Shipping calculation result with fee, distance, and zone info
     */
    public static function calculateShippingFee($customerLatitude, $customerLongitude)
    {
        // Get the active pivot point (usually there's one main warehouse)
        $pivotPoint = ShippingPivotPoint::where('is_active', true)->first();

        if (!$pivotPoint) {
            // Fallback: if no pivot point is configured, return a default fee
            return [
                'distance' => 0,
                'fee' => 0,
                'zone' => 'Default',
                'zone_id' => null,
                'error' => 'No shipping pivot point configured'
            ];
        }

        return $pivotPoint->calculateShippingFee($customerLatitude, $customerLongitude);
    }

    /**
     * Calculate shipping fee with fallback to default if no matching zone
     * 
     * @param float $customerLatitude
     * @param float $customerLongitude
     * @param float $defaultFee Fallback shipping fee if no zone matches
     * @return array
     */
    public static function calculateShippingFeeWithFallback($customerLatitude, $customerLongitude, $defaultFee = 100)
    {
        $result = self::calculateShippingFee($customerLatitude, $customerLongitude);

        // Check for error in result
        if (isset($result['error'])) {
            return [
                'success' => false,
                'message' => $result['error'],
                'fee' => $defaultFee,
                'distance' => 0,
                'zone_name' => 'Default (No Configuration)',
                'zone_id' => null
            ];
        }

        // If no zone matched, use the default fee
        if ($result['fee'] === null) {
            $result['fee'] = $defaultFee;
            $result['zone_name'] = 'Default (Out of Zone)';
            $result['zone_id'] = null;
        }

        return [
            'success' => true,
            'fee' => $result['fee'] ?? $defaultFee,
            'distance' => $result['distance'] ?? 0,
            'zone_name' => $result['zone'] ?? $result['zone_name'] ?? null,
            'zone_id' => $result['zone_id'] ?? null
        ];
    }
}
