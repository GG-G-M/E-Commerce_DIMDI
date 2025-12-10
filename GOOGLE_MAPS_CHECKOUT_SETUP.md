# Google Maps Integration for Distance-Based Shipping - Setup Guide

## Overview

The e-commerce application now features **Google Maps Geocoding Integration** for the checkout process. Customers can visually confirm their delivery address on an interactive map, and the system automatically calculates distance-based shipping fees in real-time.

## What's New

### 1. **Interactive Delivery Map** (Checkout Page)
- Full-featured Google Map embedded in the checkout page
- Customers can:
  - Click on the map to set delivery location
  - Use address search box for quick location lookup
  - View real-time map updates with location marker
- Map automatically geocodes customer address from profile on page load

### 2. **Real-Time Shipping Fee Calculation**
- Shipping fee calculated based on delivery coordinates (latitude/longitude)
- Displayed in checkout summary with zone information
- Updates dynamically as customer changes location on map

### 3. **Form Validation**
- Coordinates (latitude/longitude) are required fields before order submission
- User receives clear error message if location not confirmed
- Server-side validation ensures coordinates are within valid range (±90 lat, ±180 lng)

## Files Modified

### Views
- **`resources/views/orders/create.blade.php`**
  - Added delivery map container (300px height)
  - Added hidden input fields for `shipping_latitude` and `shipping_longitude`
  - Updated shipping display to show dynamic fee calculation
  - Added Google Maps script and initialization code
  - Added form submission validation for coordinates
  
- **`resources/views/cart/index.blade.php`**
  - Added shipping note: "Final shipping fee will be calculated at checkout based on your delivery address"

### Controllers
- **`app/Http/Controllers/OrderController.php`**
  - Added `calculateShipping()` method (POST endpoint for AJAX requests)
  - Returns JSON response with shipping fee, distance, and zone information
  - Server-side validation for latitude/longitude

### Routes
- **`routes/web.php`**
  - Added route: `POST /orders/calculate-shipping` → `OrderController@calculateShipping`
  - Route name: `orders.calculate-shipping`

### Services (Updated)
- **`app/Services/ShippingCalculationService.php`**
  - Updated `calculateShippingFeeWithFallback()` to return proper API response format
  - Response includes: `success`, `fee`, `distance`, `zone_name`, `zone_id`

## Setup Instructions

### Step 1: Configure Google Maps API Key

Add your Google Maps API key to `.env`:

```env
GOOGLE_MAPS_API_KEY=YOUR_API_KEY_HERE
```

**Note:** Your API key must have the following APIs enabled in Google Cloud Console:
- Maps JavaScript API
- Places API (for address search)
- Geocoding API (for address to coordinates conversion)

### Step 2: Run Migrations (if not already done)

```bash
php artisan migrate
php artisan db:seed --class=ShippingZoneSeeder
```

This creates the shipping infrastructure and populates sample data:
- **Manila Warehouse**: 14.5995, 120.9842
  - Local Zone (0–15km): ₱50
  - Metro Zone (15–50km): ₱100
  - Provincial Zone (50–150km): ₱200
  - Far Provincial Zone (150–500km): ₱350

- **Cebu Branch**: 10.3157, 123.8854
  - Same zone structure as Manila

### Step 3: Test the Checkout Flow

1. Add items to cart
2. Proceed to checkout
3. Confirm delivery address on the interactive map
4. Watch shipping fee calculate in real-time
5. Place order

## How It Works

### User Experience Flow

```
Customer adds items to cart
        ↓
Proceeds to checkout
        ↓
Views interactive map with address
        ↓
Clicks on map or searches for address
        ↓
Marker appears, coordinates captured
        ↓
JavaScript calls /orders/calculate-shipping (AJAX)
        ↓
Backend calculates distance using Haversine formula
        ↓
Shipping fee determined from distance-based zone
        ↓
Frontend updates summary with fee and zone info
        ↓
Customer reviews total with calculated shipping
        ↓
Submits order with coordinates included
        ↓
Backend validates coordinates and creates order
```

### Backend Calculation Flow

```
Customer submits form with:
- Delivery coordinates (lat/lng)
- Payment method
- Address and phone

Server validates coordinates
        ↓
Calls ShippingCalculationService::calculateShippingFeeWithFallback()
        ↓
Service finds active ShippingPivotPoint (warehouse)
        ↓
Model calculates distance using Haversine formula
        ↓
Searches for matching ShippingZone (based on distance)
        ↓
Returns zone fee or default fee (₱100)
        ↓
Order created with shipping cost included
```

## API Endpoint Reference

### Calculate Shipping Fee

**Endpoint:** `POST /orders/calculate-shipping`

**Request Body:**
```json
{
  "latitude": 14.5995,
  "longitude": 120.9842
}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "shipping_fee": 100.00,
  "distance": 25.45,
  "zone_name": "Metro Zone",
  "zone_id": 2
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Unable to calculate shipping fee"
}
```

## Validation Rules

### Client-Side (JavaScript)
- Latitude: `-90` to `90`
- Longitude: `-180` to `180`
- Both fields required before form submission

### Server-Side (Laravel)
- Latitude: required, numeric, between -90 and 90
- Longitude: required, numeric, between -180 and 180

## Troubleshooting

### Map Not Displaying
- Verify `GOOGLE_MAPS_API_KEY` is set in `.env`
- Check that browser has access to `maps.googleapis.com`
- Open browser console (F12) for JavaScript errors

### Address Search Not Working
- Ensure "Places API" is enabled in Google Cloud Console
- Check that API key has Places API permission

### Shipping Fee Showing Error
- Verify migrations were run: `php artisan migrate`
- Check that at least one `ShippingPivotPoint` exists and is active
- Verify seeder was run: `php artisan db:seed --class=ShippingZoneSeeder`

### Coordinates Not Saving
- Check browser console for form submission errors
- Verify coordinates are being set when map is clicked
- Confirm form validation passes (check Network tab)

## Admin Management

### View/Edit Shipping Zones

Access database directly or create admin panel:

```bash
# View all pivot points
php artisan tinker
ShippingPivotPoint::all()

# View zones for a pivot point
ShippingPivotPoint::first()->shippingZones

# Update a zone fee
$zone = ShippingZone::find(1);
$zone->update(['shipping_fee' => 150]);
```

### Add New Warehouse/Pivot Point

```php
ShippingPivotPoint::create([
    'name' => 'Davao Branch',
    'latitude' => 7.0905,
    'longitude' => 125.6127,
    'address' => 'Davao City, Philippines',
    'is_active' => true
]);
```

## Distance Calculation Formula

The system uses the **Haversine Formula** for accurate great-circle distance calculations:

```
R = 6371 km (Earth's radius)
Δlat = lat2 - lat1
Δlon = lon2 - lon1

a = sin²(Δlat/2) + cos(lat1) × cos(lat2) × sin²(Δlon/2)
c = 2 × atan2(√a, √(1−a))
d = R × c
```

This provides accurate distances up to ±200m for shipping purposes.

## Next Steps

1. **Create Admin Panel** for managing shipping zones (optional)
2. **Implement Address Validation** for invalid coordinates
3. **Add Multi-Warehouse Selection** (choose nearest warehouse automatically)
4. **Integrate with Delivery Partners** for live tracking
5. **Add Shipping History** to customer dashboard

## Support

For issues or questions regarding the distance-based shipping feature:

1. Check `SHIPPING_IMPLEMENTATION.md` for detailed technical documentation
2. Review error logs: `storage/logs/laravel.log`
3. Verify all migrations are applied: `php artisan migrate:status`
4. Ensure API keys and environment variables are correctly configured

---

**Last Updated:** December 7, 2025
**Version:** 1.0
