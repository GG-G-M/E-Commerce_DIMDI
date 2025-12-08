<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class AuditServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Listen for login events
        Event::listen(Login::class, function (Login $event) {
            try {
                $user = $event->user;
                // Log only admin users
                if ($user && in_array($user->role, [
                    \App\Models\User::ROLE_ADMIN ?? 'admin',
                    \App\Models\User::ROLE_SUPER_ADMIN ?? 'super_admin'
                ])) {
                    Audit::create([
                        'user_id' => $user->id,
                        'action' => 'login',
                        'auditable_type' => null,
                        'auditable_id' => null,
                        'old_values' => null,
                        'new_values' => null,
                        'url' => request()->fullUrl(),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);
                }
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
}
