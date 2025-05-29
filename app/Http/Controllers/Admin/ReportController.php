<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        
        $reports = [
            'daily_sales' => Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total_amount'),
                
            'monthly_sales' => Order::whereMonth('created_at', $startOfMonth->month)
                ->whereYear('created_at', $startOfMonth->year)
                ->where('status', 'completed')
                ->sum('total_amount'),
                
            'top_products' => Product::withCount(['orders as sales_count' => function($query) {
                    $query->where('status', 'completed');
                }])
                ->orderByDesc('sales_count')
                ->take(5)
                ->get(),
                
            'recent_orders' => Order::with(['user', 'items.product'])
                ->where('status', 'completed')
                ->latest()
                ->take(10)
                ->get()
        ];
        
        return view('admin.reports', compact('reports'));
    }
} 