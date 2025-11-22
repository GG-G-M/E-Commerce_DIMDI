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

    .btn-success {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: #fff;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        color: #fff;
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

    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }
</style>

<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Low Stock Items</h1>
    <a href="{{ route('admin.low_stock.download_csv', ['threshold' => $threshold]) }}" class="btn btn-success">
        <i class="fas fa-file-download me-2"></i>Download CSV
    </a>
</div>

<div class="card card-custom">
    <div class="card-header card-header-custom">Low Stock List (Threshold: {{ $threshold }})</div>
    <div class="card-body">
        <table class="table table-bordered align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product / Variant</th>
                    <th>Stock Quantity</th>
                    {{-- <th>Warehouse</th>
                    <th>Supplier</th>
                    <th>Stock Checker</th>
                    <th>Reason</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        {{-- <td>-</td>
                        <td>{{ $product->supplier?->name ?? '-' }}</td>
                        <td>{{ $product->stockChecker?->firstname ?? '-' }}</td>
                        <td>-</td> --}}
                    </tr>
                @endforeach

                @foreach ($variants as $variant)
                    <tr>
                        <td>{{ $variant->id }}</td>
                        <td>{{ $variant->product->name }} / {{ $variant->variant_name }}</td>
                        <td>{{ $variant->stock_quantity }}</td>
                        {{-- <td>-</td>
                        <td>{{ $variant->supplier?->name ?? '-' }}</td>
                        <td>{{ $variant->stockChecker?->firstname ?? '-' }}</td>
                        <td>-</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
