<?php

namespace App;

use Illuminate\Support\Facades\Http;

class Dexie
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public static function getDexieCatAssets(){
        $page = 0;
        $page_size = 100;
        $assets = [];
        do{
            $page++;
            $uri = 'https://dexie.space/v1/assets?type=cat&page='.$page.'&page_size='.$page_size;
            $response = Http::get($uri)->json();
            $assets = array_merge($assets, $response['assets']);

        } while (count($response['assets']) > 0);

        return $assets;
    }





}
