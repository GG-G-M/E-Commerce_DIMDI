<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Redirect authenticated users to their appropriate dashboard
        if (Auth::check()) {
            $user = Auth::user();
            
            // Redirect based on role
            if ($user->role === 'super_admin') {
                return redirect()->route('superadmin.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'delivery') {
                return redirect()->route('delivery.dashboard');
            }
            
            // Default redirect for customers
            return redirect('/');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Handle encrypted password
        $password = $request->input('password');
        
        // If password is base64 encoded, decode it
        if (preg_match('/^[A-Za-z0-9+\/]*={0,2}$/', $password) && base64_decode($password, true) !== false) {
            $decodedPassword = base64_decode($password);
        } else {
            // Fallback for non-encrypted passwords (backward compatibility)
            $decodedPassword = $password;
        }

        $credentials = [
            'email' => $request->input('email'),
            'password' => $decodedPassword
        ];
        
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Transfer guest cart to user after login
            $this->transferGuestCartToUser(Auth::user());

            $user = Auth::user();
            
            // FIXED: Added super_admin role check FIRST (before admin)
            if ($user->role === 'super_admin') {
                return redirect()->route('superadmin.dashboard')->with('login_success', true);
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('login_success', true);
            } elseif ($user->role === 'delivery') {
                return redirect()->route('delivery.dashboard')->with('login_success', true);
            }

            // Redirect to intended URL or home for customers
            return redirect()->intended('/')->with('login_success', true);
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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            
            // Find or create user
            $user = $this->findOrCreateUser($socialUser, 'google');
            
            // Login the user
            Auth::login($user, true);
            
            // Transfer guest cart to user
            $this->transferGuestCartToUser($user);
            
            // Redirect based on role
            return $this->redirectBasedOnRole($user);
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Google login failed. Please try again.');
        }
    }

    /**
     * Redirect to Facebook
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback
     */
    public function handleFacebookCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            
            // Find or create user
            $user = $this->findOrCreateUser($socialUser, 'facebook');
            
            // Login the user
            Auth::login($user, true);
            
            // Transfer guest cart to user
            $this->transferGuestCartToUser($user);
            
            // Redirect based on role
            return $this->redirectBasedOnRole($user);
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Facebook login failed. Please try again.');
        }
    }

    /**
     * Find or create user from social provider
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        // Check if user exists by email
        $user = User::where('email', $socialUser->getEmail())->first();
        
        if ($user) {
            // Update provider info if missing
            if (empty($user->{$provider . '_id'})) {
                $user->update([
                    $provider . '_id' => $socialUser->getId(),
                    $provider . '_avatar' => $socialUser->getAvatar()
                ]);
            }
            return $user;
        }
        
        // Extract name components
        $name = $socialUser->getName();
        $nameParts = explode(' ', $name);
        
        $firstName = $nameParts[0] ?? 'User';
        $lastName = $nameParts[1] ?? '';
        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : null;
        
        if (empty($lastName) && count($nameParts) > 1) {
            $lastName = $nameParts[count($nameParts) - 1];
        }
        
        // Create new user
        $user = User::create([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(24)), // Random password
            'role' => 'customer',
            $provider . '_id' => $socialUser->getId(),
            $provider . '_avatar' => $socialUser->getAvatar(),
            'email_verified_at' => now(), // Social login emails are verified
        ]);
        
        return $user;
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard')->with('login_success', true);
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('login_success', true);
        } elseif ($user->role === 'delivery') {
            return redirect()->route('delivery.dashboard')->with('login_success', true);
        }
        
        return redirect()->intended('/')->with('login_success', true);
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