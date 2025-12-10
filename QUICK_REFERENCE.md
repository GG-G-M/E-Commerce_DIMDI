# Quick Reference - Distance-Based Shipping Integration

## 🚀 Quick Start (5 minutes)

### 1. Enable Shipping System
```bash
# Apply database changes
php artisan migrate

# Populate sample warehouses
php artisan db:seed --class=ShippingZoneSeeder
```

### 2. Configure Google Maps
Add to `.env`:
```env
GOOGLE_MAPS_API_KEY=AIzaSyD1234567890ABC...
```

### 3. Done! 
- Users can now see interactive map on checkout
- Shipping fees calculate automatically based on delivery location

---

## 📍 How It Works

**Step 1:** Customer goes to checkout
**Step 2:** Map appears with their address
**Step 3:** Customer clicks map or searches for address
**Step 4:** Coordinates captured
**Step 5:** Backend calculates distance to warehouse
**Step 6:** Shipping fee displayed based on distance zone
**Step 7:** Order placed with calculated shipping

---

## 🔧 API Endpoints

### Calculate Shipping Fee
```
POST /orders/calculate-shipping
Content-Type: application/json
X-CSRF-TOKEN: {{csrf_token}}

{
  "latitude": 14.5995,
  "longitude": 120.9842
}

Response:
{
  "success": true,
  "shipping_fee": 100,
  "distance": 25.45,
  "zone_name": "Metro Zone",
  "zone_id": 2
}
```

---

## 💾 Database Structure

### shipping_pivot_points (Warehouses)
```
id          INTEGER (Primary)
name        STRING (e.g., "Manila")
latitude    DECIMAL(8,5)
longitude   DECIMAL(8,5)
address     TEXT
is_active   BOOLEAN
timestamps
```

### shipping_zones (Distance-Based Fees)
```
id              INTEGER (Primary)
pivot_point_id  INTEGER (Foreign Key)
zone_name       STRING (e.g., "Metro Zone")
min_distance    DECIMAL(10,2) (in km)
max_distance    DECIMAL(10,2) (in km)
shipping_fee    DECIMAL(10,2) (in PHP)
description     TEXT
is_active       BOOLEAN
timestamps
```

---

## 🎯 Sample Zones (Provided in Seeder)

| Zone | Distance | Fee |
|------|----------|-----|
| Local | 0-15 km | ₱50 |
| Metro | 15-50 km | ₱100 |
| Provincial | 50-150 km | ₱200 |
| Far Provincial | 150-500 km | ₱350 |

---

## 📝 Manage Shipping Zones (Admin)

### View All Zones
```php
php artisan tinker
ShippingPivotPoint::with('shippingZones')->get()
```

### Add New Warehouse
```php
ShippingPivotPoint::create([
    'name' => 'Davao Branch',
    'latitude' => 7.0905,
    'longitude' => 125.6127,
    'address' => 'Davao City',
    'is_active' => true
]);
```

### Update Zone Fee
```php
ShippingZone::find(1)->update(['shipping_fee' => 150]);
```

### Disable a Zone
```php
ShippingZone::find(1)->update(['is_active' => false]);
```

---

## 🧪 Test Cases

### ✅ Test 1: Calculate shipping for Manila address
```javascript
fetch('/orders/calculate-shipping', {
  method: 'POST',
  headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
  body: JSON.stringify({latitude: 14.5995, longitude: 120.9842})
})
.then(r => r.json())
.then(d => console.log(d)); // Should show ₱50 (Local Zone)
```

### ✅ Test 2: Test map interaction
- Open checkout page
- Click on map → marker should appear
- Check hidden fields: `shipping_latitude` and `shipping_longitude`
- Should not be empty

### ✅ Test 3: Test form validation
- Try submitting checkout without clicking map
- Should get error: "Please confirm your delivery location"

### ✅ Test 4: Test distance calculation accuracy
- Manila (14.5995, 120.9842) → Makati (14.5546, 121.0240) ≈ 5.5 km (Local Zone)
- Manila → Tagaytay (14.1358, 121.0192) ≈ 48 km (Metro Zone)

---

## 🐛 Troubleshooting

### Map not showing?
- Check `.env` has `GOOGLE_MAPS_API_KEY`
- Verify API key is valid and has Maps API enabled

### Shipping fee always ₱100?
- Confirm migrations ran: `php artisan migrate:status`
- Check seeder ran: `php artisan db:seed --class=ShippingZoneSeeder`
- Verify pivot point is active: `ShippingPivotPoint::where('is_active', true)->first()`

### Address search not working?
- Enable "Places API" in Google Cloud Console
- Ensure API key has Places permission

### Form won't submit?
- Open browser console (F12)
- Check shipping_latitude and shipping_longitude are populated
- Verify coordinates are valid (±90 lat, ±180 lng)

---

## 🔑 Key Files

| File | Purpose |
|------|---------|
| `OrderController.php` | Contains `calculateShipping()` method |
| `ShippingPivotPoint.php` | Warehouse model with distance calculation |
| `ShippingZone.php` | Zone/fee model |
| `ShippingCalculationService.php` | Centralized fee calculation logic |
| `orders/create.blade.php` | Checkout page with map |
| `routes/web.php` | Contains new `/orders/calculate-shipping` route |

---

## 🔐 Validation Rules

### Latitude
- Type: Numeric
- Range: -90 to 90
- Required: Yes

### Longitude
- Type: Numeric
- Range: -180 to 180
- Required: Yes

---

## 📊 Formula Used

**Haversine Formula** (Great-Circle Distance):

```
R = 6371 km (Earth radius)
Δlat = lat2 - lat1
Δlon = lon2 - lon1

a = sin²(Δlat/2) + cos(lat1) × cos(lat2) × sin²(Δlon/2)
c = 2 × atan2(√a, √(1−a))
distance = R × c
```

Accuracy: ±200 meters (sufficient for shipping)

---

## 🚢 Deployment Checklist

- [ ] Database migrated: `php artisan migrate`
- [ ] Seeder ran: `php artisan db:seed --class=ShippingZoneSeeder`
- [ ] `.env` has `GOOGLE_MAPS_API_KEY`
- [ ] Google Maps API enabled (Maps, Places, Geocoding)
- [ ] Checkout page tested
- [ ] Map loads correctly
- [ ] Shipping fees calculate
- [ ] Order submits with coordinates

---

## 📞 Support Resources

- Full docs: See `GOOGLE_MAPS_CHECKOUT_SETUP.md`
- Implementation details: See `SHIPPING_IMPLEMENTATION.md`
- Session summary: See `IMPLEMENTATION_SUMMARY.md`

---

**Last Updated:** December 7, 2025
**Quick Reference v1.0**
