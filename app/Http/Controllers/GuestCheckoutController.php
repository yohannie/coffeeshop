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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GuestCheckoutController extends Controller
{
    public function show()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.guest', compact('cart', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // Create user with role_id 2 (regular user)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2, // Regular user role
            ]);

            // Get cart data
            $cart = session()->get('cart', []);
            
            if (empty($cart)) {
                throw new \Exception('Cart is empty');
            }

            $total = 0;
            foreach($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'pending',
                'expected_delivery' => now()->addHours(1),
            ]);

            // Create order items
            foreach($cart as $id => $item) {
                $order->items()->create([
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // Clear cart
            session()->forget('cart');

            DB::commit();

            // Log in the user
            auth()->login($user);

            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'An error occurred while processing your order: ' . $e->getMessage());
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