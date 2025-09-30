<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Transfer guest cart to user after login
            $this->transferGuestCartToUser(Auth::user());

            return redirect()->intended('/')->with('success', 'Login successful! Welcome back.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    private function transferGuestCartToUser(User $user)
    {
        $sessionId = session()->get('cart_session_id');
        
        if ($sessionId) {
            // Get existing user cart items
            $userCartItems = Cart::where('user_id', $user->id)->get()->keyBy('product_id');
            
            // Get guest cart items
            $guestCartItems = Cart::where('session_id', $sessionId)->get();
            
            foreach ($guestCartItems as $guestItem) {
                if (isset($userCartItems[$guestItem->product_id])) {
                    // Product exists in user's cart, update quantity
                    $userCartItems[$guestItem->product_id]->increment('quantity', $guestItem->quantity);
                    $guestItem->delete();
                } else {
                    // Product doesn't exist in user's cart, transfer it
                    $guestItem->update([
                        'session_id' => null,
                        'user_id' => $user->id
                    ]);
                }
            }
            
            // Clear the session cart ID
            session()->forget('cart_session_id');
        }
    }
}