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
        $products = Product::where('is_available', true)->get();
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->with('products')
            ->get();
            
        return view('dashboard.user', compact('products', 'orders'));
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