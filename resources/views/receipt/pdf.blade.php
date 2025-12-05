<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - Order #{{ $order->order_number }}</title>
    <style>
        @page { margin: 20px; }
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 12px;
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            border-bottom: 2px solid #2C8F0C;
            padding-bottom: 15px;
        }
        .company-name {
            color: #2C8F0C;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .receipt-title {
            font-size: 18px;
            margin: 10px 0;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .info-row {
            margin-bottom: 8px;
            display: flex;
        }
        .info-label {
            font-weight: bold;
            min-width: 120px;
        }
        .info-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #2C8F0C;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            background-color: #f0f9f0 !important;
            font-weight: bold;
        }
        .totals-section {
            margin-top: 30px;
            padding: 15px;
            background: #f0f9f0;
            border-radius: 5px;
        }
        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
        }
        .grand-total {
            font-size: 16px;
            color: #2C8F0C;
            font-weight: bold;
            border-bottom: 2px solid #2C8F0C;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-paid {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .thank-you {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
            color: #2C8F0C;
        }
        .watermark {
            position: fixed;
            bottom: 50%;
            left: 0;
            right: 0;
            text-align: center;
            opacity: 0.1;
            font-size: 60px;
            transform: rotate(-45deg);
            color: #2C8F0C;
        }
    </style>
</head>
<body>
    <div class="watermark">DIMDI STORE</div>
    
    <div class="header">
        <div class="company-name">DIMDI STORE</div>
        <div class="receipt-title">ORDER RECEIPT</div>
        <div>Your trusted destination for premium appliances and furniture</div>
    </div>
    
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Receipt No:</div>
            <div class="info-value">{{ $order->order_number }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date:</div>
            <div class="info-value">{{ $order->created_at->format('F d, Y h:i A') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Customer:</div>
            <div class="info-value">{{ $order->customer_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $order->customer_email }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">{{ $order->customer_phone }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge status-confirmed">{{ ucfirst($order->order_status) }}</span>
                <span class="status-badge status-paid">{{ ucfirst($order->payment_status) }}</span>
            </div>
        </div>
    </div>
    
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Shipping Address:</div>
            <div class="info-value">{{ $order->shipping_address }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Billing Address:</div>
            <div class="info-value">{{ $order->billing_address }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Payment Method:</div>
            <div class="info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    {{ $item->product_name }}
                    @if($item->selected_size)
                        <br><small>Variant: {{ $item->selected_size }}</small>
                    @endif
                </td>
                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₱{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="totals-section">
        <div class="total-line">
            <span>Subtotal:</span>
            <span>₱{{ number_format($order->subtotal, 2) }}</span>
        </div>
        <div class="total-line">
            <span>Shipping:</span>
            <span>₱{{ number_format($order->shipping_cost, 2) }}</span>
        </div>
        @if($order->notes)
        <div class="total-line">
            <span>Order Notes:</span>
            <span><em>{{ $order->notes }}</em></span>
        </div>
        @endif
        <div class="total-line grand-total">
            <span>GRAND TOTAL:</span>
            <span>₱{{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>
    
    <div class="thank-you">
        Thank you for shopping with DIMDI Store!
    </div>
    
    <div class="footer">
        <p>This is an official receipt from DIMDI Store.</p>
        <p>For inquiries, contact us at: support@dimdistore.com | (02) 8888-8888</p>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>