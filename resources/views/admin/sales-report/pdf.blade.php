<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background: white;
        }
        
        .container {
            max-width: 100%;
            padding: 20px;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2C8F0C;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2C8F0C;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header .company-name {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .header .report-info {
            color: #999;
            font-size: 12px;
        }
        
        /* Summary Stats */
        .summary-section {
            margin-bottom: 30px;
        }
        
        .summary-title {
            background: #2C8F0C;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .summary-item {
            display: table-cell;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            width: 25%;
        }
        
        .summary-item:nth-child(odd) {
            background: #f9f9f9;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #2C8F0C;
            display: block;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        /* Tables */
        .data-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #2C8F0C;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        table th {
            background: #f5f5f5;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            color: #333;
        }
        
        table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .total-row {
            font-weight: bold;
            background: #E8F5E9 !important;
            border-top: 2px solid #2C8F0C;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        
        .footer-info {
            margin-bottom: 10px;
        }
        
        /* Page break */
        .page-break {
            page-break-after: always;
        }
        
        /* Responsive */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .container {
                padding: 10px;
            }
            
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Sales Report</h1>
            <div class="company-name">{{ config('app.name') }}</div>
            <div class="report-info">
                <strong>Period:</strong> {{ $salesData['dateRangeText'] }}<br>
                <strong>Generated:</strong> {{ now()->format('F j, Y g:i A') }}
            </div>
        </div>
        
        <!-- Summary Stats -->
        <div class="summary-section">
            <div class="summary-title">📊 Summary Statistics</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-value">₱{{ number_format($salesData['totalSales'], 2) }}</span>
                    <span class="summary-label">Total Sales</span>
                </div>
                <div class="summary-item">
                    <span class="summary-value">{{ $salesData['totalOrders'] }}</span>
                    <span class="summary-label">Total Orders</span>
                </div>
                <div class="summary-item">
                    <span class="summary-value">₱{{ number_format($salesData['averageOrderValue'], 2) }}</span>
                    <span class="summary-label">Avg Order Value</span>
                </div>
                <div class="summary-item">
                    <span class="summary-value">{{ $salesData['dailySales']->count() }}</span>
                    <span class="summary-label">Days in Period</span>
                </div>
            </div>
        </div>
        
        <!-- Daily Sales Data -->
        @if($salesData['dailySales']->count() > 0)
        <div class="data-section">
            <div class="section-title">📅 Daily Sales Data</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th class="text-right">Sales Amount</th>
                        <th class="text-center">Orders</th>
                        <th class="text-right">Average/Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData['dailySales'] as $sale)
                    <tr>
                        <td>{{ $sale['date'] }}</td>
                        <td class="text-right">₱{{ number_format($sale['total'], 2) }}</td>
                        <td class="text-center">{{ $sale['count'] }}</td>
                        <td class="text-right">₱{{ $sale['count'] > 0 ? number_format($sale['total'] / $sale['count'], 2) : '0.00' }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td><strong>TOTAL</strong></td>
                        <td class="text-right"><strong>₱{{ number_format($salesData['totalSales'], 2) }}</strong></td>
                        <td class="text-center"><strong>{{ $salesData['totalOrders'] }}</strong></td>
                        <td class="text-right"><strong>₱{{ number_format($salesData['averageOrderValue'], 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Payment Methods Summary -->
        @if($salesData['salesByPayment']->count() > 0)
        <div class="data-section">
            <div class="section-title">💳 Payment Methods Summary</div>
            <table>
                <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th class="text-right">Total Sales</th>
                        <th class="text-center">Orders</th>
                        <th class="text-center">Percentage</th>
                        <th class="text-right">Average/Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData['salesByPayment'] as $method => $data)
                    <tr>
                        <td><strong>{{ ucfirst($method) }}</strong></td>
                        <td class="text-right">₱{{ number_format($data['total'], 2) }}</td>
                        <td class="text-center">{{ $data['count'] }}</td>
                        <td class="text-center">{{ number_format($data['percentage'], 1) }}%</td>
                        <td class="text-right">₱{{ number_format($data['total'] / max(1, $data['count']), 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td><strong>TOTAL</strong></td>
                        <td class="text-right"><strong>₱{{ number_format($salesData['totalSales'], 2) }}</strong></td>
                        <td class="text-center"><strong>{{ $salesData['totalOrders'] }}</strong></td>
                        <td class="text-center"><strong>100%</strong></td>
                        <td class="text-right"><strong>₱{{ number_format($salesData['averageOrderValue'], 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Sales Orders -->
        @if($orders->count() > 0)
        <div class="data-section">
            <div class="section-title">📦 Sales Orders</div>
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Payment</th>
                        <th class="text-right">Amount</th>
                        <th class="text-center">Items</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>
                            <strong>{{ $order->customer_name }}</strong><br>
                            <small>{{ $order->customer_email }}</small>
                        </td>
                        <td>{{ strtoupper($order->payment_method) }}</td>
                        <td class="text-right"><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
                        <td class="text-center">{{ $order->items->count() }}</td>
                        <td>{{ $order->created_at->format('M j, Y') }}</td>
                        <td>
                            @if($order->delivered_at)
                                {{ $order->delivered_at->format('M j, Y') }}
                            @else
                                <em>Pending</em>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="data-section">
            <p style="text-align: center; color: #999; padding: 20px;">No orders found for the selected filters.</p>
        </div>
        @endif
        
        <!-- Report Summary -->
        <div class="data-section">
            <div class="section-title">📋 Report Summary</div>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Report Period:</td>
                        <td>{{ $salesData['dateRangeText'] }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Total Revenue:</td>
                        <td>₱{{ number_format($salesData['totalSales'], 2) }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Total Orders:</td>
                        <td>{{ $salesData['totalOrders'] }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Average Order Value:</td>
                        <td>₱{{ number_format($salesData['averageOrderValue'], 2) }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Report Generated:</td>
                        <td>{{ now()->format('F j, Y g:i A') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-info">
                {{ config('app.name') }} Sales Report
            </div>
            <div class="footer-info">
                This is an automatically generated report. For inquiries, please contact the administrator.
            </div>
        </div>
    </div>
</body>
</html>
