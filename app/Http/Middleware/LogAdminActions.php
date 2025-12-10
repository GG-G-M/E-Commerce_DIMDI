<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class LogAdminActions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $user = Auth::user();
            if ($user && in_array($user->role, [
                \App\Models\User::ROLE_ADMIN ?? 'admin',
                \App\Models\User::ROLE_SUPER_ADMIN ?? 'super_admin'
            ])) {
                // Only log write operations (POST/PUT/PATCH/DELETE) and login is handled separately
                $method = strtoupper($request->method());
                if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                    $payload = $request->except(['_token', '_method', 'password', 'password_confirmation']);

                    Audit::create([
                        'user_id' => $user->id,
                        'action' => $method,
                        'auditable_type' => null,
                        'auditable_id' => null,
                        'old_values' => null,
                        'new_values' => json_encode($payload),
                        'url' => $request->fullUrl(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // swallow exceptions to avoid breaking normal flow
            // You can log this if you want
        }

        return $response;
    }
}
