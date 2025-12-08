<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\User;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isSuperAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $query = Audit::with('user')->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($s) use ($q) {
                $s->where('url', 'like', "%{$q}%")
                  ->orWhere('user_agent', 'like', "%{$q}%")
                  ->orWhere('ip_address', 'like', "%{$q}%")
                  ->orWhere('new_values', 'like', "%{$q}%")
                  ->orWhere('old_values', 'like', "%{$q}%");
            });
        }

        $audits = $query->paginate(25)->withQueryString();

        $admins = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])->orderBy('first_name')->get();

        return view('superadmin.audits.index', compact('audits', 'admins'));
    }
}
