<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Events\NewOrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    public function addToCart(Request $request, Product $product)
    {
        try {
            if (!$product->is_available) {
                return response()->json([
                    'error' => 'This product is currently unavailable.'
                ], 400);
            }

            $cart = session()->get('cart', []);
            
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity']++;
            } else {
                $cart[$product->id] = [
                    'name' => $product->name,
                    'quantity' => 1,
                    'price' => $product->price,
                    'image' => $product->image_full_url
                ];
            }
            
            session()->put('cart', $cart);
            
            return response()->json([
                'message' => 'Product added to cart successfully!',
                'cartCount' => count($cart),
                'cartItems' => $cart
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error adding product to cart. Please try again.'
            ], 500);
        }
    }

    public function checkout(Request $request)
    {
        try {
            $cart = session()->get('cart', []);
            
            if (empty($cart)) {
                return response()->json([
                    'error' => 'Cart is empty'
                ], 400);
            }

            // Start database transaction
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItems = [];

            // First, verify all products exist and are available
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                
                if (!$product) {
                    DB::rollBack();
                    return response()->json([
                        'error' => "Product not found: {$item['name']}"
                    ], 400);
                }

                if (!$product->is_available) {
                    DB::rollBack();
                    return response()->json([
                        'error' => "Product is no longer available: {$item['name']}"
                    ], 400);
                }

                $totalAmount += $item['price'] * $item['quantity'];
                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }

            // Create the order
            $orderData = [
                'total_amount' => $totalAmount,
                'status' => 'processing',
                'expected_delivery' => now()->addMinutes(30)
            ];

            // Add user_id if user is authenticated
            if (auth()->check()) {
                $orderData['user_id'] = auth()->id();
            }

            $order = Order::create($orderData);

            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Clear the cart
            session()->forget('cart');

            // Commit the transaction
            DB::commit();

            // Broadcast the new order event
            try {
                broadcast(new NewOrderPlaced($order))->toOthers();
            } catch (Exception $e) {
                // Log broadcasting error but don't fail the order
                \Log::error('Error broadcasting new order: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Order placed successfully! Your order will be ready in approximately 30 minutes.',
                'order' => $order->load('items.product')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Error creating order: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Error placing order. Please try again.'
            ], 500);
        }
    }

    public function removeFromCart(Request $request, $productId)
    {
        try {
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
            
            return response()->json([
                'message' => 'Product removed from cart successfully!',
                'cartCount' => count($cart),
                'cartItems' => $cart
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error removing product from cart. Please try again.'
            ], 500);
        }
    }

    public function updateCart(Request $request, $productId)
    {
        try {
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = max(1, $request->quantity);
                session()->put('cart', $cart);
            }
            
            return response()->json([
                'message' => 'Cart updated successfully!',
                'cartCount' => count($cart),
                'cartItems' => $cart
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error updating cart. Please try again.'
            ], 500);
        }
    }
} 