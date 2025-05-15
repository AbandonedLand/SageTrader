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


    public static function syncDexieAssets(){
        $assets = self::getDexieCatAssets();
        foreach ($assets as $asset){
            $dbasset = \App\Models\Asset::where('asset_id',$asset['id'])->first();
            if($dbasset){
                $dbasset->updated_at = \Carbon\Carbon::now();
                $dbasset->save();
            } else {
                $dbasset = new \App\Models\Asset();
                $dbasset->asset_id = $asset['id'];
                $dbasset->code = $asset['code'];
                $dbasset->name = $asset['name'];
                $dbasset->denom = $asset['denom'];
                $dbasset->save();
            }
        }
    }



}
