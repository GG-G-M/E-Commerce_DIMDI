# Distance-Based Shipping Fee Implementation

## Overview
This implementation replaces the flat shipping fee system with a **distance-based calculation** that computes shipping costs based on the geographical distance between a pivot warehouse point and the customer's delivery address.

## How It Works

### 1. **Shipping Pivot Points**
A pivot point is the warehouse/origin location. You can have multiple pivot points (e.g., Manila warehouse, Cebu branch).

- **Coordinates**: Each pivot point has GPS latitude and longitude
- **Default**: Only one active pivot point is used for calculations
- Example: Manila warehouse at (14.5995, 120.9842)

### 2. **Shipping Zones**
Each pivot point has distance-based zones:

| Zone | Distance | Fee |
|------|----------|-----|
| Local | 0-15 km | ₱50 |
| Metro | 15-50 km | ₱100 |
| Provincial | 50-150 km | ₱200 |
| Far Provincial | 150-500 km | ₱350 |

### 3. **Distance Calculation**
Uses the **Haversine formula** to calculate great-circle distance between:
- Warehouse coordinates (pivot point)
- Customer delivery address coordinates (provided during checkout)

Result: Accurate distance in kilometers

### 4. **Fee Assignment**
System finds the zone matching the calculated distance and applies that zone's shipping fee.

## Implementation Steps

### Step 1: Run Migrations
```powershell
php artisan migrate
```

This creates two tables:
- `shipping_pivot_points` - Warehouse locations
- `shipping_zones` - Distance-based zones and fees

### Step 2: Seed Sample Data
```powershell
php artisan db:seed --class=ShippingZoneSeeder
```

Creates example pivot points and zones:
- **Main Warehouse - Manila** (14.5995, 120.9842)
- **Branch Warehouse - Cebu** (10.3157, 123.8854)

### Step 3: Update Checkout Form
The checkout form must capture customer address **latitude and longitude**.

#### Option A: Use Google Maps Geocoding API (Recommended)
Add hidden fields to `orders/create.blade.php`:
```blade
<input type="hidden" name="shipping_latitude" id="shipping_latitude" required>
<input type="hidden" name="shipping_longitude" id="shipping_longitude" required>
```

Add JavaScript to geocode address on form submission:
```javascript
const form = document.querySelector('form');
form.addEventListener('submit', async (e) => {
    const address = document.querySelector('[name="shipping_address"]').value;
    const response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=YOUR_API_KEY`);
    const data = await response.json();
    
    if (data.results.length > 0) {
        const { lat, lng } = data.results[0].geometry.location;
        document.getElementById('shipping_latitude').value = lat;
        document.getElementById('shipping_longitude').value = lng;
    }
});
```

#### Option B: Use Interactive Map
Let customer click on a map to select delivery location, capturing lat/long.

#### Option C: Use Mapbox or Similar Service
Similar to Google Maps but with different API endpoints.

### Step 4: Verify OrderController Updates
Check `app/Http/Controllers/OrderController.php`:
- ✅ Imports `ShippingCalculationService`
- ✅ Validates `shipping_latitude` and `shipping_longitude` in store()
- ✅ Calls `ShippingCalculationService::calculateShippingFeeWithFallback()`
- ✅ Uses calculated `$shipping` in order creation

### Step 5: Test the Flow
1. Login to app
2. Go to checkout
3. Provide shipping address with GPS coordinates
4. Verify shipping fee calculated correctly based on distance
5. Check `shipping_pivot_points` and `shipping_zones` tables for configuration

## Files Created/Modified

### New Files
- **Migration**: `database/migrations/2025_12_07_000000_create_shipping_zones_table.php`
- **Models**: `app/Models/ShippingPivotPoint.php`, `app/Models/ShippingZone.php`
- **Service**: `app/Services/ShippingCalculationService.php`
- **Seeder**: `database/seeders/ShippingZoneSeeder.php`

### Modified Files
- **Controller**: `app/Http/Controllers/OrderController.php`
  - Added import: `ShippingCalculationService`
  - Updated `create()`: No default shipping (calculated later)
  - Updated `store()`: Validates lat/long, calculates shipping fee

## API Reference

### ShippingCalculationService

#### `calculateShippingFee($latitude, $longitude)`
Returns array:
```php
[
    'distance' => 25.5,        // km
    'fee' => 100,              // PHP
    'zone' => 'Metro',         // Zone name
    'zone_id' => 2,            // Zone ID
    'error' => null            // Error message if any
]
```

#### `calculateShippingFeeWithFallback($latitude, $longitude, $defaultFee = 100)`
Same as above, but if no zone matches distance, uses `$defaultFee`.

### ShippingPivotPoint Model

#### `calculateShippingFee($lat, $lng)`
Finds matching zone and returns shipping calculation.

#### `haversineDistance($lat1, $lon1, $lat2, $lon2)`
Private method. Calculates distance in km using Haversine formula.

## Database Management

### Add New Pivot Point
```php
ShippingPivotPoint::create([
    'name' => 'Davao Branch',
    'latitude' => 7.0904,
    'longitude' => 125.6127,
    'address' => 'Davao City, Philippines',
    'is_active' => true
]);
```

### Add New Shipping Zone to Pivot Point
```php
ShippingZone::create([
    'pivot_point_id' => 1,
    'zone_name' => 'Ultra Local',
    'min_distance' => 0,
    'max_distance' => 10,
    'shipping_fee' => 30,
    'description' => 'Same-day delivery',
    'is_active' => true
]);
```

### Deactivate Shipping Zone
```php
ShippingZone::find($id)->update(['is_active' => false]);
```

## Admin Panel (Optional)

You may want to create admin routes to manage:
- View/edit pivot points
- View/edit shipping zones
- Add new zones for different distances

Example routes:
```php
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('shipping-pivot-points', Admin\ShippingPivotPointController::class);
    Route::resource('shipping-zones', Admin\ShippingZoneController::class);
});
```

## Troubleshooting

### Issue: "No shipping pivot point configured"
**Solution**: Ensure at least one active pivot point exists:
```bash
php artisan db:seed --class=ShippingZoneSeeder
```

### Issue: Shipping fee always uses default fallback
**Solution**: Check that:
1. Pivot point is active (`is_active = true`)
2. Shipping zone is active (`is_active = true`)
3. Distance calculation is correct (check lat/long values)
4. Zone distance range is correct (min_distance ≤ calculated_distance ≤ max_distance)

### Issue: Incorrect distance calculation
**Solution**: Verify lat/long coordinates:
- Valid latitude: -90 to +90
- Valid longitude: -180 to +180
- Use online tools (Google Maps, etc.) to verify coordinates

## Next Steps

1. **Implement address geocoding** in checkout form (Google Maps or Mapbox API)
2. **Create admin panel** for managing shipping zones
3. **Add shipping cost estimation** in cart preview
4. **Handle multiple warehouses** (choose nearest pivot point for customer)
5. **Add real-time shipping updates** based on order status

## Example Coordinates (Philippines)

```
Manila: (14.5995, 120.9842)
Cebu: (10.3157, 123.8854)
Davao: (7.0904, 125.6127)
Quezon City: (14.6349, 121.0388)
Las Piñas: (14.3521, 120.9814)
```
