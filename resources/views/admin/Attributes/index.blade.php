@extends('layouts.admin')

@section('content')
<style>
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

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
    }

    .badge-active {
        background-color: #2C8F0C;
        color: white;
    }

    .badge-inactive {
        background-color: #C62828;
        color: white;
    }

    .search-box {
        border: 1px solid #E8F5E6;
        border-radius: 8px;
        padding: 1rem;
        background-color: #F8FDF8;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Attributes Management</h1>
        <p class="text-muted mb-0">Manage and organize your product attributes easily.</p>
    </div>
    <button type="button" class="btn btn-primary" 
            data-bs-toggle="modal" 
            data-bs-target="#attributeModal" 
            data-mode="create" 
            data-action="{{ route('admin.attributes.store') }}">
        <i class="fas fa-plus me-1"></i> Add Attribute
    </button>
</div>

<!-- Search -->
<div class="card card-custom mb-4">
    <div class="card-header card-header-custom">
        <i class="fas fa-search me-2"></i> Search Attributes
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.attributes.index') }}">
            <div class="row align-items-end">
                <div class="col-md-9">
                    <div class="mb-3">
                        <label for="search" class="form-label fw-bold">Keyword</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Search by name...">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Attributes Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-list me-2"></i> Attribute List
    </div>
    <div class="card-body">
        @if($attributes->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attributes as $attribute)
                    <tr>
                        <td class="fw-semibold">{{ $attribute->name }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-success me-1" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#attributeModal"
                                    data-mode="edit"
                                    data-id="{{ $attribute->id }}"
                                    data-name="{{ $attribute->name }}"
                                    data-action="{{ route('admin.attributes.update', $attribute) }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this attribute?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $attributes->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-list fa-3x text-success mb-3"></i>
            <p class="text-muted mb-0">No attributes found. Try adding one!</p>
        </div>
        @endif
    </div>
</div>

<!-- Single Attribute Modal (Create/Edit) -->
<div class="modal fade" id="attributeModal" tabindex="-1" aria-labelledby="attributeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="attributeForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attributeModalLabel">Add New Attribute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="attributeName" class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control" id="attributeName" name="name" placeholder="Enter attribute name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="attributeSubmitButton">Add Attribute</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const attributeModal = document.getElementById('attributeModal');
    const modalTitle = document.getElementById('attributeModalLabel');
    const modalForm = document.getElementById('attributeForm');
    const modalNameInput = document.getElementById('attributeName');
    const submitButton = document.getElementById('attributeSubmitButton');

    attributeModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const mode = button.dataset.mode;

        if (mode === 'create') {
            modalTitle.textContent = 'Add New Attribute';
            submitButton.textContent = 'Add Attribute';
            modalForm.action = button.dataset.action;
            modalForm.method = 'POST';
            modalNameInput.value = '';
            const methodInput = modalForm.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        } else if (mode === 'edit') {
            modalTitle.textContent = 'Edit Attribute';
            submitButton.textContent = 'Save Changes';
            modalForm.action = button.dataset.action;
            modalForm.method = 'POST';
            modalNameInput.value = button.dataset.name;

            let methodInput = modalForm.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                modalForm.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
        }
    });
});
</script>



@endsection



