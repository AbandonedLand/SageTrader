<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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


}
