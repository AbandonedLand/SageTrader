<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try{
        $status = \App\ChiaWallet::get_sync_status();
        if($status){
            $wallet = new \App\ChiaWallet();
            $xch = $wallet->get_sync_status();
            $cats = collect($wallet->post('/get_cats',[],true)['cats'])->sortBy('balance',1,1);
            return view('welcome')->with(['cats'=>$cats,'xch'=>$xch]);

        }
    } catch(\Exception $e){
        return view('error');
    }

});

Route::get('/tibet', function () {
    return view('tibet');
});
Route::get('/tibet/{asset_id}', function ($asset_id) {
    return view('tibet')->with(['asset_id'=>$asset_id]);
});

Route::get('/market/market',[\App\Http\Controllers\marketController::class,'market'])->name('market');

Route::get('/orders',[\App\Http\Controllers\marketController::class,'orders'])->name('orders');



Route::get('/trade', function () {

});

Route::get('/liquidity',[\App\Http\Controllers\LiquidityController::class,'index']);
Route::get('/liquidity/create',[\App\Http\Controllers\LiquidityController::class,'create']);
Route::get('/liquidity/tibet',[\App\Http\Controllers\LiquidityController::class,'tibet']);





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
    return redirect('/');
});

