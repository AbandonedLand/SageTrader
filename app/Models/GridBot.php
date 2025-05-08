<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GridBot extends Model
{

    public function orders(){
        return $this->morphMany('App\Models\Order', 'orderable');
    }

    public function tokenX(){
        return $this->hasOne(\App\Models\Asset::class, 'asset_id', 'token_x_asset_id');
    }

    public function tokenY(){
        return $this->hasOne(\App\Models\Asset::class, 'asset_id', 'token_y_asset_id');
    }

    public function nextOrder(\App\Models\Order $order){
        # Find out what was sold and what run of the ladder it was on.
        # Add together the fee and th
        if($this->fee_is_token_x){
            $x = $order->offered_amount - $this->liquidity_fee;
        }
    }



}
