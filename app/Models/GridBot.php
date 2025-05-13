<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GridBot extends Model
{

    protected $casts = [
        'grid'=>'array'
    ];

    public function orders(){
        return $this->morphMany('App\Models\Order', 'orderable');
    }

    public function tokenX(){
        return $this->hasOne(\App\Models\Asset::class, 'asset_id', 'token_x_asset_id');
    }

    public function tokenY(){
        return $this->hasOne(\App\Models\Asset::class, 'asset_id', 'token_y_asset_id');
    }

    public function makeOrder($side, $index){
        if($this->steps() < $index){
            $this->msg('Grid is out of range');
            return false;
        }
        if($index < 0){
            $this->msg('Grid is out of range');
            return false;
        }

        $grid = $this->grid['grid'][$index];
        if(!$grid || !$side ){
            $this->msg('Failed to get the grid config.  No Side or Grid.');
            return false;
        }
        $x_asset = \App\Models\Asset::where('asset_id', $grid['x_asset_id'])->first();
        $y_asset = \App\Models\Asset::where('asset_id', $grid['y_asset_id'])->first();
        $order = new Order();
        $next = [];

        if($side == 'bid'){

            $next = [
                'ask'=>[
                    'gridbot_id'=>$this->id,
                    'side'=>'ask',
                    'index'=>$index
                    ],
                'bid'=>[
                    'gridbot_id'=>$this->id,
                    'side'=>'bid',
                    'index'=>($index-1)
                ]
            ];

            $order->requested_asset = $x_asset->asset_id;
            $order->requested_code = $x_asset->ticker;
            $order->offered_asset = $y_asset->asset_id;
            $order->offered_code = $y_asset->ticker;
            $order->offered_amount = $grid['bid_y_offered'];
            $order->requested_amount = $grid['bid_x_requested'];
            $order->market_fee_paid = $grid['fee_collected'];
            $order->price = $grid['price'];
            $order->initiated_by = 'GridBot';

        } elseif($side == 'ask'){

            $next = [
                'ask'=>[
                    'gridbot_id'=>$this->id,
                    'side'=>'ask',
                    'index'=>$index+1
                ],
                'bid'=>[
                    'gridbot_id'=>$this->id,
                    'side'=>'bid',
                    'index'=>$index
                ]
            ];
            $order->requested_asset = $y_asset->asset_id;
            $order->requested_code = $y_asset->ticker;
            $order->offered_asset = $x_asset->asset_id;
            $order->offered_code = $x_asset->ticker;
            $order->offered_amount = $grid['ask_x_offered'];
            $order->requested_amount = $grid['ask_y_requested'];
            $order->market_fee_paid = $grid['fee_collected'];
            $order->price = $grid['price'];
            $order->initiated_by = 'GridBot';

        }
        $order->meta_data = ['side'=>$side, 'index'=>$index, 'next'=>$next];

        # Checking if needed to create offer.
        $check = \App\Models\Order::where('requested_asset',$order->requested_asset)
            ->where('requested_amount',$order->requested_amount)
            ->where('offered_asset',$order->offered_asset)
            ->where('offered_amount',$order->offered_amount)
            ->where('is_filled',false)
            ->where('is_submitted',true)
            ->where('is_cancelled',false)
            ->where('initiated_by','GridBot')
            ->first();

        if(!$check){
            $this->msg('Creating an order for index '.$index.' and side of '.$side);
            $order->save();
            $this->orders()->save($order);
            return $order;
        }
        $this->msg("Found existing order for index ".$index." and side of ".$side." ID: ".$check->id);
        return $check;
    }


    public function msg($message){
        $log = \App\Models\log::create(['message' => $message]);
        $this->log()->save($log);
    }

    public function log(){
        return $this->morphMany('App\Models\Log', 'logable');
    }
    public function steps(){
        return count($this->grid['grid']);
    }

    public function setMaxOffersAtOnce($num,$from){

        for ($i = 0; $i < $num; $i++) {
            $bid = $this->makeOrder('bid', ($from-$i));
            $bid->createSageOffer();
            $bid->submitOrder();
            $ask = $this->makeOrder('ask', ($from+$i));
            $ask->createSageOffer();
            $ask->submitOrder();
        }

    }


    public function bootbot(){
        $start = $this->grid['start_at'];
        $bid = $this->makeOrder('bid', $start);
        $bid->createSageOffer();
        $bid->submitOrder();

        $ask = $this->makeOrder('ask', $start);
        $ask->createSageOffer();
        $ask->submitOrder();
    }



}
