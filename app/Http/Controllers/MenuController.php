<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('menu.index', [
            'products' => $products
        ]);
    }
} 