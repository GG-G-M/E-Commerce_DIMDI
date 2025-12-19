{{-- resources/views/admin/sales-report/export/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Sales Report - {{ date('Y-m-d') }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .summary { margin-bottom: 20px; }
        .summary-card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 20px; }
        .payment-method { margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DIMDI STORE - SALES REPORT</h1>
        <p>Generated on: {{ date('F j, Y g:i A') }}</p>
        <p>Date Range: {{ $salesData['dateRangeText'] }}</p>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-card">
            <p><strong>Total Sales:</strong> {{ pdf_currency($salesData['totalSales']) }}</p>
            <p><strong>Total Orders:</strong> {{ $salesData['totalOrders'] }}</p>
            <p><strong>Average Order Value:</strong> {{ pdf_currency($salesData['averageOrderValue']) }}</p>
        </div>
    </div>

    <div class="payment-breakdown">
        <h3>Sales by Payment Method</h3>
        @foreach($salesData['salesByPayment'] as $method => $data)
        <div class="payment-method">
            <strong>{{ ucfirst($method) }}:</strong> 
            {{ pdf_currency($data['total']) }} ({{ $data['count'] }} orders, {{ number_format($data['percentage'], 1) }}%)
        </div>
        @endforeach
    </div>

    <div class="orders">
        <h3>Sales Orders ({{ $orders->count() }} orders)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Payment Method</th>
                    <th>Total Amount</th>
                    <th>Items</th>
                    <th>Delivery Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->payment_method }}</td>
                    <td>{{ pdf_currency($order->total_amount) }}</td>
                    <td>{{ $order->items->count() }}</td>
                    <td>{{ $order->delivered_at ? $order->delivered_at->format('M j, Y') : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>