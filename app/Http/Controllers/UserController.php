<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function products()
    {
        $products =  Product::with('images')->get();
        //dd($products);
        return view('users.product', compact('products'));
    }

    public function buyNow(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        // 1️⃣ Create Order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id'      => Auth::guard('web')->id(),
            'order_date'   => Carbon::now()->format('Y-m-d'),
        ]);

        // 2️⃣ Create Order Item
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'unit_price' => $product->price,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Order placed successfully 🎉');
    }

     public function myorder()
    {
        $orders = Order::with('orderItem')->get();
        $orderItems =  OrderItem::with('products', 'orders')->get();
        dd($orderItems);
        return view('users.myorder', compact('orderItems'));
    }
}
