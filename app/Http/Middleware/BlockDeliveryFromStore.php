<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockDeliveryFromStore
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isDelivery()) {
            return redirect()->route('delivery.dashboard')->with('error', 'Delivery accounts cannot access the customer storefront.');
        }

        return $next($request);
    }
}
