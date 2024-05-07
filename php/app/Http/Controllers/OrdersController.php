<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request) {
        $request->validate([
            'items' => 'required|array',
            'totalPrice' => 'required|integer'
        ]);
        $items = $request->items;
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'total_price' => $request->totalPrice
        ]);

        foreach ($items as $item){
            OrderItems::create([
                'item_id' => $item['id'],
                'order_id' => $order->id
            ]);
        }
        return response()->json([
            'id' => $order->id
        ]);
    }
}
