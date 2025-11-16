<?php
// app/Http\Controllers/Admin\SalesReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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

    // ... rest of the methods remain the same as before ...
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

        // Daily sales for the last 30 days
        $dailySales = Order::where('order_status', 'delivered')
            ->where('delivered_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(delivered_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly sales for the year
        $monthlySales = Order::where('order_status', 'delivered')
            ->whereYear('delivered_at', Carbon::now()->year)
            ->selectRaw('MONTH(delivered_at) as month, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare monthly data for chart
        $monthlySalesArray = array_fill(0, 12, 0);
        foreach ($monthlySales as $sale) {
            $monthlySalesArray[$sale->month - 1] = $sale->total;
        }

        // Prepare daily data for chart
        $dailyLabels = [];
        $dailyData = [];
        foreach ($dailySales as $sale) {
            $dailyLabels[] = Carbon::parse($sale->date)->format('M j');
            $dailyData[] = $sale->total;
        }

        // Prepare payment method data for chart
        $paymentLabels = [];
        $paymentData = [];
        foreach ($salesByPayment as $method => $data) {
            $paymentLabels[] = ucfirst($method);
            $paymentData[] = $data['total'];
        }

        // Date range text for display
        $dateRangeText = $this->getDateRangeText($request);

        return [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'salesByPayment' => $salesByPayment,
            'dailySales' => $dailySales,
            'monthlySales' => $monthlySales,
            'monthlySalesArray' => $monthlySalesArray,
            'averageOrderValue' => $totalOrders > 0 ? $totalSales / $totalOrders : 0,
            'dateRangeText' => $dateRangeText,
            'chartData' => [
                'payment' => [
                    'labels' => $paymentLabels,
                    'data' => $paymentData
                ],
                'daily' => [
                    'labels' => $dailyLabels,
                    'data' => $dailyData
                ]
            ]
        ];
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
        return 'All Time';
    }

    public function export(Request $request)
    {
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
    // Get years for comparison (default to current year and previous year)
    $year1 = $request->get('year1', date('Y'));
    $year2 = $request->get('year2', date('Y') - 1);
    
    // Get monthly sales data for both years
    $year1Sales = $this->getMonthlySalesData($year1);
    $year2Sales = $this->getMonthlySalesData($year2);
    
    // Calculate growth percentages
    $growthData = [];
    $totalYear1 = array_sum($year1Sales);
    $totalYear2 = array_sum($year2Sales);
    $totalGrowth = $totalYear2 > 0 ? (($totalYear1 - $totalYear2) / $totalYear2) * 100 : ($totalYear1 > 0 ? 100 : 0);
    
    // Monthly growth
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
        $sales = Order::where('order_status', 'delivered') // Changed from 'status' to 'order_status'
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('total_amount');
        
        $monthlySales[$month] = (float) $sales;
    }
    
    return $monthlySales;
}
}