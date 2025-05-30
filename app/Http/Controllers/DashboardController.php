<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $orders = Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard', compact('orders'));
    }

    public function admin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $products = Product::latest()->get();
        $orders = Order::with(['user', 'products'])
            ->latest()
            ->get();
            
        return view('dashboard.admin', compact('products', 'orders'));
    }
} 