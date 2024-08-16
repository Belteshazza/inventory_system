<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function mockOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'order_date' => now(),
            'total_amount' => 0,  // To be calculated
        ]);

        $totalAmount = 0;
        foreach ($request->products as $productOrder) {
            $product = Product::findOrFail($productOrder['product_id']);
            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $productOrder['quantity'],
                'price' => $product->price,
            ]);
            $totalAmount += $product->price * $productOrder['quantity'];
        }

        $order->update(['total_amount' => $totalAmount]);

        return response()->json($order->load('details.product'), 201);
    }
}
