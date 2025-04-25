<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('welcome');
});

Route::get('/dashboard', function () {
    $wallet = new \App\ChiaWallet();
    $xch = $wallet->get_sync_status();
    $cats = $wallet->post('/get_cats',[],false)->cats;
    return view('dashboard')->with(['cats'=>$cats,'xch'=>$xch]);
});



Route::get('/trade', function () {

});

Route::get('/liquidity',function(){
    return view('liquidity');
});

Route::get('/bots', function(){
    return view('bots');
});

Route::get('/logout', function() {
    $wallet = new \App\ChiaWallet();
    $wallet->post('/logout',[],true);
    $keys = $wallet->post('/get_keys',[],false)->keys;
    return view('show_keys')->with('keys',$keys);
});

Route::get('/login/{fingerprint}', function($fingerprint) {
    $wallet = new \App\ChiaWallet();
    $wallet->post('/login',['fingerprint'=>(int)$fingerprint],true);
    return redirect('/dashboard');
});

