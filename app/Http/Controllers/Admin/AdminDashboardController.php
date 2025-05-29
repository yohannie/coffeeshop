<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        $products = Product::all();
        $orders = Order::with(['user', 'items.product'])->latest()->take(5)->get();
        
        return view('dashboard.admin', compact('users', 'roles', 'products', 'orders'));
    }
} 