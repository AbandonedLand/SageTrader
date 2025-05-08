<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class marketController extends Controller
{
    public function market(){
        return view('market.market');
    }

    public function orders(){
        $orders = Order::all()->sortByDesc('created_at');
        return view('market.orders')->with('orders', $orders);
    }

    public function order(\App\Models\Order $order){
        return view('market.order', compact('order'));
    }

    public function dca(){
        return view('market.dca');
    }

    public function view_dca(\App\Models\dca $dca){
        return view('market.view_dca')->with('dca', $dca);
    }


}
