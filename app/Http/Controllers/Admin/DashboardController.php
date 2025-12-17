<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, year, month, week, today, custom
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Get date range based on filter (in Philippine Time)
        $dateRange = $this->getDateRange($filter, $startDate, $endDate);

        $stats = [
            'total_products' => $this->getProductsCount($dateRange),
            'total_orders' => $this->getOrdersCount($dateRange),
            'total_customers' => $this->getCustomersCount($dateRange),
            'total_categories' => Category::count(),
            'revenue' => $this->getRevenue($dateRange),
            'pending_orders' => $this->getPendingOrdersCount($dateRange),
        ];

        // Get top selling products
        $topSellingProducts = $this->getTopSellingProducts($dateRange);
        
        // Get low selling products
        $lowSellingProducts = $this->getLowSellingProducts($dateRange);

        // Get sales data for line graph
        $salesData = $this->getSalesData($dateRange, $filter);

        return view('admin.dashboard', compact(
            'stats', 
            'topSellingProducts', 
            'lowSellingProducts', 
            'salesData',
            'filter',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Convert to Philippine Time (UTC+8)
     */
    private function toPhilippineTime(Carbon $date): Carbon
    {
        return $date->copy()->setTimezone('Asia/Manila');
    }

    /**
     * Convert UTC time to Philippine Time string for database queries
     */
    private function convertToPhTimeForQuery($column, $format = null)
    {
        if ($format) {
            return DB::raw("DATE_FORMAT(CONVERT_TZ($column, '+00:00', '+08:00'), '$format')");
        }
        return DB::raw("CONVERT_TZ($column, '+00:00', '+08:00')");
    }

    private function getDateRange($filter, $startDate = null, $endDate = null)
    {
        $now = $this->toPhilippineTime(Carbon::now());

        switch ($filter) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            
            case 'year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            
            case 'custom':
                if ($startDate && $endDate) {
                    $start = $this->toPhilippineTime(Carbon::parse($startDate));
                    $end = $this->toPhilippineTime(Carbon::parse($endDate));
                    return [
                        'start' => $start->startOfDay(),
                        'end' => $end->endOfDay()
                    ];
                }
                // Fall through to 'all' if custom dates not provided
            
            case 'all':
            default:
                return null; // No date filtering for 'all'
        }
    }

    private function getProductsCount($dateRange)
    {
        $query = Product::query();
        
        if ($dateRange) {
            $query->whereBetween(
                $this->convertToPhTimeForQuery('created_at'), 
                [$dateRange['start'], $dateRange['end']]
            );
        }
        
        return $query->count();
    }

    private function getOrdersCount($dateRange)
    {
        $query = Order::query();
        
        if ($dateRange) {
            $query->whereBetween(
                $this->convertToPhTimeForQuery('created_at'), 
                [$dateRange['start'], $dateRange['end']]
            );
        }
        
        return $query->count();
    }

    private function getCustomersCount($dateRange)
    {
        $query = User::where('role', 'customer');
        
        if ($dateRange) {
            $query->whereBetween(
                $this->convertToPhTimeForQuery('created_at'), 
                [$dateRange['start'], $dateRange['end']]
            );
        }
        
        return $query->count();
    }

    private function getRevenue($dateRange)
    {
        $query = Order::where('order_status', 'delivered');
        
        if ($dateRange) {
            $query->whereBetween(
                $this->convertToPhTimeForQuery('created_at'), 
                [$dateRange['start'], $dateRange['end']]
            );
        }
        
        return $query->sum('total_amount');
    }

    private function getPendingOrdersCount($dateRange)
    {
        $query = Order::where('order_status', 'pending');
        
        if ($dateRange) {
            $query->whereBetween(
                $this->convertToPhTimeForQuery('created_at'), 
                [$dateRange['start'], $dateRange['end']]
            );
        }
        
        return $query->count();
    }

    private function getTopSellingProducts($dateRange, $limit = 5)
    {
        $query = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity_sold'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->whereHas('order', function($query) {
                $query->where('order_status', 'delivered');
            })
            ->with(['product' => function($query) {
                $query->with('category');
            }])
            ->groupBy('product_id')
            ->having('total_quantity_sold', '>', 0)
            ->orderBy('total_quantity_sold', 'desc')
            ->limit($limit);

        if ($dateRange) {
            $query->whereHas('order', function($query) use ($dateRange) {
                $query->whereBetween(
                    DB::raw('CONVERT_TZ(created_at, "+00:00", "+08:00")'), 
                    [$dateRange['start'], $dateRange['end']]
                );
            });
        }

        return $query->get();
    }

    private function getLowSellingProducts($dateRange, $limit = 5)
    {
        // Get all active products
        $allActiveProducts = Product::where('is_active', true)
            ->where('is_archived', false)
            ->pluck('id');

        // Get products that have sales in the period
        $soldProductsQuery = OrderItem::select('product_id')
            ->whereHas('order', function($query) {
                $query->where('order_status', 'delivered');
            })
            ->whereIn('product_id', $allActiveProducts)
            ->groupBy('product_id');

        if ($dateRange) {
            $soldProductsQuery->whereHas('order', function($query) use ($dateRange) {
                $query->whereBetween(
                    DB::raw('CONVERT_TZ(created_at, "+00:00", "+08:00")'), 
                    [$dateRange['start'], $dateRange['end']]
                );
            });
        }

        $soldProductIds = $soldProductsQuery->pluck('product_id');

        // Products with zero sales
        $zeroSalesProducts = Product::whereIn('id', $allActiveProducts)
            ->whereNotIn('id', $soldProductIds)
            ->with('category')
            ->limit($limit)
            ->get()
            ->map(function($product) {
                return (object)[
                    'product_id' => $product->id,
                    'product' => $product,
                    'total_quantity_sold' => 0,
                    'total_revenue' => 0
                ];
            });

        // If we need more low-selling products, get ones with minimal sales
        if ($zeroSalesProducts->count() < $limit) {
            $remaining = $limit - $zeroSalesProducts->count();
            
            $lowSalesProducts = OrderItem::select(
                    'product_id',
                    DB::raw('SUM(quantity) as total_quantity_sold'),
                    DB::raw('SUM(total_price) as total_revenue')
                )
                ->whereHas('order', function($query) {
                    $query->where('order_status', 'delivered');
                })
                ->whereIn('product_id', $allActiveProducts)
                ->with(['product' => function($query) {
                    $query->with('category');
                }])
                ->groupBy('product_id')
                ->having('total_quantity_sold', '>', 0)
                ->orderBy('total_quantity_sold', 'asc')
                ->limit($remaining)
                ->get();

            return $zeroSalesProducts->merge($lowSalesProducts);
        }

        return $zeroSalesProducts;
    }

    private function getSalesData($dateRange, $filter)
    {
        $query = Order::where('order_status', 'delivered');

        if ($dateRange) {
            $query->whereBetween(
                DB::raw('CONVERT_TZ(created_at, "+00:00", "+08:00")'), 
                [$dateRange['start'], $dateRange['end']]
            );
        }

        // Group by different time intervals based on filter (using Philippine Time)
        switch ($filter) {
            case 'today':
                // Group by hours for today
                $salesData = $query->select(
                    DB::raw('HOUR(CONVERT_TZ(created_at, "+00:00", "+08:00")) as period'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

                // Fill in missing hours
                $result = [];
                for ($hour = 0; $hour < 24; $hour++) {
                    $data = $salesData->where('period', $hour)->first();
                    $result[] = [
                        'period' => sprintf('%02d:00', $hour),
                        'order_count' => $data ? $data->order_count : 0,
                        'revenue' => $data ? $data->revenue : 0
                    ];
                }
                return $result;

            case 'week':
                // Group by days for week
                $salesData = $query->select(
                    DB::raw('DAYNAME(CONVERT_TZ(created_at, "+00:00", "+08:00")) as period'),
                    DB::raw('DAYOFWEEK(CONVERT_TZ(created_at, "+00:00", "+08:00")) as day_order'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->groupBy('period', 'day_order')
                ->orderBy('day_order')
                ->get();

                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $result = [];
                foreach ($days as $index => $day) {
                    $data = $salesData->where('period', $day)->first();
                    $result[] = [
                        'period' => substr($day, 0, 3),
                        'order_count' => $data ? $data->order_count : 0,
                        'revenue' => $data ? $data->revenue : 0
                    ];
                }
                return $result;

            case 'month':
                // Group by weeks for month
                $salesData = $query->select(
                    DB::raw('WEEK(CONVERT_TZ(created_at, "+00:00", "+08:00"), 1) - 
                            WEEK(DATE_SUB(CONVERT_TZ(created_at, "+00:00", "+08:00"), 
                            INTERVAL DAYOFMONTH(CONVERT_TZ(created_at, "+00:00", "+08:00"))-1 DAY), 1) + 1 as period'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

                $result = [];
                for ($week = 1; $week <= 5; $week++) {
                    $data = $salesData->where('period', $week)->first();
                    $result[] = [
                        'period' => 'Week ' . $week,
                        'order_count' => $data ? $data->order_count : 0,
                        'revenue' => $data ? $data->revenue : 0
                    ];
                }
                return $result;

            case 'year':
                // Group by months for year
                $salesData = $query->select(
                    DB::raw('MONTHNAME(CONVERT_TZ(created_at, "+00:00", "+08:00")) as period'),
                    DB::raw('MONTH(CONVERT_TZ(created_at, "+00:00", "+08:00")) as month_order'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->groupBy('period', 'month_order')
                ->orderBy('month_order')
                ->get();

                $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                          'July', 'August', 'September', 'October', 'November', 'December'];
                $result = [];
                foreach ($months as $index => $month) {
                    $data = $salesData->where('period', $month)->first();
                    $result[] = [
                        'period' => substr($month, 0, 3),
                        'order_count' => $data ? $data->order_count : 0,
                        'revenue' => $data ? $data->revenue : 0
                    ];
                }
                return $result;

            case 'custom':
            case 'all':
            default:
                // Group by months for all time or custom range
                $salesData = $query->select(
                    DB::raw('DATE_FORMAT(CONVERT_TZ(created_at, "+00:00", "+08:00"), "%Y-%m") as period'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

                return $salesData->map(function($item) {
                    return [
                        'period' => $item->period,
                        'order_count' => $item->order_count,
                        'revenue' => $item->revenue
                    ];
                })->toArray();
        }
    }
}