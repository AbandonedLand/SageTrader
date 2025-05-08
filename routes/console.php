<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


\Illuminate\Support\Facades\Schedule::call(function(){

    //Run DCA Bot
    \App\Models\dca::RunSchedule();
    // Check if offer went through
    \App\Models\Order::checkOrders();
    // Sync CAT Balances
    \App\Models\Asset::syncBalances();

})->everyMinute();

\Illuminate\Support\Facades\Schedule::call(function(){
    \App\Models\Asset::syncDexieAssets();
    \App\Models\Asset::syncTibetPairs();
    \App\Models\Asset::syncDexieSwapTokens();
})->everyFourHours();
