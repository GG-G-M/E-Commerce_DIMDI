@extends('layouts.superadmin')

@section('content')
<style>
    :root {
        --primary: #2C8F0C;
        --primary-light: #E8F5E9;
        --primary-dark: #1B5E20;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-header h1 {
        color: var(--gray-800);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header .subtitle {
        color: var(--gray-600);
        font-size: 0.95rem;
    }

    /* Filter Panel */
    .filter-panel {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    /* Form Elements */
    .form-label {
        font-weight: 500;
        color: var(--gray-700);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        width: 100%;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
        outline: none;
    }

    .date-inputs {
        display: flex;
        gap: 0.75rem;
        align-items: flex-end;
    }

    .date-inputs .form-group {
        flex: 1;
    }

    /* Buttons */
    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), #1B5E20);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-secondary {
        background: white;
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .btn-outline-secondary:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
        color: var(--gray-800);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }

    /* Audit Table */
    .audit-table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .audit-table {
        width: 100%;
        border-collapse: collapse;
    }

    .audit-table thead {
        background: var(--gray-50);
    }

    .audit-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: var(--primary);
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--primary-light);
    }

    .audit-table tbody tr {
        border-bottom: 1px solid var(--gray-200);
        transition: background-color 0.2s ease;
    }

    .audit-table tbody tr:hover {
        background: var(--gray-50);
    }

    .audit-table td {
        padding: 1rem 1.5rem;
        color: var(--gray-700);
        vertical-align: top;
    }

    /* Audit Data */
    .audit-id {
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    .audit-time {
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    .audit-admin {
        font-weight: 500;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
    }

    .audit-admin-email {
        font-size: 0.8125rem;
        color: var(--gray-500);
    }

    .audit-action {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .action-login { background: #E8F5E9; color: #2C8F0C; }
    .action-post { background: #E3F2FD; color: #1976D2; }
    .action-put { background: #FFF3E0; color: #F57C00; }
    .action-delete { background: #FFEBEE; color: #C62828; }
    .action-default { background: var(--gray-100); color: var(--gray-600); }

    .audit-url {
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 0.8125rem;
        color: var(--gray-600);
        word-break: break-all;
    }

    .data-pre {
        max-height: 120px;
        overflow: auto;
        white-space: pre-wrap;
        word-break: break-word;
        background: var(--gray-50);
        padding: 0.75rem;
        border-radius: 6px;
        border: 1px solid var(--gray-200);
        font-size: 0.8125rem;
        line-height: 1.4;
        font-family: 'SF Mono', Monaco, monospace;
        color: var(--gray-700);
        margin: 0;
    }

    .ip-address {
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 0.875rem;
        color: var(--gray-700);
        margin-bottom: 0.25rem;
    }

    .user-agent {
        font-size: 0.75rem;
        color: var(--gray-500);
        line-height: 1.3;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        color: var(--gray-300);
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: var(--gray-600);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--gray-500);
        margin-bottom: 1.5rem;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-200);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .date-inputs {
            flex-direction: column;
        }
        
        .audit-table {
            display: block;
            overflow-x: auto;
        }
        
        .audit-table th,
        .audit-table td {
            padding: 0.75rem;
        }
    }
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="h4 mb-1">Audit Log</h1>
                <p class="subtitle mb-0">Track all administrative actions and changes</p>
            </div>
            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel">
        <form method="GET" id="auditFilterForm">
            <div class="filter-grid">
                <!-- Search -->
                <div>
                    <label class="form-label">Search</label>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Search URL, IP, or data..." 
                           class="form-control">
                </div>

                <!-- Admin Filter -->
                <div>
                    <label class="form-label">Admin</label>
                    <select name="user_id" class="form-select">
                        <option value="">All Admins</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('user_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->first_name }} {{ $admin->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="form-label">Action</label>
                    <select name="action" class="form-select">
                        <option value="">All Actions</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="POST" {{ request('action') == 'POST' ? 'selected' : '' }}>Create (POST)</option>
                        <option value="PUT" {{ request('action') == 'PUT' ? 'selected' : '' }}>Update (PUT/PATCH)</option>
                        <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>Delete</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="form-label">Date Range</label>
                    <div class="date-inputs">
                        <div class="form-group">
                            <input type="date" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}" 
                                   class="form-control" 
                                   placeholder="From">
                        </div>
                        <div class="form-group">
                            <input type="date" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}" 
                                   class="form-control" 
                                   placeholder="To">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Apply Filters
                </button>
                <a href="{{ route('superadmin.audits.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Table -->
    <div class="audit-table-container">
        <div class="table-responsive">
            <table class="audit-table">
                <thead>
                    <tr>
                        <th style="width: 60px">ID</th>
                        <th style="width: 140px">Time</th>
                        <th>Admin</th>
                        <th style="width: 100px">Action</th>
                        <th style="min-width: 200px">URL</th>
                        <th style="min-width: 200px">Old Data</th>
                        <th style="min-width: 200px">New Data</th>
                        <th style="width: 180px">IP / Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                    <tr>
                        <td class="audit-id">{{ $audit->id }}</td>
                        <td class="audit-time">{{ $audit->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            @if($audit->user)
                                <div class="audit-admin">{{ $audit->user->first_name }} {{ $audit->user->last_name }}</div>
                                <div class="audit-admin-email">{{ $audit->user->email }}</div>
                            @else
                                <div class="text-muted">—</div>
                            @endif
                        </td>
                        <td>
                            @php
                                $actionClasses = [
                                    'login' => 'action-login',
                                    'POST' => 'action-post',
                                    'PUT' => 'action-put',
                                    'DELETE' => 'action-delete',
                                ];
                            @endphp
                            <span class="audit-action {{ $actionClasses[$audit->action] ?? 'action-default' }}">
                                {{ $audit->action }}
                            </span>
                        </td>
                        <td>
                            <code class="audit-url">{{ $audit->url ?? '—' }}</code>
                        </td>
                        <td>
                            @if($audit->old_values)
                                <pre class="data-pre">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($audit->new_values)
                                <pre class="data-pre">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="ip-address">{{ $audit->ip_address ?? '—' }}</div>
                            <div class="user-agent">{{ \Illuminate\Support\Str::limit($audit->user_agent, 100) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <h4>No Audit Records</h4>
                                <p>No audit records found matching your criteria.</p>
                                <a href="{{ route('superadmin.audits.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-1"></i> Reset Filters
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($audits->hasPages())
    <div class="pagination-container">
        {{ $audits->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when date inputs change (optional)
    const dateFrom = document.querySelector('input[name="date_from"]');
    const dateTo = document.querySelector('input[name="date_to"]');
    const form = document.getElementById('auditFilterForm');
    
    [dateFrom, dateTo].forEach(input => {
        input.addEventListener('change', function() {
            if (dateFrom.value && dateTo.value) {
                form.submit();
            }
        });
    });
});
</script>
@endsection