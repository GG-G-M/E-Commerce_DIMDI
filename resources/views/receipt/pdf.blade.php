<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2937; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .title { font-size: 22px; font-weight: bold; color: #2C8F0C; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px 10px; border: 1px solid #e5e7eb; font-size: 12px; }
        th { background: #f3f4f6; text-align: left; }
        .text-right { text-align: right; }
        .totals td { font-weight: bold; }
        .footer { margin-top: 20px; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="title">DIMDI Store</div>
            <div>Order Receipt</div>
            <div>Order #: {{ $order->order_number }}</div>
        </div>
        <div>
            <div>Date: {{ now()->format('M d, Y') }}</div>
            <div>Customer: {{ $order->customer_name }}</div>
            <div>Email: {{ $order->customer_email }}</div>
        </div>
    </div>

    <div>
        <strong>Billing / Shipping Address</strong>
        <p style="white-space: pre-line; margin: 6px 0 0 0;">{{ $order->shipping_address }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th class="text-right">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }} @if($item->selected_size) ({{ $item->selected_size }}) @endif</td>
                <td>{{ $item->quantity }}</td>
                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">₱{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="margin-top: 10px;">
        <tbody>
            <tr>
                <td style="border: none;">Payment Method</td>
                <td class="text-right" style="border: none;">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
            </tr>
            <tr>
                <td style="border: none;">Subtotal</td>
                <td class="text-right" style="border: none;">₱{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td style="border: none;">Tax</td>
                <td class="text-right" style="border: none;">₱{{ number_format($order->tax_amount ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td style="border: none;">Shipping</td>
                <td class="text-right" style="border: none;">₱{{ number_format($order->shipping_cost, 2) }}</td>
            </tr>
            <tr class="totals">
                <td style="border: none;">Total</td>
                <td class="text-right" style="border: none;">₱{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        This receipt confirms payment for the order shown above. Please keep it for your records.
    </div>
</body>
</html>

