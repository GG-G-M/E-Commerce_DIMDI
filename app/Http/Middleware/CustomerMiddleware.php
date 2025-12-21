<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow customers normally; admins and super admins can view store/customer pages; block delivery/riders.
        if (!($user->isCustomer() || $user->isAdmin() || $user->isSuperAdmin())) {
            return redirect()->route('home')->with('error', 'Access denied. Customers only.');
        }

        return $next($request);
    }
}
