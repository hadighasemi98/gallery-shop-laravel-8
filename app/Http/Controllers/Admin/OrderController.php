<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10) ;
        return view('frontend.panel.orders.orders' , compact('orders') );
    }

    public function get_orderItem($orderItem)
    {
        $orders = OrderItem::where('order_id' , $orderItem)->get();
        // dd($orders);
        return view('frontend.panel.orders.orders-items',compact('orders','orderItem') );
    }



}
