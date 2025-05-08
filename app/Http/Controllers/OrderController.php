<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders = \App\Models\Order::all();
        return view('market.orders')->with('orders',$orders);
    }

}
