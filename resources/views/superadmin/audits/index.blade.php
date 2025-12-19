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
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-header h1 {
        color: var(--gray-800);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .page-header .subtitle {
        color: var(--gray-600);
        font-size: 0.95rem;
    }

    /* Filter Panel */
    .filter-panel {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .filter-title {
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.95rem;
    }

    /* Form Elements */
    .form-label {
        font-weight: 500;
        color: var(--gray-700);
        font-size: 0.8125rem;
        margin-bottom: 0.375rem;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.8125rem;
        width: 100%;
        transition: all 0.2s ease;
        height: 38px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
        outline: none;
    }

    .date-inputs {
        display: flex;
        gap: 0.5rem;
        align-items: flex-end;
    }

    .date-inputs .form-group {
        flex: 1;
        min-width: 140px;
    }

    /* Buttons */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.8125rem;
        border: none;
        transition: all 0.2s ease;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
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
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        height: 32px;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        justify-content: center;
    }

    /* Audit Table */
    .audit-table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .audit-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8125rem;
    }

    .audit-table thead {
        background: var(--gray-50);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .audit-table th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--primary-light);
        white-space: nowrap;
    }

    .audit-table tbody tr {
        border-bottom: 1px solid var(--gray-100);
        transition: background-color 0.15s ease;
    }

    .audit-table tbody tr:hover {
        background: var(--gray-50);
    }

    .audit-table td {
        padding: 0.75rem 1rem;
        color: var(--gray-700);
        vertical-align: top;
        max-width: 200px;
    }

    /* Audit Data - Compact */
    .audit-id {
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 0.75rem;
        color: var(--gray-500);
    }

    .audit-time {
        font-size: 0.75rem;
        color: var(--gray-600);
        white-space: nowrap;
    }

    .audit-admin {
        font-weight: 500;
        color: var(--gray-800);
        font-size: 0.8125rem;
        margin-bottom: 0.125rem;
    }

    .audit-admin-email {
        font-size: 0.75rem;
        color: var(--gray-500);
        word-break: break-all;
    }

    .audit-action {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .action-login { background: #E8F5E9; color: #2C8F0C; }
    .action-post { background: #E3F2FD; color: #1976D2; }
    .action-put { background: #FFF3E0; color: #F57C00; }
    .action-delete { background: #FFEBEE; color: #C62828; }
    .action-default { background: var(--gray-100); color: var(--gray-600); }

    .audit-url {
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 0.75rem;
        color: var(--gray-600);
        word-break: break-all;
        line-height: 1.3;
    }

    .data-pre {
        max-height: 80px;
        overflow: auto;
        white-space: pre-wrap;
        word-break: break-word;
        background: var(--gray-50);
        padding: 0.5rem;
        border-radius: 4px;
        border: 1px solid var(--gray-200);
        font-size: 0.75rem;
        line-height: 1.3;
        font-family: 'SF Mono', Monaco, monospace;
        color: var(--gray-700);
        margin: 0;
    }

    .compact-data {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .data-item {
        display: flex;
        gap: 0.5rem;
        font-size: 0.75rem;
    }

    .data-key {
        color: var(--gray-600);
        font-weight: 500;
        min-width: 80px;
    }

    .data-value {
        color: var(--gray-700);
        word-break: break-word;
    }

    .ip-address {
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 0.75rem;
        color: var(--gray-700);
        margin-bottom: 0.25rem;
    }

    .user-agent {
        font-size: 0.6875rem;
        color: var(--gray-500);
        line-height: 1.2;
        word-break: break-all;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
    }

    .empty-state-icon {
        color: var(--gray-300);
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: var(--gray-600);
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .empty-state p {
        color: var(--gray-500);
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    .pagination .page-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
        border-color: var(--gray-300);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    /* Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .icon-login { background: #2C8F0C; }
    .icon-create { background: #1976D2; }
    .icon-update { background: #F57C00; }
    .icon-delete { background: #C62828; }

    .stat-info h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-800);
        margin: 0;
        line-height: 1;
    }

    .stat-info p {
        font-size: 0.75rem;
        color: var(--gray-600);
        margin: 0.25rem 0 0 0;
    }

    /* Loading State */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--gray-200);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .audit-table td:nth-child(7),
        .audit-table th:nth-child(7) {
            display: none;
        }
    }

    @media (max-width: 992px) {
        .audit-table td:nth-child(6),
        .audit-table th:nth-child(6) {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .date-inputs {
            flex-direction: column;
        }
        
        .audit-table-container {
            overflow-x: auto;
        }
        
        .audit-table {
            min-width: 900px;
        }
        
        .audit-table th,
        .audit-table td {
            padding: 0.5rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            padding: 1rem;
        }
        
        .filter-panel {
            padding: 1rem;
        }
    }
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>


   <!-- Filter Panel -->
<div class="filter-panel">
    <div class="filter-header">
        <div class="filter-title">
            <i class="fas fa-filter me-1"></i> Filters
        </div>
        <div class="filter-status">
            <small class="text-muted" id="filterStatus">
                @if(request()->anyFilled(['q', 'user_id', 'action', 'date_from', 'date_to']))
                Filters Active
                @endif
            </small>
        </div>
    </div>
    
    <form method="GET" id="auditFilterForm">
        <div class="row g-3">
            <!-- Search -->
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Search URL, IP, or data..." 
                       class="form-control"
                       id="searchInput">
            </div>

            <!-- Admin Filter -->
            <div class="col-md-2">
                <label class="form-label">Admin</label>
                <select name="user_id" class="form-select" id="adminSelect">
                    <option value="">All Admins</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('user_id') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->first_name }} {{ $admin->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Filter -->
            <div class="col-md-2">
                <label class="form-label">Action</label>
                <select name="action" class="form-select" id="actionSelect">
                    <option value="">All Actions</option>
                    <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="edited" {{ request('action') == 'edited' ? 'selected' : '' }}>Edited</option>
                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    <option value="archived" {{ request('action') == 'archived' ? 'selected' : '' }}>Archived</option>
                    <option value="unarchived" {{ request('action') == 'unarchived' ? 'selected' : '' }}>Unarchived</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="col-md-3">
                <label class="form-label">Date Range</label>
                <div class="d-flex gap-2">
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}" 
                           class="form-control" 
                           placeholder="From"
                           id="dateFrom">
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}" 
                           class="form-control" 
                           placeholder="To"
                           id="dateTo">
                </div>
            </div>

            <!-- Clear Button -->
            <!-- <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('superadmin.audits.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Clear
                </a>
            </div> -->
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
                        <th style="width: 120px">Time</th>
                        <th style="min-width: 150px">Admin</th>
                        <th style="width: 80px">Action</th>
                        <th style="min-width: 180px">URL</th>
                        <th style="min-width: 200px">Data</th>
                        <th style="width: 160px">IP / Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                    <tr>
                        <td class="audit-id">#{{ $audit->id }}</td>
                        <td class="audit-time">
                            <div>{{ $audit->created_at->format('M d') }}</div>
                            <div class="text-muted">{{ $audit->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            @if($audit->user)
                                <div class="audit-admin">{{ \Illuminate\Support\Str::limit($audit->user->first_name . ' ' . $audit->user->last_name, 20) }}</div>
                                <div class="audit-admin-email">{{ \Illuminate\Support\Str::limit($audit->user->email, 25) }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $actionClasses = [
                                    'login' => 'action-login',
                                    'created' => 'action-post',
                                    'edited' => 'action-put',
                                    'deleted' => 'action-delete',
                                    'archived' => 'action-delete',
                                    'unarchived' => 'action-post',
                                ];
                            @endphp
                            <span class="audit-action {{ $actionClasses[$audit->action] ?? 'action-default' }}">
                                {{ $audit->action }}
                            </span>
                        </td>
                        <td>
                            <code class="audit-url">{{ \Illuminate\Support\Str::limit($audit->url, 50) }}</code>
                        </td>
                        <td>
                            <div class="compact-data">
                                @if($audit->old_values || $audit->new_values)
                                    @if($audit->old_values)
                                        <div class="data-item">
                                            <span class="data-key">Old:</span>
                                            <span class="data-value">{{ \Illuminate\Support\Str::limit(json_encode($audit->old_values), 50) }}</span>
                                        </div>
                                    @endif
                                    @if($audit->new_values)
                                        <div class="data-item">
                                            <span class="data-key">New:</span>
                                            <span class="data-value">{{ \Illuminate\Support\Str::limit(json_encode($audit->new_values), 50) }}</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="ip-address">{{ $audit->ip_address ?? '—' }}</div>
                            <div class="user-agent">{{ \Illuminate\Support\Str::limit($audit->user_agent, 60) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <h4>No Audit Records</h4>
                                <p>No audit records found matching your criteria.</p>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                    <i class="fas fa-redo me-1"></i> Clear Filters
                                </button>
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
        {{ $audits->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('auditFilterForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    let submitTimeout;
    let isSubmitting = false;

    // Get all filter inputs
    const filterInputs = [
        document.getElementById('searchInput'),
        document.getElementById('adminSelect'),
        document.getElementById('actionSelect'),
        document.getElementById('dateFrom'),
        document.getElementById('dateTo')
    ];

    // Function to submit form with debounce
    function submitForm() {
        if (isSubmitting) return;
        
        clearTimeout(submitTimeout);
        submitTimeout = setTimeout(function() {
            isSubmitting = true;
            loadingOverlay.style.display = 'flex';
            form.submit();
        }, 500); // 500ms debounce delay
    }

    // Add event listeners for automatic filtering
    filterInputs.forEach(input => {
        if (input) {
            // For text inputs (search)
            if (input.type === 'text') {
                input.addEventListener('input', submitForm);
            }
            // For select inputs and date inputs
            else {
                input.addEventListener('change', submitForm);
            }
        }
    });

    // Show loading overlay when form submits
    form.addEventListener('submit', function() {
        loadingOverlay.style.display = 'flex';
    });

    // Clear filters function
    window.clearFilters = function() {
        // Reset all form inputs
        form.reset();
        
        // Reset URL parameters
        const url = new URL(window.location);
        url.search = '';
        window.location.href = url.toString();
    };

    // Export button functionality
    document.getElementById('exportBtn')?.addEventListener('click', function() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        window.location.href = '{{ route("superadmin.audits.index") }}?' + params.toString();
    });

    // Update filter status
    function updateFilterStatus() {
        const hasFilters = Array.from(filterInputs).some(input => {
            if (input) {
                if (input.type === 'text') return input.value.trim() !== '';
                return input.value !== '';
            }
            return false;
        });
        
        const statusElement = document.getElementById('filterStatus');
        if (statusElement) {
            statusElement.textContent = hasFilters ? 'Filters Active' : '';
        }
    }

    // Initialize filter status
    updateFilterStatus();
    
    // Update status when inputs change
    filterInputs.forEach(input => {
        if (input) {
            input.addEventListener('change', updateFilterStatus);
            input.addEventListener('input', updateFilterStatus);
        }
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        loadingOverlay.style.display = 'flex';
        location.reload();
    });
});
</script>
@endsection