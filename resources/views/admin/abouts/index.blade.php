@extends('layouts.admin')

@section('content')
<style>
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
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
    }

    .btn-outline-success {
        border-color: #2C8F0C;
        color: #2C8F0C;
        font-weight: 500;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-warning {
        border-color: #FBC02D;
        color: #FBC02D;
        font-weight: 500;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        border-color: #FBC02D;
        color: white;
        transform: translateY(-2px);
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

    /* Modal Styles */
    .modal-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem;
    }

    .modal-header-custom .modal-title {
        font-weight: 700;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

    .badge-active {
        background-color: #E8F5E6;
        color: #2C8F0C;
        padding: 0.35em 0.65em;
        border-radius: 0.25rem;
        font-size: 0.75em;
        font-weight: 600;
    }

    .badge-archived {
        background-color: #FFF3E0;
        color: #F57C00;
        padding: 0.35em 0.65em;
        border-radius: 0.25rem;
        font-size: 0.75em;
        font-weight: 600;
    }

    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .position-relative {
        position: relative;
    }

    .warehouse-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .tips-box {
        background-color: #F8FDF8;
        border-left: 4px solid #2C8F0C;
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.9rem;
        color: #2C8F0C;
    }

    .tips-box i {
        color: #2C8F0C;
        margin-right: 5px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .warehouse-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>

<!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.abouts.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-bold">Search About Entry</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Search by title...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Abouts Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">About Section Management</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAboutModal">
            <i class="fas fa-plus me-2"></i> Add About Entry
        </button>
    </div>

    <div class="card-body">
        @if($abouts->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description 1</th>
                            <th>Feature 1</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($abouts as $about)
                        <tr>
                            <td class="fw-semibold">#{{ $about->id }}</td>

                            <td><strong>{{ $about->title }}</strong></td>

                            <td>{{ Str::limit($about->description_1, 80) }}</td>

                            <td>
                                <strong>{{ $about->feature_1_title }}</strong><br>
                                <small class="text-muted">{{ Str::limit($about->feature_1_description, 60) }}</small>
                            </td>

                            <td>
                                <span class="{{ $about->is_archived ? 'badge-archived' : 'badge-active' }}">
                                    {{ $about->is_archived ? 'Archived' : 'Active' }}
                                </span>
                            </td>

                            <td class="text-center">
                                <div class="action-buttons">

                                    <!-- Edit -->
                                    <button class="btn btn-sm btn-outline-success edit-about-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editAboutModal"
                                        data-id="{{ $about->id }}"
                                        data-title="{{ $about->title }}"
                                        data-description_1="{{ $about->description_1 }}"
                                        data-description_2="{{ $about->description_2 }}"
                                        data-f1_title="{{ $about->feature_1_title }}"
                                        data-f1_desc="{{ $about->feature_1_description }}"
                                        data-f2_title="{{ $about->feature_2_title }}"
                                        data-f2_desc="{{ $about->feature_2_description }}"
                                        data-image="{{ $about->image }}"
                                        data-is_archived="{{ $about->is_archived }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Archive/Unarchive -->
                                    @if (!$about->is_archived)
                                        <button class="btn btn-sm btn-outline-warning toggle-status-btn"
                                            data-id="{{ $about->id }}"
                                            data-action="archive">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success toggle-status-btn"
                                            data-id="{{ $about->id }}"
                                            data-action="unarchive">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $abouts->links('pagination::bootstrap-5') }}
            </div>

        @else
            <div class="empty-state">
                <i class="fas fa-info-circle"></i>
                <h4 class="mt-3">No About Entries Found</h4>
                <p class="text-muted">Click the button above to create the first About section entry.</p>
            </div>
        @endif
    </div>
</div>

<!-- Add About Modal -->
<div class="modal fade" id="addAboutModal" tabindex="-1" aria-labelledby="addAboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="addAboutModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Add New About Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAboutForm" action="{{ route('admin.abouts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_title" class="form-label">Title *</label>
                        <input type="text" id="add_title" name="title" class="form-control" 
                               placeholder="Enter about section title" required>
                    </div>

                    <div class="mb-3">
                        <label for="add_description_1" class="form-label">Description 1 *</label>
                        <textarea id="add_description_1" name="description_1" class="form-control" rows="3"
                                  placeholder="Enter main description" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="add_description_2" class="form-label">Description 2</label>
                        <textarea id="add_description_2" name="description_2" class="form-control" rows="3"
                                  placeholder="Enter secondary description (optional)"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_feature_1_title" class="form-label">Feature 1 Title *</label>
                                <input type="text" id="add_feature_1_title" name="feature_1_title" class="form-control" 
                                       placeholder="Enter feature 1 title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_feature_2_title" class="form-label">Feature 2 Title *</label>
                                <input type="text" id="add_feature_2_title" name="feature_2_title" class="form-control" 
                                       placeholder="Enter feature 2 title" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_feature_1_description" class="form-label">Feature 1 Description *</label>
                                <textarea id="add_feature_1_description" name="feature_1_description" class="form-control" rows="3"
                                          placeholder="Enter feature 1 description" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_feature_2_description" class="form-label">Feature 2 Description *</label>
                                <textarea id="add_feature_2_description" name="feature_2_description" class="form-control" rows="3"
                                          placeholder="Enter feature 2 description" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="add_image" class="form-label">Image URL</label>
                        <input type="text" id="add_image" name="image" class="form-control" 
                               placeholder="Enter image URL (optional)">
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> Make sure to provide all required fields. The about section will be displayed on the frontend of your website.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save About Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit About Modal -->
<div class="modal fade" id="editAboutModal" tabindex="-1" aria-labelledby="editAboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="editAboutModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit About Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAboutForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title *</label>
                        <input type="text" id="edit_title" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description_1" class="form-label">Description 1 *</label>
                        <textarea id="edit_description_1" name="description_1" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description_2" class="form-label">Description 2</label>
                        <textarea id="edit_description_2" name="description_2" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_feature_1_title" class="form-label">Feature 1 Title *</label>
                                <input type="text" id="edit_feature_1_title" name="feature_1_title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_feature_2_title" class="form-label">Feature 2 Title *</label>
                                <input type="text" id="edit_feature_2_title" name="feature_2_title" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_feature_1_description" class="form-label">Feature 1 Description *</label>
                                <textarea id="edit_feature_1_description" name="feature_1_description" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_feature_2_description" class="form-label">Feature 2 Description *</label>
                                <textarea id="edit_feature_2_description" name="feature_2_description" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Image URL</label>
                        <input type="text" id="edit_image" name="image" class="form-control">
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Note:</strong> Archiving an about entry will hide it from the frontend but won't delete it permanently.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update About Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const perPageSelect = document.getElementById('per_page');
    const searchLoading = document.getElementById('searchLoading');
    
    let searchTimeout;

    // Auto-submit search with delay
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoading.style.display = 'block';
        
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800);
    });

    // Auto-submit status filter immediately
    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Auto-submit per page selection immediately
    perPageSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Clear loading indicator when form submits
    filterForm.addEventListener('submit', function() {
        searchLoading.style.display = 'none';
    });

    // Edit about modal handling
    const editAboutButtons = document.querySelectorAll('.edit-about-btn');
    const editAboutForm = document.getElementById('editAboutForm');
    const editIdInput = editAboutForm.querySelector('[name="id"]');
    const editTitleInput = document.getElementById('edit_title');
    const editDescription1Input = document.getElementById('edit_description_1');
    const editDescription2Input = document.getElementById('edit_description_2');
    const editFeature1TitleInput = document.getElementById('edit_feature_1_title');
    const editFeature1DescInput = document.getElementById('edit_feature_1_description');
    const editFeature2TitleInput = document.getElementById('edit_feature_2_title');
    const editFeature2DescInput = document.getElementById('edit_feature_2_description');
    const editImageInput = document.getElementById('edit_image');

    editAboutButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const description1 = this.getAttribute('data-description_1');
            const description2 = this.getAttribute('data-description_2');
            const f1Title = this.getAttribute('data-f1_title');
            const f1Desc = this.getAttribute('data-f1_desc');
            const f2Title = this.getAttribute('data-f2_title');
            const f2Desc = this.getAttribute('data-f2_desc');
            const image = this.getAttribute('data-image');

            // Set form action URL
            editAboutForm.action = `/admin/abouts/${id}`;
            
            // Populate form fields
            editIdInput.value = id;
            editTitleInput.value = title || '';
            editDescription1Input.value = description1 || '';
            editDescription2Input.value = description2 || '';
            editFeature1TitleInput.value = f1Title || '';
            editFeature1DescInput.value = f1Desc || '';
            editFeature2TitleInput.value = f2Title || '';
            editFeature2DescInput.value = f2Desc || '';
            editImageInput.value = image || '';
        });
    });

    // ADD ABOUT
    document.getElementById('addAboutForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new FormData(form)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addAboutModal'));
                modal.hide();
                // Reset form
                form.reset();
                // Reload page to show new about entry
                location.reload();
            } else {
                alert('Error: Could not add about entry');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // UPDATE ABOUT
    document.getElementById('editAboutForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: new FormData(form)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editAboutModal'));
                modal.hide();
                // Reload page to show updated about entry
                location.reload();
            } else {
                alert('Error updating about entry');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // ARCHIVE / UNARCHIVE
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const action = btn.getAttribute('data-action');
            const actionText = action === 'archive' ? 'archive' : 'activate';
            
            if (!confirm(`Are you sure you want to ${actionText} this about entry?`)) {
                return;
            }
            
            const id = btn.getAttribute('data-id');
            const originalText = btn.innerHTML;
            
            // Show loading on button
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            try {
                const response = await fetch(`/admin/abouts/${id}/${action}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    location.reload();
                } else {
                    alert('Failed to update status');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                alert('Network error. Please try again.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    });

    // Form validation
    const addForm = document.getElementById('addAboutForm');
    const editForm = document.getElementById('editAboutForm');
    
    [addForm, editForm].forEach(form => {
        if (form) {
            form.addEventListener('submit', function(e) {
                const titleInput = form.querySelector('[name="title"]');
                const desc1Input = form.querySelector('[name="description_1"]');
                const f1TitleInput = form.querySelector('[name="feature_1_title"]');
                const f1DescInput = form.querySelector('[name="feature_1_description"]');
                const f2TitleInput = form.querySelector('[name="feature_2_title"]');
                const f2DescInput = form.querySelector('[name="feature_2_description"]');
                
                if (!titleInput.value.trim()) {
                    e.preventDefault();
                    alert('Title is required.');
                    titleInput.focus();
                    return false;
                }
                
                if (!desc1Input.value.trim()) {
                    e.preventDefault();
                    alert('Description 1 is required.');
                    desc1Input.focus();
                    return false;
                }
                
                if (!f1TitleInput.value.trim()) {
                    e.preventDefault();
                    alert('Feature 1 Title is required.');
                    f1TitleInput.focus();
                    return false;
                }
                
                if (!f1DescInput.value.trim()) {
                    e.preventDefault();
                    alert('Feature 1 Description is required.');
                    f1DescInput.focus();
                    return false;
                }
                
                if (!f2TitleInput.value.trim()) {
                    e.preventDefault();
                    alert('Feature 2 Title is required.');
                    f2TitleInput.focus();
                    return false;
                }
                
                if (!f2DescInput.value.trim()) {
                    e.preventDefault();
                    alert('Feature 2 Description is required.');
                    f2DescInput.focus();
                    return false;
                }
                
                return true;
            });
        }
    });

    // Clear add form when modal closes
    const addModal = document.getElementById('addAboutModal');
    addModal.addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        if (form) {
            form.reset();
        }
    });
});
</script>
@endpush

@endsection