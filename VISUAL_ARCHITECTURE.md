# Visual Architecture Guide - Google Maps Shipping Integration

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                      CUSTOMER BROWSER                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌─────────────────────────────────────┐                        │
│  │   Checkout Page (create.blade.php)  │                        │
│  │                                     │                        │
│  │  ┌──────────────────────────────┐  │                        │
│  │  │  Delivery Address Card       │  │  Hidden Fields:        │
│  │  │  - Name, Email, Phone        │  │  - shipping_latitude   │
│  │  │  - Full Address              │  │  - shipping_longitude  │
│  │  │                              │  │                        │
│  │  │  ┌──────────────────────┐   │  │                        │
│  │  │  │   Google Map         │   │  │  300px Height          │
│  │  │  │   (Interactive)      │   │  │  Click to Set          │
│  │  │  │                      │   │  │  Location Marker       │
│  │  │  │  [Search Box]        │   │  │  Drag/Pan/Zoom         │
│  │  │  └──────────────────────┘   │  │                        │
│  │  └──────────────────────────────┘  │                        │
│  │                                     │                        │
│  │  Order Summary Card:                │                        │
│  │  - Subtotal: ₱500                   │                        │
│  │  - Shipping: ₱100 (updates)         │                        │
│  │  - Total: ₱600 (updates)            │                        │
│  │  - Zone Info: Metro Zone, 25.45 km  │                        │
│  │                                     │                        │
│  │  [Place Order & Pay] Button          │                        │
│  └─────────────────────────────────────┘                        │
│                                                                   │
│  JavaScript Layer (Embedded):                                    │
│  ├─ initializeMap() - Load Google Maps API                       │
│  ├─ addSearchBox() - Places API autocomplete                     │
│  ├─ setMarkerAndCoordinates() - Set marker position              │
│  ├─ calculateShippingFee() - AJAX call to backend                │
│  └─ Form validation - Check coordinates before submit            │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                            │
                            │ AJAX POST
                            │ /orders/calculate-shipping
                            │ {latitude, longitude}
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                         LARAVEL SERVER                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌─────────────────────────────────────┐                        │
│  │     routes/web.php                  │                        │
│  │  POST /orders/calculate-shipping    │                        │
│  │  → OrderController@calculateShipping│                        │
│  └────────────────┬────────────────────┘                        │
│                   │                                              │
│                   ▼                                              │
│  ┌─────────────────────────────────────┐                        │
│  │  OrderController::calculateShipping │                        │
│  │                                     │                        │
│  │  1. Validate request               │                        │
│  │     (±90, ±180 check)              │                        │
│  │                                     │                        │
│  │  2. Call ShippingCalculationService │                        │
│  │     ::calculateShippingFeeWithFall- │                        │
│  │       back($lat, $lng, 100)        │                        │
│  │                                     │                        │
│  │  3. Return JSON response           │                        │
│  │     {success, fee, distance, zone} │                        │
│  └────────────────┬────────────────────┘                        │
│                   │                                              │
│                   ▼                                              │
│  ┌─────────────────────────────────────┐                        │
│  │  ShippingCalculationService         │                        │
│  │                                     │                        │
│  │  calculateShippingFeeWithFallback() │                        │
│  │  ├─ Check if pivot point exists    │                        │
│  │  ├─ If not: return error           │                        │
│  │  ├─ If exists: call pivotPoint     │                        │
│  │  │   →calculateShippingFee()       │                        │
│  │  ├─ Format result with success=true│                        │
│  │  └─ Return {success, fee, distance,│                        │
│  │     zone_name, zone_id}            │                        │
│  └────────────────┬────────────────────┘                        │
│                   │                                              │
│                   ▼                                              │
│  ┌─────────────────────────────────────┐                        │
│  │  ShippingPivotPoint Model           │                        │
│  │                                     │                        │
│  │  calculateShippingFee($lat, $lng):  │                        │
│  │  ├─ Call haversineDistance()       │                        │
│  │  │   ├─ Δlat = lat2 - lat1        │                        │
│  │  │   ├─ Δlon = lon2 - lon1        │                        │
│  │  │   ├─ a = sin²(Δlat/2) + cos... │                        │
│  │  │   ├─ c = 2 × atan2(√a, √1-a)   │                        │
│  │  │   └─ d = 6371 × c              │                        │
│  │  │                                │                        │
│  │  ├─ Find zone: min ≤ distance ≤ max│                        │
│  │  │   (from database query)         │                        │
│  │  │                                │                        │
│  │  └─ Return {distance, fee, zone,   │                        │
│  │     zone_id}                       │                        │
│  └────────────────┬────────────────────┘                        │
│                   │                                              │
│                   ▼                                              │
│       Database Query (Eager Load):                              │
│  ShippingZone::where('is_active', true)                        │
│              ->whereRaw('distance BETWEEN min AND max')         │
│              ->first()                                          │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                            │
                            │ JSON Response
                            │ {success, fee, distance, zone}
                            ▼
        Update UI with calculated shipping fee
        └─ Shipping: ₱100 (Metro Zone, 25.45 km)
        └─ Total: ₱600 (updated)
```

## Database Schema

```
┌─────────────────────────────────────────────────┐
│         shipping_pivot_points                   │
├─────────────────────────────────────────────────┤
│ id (PK)                    │ INTEGER             │
│ name                       │ VARCHAR(255)        │
│ latitude                   │ DECIMAL(8,5)        │
│ longitude                  │ DECIMAL(8,5)        │
│ address                    │ TEXT                │
│ is_active                  │ BOOLEAN (DEFAULT 1) │
│ created_at                 │ TIMESTAMP           │
│ updated_at                 │ TIMESTAMP           │
└─────────────────────────────────────────────────┘
              │
              │ One-to-Many
              │
┌─────────────▼──────────────────────────────────┐
│         shipping_zones                         │
├────────────────────────────────────────────────┤
│ id (PK)                    │ INTEGER            │
│ pivot_point_id (FK)        │ INTEGER            │
│ zone_name                  │ VARCHAR(255)       │
│ min_distance               │ DECIMAL(10,2)      │
│ max_distance               │ DECIMAL(10,2)      │
│ shipping_fee               │ DECIMAL(10,2)      │
│ description                │ TEXT               │
│ is_active                  │ BOOLEAN (DEFAULT 1)│
│ created_at                 │ TIMESTAMP          │
│ updated_at                 │ TIMESTAMP          │
│                            │                    │
│ UNIQUE: (pivot_point_id,   │                    │
│          zone_name)        │                    │
└────────────────────────────────────────────────┘
```

## Sample Data Flow

```
Customer Input:
└─ Address: "123 Makati Avenue, Makati, NCR"
└─ Coordinates: 14.5546, 121.0240

Browser JavaScript:
1. User clicks map → marker placed
2. Hidden fields populated:
   └─ shipping_latitude = 14.5546
   └─ shipping_longitude = 121.0240

3. AJAX Request:
   POST /orders/calculate-shipping
   {
     "latitude": 14.5546,
     "longitude": 121.0240
   }

Server Calculation:
1. Validate coordinates ✓ (14.5546 between -90 to 90 ✓)
2. Find active pivot point: Manila (14.5995, 120.9842)
3. Calculate distance:
   ├─ Δlat = 14.5546 - 14.5995 = -0.0449
   ├─ Δlon = 121.0240 - 120.9842 = 0.0398
   ├─ Using Haversine: distance ≈ 5.45 km
   
4. Find matching zone:
   └─ Check: 5.45 BETWEEN min AND max?
   └─ Local Zone (0-15) ✓ MATCH
   └─ shipping_fee = 50
   
5. Format response:
   {
     "success": true,
     "shipping_fee": 50,
     "distance": 5.45,
     "zone_name": "Local Zone",
     "zone_id": 1
   }

Browser Update:
├─ UI: "Shipping: ₱50"
├─ Info: "Local Zone, 5.45 km"
└─ Total: ₱550 (₱500 subtotal + ₱50 shipping)

User Submits Order:
├─ Form includes:
│  ├─ shipping_latitude = 14.5546
│  ├─ shipping_longitude = 121.0240
│  └─ other order details
│
└─ Server:
   ├─ Validates coordinates ✓
   ├─ Recalculates shipping ✓ (₱50)
   ├─ Creates order with shipping_cost = 50
   └─ Order saved to database
```

## Component Interaction Diagram

```
┌──────────────────────────────────────────────────────────────┐
│                     USER INTERACTION                         │
└──────────────────────┬───────────────────────────────────────┘
                       │
        ┌──────────────┴──────────────┐
        │                             │
        ▼                             ▼
    Click Map                   Search Address
        │                             │
        └──────────────┬──────────────┘
                       │
                       ▼
            Place Marker on Map
                       │
                       ▼
        setMarkerAndCoordinates()
         ├─ Marker animation
         ├─ Map pan/zoom
         └─ Update hidden fields
                       │
                       ▼
        calculateShippingFee() [AJAX]
                       │
        ┌──────────────┴──────────────┐
        │                             │
        ▼                             ▼
    Success Response              Error Response
        │                             │
        └──────────────┬──────────────┘
                       │
                       ▼
            Update UI Elements:
        ├─ display-shipping
        ├─ shipping-info
        └─ display-total
                       │
                       ▼
            Form Ready for Submission
            (coordinates captured)
                       │
                       ▼
        [Place Order & Pay] Button
                       │
                       ▼
            Form Validation:
         ├─ Coordinates present? ✓
         ├─ Payment method selected? ✓
         └─ All fields valid? ✓
                       │
                       ▼
            POST /orders (Create Order)
```

## Request/Response Flow

```
CLIENT                                SERVER
  │                                     │
  │  1. Load checkout page              │
  │────────────────────────────────────>│
  │                                     │
  │  2. GET /orders/create              │
  │<────────────────────────────────────│
  │     (HTML with map container)       │
  │                                     │
  │  3. Load Google Maps JS API         │
  │     (via CDN)                       │
  │                                     │
  │  4. initializeMap() runs            │
  │     - Display map                   │
  │     - Add search box                │
  │                                     │
  │  5. User clicks map                 │
  │                                     │
  │  6. POST /orders/calculate-shipping │
  │     {lat, lng}                      │
  │────────────────────────────────────>│
  │                                     │
  │                    Validate coords  │
  │                    Find pivot point │
  │                    Calculate distance
  │                    Find zone        │
  │                                     │
  │     {success, fee, distance, zone}  │
  │<────────────────────────────────────│
  │                                     │
  │  7. Update UI with response         │
  │                                     │
  │  8. User fills payment info         │
  │                                     │
  │  9. POST /orders (Submit Order)     │
  │     {lat, lng, payment, address...} │
  │────────────────────────────────────>│
  │                                     │
  │                    Validate all     │
  │                    Calculate ship   │
  │                    Create order     │
  │                    Store coordinates│
  │                                     │
  │     Redirect to payment             │
  │<────────────────────────────────────│
  │                                     │
  │  10. Process payment                │
  │     (via PayMongo)                  │
  │                                     │
  │  11. Payment success notification   │
  │<────────────────────────────────────│
  │                                     │
```

## Error Handling Flow

```
Map/Coordinates Issues:
├─ Map fails to load
│  └─ Check: GOOGLE_MAPS_API_KEY in .env
│
├─ Search box doesn't work
│  └─ Check: Places API enabled
│
├─ Coordinates not captured
│  └─ Verify: marker position set
│
└─ Form won't submit
   └─ Error: "Please confirm delivery location"

Shipping Calculation Issues:
├─ Fee always ₱100 (fallback)
│  ├─ Check: Pivot point exists & active
│  ├─ Check: Zones exist for pivot point
│  └─ Check: Zone distance ranges configured
│
├─ Incorrect distance
│  └─ Issue: Zone min/max overlapping
│
└─ API returns error
   └─ Check: Server logs for validation errors

Database Issues:
├─ Tables don't exist
│  └─ Solution: php artisan migrate
│
├─ No pivot points
│  └─ Solution: php artisan db:seed --class=ShippingZoneSeeder
│
└─ Zones exist but not used
   └─ Check: is_active = 1 for both pivot point and zones
```

---

**Visual Architecture Guide v1.0**
**Updated: December 7, 2025**
