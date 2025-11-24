<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $table = config('session.table', 'sessions');

        $sessionsQuery = DB::table($table)->orderBy('last_activity', 'desc');

        $sessions = $sessionsQuery->paginate(50)->appends($request->query());

        // Enrich sessions with readable data
        $rows = $sessions->map(function ($s) {
            $item = (array) $s;

            $userModel = null;

            // If table has explicit user_id column, try to resolve
            if (isset($item['user_id']) && $item['user_id']) {
                try {
                    $userModel = User::find($item['user_id']);
                } catch (\Throwable $e) {
                    $userModel = null;
                }
            } else {
                // Attempt to decode payload (best-effort)
                if (!empty($item['payload'])) {
                    try {
                        $decoded = @unserialize(base64_decode($item['payload']));
                        if (is_array($decoded) && isset($decoded['login_web'])) {
                            // common key might not exist; skip
                        }
                        // Try to find user id inside serialized payload
                        $payloadStr = print_r($decoded, true);
                        if (preg_match('/"id";i?:?(\d+)/', $payloadStr, $m)) {
                            $uid = (int) $m[1];
                            $userModel = User::find($uid);
                        }
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }

            return [
                'id' => $item['id'] ?? null,
                'user' => $userModel,
                'ip_address' => $item['ip_address'] ?? null,
                'user_agent' => $item['user_agent'] ?? null,
                'last_activity' => isset($item['last_activity']) ? Carbon::createFromTimestamp($item['last_activity'])->toDateTimeString() : null,
                'payload_preview' => isset($item['payload']) ? \Illuminate\Support\Str::limit($item['payload'], 200) : null,
            ];
        });

        return view('admin.sessions', [
            'sessions' => $sessions,
            'rows' => $rows,
        ]);
    }
}
