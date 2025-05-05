<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
           if(! \App\Models\Asset::all()->count() > 0){
               $event->menu->add( [
                   'text'=>'Sync Tokens',
                   'icon' => 'fas fa-cogs',
                   'url' => '/sync_dexie',
                   'topnav_right'=>true,
               ]);
           }
        });
    }
}
