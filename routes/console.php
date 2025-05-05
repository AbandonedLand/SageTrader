<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


\Illuminate\Support\Facades\Schedule::call(function(){

    //Run DCA Bot
    \App\Models\dca::RunSchedule();
    \App\Models\Order::checkOrders();

})->everyThirtySeconds();
