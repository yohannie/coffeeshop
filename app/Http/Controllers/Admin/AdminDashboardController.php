<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'orders_count' => Order::count(),
            'products_count' => Product::count(),
            'revenue' => Order::where('status', 'completed')->sum('total_amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}