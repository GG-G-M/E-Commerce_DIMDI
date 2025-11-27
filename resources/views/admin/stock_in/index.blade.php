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

        .csv-instructions {
            background: #f8f9fa;
            border-left: 4px solid #2C8F0C;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .csv-instructions h6 {
            color: #2C8F0C;
            margin-bottom: 10px;
        }
    </style>

    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Stock-In Management</h1>
        <div class="btn-group">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockInModal">
                <i class="bi bi-plus-circle"></i> Add Stock-In
            </button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                <i class="fas fa-file-csv"></i> Import CSV
            </button>
        </div>
    </div>

    <!-- CSV Upload Modal -->
    <div class="modal fade" id="csvUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.stock_in.import.csv') }}" method="POST" enctype="multipart/form-data"
                    id="csvUploadForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-file-csv me-2"></i>Upload Stock-In via CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Template Download -->
                        <div class="mb-3">
                            <a href="{{ route('admin.stock_in.csv.template') }}" class="btn btn-success">
                                <i class="fas fa-file-download me-2"></i>Download CSV Template
                            </a>
                        </div>

                        <!-- Instructions -->
                        <div class="csv-instructions">
                            <h6><i class="fas fa-info-circle me-2"></i>CSV Format Instructions</h6>
                            <ul class="small mb-0">
                                <li>File must be CSV format</li>
                                <li>Required columns: <code>product_id</code>, <code>variant_id</code>,
                                    <code>warehouse_id</code>, <code>supplier_id</code>, <code>stock_checker_id</code>,
                                    <code>quantity</code>, <code>reason</code>
                                </li>
                                <li>Ensure IDs correspond to existing products, variants, warehouses, suppliers, and stock
                                    checkers</li>
                            </ul>
                        </div>

                        <!-- File Input -->
                        <div class="mb-3">
                            <label class="form-label">Select CSV File</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                            <div class="form-text">Only CSV files allowed, max 10MB</div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-3" style="height: 20px; display: none;" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <span class="progress-text">0%</span>
                            </div>
                        </div>

                        <div id="uploadStatus" class="alert" style="display: none;"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="uploadCsvBtn">
                            <i class="fas fa-upload me-2"></i>Upload CSV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stock-In Table -->
    <div class="card card-custom">
        <div class="card-header card-header-custom">Stock-In List</div>
        <div class="card-body">
            <table class="table table-bordered align-middle w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product / Variant</th>
                        <th>Warehouse</th>
                        <th>Supplier</th>
                        <th>Stock Checker</th>
                        <th>Quantity</th>
                        <th>Remaining Quantity</th>
                        <th>Reason</th>
                        <th>Date</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockIns as $stock)
                        <tr>
                            <td>{{ $stock->id }}</td>
                            <td>
                                @if ($stock->product)
                                    {{ $stock->product->name }}
                                @elseif($stock->variant)
                                    {{ $stock->variant->product->name }} / {{ $stock->variant->variant_name }}
                                @endif
                            </td>
                            <td>{{ $stock->warehouse->name }}</td>
                            <td>{{ $stock->supplier?->name ?? '-' }}</td>
                            <td>{{ $stock->checker?->firstname ?? '-' }} {{ $stock->checker?->lastname ?? '' }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ $stock->remaining_quantity }}</td>
                            <td>{{ $stock->reason }}</td>
                            <td>{{ $stock->created_at->format('Y-m-d H:i') }}</td>
                            {{-- <td>
                                <button class="btn btn-warning btn-sm me-1 editStockBtn" data-id="{{ $stock->id }}"
                                    data-product-id="{{ $stock->product_id }}"
                                    data-variant-id="{{ $stock->product_variant_id }}"
                                    data-warehouse-id="{{ $stock->warehouse_id }}"
                                    data-supplier-id="{{ $stock->supplier_id }}"
                                    data-checker-id="{{ $stock->stock_checker_id }}"
                                    data-quantity="{{ $stock->quantity }}" data-reason="{{ $stock->reason }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
                {{ $stockIns->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Stock-In Modal (Add/Edit) -->
    <div class="modal fade" id="stockInModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="stockInForm" method="POST" action="{{ route('admin.stock_in.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Stock-In</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select class="form-select" name="product_id" id="productSelect">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Variant</label>
                            <select class="form-select" name="product_variant_id" id="variantSelect">
                                <option value="">Select Variant</option>
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}">{{ $variant->product->name }} /
                                        {{ $variant->variant_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Warehouse</label>
                            <select class="form-select" name="warehouse_id" id="warehouseSelect" required>
                                <option value="">Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" name="supplier_id" id="supplierSelect" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock Checker</label>
                            <select class="form-select" name="stock_checker_id" id="checkerSelect" required>
                                <option value="">Select Stock Checker</option>
                                @foreach ($stockCheckers as $checker)
                                    <option value="{{ $checker->id }}">
                                        {{ $checker->firstname }} {{ $checker->middlename }} {{ $checker->lastname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantityInput"
                                min="1" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <input type="text" class="form-control" name="reason" id="reasonInput">
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csvUploadForm = document.getElementById('csvUploadForm');
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadStatus = document.getElementById('uploadStatus');
            const uploadCsvBtn = document.getElementById('uploadCsvBtn');
            const progressBar = uploadProgress.querySelector('.progress-bar');
            const progressText = uploadProgress.querySelector('.progress-text');

            csvUploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                uploadProgress.style.display = 'block';
                uploadStatus.style.display = 'none';
                uploadCsvBtn.disabled = true;
                uploadCsvBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';

                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += 5;
                    if (progress <= 100) {
                        progressBar.style.width = progress + '%';
                        progressBar.setAttribute('aria-valuenow', progress);
                        progressText.textContent = progress + '%';
                    }
                }, 100);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        clearInterval(progressInterval);
                        progressBar.style.width = '100%';
                        progressText.textContent = '100%';
                        if (data.success) {
                            showUploadStatus('success', data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            showUploadStatus('danger', data.message || 'Upload failed.');
                        }
                    })
                    .catch(error => {
                        clearInterval(progressInterval);
                        showUploadStatus('danger', 'Upload failed: ' + error.message);
                    })
                    .finally(() => {
                        uploadCsvBtn.disabled = false;
                        uploadCsvBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload CSV';
                    });
            });

            function showUploadStatus(type, message) {
                uploadStatus.className = `alert alert-${type}`;
                uploadStatus.innerHTML = message;
                uploadStatus.style.display = 'block';
            }

            document.getElementById('csvUploadModal').addEventListener('hidden.bs.modal', function() {
                csvUploadForm.reset();
                uploadProgress.style.display = 'none';
                uploadStatus.style.display = 'none';
                progressBar.style.width = '0%';
                progressText.textContent = '0%';
            });
        });
    </script>
@endsection
