@extends('layouts.admin')

@section('content')
    <style>
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #2C8F0C;
        }

        .page-header h1 {
            color: #2C8F0C;
            font-weight: 700;
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            color: white;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        }

        .btn-warning {
            background: #FBC02D;
            border: none;
            color: #fff;
        }

        .btn-danger {
            background: #C62828;
            border: none;
        }

        .table th {
            background-color: #E8F5E6;
            color: #2C8F0C;
            font-weight: 600;
            border-bottom: 2px solid #2C8F0C;
        }

        .table tbody tr:hover {
            background-color: #F8FDF8;
            transition: background-color 0.2s ease;
        }

        .modal-header {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            color: white;
        }

        .form-label {
            font-weight: 600;
            color: #2C8F0C;
        }
    </style>

    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Stock Checker Management</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockCheckerModal">
            <i class="bi bi-plus-circle"></i> Add Stock Checker
        </button>
    </div>

    <!-- Filters -->
    <div class="card card-custom mb-4">
        <div class="card-header card-header-custom">
            <i class="fas fa-filter me-2"></i> Stock Checker Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.stock_checkers.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="search" class="form-label fw-bold">Search Stock Checker</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Search by name or contact...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Filter by Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="per_page" class="form-label fw-bold">Items per page</label>
                            <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                                @foreach ([5, 10, 15, 25, 50] as $option)
                                    <option value="{{ $option }}"
                                        {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check me-1"></i> Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stock Checker Table -->
    <div class="card card-custom">
        <div class="card-header card-header-custom">Stock Checker List</div>
        <div class="card-body">
            <table class="table table-bordered align-middle w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockCheckers as $checker)
                        <tr>
                            <td>{{ $checker->id }}</td>
                            <td>{{ $checker->firstname }}</td>
                            <td>{{ $checker->middlename }}</td>
                            <td>{{ $checker->lastname }}</td>
                            <td>{{ $checker->contact }}</td>
                            <td>{{ $checker->address }}</td>
                            <td>
                                @if (!$checker->is_archived)
                                    <span>Active</span>
                                @else
                                    <span>Archived</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-outline-success btn-sm editBtn" data-bs-toggle="modal"
                                    data-bs-target="#editStockCheckerModal" data-checker='@json($checker)'>
                                    <i class="fas fa-edit"></i>
                                </button>

                                @if (!$checker->is_archived)
                                    <button class="btn btn-outline-warning btn-sm toggleStatusBtn"
                                        data-id="{{ $checker->id }}" data-action="archive">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                @else
                                    <button class="btn btn-outline-success btn-sm toggleStatusBtn"
                                        data-id="{{ $checker->id }}" data-action="unarchive">
                                        <i class="fas fa-box-open"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $stockCheckers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Add Stock Checker Modal -->
    <div class="modal fade" id="addStockCheckerModal">
        <div class="modal-dialog">
            <form id="addStockCheckerForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Stock Checker</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="firstname" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middlename">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="lastname" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Save Stock Checker</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Stock Checker Modal -->
    <div class="modal fade" id="editStockCheckerModal">
        <div class="modal-dialog">
            <form id="editStockCheckerForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Stock Checker</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="firstname" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middlename">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="lastname" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Update Stock Checker</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // ADD STOCK CHECKER
            document.getElementById('addStockCheckerForm').addEventListener('submit', e => {
                e.preventDefault();
                fetch('{{ route('admin.stock_checkers.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new FormData(e.target)
                }).then(r => r.json()).then(d => {
                    if (d.success) location.reload();
                    else alert('Error: could not add stock checker');
                });
            });

            // FILL EDIT MODAL
            document.querySelectorAll('.editBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const checker = JSON.parse(btn.dataset.checker);
                    const form = document.getElementById('editStockCheckerForm');
                    form.action = `/admin/stock_checkers/${checker.id}`;
                    form.querySelector('[name="id"]').value = checker.id;
                    form.querySelector('[name="firstname"]').value = checker.firstname;
                    form.querySelector('[name="middlename"]').value = checker.middlename;
                    form.querySelector('[name="lastname"]').value = checker.lastname;
                    form.querySelector('[name="contact"]').value = checker.contact;
                    form.querySelector('[name="address"]').value = checker.address;
                });
            });

            // UPDATE STOCK CHECKER
            document.getElementById('editStockCheckerForm').addEventListener('submit', e => {
                e.preventDefault();
                const form = e.target;
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: new FormData(form)
                }).then(r => r.json()).then(d => {
                    if (d.success) location.reload();
                    else alert('Error updating stock checker');
                });
            });

            // ARCHIVE / UNARCHIVE
            document.querySelectorAll('.toggleStatusBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const action = btn.dataset.action;
                    fetch(`/admin/stock_checkers/${id}/${action}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(r => r.json()).then(d => {
                        if (d.success) location.reload();
                        else alert('Failed to update status');
                    });
                });
            });
        </script>
    @endpush
@endsection
