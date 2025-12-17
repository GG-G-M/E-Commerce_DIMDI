<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Comparison Report</title>
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
            width: 33.33%;
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
        
        .summary-item.orange .summary-value {
            color: #FFA000;
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
        
        .positive {
            color: #2C8F0C;
        }
        
        .negative {
            color: #dc3545;
        }
        
        .month-label {
            font-weight: 600;
            min-width: 60px;
        }
        
        /* Growth Indicator */
        .growth-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .growth-positive {
            background: rgba(44, 143, 12, 0.1);
            color: #2C8F0C;
        }
        
        .growth-negative {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
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
            <h1>Sales Comparison Report</h1>
            <div class="company-name">{{ config('app.name') }}</div>
            <div class="report-info">
                <strong>Comparing:</strong> {{ $year1 }} vs {{ $year2 }}<br>
                <strong>Generated:</strong> {{ now()->format('F j, Y g:i A') }}
            </div>
        </div>
        
        <!-- Summary Stats -->
        <div class="summary-section">
            <div class="summary-title">📊 Comparison Summary</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-value">{{ pdf_currency(array_sum($year1Sales)) }}</span>
                    <span class="summary-label">{{ $year1 }} Total Sales</span>
                </div>
                <div class="summary-item orange">
                    <span class="summary-value">{{ pdf_currency(array_sum($year2Sales)) }}</span>
                    <span class="summary-label">{{ $year2 }} Total Sales</span>
                </div>
                <div class="summary-item">
                    <span class="summary-value {{ $totalGrowth >= 0 ? 'positive' : 'negative' }}">
                        {{ number_format($totalGrowth, 1) }}%
                    </span>
                    <span class="summary-label">Overall Growth Rate</span>
                </div>
            </div>
        </div>
        
        <!-- Growth Analysis -->
        <div class="data-section">
            <div class="section-title">📈 Growth Analysis</div>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 40%; font-weight: bold;">{{ $year1 }} Total Revenue:</td>
                        <td class="text-right">{{ pdf_currency(array_sum($year1Sales)) }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">{{ $year2 }} Total Revenue:</td>
                        <td class="text-right">{{ pdf_currency(array_sum($year2Sales)) }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Revenue Difference:</td>
                        <td class="text-right {{ (array_sum($year1Sales) - array_sum($year2Sales)) >= 0 ? 'positive' : 'negative' }}">
                            {{ pdf_currency(array_sum($year1Sales) - array_sum($year2Sales)) }}
                        </td>
                    </tr>
                    <tr class="total-row">
                        <td style="font-weight: bold;">Growth Rate:</td>
                        <td class="text-right {{ $totalGrowth >= 0 ? 'positive' : 'negative' }}">
                            {{ number_format($totalGrowth, 1) }}%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Monthly Breakdown -->
        <div class="data-section">
            <div class="section-title">📅 Monthly Breakdown & Comparison</div>
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-right">{{ $year1 }} Sales</th>
                        <th class="text-right">{{ $year2 }} Sales</th>
                        <th class="text-right">Growth %</th>
                        <th class="text-right">Difference</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    @endphp
                    @foreach($months as $index => $month)
                    @php
                        $monthNum = $index + 1;
                        $sales1 = $year1Sales[$monthNum];
                        $sales2 = $year2Sales[$monthNum];
                        $growth = $growthData[$monthNum];
                        $difference = $sales1 - $sales2;
                    @endphp
                    <tr>
                        <td class="month-label">{{ $month }}</td>
                        <td class="text-right">{{ pdf_currency($sales1) }}</td>
                        <td class="text-right">{{ pdf_currency($sales2) }}</td>
                        <td class="text-right">
                            <span class="growth-badge {{ $growth >= 0 ? 'growth-positive' : 'growth-negative' }}">
                                {{ number_format($growth, 1) }}%
                            </span>
                        </td>
                        <td class="text-right {{ $difference >= 0 ? 'positive' : 'negative' }}">
                            {{ pdf_currency($difference) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td class="month-label">TOTAL</td>
                        <td class="text-right">{{ pdf_currency(array_sum($year1Sales)) }}</td>
                        <td class="text-right">{{ pdf_currency(array_sum($year2Sales)) }}</td>
                        <td class="text-right">
                            <span class="growth-badge {{ $totalGrowth >= 0 ? 'growth-positive' : 'growth-negative' }}">
                                {{ number_format($totalGrowth, 1) }}%
                            </span>
                        </td>
                        <td class="text-right {{ (array_sum($year1Sales) - array_sum($year2Sales)) >= 0 ? 'positive' : 'negative' }}">
                            {{ pdf_currency(array_sum($year1Sales) - array_sum($year2Sales)) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Performance Insights -->
        <div class="data-section">
            <div class="section-title">💡 Performance Insights</div>
            <table>
                <tbody>
                    @php
                        $bestMonth1 = array_search(max($year1Sales), $year1Sales);
                        $worstMonth1 = array_search(min($year1Sales), $year1Sales);
                        $bestMonth2 = array_search(max($year2Sales), $year2Sales);
                        $worstMonth2 = array_search(min($year2Sales), $year2Sales);
                        $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    @endphp
                    <tr>
                        <td style="width: 40%; font-weight: bold;">Best Performing Month ({{ $year1 }}):</td>
                        <td>{{ $months[$bestMonth1] }} - {{ pdf_currency($year1Sales[$bestMonth1]) }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Worst Performing Month ({{ $year1 }}):</td>
                        <td>{{ $months[$worstMonth1] }} - {{ pdf_currency($year1Sales[$worstMonth1]) }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Best Performing Month ({{ $year2 }}):</td>
                        <td>{{ $months[$bestMonth2] }} - {{ pdf_currency($year2Sales[$bestMonth2]) }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Worst Performing Month ({{ $year2 }}):</td>
                        <td>{{ $months[$worstMonth2] }} - {{ pdf_currency($year2Sales[$worstMonth2]) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="font-weight: bold;">Average Monthly Sales ({{ $year1 }}):</td>
                        <td>{{ pdf_currency(array_sum($year1Sales) / 12) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="font-weight: bold;">Average Monthly Sales ({{ $year2 }}):</td>
                        <td>{{ pdf_currency(array_sum($year2Sales) / 12) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Report Summary -->
        <div class="data-section">
            <div class="section-title">📋 Report Summary</div>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 40%; font-weight: bold;">Report Type:</td>
                        <td>Year-over-Year Sales Comparison</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Comparison Period:</td>
                        <td>{{ $year1 }} vs {{ $year2 }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Overall Growth:</td>
                        <td class="{{ $totalGrowth >= 0 ? 'positive' : 'negative' }}">
                            {{ number_format($totalGrowth, 1) }}%
                        </td>
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
                {{ config('app.name') }} Sales Comparison Report
            </div>
            <div class="footer-info">
                This is an automatically generated report. For inquiries, please contact the administrator.
            </div>
        </div>
    </div>
</body>
</html>
