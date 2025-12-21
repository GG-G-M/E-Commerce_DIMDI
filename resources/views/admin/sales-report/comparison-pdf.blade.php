<!DOCTYPE html>
<html>
<head>
    <title>Sales Report Comparison - {{ $year1 }} vs {{ $year2 }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .comparison-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .comparison-table th, .comparison-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .comparison-table th { background-color: #f8f9fa; font-weight: bold; }
        .positive { color: green; }
        .negative { color: red; }
        .summary { margin-bottom: 20px; }
        .summary-card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DIMDI STORE - SALES COMPARISON REPORT</h1>
        <p>Comparing {{ $year1 }} vs {{ $year2 }}</p>
        <p>Generated on: {{ date('F j, Y g:i A') }}</p>
    </div>

    <div class="summary">
        <h3>Yearly Summary</h3>
        <div class="summary-card">
            <p><strong>{{ $year1 }} Total Sales:</strong> {{ pdf_currency(array_sum($year1Sales)) }}</p>
            <p><strong>{{ $year2 }} Total Sales:</strong> {{ pdf_currency(array_sum($year2Sales)) }}</p>
            <p><strong>Growth Rate:</strong> <span class="{{ $totalGrowth >= 0 ? 'positive' : 'negative' }}">{{ number_format($totalGrowth, 2) }}%</span></p>
        </div>
    </div>

    <div class="monthly-comparison">
        <h3>Monthly Comparison</h3>
        <table class="comparison-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>{{ $year2 }} Sales</th>
                    <th>{{ $year1 }} Sales</th>
                    <th>Growth</th>
                    <th>Growth %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($year1Sales as $month => $sales1)
                <tr>
                    <td>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</td>
                    <td>{{ pdf_currency($year2Sales[$month]) }}</td>
                    <td>{{ pdf_currency($sales1) }}</td>
                    <td class="{{ $growthData[$month] >= 0 ? 'positive' : 'negative' }}">
                        {{ pdf_currency($sales1 - $year2Sales[$month]) }}
                    </td>
                    <td class="{{ $growthData[$month] >= 0 ? 'positive' : 'negative' }}">
                        {{ number_format($growthData[$month], 2) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
