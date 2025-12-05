<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'street_address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:100', // code from API
            'city' => 'nullable|string|max:100',     // code from API
            'province' => 'nullable|string|max:100', // code from API
            'region' => 'nullable|string|max:100',   // optional if you compute it
            'country' => 'nullable|string|max:100',
        ]);

        // Convert codes to readable names using AddressController helpers
        $provinceName = $request->province ? AddressController::getProvinceName($request->province) : $user->province;
        $cityName     = $request->city ? AddressController::getCityName($request->city) : $user->city;
        $barangayName = $request->barangay ? AddressController::getBarangayName($request->barangay) : $user->barangay;

        // Fetch region name from province code if not provided
        $regionName = $request->region;
        if (!$regionName && $request->province) {
            $regionName = $this->getRegionFromProvince($request->province);
        } elseif (!$regionName) {
            $regionName = $user->region;
        }

        // Update user
        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'street_address' => $request->street_address,
            'barangay' => $barangayName,
            'city' => $cityName,
            'province' => $provinceName,
            'region' => $regionName,
            'country' => $request->country,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password updated successfully!');
    }

    /**
     * Helper: Get region name from province code
     */
    private function getRegionFromProvince($provinceCode)
    {
        $res = Http::get("https://psgc.gitlab.io/api/provinces/$provinceCode");
        if ($res->successful()) {
            return $res->json()['region'] ?? '';
        }
        return '';
    }
}
