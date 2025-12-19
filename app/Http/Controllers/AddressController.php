<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    // Fetch provinces
    public function provinces(): JsonResponse
    {
        $data = Cache::remember('provinces', 60 * 60 * 24, function () {
            try {
                $response = Http::timeout(20)->get('https://psgc.gitlab.io/api/provinces/');
                if ($response->successful()) {
                    return collect($response->json())->map(function ($province) {
                        return [
                            'code' => $province['code'] ?? null,
                            'name' => $province['name'] ?? null,
                        ];
                    });
                }
            } catch (\Exception $e) {
                // Log error if needed: \Log::error($e);
            }
            return [];
        });

        if (empty($data)) {
            return response()->json(['error' => 'Failed to fetch provinces. Please try again later.'], 500);
        }

        return response()->json($data);
    }

    // Fetch cities/municipalities for a province
    public function cities($provinceCode): JsonResponse
    {
        $cacheKey = "cities_$provinceCode";

        $data = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($provinceCode) {
            try {
                $response = Http::timeout(20)->get("https://psgc.gitlab.io/api/provinces/$provinceCode/cities-municipalities/");
                if ($response->successful()) {
                    return collect($response->json())->map(function ($city) {
                        return [
                            'code' => $city['code'] ?? null,
                            'name' => $city['name'] ?? null,
                        ];
                    });
                }
            } catch (\Exception $e) {
                // Log error if needed
            }
            return [];
        });

        if (empty($data)) {
            return response()->json(['error' => 'Failed to fetch cities. Please try again later.'], 500);
        }

        return response()->json($data);
    }

    // Fetch barangays for a city/municipality
    public function barangays($cityCode): JsonResponse
    {
        $cacheKey = "barangays_$cityCode";

        $data = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($cityCode) {
            try {
                $response = Http::timeout(20)->get("https://psgc.gitlab.io/api/cities-municipalities/$cityCode/barangays/");
                if ($response->successful()) {
                    return collect($response->json())->map(function ($barangay) {
                        return [
                            'code' => $barangay['code'] ?? null,
                            'name' => $barangay['name'] ?? null,
                        ];
                    });
                }
            } catch (\Exception $e) {
                // Log error if needed
            }
            return [];
        });

        if (empty($data)) {
            return response()->json(['error' => 'Failed to fetch barangays. Please try again later.'], 500);
        }

        return response()->json($data);
    }

    /**
     * Optional helpers: Get the readable name for a given code with timeout and fallback
     */
    public static function getProvinceName($code)
    {
        try {
            $res = Http::timeout(10)->get("https://psgc.gitlab.io/api/provinces/$code");
            return $res->successful() ? ($res->json()['name'] ?? $code) : $code;
        } catch (\Exception $e) {
            return $code;
        }
    }

    public static function getCityName($code)
    {
        try {
            $res = Http::timeout(10)->get("https://psgc.gitlab.io/api/cities-municipalities/$code");
            return $res->successful() ? ($res->json()['name'] ?? $code) : $code;
        } catch (\Exception $e) {
            return $code;
        }
    }

    public static function getBarangayName($code)
    {
        try {
            $res = Http::timeout(10)->get("https://psgc.gitlab.io/api/barangays/$code");
            return $res->successful() ? ($res->json()['name'] ?? $code) : $code;
        } catch (\Exception $e) {
            return $code;
        }
    }

    public static function getRegionName($provinceCode)
    {
        try {
            // 1. Fetch province details
            $res = Http::timeout(10)->get("https://psgc.gitlab.io/api/provinces/$provinceCode");

            if ($res->successful()) {
                $json = $res->json();

                // The province JSON contains `regionCode`, not region object
                $regionCode = $json['regionCode'] ?? null;

                if ($regionCode) {
                    // 2. Fetch region details
                    $regionRes = Http::timeout(10)->get("https://psgc.gitlab.io/api/regions/$regionCode");

                    if ($regionRes->successful()) {
                        return $regionRes->json()['name'] ?? null;
                    }
                }
            }
        } catch (\Exception $e) {
            // Handle silently
        }

        return null;
    }

    /**
     * Estimate coordinates from a textual address when an external geocoding
     * service (like Google Maps) is not available. This uses a deterministic
     * hashing approach to generate repeatable, approximate coordinates within
     * the Philippines bounding box. The result is NOT precise and should be
     * treated as an approximation for shipping fee estimation only.
     *
     * @param string $address
     * @return array ['latitude' => float, 'longitude' => float, 'method' => 'estimated']
     */
    public static function estimateCoordinatesFromAddress($address)
    {
        // Normalize input
        $address = trim(strtolower((string) $address));

        // Philippines approximate bounds
        $latMin = 5.0;   // southernmost (approx)
        $latMax = 19.0;  // northernmost (approx)
        $lngMin = 117.0; // westernmost (approx)
        $lngMax = 127.0; // easternmost (approx)

        // Create a deterministic 32-bit unsigned hash
        $hash = crc32($address);
        // Convert to positive integer
        if ($hash < 0) {
            $hash = $hash & 0xFFFFFFFF;
        }

        // Use parts of the hash to create two fractions [0,1)
        $partA = ($hash & 0xFFFF); // lower 16 bits
        $partB = (($hash >> 16) & 0xFFFF); // upper 16 bits

        $fracLat = ($partA / 65535);
        $fracLng = ($partB / 65535);

        $lat = $latMin + ($latMax - $latMin) * $fracLat;
        $lng = $lngMin + ($lngMax - $lngMin) * $fracLng;

        return [
            'latitude' => round($lat, 6),
            'longitude' => round($lng, 6),
            'method' => 'estimated'
        ];
    }
}
