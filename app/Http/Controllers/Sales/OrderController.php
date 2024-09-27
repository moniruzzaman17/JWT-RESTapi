<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = $this->guard()->user(); 

        // Validate the incoming request
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // start a database transaction
        DB::beginTransaction();

        try {
            $totalAmount = 0; // Total amoynt of the order

            foreach ($validatedData['items'] as $item) {
                $product = Product::find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'error' => "'{$product->name}' is out of stovck."
                    ], 400);
                }

                $totalAmount += $product->price * $item['quantity'];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
            ]);

            foreach ($validatedData['items'] as $item) {
                $product = Product::find($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price, 
                ]);

                // Reduce the product's stock
                $product->stock -= $item['quantity'];
                $product->save();
            }

            // Commit the transactions
            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully!',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return response()->json([
                'error' => 'Failed to place order. Please try again.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function viewOrderHistory()
    {
        $user = $this->guard()->user(); 

        $orders = Order::with(['orderItems.product'])
                        ->where('user_id', $user->id)
                        ->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        // Format the orders
        $orderHistory = $orders->map(function($order) {
            return [
                'order_id' => $order->id,
                'total_amount' => $order->total_amount,
                'created_at' => $order->created_at,
                'items' => $order->orderItems->map(function($item) {
                    return [
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                }),
            ];
        });

        return response()->json($orderHistory, 200);
    }

    protected function guard()
    {
        // Return the API authentication guard
        return Auth::guard('api');
    }
}
