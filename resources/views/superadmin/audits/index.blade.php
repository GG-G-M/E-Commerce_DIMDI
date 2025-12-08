@extends('layouts.superadmin')

@section('content')
    <style>
        .audit-panel {
            background: var(--sa-card-bg, #fff);
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.04);
        }

        .audit-header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px; }
        .audit-filters { display:flex; gap:8px; flex-wrap:wrap; align-items:center; }

        .audit-table thead th { background: transparent; color: var(--bs-success); border-bottom:2px solid rgba(0,0,0,0.03); }
        .audit-table td, .audit-table th { vertical-align:middle; }

        .data-pre { max-height:120px; overflow:auto; white-space:pre-wrap; word-break:break-word; background:#FAFBFD; padding:8px; border-radius:6px; }
        .muted-small { color:#6c757d; font-size:.85rem; }
    </style>

    <div class="container py-4">
        <div class="audit-panel">
            <div class="audit-header">
                <h4 class="mb-0 text-success"><i class="fas fa-user-shield me-2"></i>Audit Log</h4>
                <div>
                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
            </div>

            <form method="GET" class="audit-filters mb-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search url, ip, data..." class="form-control form-control-sm" style="min-width:220px">

                <select name="user_id" class="form-select form-select-sm">
                    <option value="">All admins</option>
                    @foreach($admins as $a)
                        <option value="{{ $a->id }}" {{ request('user_id') == $a->id ? 'selected' : '' }}>{{ $a->first_name }} {{ $a->last_name }}</option>
                    @endforeach
                </select>

                <select name="action" class="form-select form-select-sm">
                    <option value="">All actions</option>
                    <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                    <option value="POST" {{ request('action') == 'POST' ? 'selected' : '' }}>Create (POST)</option>
                    <option value="PUT" {{ request('action') == 'PUT' ? 'selected' : '' }}>Update (PUT/PATCH)</option>
                    <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>Delete</option>
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">

                <button class="btn btn-success btn-sm">Filter</button>
                <a href="{{ route('superadmin.audits.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </form>

            <div class="table-responsive">
                <table class="table table-sm audit-table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width:56px">#</th>
                            <th style="width:140px">When</th>
                            <th>Admin</th>
                            <th style="width:110px">Action</th>
                            <th>URL</th>
                            <th style="width:220px">Old Data</th>
                            <th style="width:220px">New Data</th>
                            <th style="width:180px">IP / Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($audits as $audit)
                            <tr>
                                <td class="text-muted">{{ $audit->id }}</td>
                                <td class="muted-small">{{ $audit->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($audit->user)
                                        <div class="fw-semibold">{{ $audit->user->first_name }} {{ $audit->user->last_name }}</div>
                                        <div class="muted-small">{{ $audit->user->email ?? '' }}</div>
                                    @else
                                        <div class="muted-small">—</div>
                                    @endif
                                </td>
                                <td class="fw-bold text-uppercase text-center">{{ $audit->action }}</td>
                                <td class="muted-small" style="white-space:normal">{{ $audit->url ?? '—' }}</td>
                                <td>
                                    @if($audit->old_values)
                                        <pre class="data-pre small">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                    @else
                                        <div class="muted-small">—</div>
                                    @endif
                                </td>
                                <td>
                                    @if($audit->new_values)
                                        <pre class="data-pre small">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                    @else
                                        <div class="muted-small">—</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="muted-small">{{ $audit->ip_address ?? '—' }}</div>
                                    <div class="muted-small">{{ \Illuminate\Support\Str::limit($audit->user_agent,100) }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No audit records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('superadmin.partials.pagination', ['paginator' => $audits])
        </div>
    </div>

@endsection
