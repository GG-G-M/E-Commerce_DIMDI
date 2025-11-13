@extends('layouts.admin')

@section('content')
<style>
    /* === Green Theme and Card Styling === */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }

    .page-header h1 {
        color: #2C8F0C;
        font-weight: 700;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
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
    <h1 class="mb-0">Customer Management</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
        <i class="bi bi-person-plus"></i> Add Customer
    </button>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-header card-header-custom">
        <i class="fas fa-filter me-2"></i> Customer Filters
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.customers.index') }}">
            <div class="row">
                <!-- Search by Name or Email -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="search" class="form-label fw-bold">Search Customers</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name or email...">
                    </div>
                </div>

                <!-- Filter by Status (Active / Archived) -->
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>


                <!-- Items per page selection -->
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                            @foreach([2, 5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <!-- Apply Button -->
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i> Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card card-custom">
    <div class="card-header card-header-custom">Customer List</div>
    <div class="card-body">
        <table class="table table-bordered align-middle" id="customerTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr data-id="{{ $customer->id }}">
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>
                        @if ($customer->is_archived)
                            <span>Archived</span>
                        @else
                            <span>Active</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-outline-success btn-sm editBtn me-2" data-bs-toggle="modal" data-bs-target="#editCustomerModal" data-customer='@json($customer)'>
                            <i class="fas fa-edit"></i>
                        @if ($customer->is_archived)
                            <button class="btn btn-outline-success btn-sm unarchiveBtn" data-id="{{ $customer->id }}">
                               <i class="fas fa-box-open"></i>
                            </button>
                        @else
                            <button class="btn btn-outline-warning btn-sm archiveBtn" data-id="{{ $customer->id }}">
                                <i class="fas fa-archive"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3 d-flex justify-content-center">
            {{ $customers->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addCustomerForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    @include('admin.customers.form-fields')
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editCustomerForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    @include('admin.customers.form-fields')
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
/* === Add Customer === */
document.getElementById('addCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('{{ route("admin.customers.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert('Error adding customer.');
    });
});

/* === Edit Customer === */
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const c = JSON.parse(this.dataset.customer);
        const form = document.getElementById('editCustomerForm');
        form.action = `/admin/customers/${c.id}`;
        for (const key in c) {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) input.value = c[key];
        }
    });
});

document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-HTTP-Method-Override': 'PUT' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert('Error updating customer.');
    });
});

/* === Archive Customer === */
document.querySelectorAll('.archiveBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!confirm('Are you sure you want to archive this customer?')) return;
        const id = this.dataset.id;

        fetch(`/admin/customers/${id}/archive`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert('Failed to archive customer.');
        });
    });
});

/* === Unarchive Customer === */
document.querySelectorAll('.unarchiveBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!confirm('Are you sure you want to unarchive this customer?')) return;
        const id = this.dataset.id;

        fetch(`/admin/customers/${id}/unarchive`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert('Failed to unarchive customer.');
        });
    });
});

</script>
@endpush

@endsection
