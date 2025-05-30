<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterCheckoutController extends Controller
{
    public function showCheckoutForm()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        return view('checkout.register', [
            'cart' => $cart,
            'total' => $this->calculateTotal($cart)
        ]);
    }

    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address
            ]);

            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $this->calculateTotal($cart),
                'status' => 'processing',
                'notes' => $request->notes,
                'expected_delivery' => now()->addMinutes(30)
            ]);

            // Create order items
            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Clear the cart
            session()->forget('cart');

            // Log the user in
            Auth::login($user);

            DB::commit();

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Order placed successfully! Your order will be ready in approximately 30 minutes.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in register checkout: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error processing your order. Please try again.')
                ->withInput();
        }
    }

    public function showSuccess(Order $order)
    {
        return view('checkout.success', [
            'order' => $order
        ]);
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
} 