<?php
// app/Http/Controllers/Admin/SalesReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('order_status', 'delivered');
        
        // Apply filters
        $query = $this->applyFilters($query, $request);
        
        // Get sales data for charts
        $salesData = $this->getSalesData($request);
        $orders = $query->with('user', 'items')->orderBy('delivered_at', 'desc')->paginate(20);
        
        // If AJAX request, return only the content
        if ($request->ajax()) {
            return view('admin.sales-report.partials.content', compact('orders', 'salesData'))->render();
        }
        
        return view('admin.sales-report.index', compact('orders', 'salesData'));
    }

    private function applyFilters($query, Request $request)
    {
        // Date filters
        if ($request->has('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('delivered_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('delivered_at', Carbon::yesterday());
                    break;
                case 'this_week':
                    $query->whereBetween('delivered_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereBetween('delivered_at', [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                    break;
                case 'this_year':
                    $query->whereBetween('delivered_at', [
                        Carbon::now()->startOfYear(),
                        Carbon::now()->endOfYear()
                    ]);
                    break;
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $query->whereBetween('delivered_at', [
                            $request->start_date,
                            $request->end_date
                        ]);
                    }
                    break;
            }
        }

        // Payment method filter
        if ($request->payment_method && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        return $query;
    }

    private function getSalesData(Request $request)
    {
        $baseQuery = Order::where('order_status', 'delivered');
        
        // Apply same filters for data
        $baseQuery = $this->applyFilters($baseQuery, $request);

        // Total sales
        $totalSales = $baseQuery->sum('total_amount');
        $totalOrders = $baseQuery->count();

        // Sales by payment method
        $paymentData = $baseQuery->selectRaw('payment_method, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();
        
        $salesByPayment = new Collection();
        foreach ($paymentData as $item) {
            $salesByPayment[$item->payment_method] = [
                'total' => $item->total,
                'count' => $item->count,
                'percentage' => $totalSales > 0 ? ($item->total / $totalSales) * 100 : 0
            ];
        }

        // Get date range for queries
        $dateRange = $this->getDateRangeForQuery($request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Daily sales - generate all dates in range (even empty ones)
        $dailySales = $this->getDailySalesWithAllDates($startDate, $endDate);

        // Weekly sales for the date range
        $weeklySales = Order::where('order_status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->selectRaw('YEAR(delivered_at) as year, WEEK(delivered_at) as week, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('year', 'week')
            ->orderBy('year', 'desc')
            ->orderBy('week', 'desc')
            ->get();

        // Monthly sales for the date range
        $monthlySales = Order::where('order_status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->selectRaw('YEAR(delivered_at) as year, MONTH(delivered_at) as month, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Prepare weekly data for chart
        $weeklySalesData = new Collection();
        foreach ($weeklySales as $sale) {
            $weekStart = Carbon::now()->setISODate($sale->year, $sale->week)->startOfWeek();
            $weekEnd = Carbon::now()->setISODate($sale->year, $sale->week)->endOfWeek();
            
            $weeklySalesData->push([
                'week' => "Week {$sale->week}",
                'week_number' => $sale->week,
                'year' => $sale->year,
                'week_range' => $weekStart->format('M j') . ' - ' . $weekEnd->format('M j'),
                'total' => $sale->total,
                'count' => $sale->count
            ]);
        }

        // Prepare monthly data for chart
        $monthlySalesData = new Collection();
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        foreach ($monthlySales as $sale) {
            $monthlySalesData->push([
                'month' => $monthNames[$sale->month - 1] . ' ' . $sale->year,
                'month_number' => $sale->month,
                'year' => $sale->year,
                'total' => $sale->total,
                'count' => $sale->count
            ]);
        }

        // Date range text for display
        $dateRangeText = $this->getDateRangeText($request);

        return [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'salesByPayment' => $salesByPayment,
            'dailySales' => $dailySales,
            'weeklySales' => $weeklySalesData->sortBy(['year', 'week_number'])->values(),
            'monthlySales' => $monthlySalesData->sortBy(['year', 'month_number'])->values(),
            'averageOrderValue' => $totalOrders > 0 ? $totalSales / $totalOrders : 0,
            'dateRangeText' => $dateRangeText,
        ];
    }

    private function getDateRangeForQuery(Request $request)
    {
        switch ($request->date_range) {
            case 'today':
                return [
                    'start' => Carbon::today(),
                    'end' => Carbon::today()->endOfDay()
                ];
            case 'yesterday':
                return [
                    'start' => Carbon::yesterday(),
                    'end' => Carbon::yesterday()->endOfDay()
                ];
            case 'this_week':
                return [
                    'start' => Carbon::now()->startOfWeek(),
                    'end' => Carbon::now()->endOfWeek()
                ];
            case 'this_month':
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth()
                ];
            case 'this_year':
                return [
                    'start' => Carbon::now()->startOfYear(),
                    'end' => Carbon::now()->endOfYear()
                ];
            case 'custom':
                if ($request->start_date && $request->end_date) {
                    return [
                        'start' => Carbon::parse($request->start_date)->startOfDay(),
                        'end' => Carbon::parse($request->end_date)->endOfDay()
                    ];
                }
                break;
        }
        
        // Default: last 30 days
        return [
            'start' => Carbon::now()->subDays(30),
            'end' => Carbon::now()
        ];
    }

    private function getDailySalesWithAllDates($startDate, $endDate)
    {
        // Get actual sales data
        $actualSales = Order::where('order_status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->selectRaw('DATE(delivered_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailySalesData = new Collection();
        $currentDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Generate all dates in the range
        while ($currentDate <= $end) {
            $dateString = $currentDate->format('Y-m-d');
            $saleData = $actualSales->get($dateString);

            $dailySalesData->push([
                'date' => $currentDate->format('M j'),
                'full_date' => $dateString,
                'total' => $saleData ? $saleData->total : 0,
                'count' => $saleData ? $saleData->count : 0
            ]);

            $currentDate->addDay();
        }

        return $dailySalesData;
    }

    private function getDateRangeText(Request $request)
    {
        switch ($request->date_range) {
            case 'today':
                return 'Today: ' . Carbon::today()->format('M j, Y');
            case 'yesterday':
                return 'Yesterday: ' . Carbon::yesterday()->format('M j, Y');
            case 'this_week':
                return 'This Week: ' . Carbon::now()->startOfWeek()->format('M j') . ' - ' . Carbon::now()->endOfWeek()->format('M j, Y');
            case 'this_month':
                return 'This Month: ' . Carbon::now()->format('F Y');
            case 'this_year':
                return 'This Year: ' . Carbon::now()->format('Y');
            case 'custom':
                if ($request->start_date && $request->end_date) {
                    return 'Custom: ' . Carbon::parse($request->start_date)->format('M j, Y') . ' - ' . Carbon::parse($request->end_date)->format('M j, Y');
                }
                break;
        }
        return 'Last 30 Days'; // Default
    }

    public function export(Request $request)
    {
        // Your existing export method
        $query = Order::where('order_status', 'delivered');
        $query = $this->applyFilters($query, $request);
        
        $orders = $query->with('user', 'items')->get();
        $salesData = $this->getSalesData($request);

        $exportType = $request->export_type ?? 'pdf';

        if ($exportType === 'pdf') {
            $html = view('admin.sales-report.export.pdf', [
                'orders' => $orders,
                'salesData' => $salesData,
                'filters' => $request->all()
            ])->render();

            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="sales-report-' . date('Y-m-d') . '.html"');
            
        } else {
            // CSV Export
            $fileName = 'sales-report-' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$fileName\""
            ];

            $callback = function() use ($orders, $salesData) {
                $file = fopen('php://output', 'w');
                
                // Header
                fputcsv($file, ['DIMDI STORE - SALES REPORT']);
                fputcsv($file, ['Generated on: ' . date('F j, Y g:i A')]);
                fputcsv($file, ['Total Sales: ₱' . number_format($salesData['totalSales'], 2)]);
                fputcsv($file, ['Total Orders: ' . $salesData['totalOrders']]);
                fputcsv($file, []); // Empty line
                
                // Orders Header
                fputcsv($file, [
                    'Order Number', 'Customer', 'Email', 'Payment Method', 
                    'Total Amount', 'Items', 'Delivery Date'
                ]);

                // Orders Data
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->order_number,
                        $order->customer_name,
                        $order->customer_email,
                        $order->payment_method,
                        $order->total_amount,
                        $order->items->count(),
                        $order->delivered_at ? $order->delivered_at->format('Y-m-d H:i:s') : 'N/A'
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }

    public function comparison(Request $request)
    {
        // Your existing comparison method
        $year1 = $request->get('year1', date('Y'));
        $year2 = $request->get('year2', date('Y') - 1);
        
        $year1Sales = $this->getMonthlySalesData($year1);
        $year2Sales = $this->getMonthlySalesData($year2);
        
        $growthData = [];
        $totalYear1 = array_sum($year1Sales);
        $totalYear2 = array_sum($year2Sales);
        $totalGrowth = $totalYear2 > 0 ? (($totalYear1 - $totalYear2) / $totalYear2) * 100 : ($totalYear1 > 0 ? 100 : 0);
        
        foreach ($year1Sales as $month => $sales1) {
            $sales2 = $year2Sales[$month];
            $growth = $sales2 > 0 ? (($sales1 - $sales2) / $sales2) * 100 : ($sales1 > 0 ? 100 : 0);
            $growthData[$month] = round($growth, 2);
        }
        
        return view('admin.sales-report.comparison', compact(
            'year1', 
            'year2', 
            'year1Sales', 
            'year2Sales',
            'growthData',
            'totalGrowth'
        ));
    }

    private function getMonthlySalesData($year)
    {
        $monthlySales = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $sales = Order::where('order_status', 'delivered')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_amount');
            
            $monthlySales[$month] = (float) $sales;
        }
        
        return $monthlySales;
    }

    // In your SalesReportController.php

public function exportPdf(Request $request)
    {
        $query = Order::where('order_status', 'delivered');
        
        // Apply filters
        $query = $this->applyFilters($query, $request);
        
        // Get sales data
        $salesData = $this->getSalesData($request);
        $orders = $query->with('items')->orderBy('delivered_at', 'desc')->get();
        
        // Calculate additional statistics for PDF
        $orderStatistics = $this->calculateOrderStatistics($orders);
        
        // Generate PDF with proper encoding
        $pdf = Pdf::loadView('admin.sales-report.pdf', [
            'orders' => $orders,
            'salesData' => $salesData,
            'orderStatistics' => $orderStatistics,
            'request' => $request
        ]);
        
        // Set paper size
        $pdf->setPaper('A4', 'portrait');
        
        // IMPORTANT: Set encoding options for Peso sign and better font support
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'Noto Sans',  // Use Noto Sans which has extensive Unicode support including peso
            'fontHeightRatio' => 0.9,
            'enable_unicode' => true,
            'charset' => 'UTF-8',
            'dpi' => 300,
            'defaultPaperSize' => 'A4',
            'isFontSubsettingEnabled' => false,  // Include full font to ensure peso symbol is available
            'pdfVersion' => '1.7',
        ]);
        
        // Set paper size
        $pdf->setPaper('A4', 'portrait');
        
        $fileName = 'sales-report-' . date('Y-m-d-H-i-s') . '.pdf';
        
        // Force download
        return $pdf->download($fileName);
    }
    
    // Helper method to format currency properly for PDF
    private function formatCurrency($amount)
    {
        // Return HTML entity for peso symbol to avoid encoding issues
        return '&#8369;' . number_format($amount, 2);
    }
    
    private function calculateOrderStatistics($orders)
    {
        if ($orders->count() === 0) {
            return [
                'min_amount' => 0,
                'max_amount' => 0,
                'median_amount' => 0,
                'std_deviation' => 0,
                'average_amount' => 0,
                'total_amount' => 0,
                'order_count' => 0,
                'first_order_date' => null,
                'last_order_date' => null,
                'period_days' => 0,
            ];
        }
        
        $amounts = $orders->pluck('total_amount')->toArray();
        
        // Calculate standard deviation manually
        $average = $orders->avg('total_amount');
        $variance = 0.0;
        foreach ($amounts as $amount) {
            $variance += pow($amount - $average, 2);
        }
        $stdDeviation = sqrt($variance / count($amounts));
        
        // Calculate median
        sort($amounts);
        $count = count($amounts);
        $middle = floor(($count - 1) / 2);
        $median = 0;
        
        if ($count % 2 == 0) {
            // Even number of items
            $median = ($amounts[$middle] + $amounts[$middle + 1]) / 2;
        } else {
            // Odd number of items
            $median = $amounts[$middle];
        }
        
        // Get first and last order dates
        $sortedOrders = $orders->sortBy('created_at');
        $firstOrder = $sortedOrders->first();
        $lastOrder = $sortedOrders->last();
        
        return [
            'min_amount' => $orders->min('total_amount'),
            'max_amount' => $orders->max('total_amount'),
            'median_amount' => $median,
            'std_deviation' => $stdDeviation,
            'average_amount' => $average,
            'total_amount' => $orders->sum('total_amount'),
            'order_count' => $orders->count(),
            'first_order_date' => $firstOrder->created_at ?? null,
            'last_order_date' => $lastOrder->created_at ?? null,
            'period_days' => $firstOrder && $lastOrder ? 
                $firstOrder->created_at->diffInDays($lastOrder->created_at) + 1 : 0,
        ];
    }
// Alternative method using DomPDF directly

    public function exportComparisonPdf(Request $request)
    {
        $year1 = $request->get('year1', date('Y'));
        $year2 = $request->get('year2', date('Y') - 1);
        
        $year1Sales = $this->getMonthlySalesData($year1);
        $year2Sales = $this->getMonthlySalesData($year2);
        
        $growthData = [];
        $totalYear1 = array_sum($year1Sales);
        $totalYear2 = array_sum($year2Sales);
        $totalGrowth = $totalYear2 > 0 ? (($totalYear1 - $totalYear2) / $totalYear2) * 100 : ($totalYear1 > 0 ? 100 : 0);
        
        foreach ($year1Sales as $month => $sales1) {
            $sales2 = $year2Sales[$month];
            $growth = $sales2 > 0 ? (($sales1 - $sales2) / $sales2) * 100 : ($sales1 > 0 ? 100 : 0);
            $growthData[$month] = round($growth, 2);
        }
        
        // Generate PDF
        $pdf = \PDF::loadView('admin.sales-report.comparison-pdf', compact(
            'year1', 
            'year2', 
            'year1Sales', 
            'year2Sales',
            'growthData',
            'totalGrowth'
        ));
        
        $fileName = 'sales-comparison-' . $year1 . '-vs-' . $year2 . '-' . date('Y-m-d-H-i-s') . '.pdf';
        
        return $pdf->download($fileName);
    }
}