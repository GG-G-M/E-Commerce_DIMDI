<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
                    $action = $this->determineAction($request);
                    $payload = $request->except(['_token', '_method', 'password', 'password_confirmation']);

                    // For updates and deletes, try to capture old values
                    $oldValues = null;
                    if (in_array($action, ['edited', 'deleted', 'archived', 'unarchived'])) {
                        $oldValues = $this->getOldValues($request, $action);
                    }

                    Audit::create([
                        'user_id' => $user->id,
                        'action' => $action,
                        'auditable_type' => $this->getAuditableType($request),
                        'auditable_id' => $this->getAuditableId($request),
                        'old_values' => $oldValues,
                        'new_values' => in_array($action, ['created', 'edited']) ? $payload : null,
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

    /**
     * Determine the specific action based on the route and method
     */
    private function determineAction(Request $request): string
    {
        $routeName = Route::currentRouteName();
        $method = strtoupper($request->method());
        $uri = $request->path();

        // Check for archive/unarchive actions
        if (str_contains($routeName, '.archive')) {
            return 'archived';
        }
        if (str_contains($routeName, '.unarchive')) {
            return 'unarchived';
        }

        // Check for specific actions based on URI patterns
        if (str_contains($uri, '/archive') || str_contains($uri, '/unarchive')) {
            return str_contains($uri, '/archive') ? 'archived' : 'unarchived';
        }

        // Standard CRUD actions
        switch ($method) {
            case 'POST':
                return 'created';
            case 'PUT':
            case 'PATCH':
                return 'edited';
            case 'DELETE':
                return 'deleted';
            default:
                return $method;
        }
    }

    /**
     * Get the auditable type (model class) from the route
     */
    private function getAuditableType(Request $request): ?string
    {
        $routeName = Route::currentRouteName();
        if (!$routeName) return null;

        // Map route patterns to model classes
        $modelMap = [
            'admin.products' => 'App\\Models\\Product',
            'admin.categories' => 'App\\Models\\Category',
            'admin.brands' => 'App\\Models\\Brand',
            'admin.customers' => 'App\\Models\\User',
            'admin.suppliers' => 'App\\Models\\Supplier',
            'admin.warehouses' => 'App\\Models\\Warehouse',
            'admin.deliveries' => 'App\\Models\\User',
            'admin.stock_checkers' => 'App\\Models\\StockChecker',
            'admin.abouts' => 'App\\Models\\About',
            'admin.stock_in' => 'App\\Models\\StockIn',
            'admin.stock_out' => 'App\\Models\\StockOut',
            'superadmin.users' => 'App\\Models\\User',
        ];

        foreach ($modelMap as $prefix => $model) {
            if (str_starts_with($routeName, $prefix)) {
                return $model;
            }
        }

        return null;
    }

    /**
     * Get the auditable ID from the route parameters
     */
    private function getAuditableId(Request $request): ?int
    {
        $route = Route::current();
        if (!$route) return null;

        $parameters = $route->parameters();

        // Common parameter names for IDs
        $idKeys = ['product', 'category', 'brand', 'user', 'supplier', 'warehouse', 'delivery', 'stockIn', 'stockOut', 'id'];

        foreach ($idKeys as $key) {
            if (isset($parameters[$key])) {
                $value = $parameters[$key];
                return is_object($value) ? $value->id : (int) $value;
            }
        }

        return null;
    }

    /**
     * Attempt to get old values for updates and deletes
     */
    private function getOldValues(Request $request, string $action): ?array
    {
        $auditableType = $this->getAuditableType($request);
        $auditableId = $this->getAuditableId($request);

        if (!$auditableType || !$auditableId) {
            return null;
        }

        try {
            $modelClass = $auditableType;
            if (class_exists($modelClass)) {
                $model = $modelClass::find($auditableId);
                if ($model) {
                    // For archived/unarchived, we want to capture the current state before the action
                    if (in_array($action, ['archived', 'unarchived'])) {
                        return $model->toArray();
                    }

                    // For edits, we need to get the original attributes from the model
                    // This is a simplified approach - in a real scenario, you'd want to use model events
                    return $model->getOriginal();
                }
            }
        } catch (\Throwable $e) {
            // If we can't get old values, return null
            return null;
        }

        return null;
    }
}
