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
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            background: white;
            font-size: 12px;
        }
        
        .container {
            max-width: 100%;
            padding: 20px 25px;
        }
        
        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2C8F0C;
            position: relative;
        }
        
        .header h1 {
            color: #2C8F0C;
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .company-name {
            color: #666;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .report-info {
            color: #777;
            font-size: 11px;
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }
        
        /* Summary Statistics */
        .summary-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background: #2C8F0C;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .stat-box {
            display: table-cell;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            width: 25%;
            vertical-align: middle;
        }
        
        .stat-box:nth-child(odd) {
            background: #f9f9f9;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #2C8F0C;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Data Tables */
        .data-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        table th {
            background: #f5f5f5;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            color: #333;
            font-size: 11px;
        }
        
        table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        table tbody tr:nth-child(even) {
            background: #fafafa;
        }
        
        .total-row {
            font-weight: bold;
            background: #E8F5E9 !important;
            border-top: 2px solid #2C8F0C;
        }
        
        .highlight-row {
            background: #f0f8ff !important;
        }
        
        /* Text Alignment Classes */
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        /* Currency formatting */
        .currency {
            font-family: monospace;
            font-weight: bold;
            white-space: nowrap;
        }
        
        /* Badges for status */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 10px;
        }
        
        .badge-success {
            background-color: #2C8F0C;
            color: white;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #888;
        }
        
        .footer-info {
            margin-bottom: 5px;
        }
        
        /* Print specific styles */
        @media print {
            .container {
                padding: 15px 20px;
            }
            
            .header {
                margin-bottom: 20px;
            }
            
            .stat-value {
                font-size: 16px;
            }
            
            table {
                font-size: 10px;
            }
            
            table th,
            table td {
                padding: 6px 8px;
            }
        }
        
        /* Utility classes */
        .mb-2 { margin-bottom: 10px; }
        .mt-2 { margin-top: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-3 { margin-top: 15px; }
        .d-flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .w-100 { width: 100%; }
        .small { font-size: 10px; }
        .text-muted { color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Report Header -->
        <div class="header">
            <h1>SALES REPORT</h1>
            <div class="company-name">{{ config('app.name', 'DIMDI STORE') }}</div>
            <div class="report-info">
                <strong>Report Period:</strong> {{ $salesData['dateRangeText'] }} &nbsp; | &nbsp;
                <strong>Generated:</strong> {{ now()->format('F j, Y g:i A') }}
            </div>
        </div>
        
        <!-- Summary Statistics -->
        <div class="summary-section">
            <div class="section-title">📊 PERFORMANCE SUMMARY</div>
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-value">{{ pdf_currency($salesData['totalSales']) }}</span>
                    <span class="stat-label">Total Revenue</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value">{{ $salesData['totalOrders'] }}</span>
                    <span class="stat-label">Total Orders</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value">{{ pdf_currency($salesData['averageOrderValue']) }}</span>
                    <span class="stat-label">Average Order Value</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value">{{ $salesData['dailySales']->count() }}</span>
                    <span class="stat-label">Days Analyzed</span>
                </div>
            </div>
        </div>
        
        <!-- Daily Sales Breakdown -->
        @if($salesData['dailySales']->count() > 0)
        <div class="data-section">
            <div class="section-title">📅 DAILY SALES BREAKDOWN</div>
            <table>
                <thead>
                    <tr>
                        <th width="15%">Date</th>
                        <th class="text-right" width="25%">Sales Amount</th>
                        <th class="text-center" width="15%"># of Orders</th>
                        <th class="text-right" width="25%">Average per Order</th>
                        <th class="text-center" width="20%">Day Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $peakSales = $salesData['dailySales']->max('total');
                        $averageSales = $salesData['dailySales']->avg('total');
                    @endphp
                    
                    @foreach($salesData['dailySales'] as $sale)
                    @php
                        $isPeak = $sale['total'] == $peakSales;
                        $isAboveAverage = $sale['total'] > $averageSales;
                        $dailyAverage = $sale['count'] > 0 ? $sale['total'] / $sale['count'] : 0;
                    @endphp
                    <tr class="{{ $isPeak ? 'highlight-row' : '' }}">
                        <td><strong>{{ $sale['date'] }}</strong></td>
                        <td class="text-right currency">
                            <strong>{{ pdf_currency($sale['total']) }}</strong>
                            @if($isPeak)
                            <br><span class="badge badge-success small">PEAK DAY</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $sale['count'] }}</td>
                        <td class="text-right currency">{{ pdf_currency($dailyAverage) }}</td>
                        <td class="text-center">
                            @if($isPeak)
                                <span class="badge badge-success small">Peak</span>
                            @elseif($isAboveAverage)
                                <span class="badge badge-info small">Above Avg</span>
                            @else
                                <span class="text-muted small">Normal</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    
                    <!-- Totals Row -->
                    <tr class="total-row">
                        <td><strong>TOTAL / AVERAGE</strong></td>
                        <td class="text-right currency">
                            <strong>{{ pdf_currency($salesData['totalSales']) }}</strong>
                        </td>
                        <td class="text-center"><strong>{{ $salesData['totalOrders'] }}</strong></td>
                        <td class="text-right currency">
                            <strong>{{ pdf_currency($salesData['averageOrderValue']) }}</strong>
                        </td>
                        <td class="text-center">
                            <strong>{{ $salesData['dailySales']->count() }} days</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Daily Performance Notes -->
            <div style="font-size: 10px; color: #666; margin-top: 10px; padding: 8px; background: #f9f9f9; border-radius: 4px;">
                <strong>Analysis:</strong> 
                @if($peakSales > 0)
                Peak sales day: {{ pdf_currency($peakSales) }} •
                Daily average: {{ pdf_currency($averageSales) }}
                @endif
            </div>
        </div>
        @endif
        
        <!-- Payment Methods Analysis -->
        @if($salesData['salesByPayment']->count() > 0)
        <div class="data-section">
            <div class="section-title">💳 PAYMENT METHOD ANALYSIS</div>
            <table>
                <thead>
                    <tr>
                        <th width="20%">Payment Method</th>
                        <th class="text-right" width="20%">Total Revenue</th>
                        <th class="text-center" width="15%">Orders</th>
                        <th class="text-center" width="15%">Market Share</th>
                        <th class="text-right" width="20%">Average per Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesData['salesByPayment'] as $method => $data)
                    @php
                        $methodTotal = $data['total'];
                        $methodCount = $data['count'];
                        $methodPercentage = $data['percentage'];
                        $methodAverage = $methodCount > 0 ? $methodTotal / $methodCount : 0;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ strtoupper($method) }}</strong>
                        </td>
                        <td class="text-right currency">
                            <strong>{{ pdf_currency($methodTotal) }}</strong>
                        </td>
                        <td class="text-center">{{ $methodCount }}</td>
                        <td class="text-center">
                            <strong>{{ number_format($methodPercentage, 1) }}%</strong>
                        </td>
                        <td class="text-right currency">{{ pdf_currency($methodAverage) }}</td>
                    </tr>
                    @endforeach
                    
                    <!-- Payment Methods Total -->
                    <tr class="total-row">
                        <td><strong>ALL METHODS</strong></td>
                        <td class="text-right currency">
                            <strong>{{ pdf_currency($salesData['totalSales']) }}</strong>
                        </td>
                        <td class="text-center"><strong>{{ $salesData['totalOrders'] }}</strong></td>
                        <td class="text-center"><strong>100%</strong></td>
                        <td class="text-right currency">
                            <strong>{{ pdf_currency($salesData['averageOrderValue']) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Detailed Order Listing -->
        @if($orders->count() > 0)
        <div class="data-section">
            <div class="section-title">📦 ORDER DETAILS ({{ $orders->count() }} Orders)</div>
            
            @if($orders->count() <= 50) {{-- Show full table for small datasets --}}
            <table>
                <thead>
                    <tr>
                        <th width="12%">Order #</th>
                        <th width="20%">Customer</th>
                        <th width="12%">Payment</th>
                        <th class="text-right" width="15%">Amount</th>
                        <th class="text-center" width="10%">Items</th>
                        <th width="16%">Order Date</th>
                        <th width="15%">Delivery Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <strong style="font-size: 10px;">{{ $order->order_number }}</strong>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $order->customer_name }}</div>
                            <div style="font-size: 9px; color: #666;">{{ $order->customer_email }}</div>
                        </td>
                        <td>
                            <span class="badge badge-info small">{{ strtoupper($order->payment_method) }}</span>
                        </td>
                        <td class="text-right currency">
                            <strong>{{ pdf_currency($order->total_amount) }}</strong>
                        </td>
                        <td class="text-center">{{ $order->items->count() }}</td>
                        <td>{{ $order->created_at->format('M j, Y') }}</td>
                        <td>
                            @if($order->delivered_at)
                                {{ $order->delivered_at->format('M j, Y') }}
                            @else
                                <em style="color: #999;">Pending</em>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else {{-- Show summary for large datasets --}}
            <div style="padding: 15px; background: #f8f9fa; border-radius: 4px; margin-bottom: 15px;">
                <p style="margin-bottom: 10px;">
                    <strong>Large dataset detected:</strong> Showing order summary instead of detailed listing.
                    Total orders: {{ $orders->count() }}
                </p>
                <div class="d-flex justify-between">
                    <div>
                        <strong>Top 5 Orders by Value:</strong><br>
                        @foreach($orders->sortByDesc('total_amount')->take(5) as $topOrder)
                        • {{ $topOrder->order_number }}: {{ pdf_currency($topOrder->total_amount) }}<br>
                        @endforeach
                    </div>
                    <div>
                        <strong>Order Value Range:</strong><br>
                        Min: {{ pdf_currency($orderStatistics['min_amount']) }}<br>
                        Max: {{ pdf_currency($orderStatistics['max_amount']) }}<br>
                        Median: {{ pdf_currency($orderStatistics['median_amount']) }}
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Order Statistics Summary -->
            <div style="margin-top: 15px; padding: 10px; background: #f0f8ff; border-radius: 4px; border-left: 4px solid #2C8F0C;">
                <div class="d-flex justify-between">
                    <div>
                        <strong>Order Statistics:</strong><br>
                        • Total Orders: {{ $orderStatistics['order_count'] }}<br>
                        • Total Revenue: {{ pdf_currency($orderStatistics['total_amount']) }}<br>
                        • Value Range: {{ pdf_currency($orderStatistics['min_amount']) }} -
                        {{ pdf_currency($orderStatistics['max_amount']) }}
                    </div>
                    <div>
                        <strong>Statistical Analysis:</strong><br>
                        • Average: {{ pdf_currency($orderStatistics['average_amount']) }}<br>
                        • Median: {{ pdf_currency($orderStatistics['median_amount']) }}<br>
                        • Std Deviation: {{ pdf_currency($orderStatistics['std_deviation']) }}
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="data-section">
            <div class="section-title">📦 ORDER DETAILS</div>
            <div style="text-align: center; padding: 30px; background: #f9f9f9; border-radius: 4px;">
                <p style="color: #999; font-size: 14px;">
                    <strong>No orders found</strong><br>
                    for the selected filter criteria
                </p>
            </div>
        </div>
        @endif
        
        <!-- Executive Summary -->
        <div class="data-section">
            <div class="section-title">📋 EXECUTIVE SUMMARY</div>
            
            <table style="background: #f8f9fa;">
                <tbody>
                    <tr>
                        <td width="30%" style="font-weight: bold; background: #e9ecef;">Report Period:</td>
                        <td>{{ $salesData['dateRangeText'] }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #e9ecef;">Total Revenue Generated:</td>
                        <td class="currency">
                            <strong>{{ pdf_currency($salesData['totalSales']) }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #e9ecef;">Total Transactions:</td>
                        <td>{{ $salesData['totalOrders'] }} orders</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #e9ecef;">Average Transaction Value:</td>
                        <td class="currency">
                            {{ pdf_currency($salesData['averageOrderValue']) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #e9ecef;">Revenue per Day:</td>
                        <td class="currency">
                            {{ pdf_currency($salesData['dailySales']->count() > 0 ? $salesData['totalSales'] / $salesData['dailySales']->count() : 0) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #e9ecef;">Orders per Day:</td>
                        <td>
                            {{ $salesData['dailySales']->count() > 0 ? number_format($salesData['totalOrders'] / $salesData['dailySales']->count(), 1) : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #e9ecef;">Report Generated:</td>
                        <td>{{ now()->format('F j, Y g:i A') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #e9ecef;">Generated By:</td>
                        <td>{{ config('app.name', 'DIMDI STORE') }} Sales System</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Performance Insights -->
            @if($salesData['totalSales'] > 0)
            <div style="margin-top: 15px; padding: 12px; background: #fff8e1; border-radius: 4px; border-left: 4px solid #ff9800;">
                <strong>📈 Performance Insights:</strong><br>
                <div style="font-size: 10px; margin-top: 5px;">
                    • {{ $salesData['totalOrders'] }} orders generated
                    {{ pdf_currency($salesData['totalSales']) }} in revenue<br>
                    • Average order value is {{ pdf_currency($salesData['averageOrderValue']) }}<br>
                    • Daily revenue average is
                    {{ pdf_currency($salesData['dailySales']->count() > 0 ? $salesData['totalSales'] / $salesData['dailySales']->count() : 0) }}<br>
                    @if($salesData['dailySales']->count() > 0)
                    • Peak revenue day:
                    {{ pdf_currency($salesData['dailySales']->max('total')) }}
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-info">
                <strong>{{ config('app.name', 'DIMDI STORE') }}</strong> - Sales Analysis Report
            </div>
            <div class="footer-info">
                This report was automatically generated by the system. For any discrepancies, please contact the administrator.
            </div>
            <div class="footer-info" style="margin-top: 10px;">
                Confidential Business Document • Generated on {{ now()->format('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>
