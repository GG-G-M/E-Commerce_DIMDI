@extends('layouts.superadmin')

@section('content')
<style>
    /* === Consistent Green Theme === */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* Dashboard Header */
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-left: 4px solid #2C8F0C;
    }

    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Alternating row colors */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Button Styles - Consistent */
    .btn-success-custom {
        background: white;
        color: #2C8F0C;
        border: 2px solid rgba(44, 143, 12, 0.3);
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
        min-width: fit-content;
        height: auto;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .btn-success-custom:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-outline-success-custom {
        background: white;
        border: 2px solid #2C8F0C;
        color: #2C8F0C;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }

    .btn-outline-success-custom:hover {
        background: #2C8F0C;
        color: white;
        transform: translateY(-2px);
    }

    /* Search Box */
    .search-box {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
        font-size: 0.9rem;
    }

    .search-box:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border: 2px solid;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }

    .btn-view {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }

    .btn-view:hover {
        background-color: #2C8F0C;
        color: white;
    }

    /* Statistics Cards */
    .stats-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .stats-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stats-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    /* Column widths - More compact */
    .id-col { width: 70px; min-width: 70px; }
    .time-col { width: 100px; min-width: 100px; }
    .admin-col { width: 180px; min-width: 180px; }
    .action-col { width: 300px; min-width: 300px; }

    /* Audit Info */
    .audit-id {
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .audit-time {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .audit-admin {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }

    .audit-admin-email {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .audit-action {
        font-weight: 600;
        font-size: 0.85rem;
        color: #2C8F0C;
    }

    /* Pagination styling - Consistent */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 1px;
        border-radius: 4px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }

    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8FDF8;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Header button group */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .header-buttons .btn {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #2C8F0C;
        font-size: 0.9rem;
    }

    /* Filter Card - FIXED PADDING */
    .filter-card {
        background: white;
        border: none;
        border-radius: 12px;
        padding: 1.5rem !important;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    /* Filter Form Row Spacing */
    .filter-form-row {
        margin-bottom: -0.5rem;
    }

    .filter-form-row > .col-md-3,
    .filter-form-row > .col-md-2 {
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }

        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .audit-admin {
            font-size: 0.8rem;
        }

        .btn-outline-success-custom,
        .btn-success-custom {
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
        }

        .stats-card {
            padding: 1rem;
        }

        .stats-number {
            font-size: 1.5rem;
        }

        /* Mobile filter adjustments */
        .filter-card {
            padding: 1rem !important;
        }

        .filter-form-row > .col-md-3,
        .filter-form-row > .col-md-2 {
            margin-bottom: 0.75rem;
        }
    }
</style>

<!-- Statistics -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $stats['total_audits'] }}</div>
            <div class="stats-label">Total Audits</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $stats['created_count'] }}</div>
            <div class="stats-label">Created Actions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $stats['updated_count'] }}</div>
            <div class="stats-label">Updated Actions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $stats['deleted_count'] }}</div>
            <div class="stats-label">Deleted Actions</div>
        </div>
    </div>
</div>

<!-- Filter Card - Automatic Filters -->
<div class="filter-card">
    <div class="card-body p-0">
        <form method="GET" action="{{ route('superadmin.audits.index') }}" class="row g-2 filter-form-row" id="filterForm">
            <div class="col-md-3">
                <div class="mb-0">
                    <label class="form-label fw-bold">Search Audits</label>
                    <input type="text" name="q" class="form-control search-box"
                           placeholder="Search URL, IP, or data..."
                           value="{{ request('q') }}" id="searchInput">
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-0">
                    <label class="form-label fw-bold">Filter by Admin</label>
                    <select name="user_id" class="form-select search-box" id="adminSelect">
                        <option value="">All Admins</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('user_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->first_name }} {{ $admin->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-0">
                    <label class="form-label fw-bold">Action</label>
                    <select name="action" class="form-select search-box" id="actionSelect">
                        <option value="">All Actions</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="edited" {{ request('action') == 'edited' ? 'selected' : '' }}>Edited</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="archived" {{ request('action') == 'archived' ? 'selected' : '' }}>Archived</option>
                        <option value="unarchived" {{ request('action') == 'unarchived' ? 'selected' : '' }}>Unarchived</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-0">
                    <label class="form-label fw-bold">Date From</label>
                    <input type="date" name="date_from" class="form-control search-box"
                           value="{{ request('date_from') }}" id="dateFrom">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-0">
                    <label class="form-label fw-bold">Date To</label>
                    <input type="date" name="date_to" class="form-control search-box"
                           value="{{ request('date_to') }}" id="dateTo">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Audits Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Audit Logs</h5>
    </div>

    <div class="card-body p-0">
        @if($audits->count() > 0)
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="time-col">Time</th>
                            <th class="admin-col">Admin</th>
                            <th class="action-col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($audits as $audit)
                        <tr>
                            <td class="id-col">
                                <span class="audit-id">#{{ $audit->id }}</span>
                            </td>
                            <td class="time-col">
                                <div class="audit-time">
                                    <div>{{ $audit->created_at->format('M d') }}</div>
                                    <div>{{ $audit->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="admin-col">
                                @if($audit->user)
                                    <div class="audit-admin">{{ \Illuminate\Support\Str::limit($audit->user->first_name . ' ' . $audit->user->last_name, 20) }}</div>
                                    <div class="audit-admin-email">{{ \Illuminate\Support\Str::limit($audit->user->email, 25) }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="action-col">
                                @php
                                    $actionVerbs = [
                                        'login' => 'logged in',
                                        'logout' => 'logged out',
                                        'created' => 'created',
                                        'edited' => 'edited',
                                        'deleted' => 'deleted',
                                        'archived' => 'archived',
                                        'unarchived' => 'unarchived',
                                    ];
                                    $actionVerb = $actionVerbs[$audit->action] ?? $audit->action;
                                    if ($audit->user) {
                                        $adminName = $audit->user->first_name . ' ' . $audit->user->last_name;
                                        if (in_array($audit->action, ['created', 'edited', 'deleted', 'archived', 'unarchived'])) {
                                            $oldData = $audit->old_values;
                                            $productName = $oldData['name'] ?? 'Unknown Item';
                                            $actionText = $adminName . ' ' . $actionVerb . ' the ' . $productName;
                                        } else {
                                            $actionText = $adminName . ' ' . $actionVerb;
                                        }
                                    } else {
                                        $actionText = $audit->action;
                                    }
                                @endphp
                                <span class="audit-action">{{ $actionText }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($audits->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3">
                <div>
                    <small class="text-muted">
                        Showing {{ $audits->firstItem() }} to {{ $audits->lastItem() }} of {{ $audits->total() }} audits
                    </small>
                </div>
                <div>
                    {{ $audits->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-user-shield"></i>
                <h5 class="text-muted">No Audit Records</h5>
                <p class="text-muted mb-4">No audit records match your search criteria</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const adminSelect = document.getElementById('adminSelect');
    const actionSelect = document.getElementById('actionSelect');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');

    let searchTimeout;

    // Function to submit the form
    function submitFilterForm() {
        filterForm.submit();
    }

    // Auto-submit on search input with delay (debounce)
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            submitFilterForm();
        }, 800); // 800ms delay for better UX
    });

    // Auto-submit on select changes
    adminSelect.addEventListener('change', function() {
        submitFilterForm();
    });

    actionSelect.addEventListener('change', function() {
        submitFilterForm();
    });

    dateFrom.addEventListener('change', function() {
        submitFilterForm();
    });

    dateTo.addEventListener('change', function() {
        submitFilterForm();
    });

    // Add enter key support for search (immediate submit)
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            submitFilterForm();
        }
    });


});
</script>
@endsection
