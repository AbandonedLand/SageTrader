<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class liquidityController extends Controller
{
    public function index(){
        return view('liquidity');
    }

    public function create(){
        return view('liquidity.create');
    }

    public function tibet(){
        return view('liquidity.tibet');
    }
}
