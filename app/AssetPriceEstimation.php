<?php

namespace App;

use Illuminate\Support\Facades\Session;

class AssetPriceEstimation
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function GetPrice(\App\Models\Asset $x, \App\Models\Asset $y)
    {
        $x_amount = $x->decimals * 1;

        $buy = \App\Dexie::getDexieQuoteForAsset($y->asset_id,$x_amount,'buy',true);
        $sell = \App\Dexie::getDexieQuoteForAsset($y->asset_id,$x_amount,'sell',false);

        if($buy && $sell){
            $avg = floor($y->decimals * (($buy['price'] + $sell['price']) / 2));
            return ['price'=>(int)$avg,'decimals'=>$y->decimals];
        }

        return null;

    }

}
