<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AddressController;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'barangay' => 'required|string|max:100', // code from API
            'city' => 'required|string|max:100',     // code from API
            'province' => 'required|string|max:100', // code from API
            'region' => 'nullable|string|max:100',   // optional if you compute it
            'country' => 'required|string|max:100',
        ]);

        // Build full name
        $fullName = $request->first_name;
        if ($request->middle_name) {
            $fullName .= ' ' . $request->middle_name;
        }
        $fullName .= ' ' . $request->last_name;

        // Convert codes to readable names using AddressController helpers
        $provinceName = AddressController::getProvinceName($request->province);
        $cityName     = AddressController::getCityName($request->city);
        $barangayName = AddressController::getBarangayName($request->barangay);

        // Fetch region name from province code if not provided
        $regionName = $request->region;
        if (!$regionName) {
            $regionName = $this->getRegionFromProvince($request->province);
        }

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'street_address' => $request->street_address,
            'barangay' => $barangayName,
            'city' => $cityName,
            'province' => $provinceName,
            'region' => $regionName,
            'country' => $request->country,
            'role' => 'customer',
        ]);

        // Transfer guest cart to user
        $this->transferGuestCartToUser($user);

        Auth::login($user);

        return redirect()->intended(route('home'))->with('success', 'Registration successful! Welcome to DIMDI Store.');
    }

    private function transferGuestCartToUser(User $user)
    {
        $sessionId = session()->get('cart_session_id');

        if ($sessionId) {
            Cart::where('session_id', $sessionId)->update([
                'session_id' => null,
                'user_id' => $user->id
            ]);

            session()->forget('cart_session_id');
        }
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
