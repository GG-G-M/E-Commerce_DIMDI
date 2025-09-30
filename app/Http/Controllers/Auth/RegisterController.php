<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
        ]);

        // Transfer guest cart to user
        $this->transferGuestCartToUser($user);

        Auth::login($user);

        return redirect()->intended('/')->with('success', 'Registration successful! Welcome to DIMDI Store.');
    }

    private function transferGuestCartToUser(User $user)
    {
        $sessionId = session()->get('cart_session_id');
        
        if ($sessionId) {
            // Update cart items from session to user
            Cart::where('session_id', $sessionId)->update([
                'session_id' => null,
                'user_id' => $user->id
            ]);
            
            // Clear the session cart ID
            session()->forget('cart_session_id');
        }
    }
}