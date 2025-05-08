<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Asset extends Model
{

    protected $fillable = [
        'asset_id',
        'name',
        'ticker',
        'tibetswap_pair_id'
    ];

    public function checkTibetSwapPair() : void
    {
        if(!$this->tibetswap_pair_id){
            $response = \App\TibetSwap::getToken($this->asset_id);

            if(isset($response['pair_id'])){
                $this->tibetswap_pair_id = $response['pair_id'];
                $this->save();
            }
        }
    }

    public static function syncDexieAssets() : void
    {
        $cats = \App\Dexie::getDexieCatAssets();

        foreach($cats as $cat) {
            $test = \App\Models\Asset::where('asset_id',$cat['id'])->first();
            if(!$test){
                \App\Models\Asset::create(['asset_id' => $cat['id'], 'name' => $cat['name'], 'ticker' => $cat['code']]);
            }
        }
    }


    public static function syncDexieSwapTokens(){
        $tokens = \App\Dexie::getDexieSwapAssets()['tokens'];
        foreach($tokens as $token){
            $test = \App\Models\Asset::where('asset_id',$token['id'])->first();
            if($test){
                $test->can_dexie_swap = true;
                $test->save();
            }
        }
    }

    public static function syncTibetPairs() : void
    {
        $uri = "https://api.v2.tibetswap.io/pairs?limit=10000";
        $tokens = Http::get($uri)->json();

        foreach($tokens as $token){
            $toUpdate = \App\Models\Asset::where('asset_id',$token['asset_id'])->first();
            if($toUpdate){
                $toUpdate->tibetswap_liquidity_asset_id = $token['liquidity_asset_id'];
                $toUpdate->tibetswap_pair_id = $token['launcher_id'];
                $toUpdate->save();
            }
        }

    }

    public static function syncBalances() : void
    {
        $xch = \App\ChiaWallet::get_sync_status();
        $xchAsset = \App\Models\Asset::where('asset_id','xch')->first();
        $xchAsset->balance = $xch['balance'];
        $xchAsset->save();

        $cats = \App\ChiaWallet::getCats();
        foreach($cats as $cat){
            $toUpdate = \App\Models\Asset::where('asset_id',$cat['asset_id'])->first();
            if($toUpdate){
                $toUpdate->balance = $cat['balance'];
                $toUpdate->save();
            }

        }
    }

    public function icon(){
        return "https://icons.dexie.space/".$this->asset_id.".webp";
    }

    public function displayAmount($amount){
        if(strtolower($this->asset_id) == 'xch'){
            return number_format($amount / $this->decimals,12);
        }
        return number_format($amount / $this->decimals,3);
    }

    public function displayMax(){
        return $this->displayAmount($this->balance);
    }


}
