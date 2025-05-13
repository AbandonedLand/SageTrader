<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class GridBot extends Component
{

    public $search;
    public $tokens;

    public $price;
    public $gridBots;
    public $alltokens;
    public $showform;

    public $token_x_asset_id;
    public $token_y_asset_id;
    public $token_x_reserve;
    public $token_y_reserve;
    public bool $showSelectX;
    public bool $showSelectY;
    public $upper_price;
    public $lower_price;
    public $steps;
    public $liquidity_fee;
    public $grid;
    public bool $fee_is_token_x;

    public $y_placeholder;
    public $x_placeholder;


    public function mount(){
        $this->clearXPlaceholder();
        $this->clearYPlaceholder();
        $this->showform = false;
        $this->showSelectX = false;
        $this->showSelectY = false;
        $this->liquidity_fee = 0.005;
        $this->steps = 100;
        $this->fee_is_token_x = true;
        $this->alltokens = \App\Models\Asset::where('balance','>',0)->get();
        $this->gridBots = \App\Models\GridBot::where('is_active',1)->get();
        $this->token_x_asset_id = \App\Models\Asset::where('asset_id','xch')->first();
    }

    public function clearXPlaceholder(){
        $this->x_placeholder = 'Enter only one side.';
    }
    public function clearYPlaceholder(){
        $this->y_placeholder = 'Enter only one side.';
    }
    public function toggleFeeIsTokenX(){
        $this->fee_is_token_x = !$this->fee_is_token_x;
        if($this->grid){
            $this->buildGrid();
        }
    }

    public function getPriceEstimate(){
        if($this->token_x_asset_id && $this->token_y_asset_id){
            $price = (\App\AssetPriceEstimation::GetPrice($this->token_x_asset_id,$this->token_y_asset_id));
            if($price){
                $this->price = $price['price']/$price['decimals'];
                $this->upper_price = round($this->price * 1.1,3,);
                $this->lower_price = round($this->price / 1.1,3,);
            }
            else{
                Session::flash('error','Could not get price from dexie.  Please enter manually.');
                $this->price = null;
            }

        }
    }

    public function toggleForm(){
        $this->showform = !$this->showform;
    }

    public function showXAssets(){
        $this->showSelectX = true;
    }
    public function showYAssets(){
        $this->showSelectY = true;
    }

    public function clearX(){
        $this->token_x_reserve = null;
    }
    public function clearY(){
        $this->token_y_reserve = null;
    }
    public function setAssetX($asset_id){
        $this->token_x_asset_id = \App\Models\Asset::where('asset_id',$asset_id)->first();
        $this->showSelectX = false;
    }

    public function setAssetY($asset_id){
        $this->token_y_asset_id = \App\Models\Asset::where('asset_id',$asset_id)->first();
        $this->showSelectY = false;
    }



    public function render()
    {
        if($this->search){
            $this->tokens = collect($this->alltokens)->filter(function($token){
                return false !== stripos($token['code'], $this->search);
            });
        } else {
            $this->tokens = collect($this->alltokens);
        }
        return view('livewire.grid-bot');
    }

    public function buildGrid(){

        if($this->token_x_reserve){
            $this->buildGridTokenX();
            $this->y_placeholder = $this->grid['total_reserve_y'] / $this->token_y_asset_id->decimals;
        }
        if($this->token_y_reserve){
            $this->buildGridTokenY();
            $this->x_placeholder = $this->grid['total_reserve_x'] / $this->token_x_asset_id->decimals;
        }

        #dd($this->grid);

    }

    public function buildGridTokenX() :void
    {
        $token_x_per_step = ($this->token_x_reserve*2) / $this->steps;
        $price_per_step = (($this->upper_price - $this->lower_price)/$this->steps);
        $loop = 0;
        $total_reserve_x =0;
        $total_reserve_y =0;
        $grid = [];
        $total_reserve_x = $this->token_x_reserve * $this->token_x_asset_id->decimals;
        while($loop < $this->steps){
            $price = ($this->lower_price) + ($loop * $price_per_step);
            $liquidity_fee = $this->liquidity_fee*$price;
            if($price < $this->price){
                $total_reserve_y += round($price * $token_x_per_step * $this->token_y_asset_id->decimals);
                $grid['start_at']=$loop;
            }

            if($this->fee_is_token_x){
                $bid_y_offered = round($price * $token_x_per_step * $this->token_y_asset_id->decimals,0);
                $bid_x_requested = round($token_x_per_step*$this->token_x_asset_id->decimals,0);
                $fee_collected = round($bid_x_requested * $this->liquidity_fee);
                $bid_x_requested = $bid_x_requested + $fee_collected;
                $ask_x_offered = round($token_x_per_step*$this->token_x_asset_id->decimals,0);
                $ask_y_requested = round($price * $token_x_per_step * $this->token_y_asset_id->decimals,0);
                $ask_x_offered = $ask_x_offered - $fee_collected;
            } else {
                $bid_y_offered = round($price * $token_x_per_step * $this->token_y_asset_id->decimals,0);
                $fee_collected = round($bid_y_offered * $this->liquidity_fee);
                $bid_y_offered = $bid_y_offered - $fee_collected;
                $bid_x_requested = round($token_x_per_step*$this->token_x_asset_id->decimals,0);
                $ask_x_offered = round($token_x_per_step*$this->token_x_asset_id->decimals,0);
                $ask_y_requested = round($price * $token_x_per_step * $this->token_y_asset_id->decimals,0);
                $ask_y_requested = $ask_y_requested + $fee_collected;
            }

            $grid['grid'][] = [
                'loop'=>$loop,
                'price_per_step'=>$price_per_step,
                'token_per_step'=>$token_x_per_step,
                'lower_price'=>$this->lower_price,
                'upper_price'=>$this->upper_price,
                'liquidity_fee'=>$liquidity_fee,
                'price'=>$price,
                'fee_collected'=>(int)$fee_collected,
                'fee_is_token_x'=>$this->fee_is_token_x,
                'x_asset_id'=>$this->token_x_asset_id->asset_id,
                'y_asset_id'=>$this->token_y_asset_id->asset_id,
                'bid_x_requested'=>(int)$bid_x_requested,
                'bid_y_offered'=>(int)$bid_y_offered,

                'ask_x_offered'=>(int)$ask_x_offered,
                'ask_y_requested'=>(int)$ask_y_requested
            ];
            $loop++;
            $grid['price'] = $this->price;
            $grid['total_reserve_x'] = (int)$total_reserve_x;
            $grid['total_reserve_y'] = (int)$total_reserve_y;

        }
        $this->grid = $grid;
    }

    public function buildGridTokenY() :void
    {
        $token_y_per_step = (2*$this->token_y_reserve) / ($this->steps-1);

        $price_per_step = (($this->upper_price - $this->lower_price)/($this->steps-1));
        $loop = 0;
        $total_reserve_x =0;
        $total_reserve_y =0;
        $grid = [];
        $total_reserve_y = $this->token_y_reserve * $this->token_y_asset_id->decimals;
        while($loop < $this->steps){
            $price = ($this->lower_price) + ($loop * $price_per_step);
            $liquidity_fee = $this->liquidity_fee*$price;
            if($price < $this->price){
                $total_reserve_x += round($token_y_per_step / $price * $this->token_x_asset_id->decimals);
                $grid['start_at']=$loop;
            }

            if($this->fee_is_token_x){
                $bid_y_offered = round($token_y_per_step * $this->token_y_asset_id->decimals,0);
                $x = round($token_y_per_step / $price * $this->token_x_asset_id->decimals,0);
                $fee_collected = round($x * $this->liquidity_fee);
                $bid_x_requested = $x + $fee_collected;
                $ask_y_requested = round($token_y_per_step * $this->token_y_asset_id->decimals,0);
                $ask_x_offered = $x - $fee_collected;
            } else {
                $y = round($token_y_per_step * $this->token_y_asset_id->decimals, 0);
                $fee_collected = round($y * $this->liquidity_fee);
                $bid_y_offered = $y - $fee_collected;
                $bid_x_requested = round($token_y_per_step / $price * $this->token_x_asset_id->decimals, 0);
                $ask_y_requested = $y + $fee_collected;
                $ask_x_offered = round($token_y_per_step / $price * $this->token_x_asset_id->decimals, 0);
            }

            $grid['grid'][] = [
                'loop'=>$loop,
                'price_per_step'=>$price_per_step,
                'token_per_step'=>$token_y_per_step,
                'lower_price'=>$this->lower_price,
                'upper_price'=>$this->upper_price,
                'liquidity_fee'=>$liquidity_fee,
                'price'=>$price,
                'fee_collected'=>(int)$fee_collected,
                'x_asset_id'=>$this->token_x_asset_id->asset_id,
                'y_asset_id'=>$this->token_y_asset_id->asset_id,
                'bid_x_requested'=>(int)$bid_x_requested,
                'bid_y_offered'=>(int)$bid_y_offered,
                'ask_x_offered'=>(int)$ask_x_offered,
                'ask_y_requested'=>(int)$ask_y_requested,
            ];
            $loop++;
            $grid['price'] = $this->price;
            $grid['total_reserve_x'] = (int)$total_reserve_x;
            $grid['total_reserve_y'] = (int)$total_reserve_y;
        }
        $this->grid = $grid;
    }

    public function createBot(){
        $gridBot = new \App\Models\GridBot();
        $gridBot->token_x_asset_id = $this->token_x_asset_id->asset_id;
        $gridBot->token_y_asset_id = $this->token_y_asset_id->asset_id;
        $gridBot->token_x_reserve = $this->grid['total_reserve_x'];
        $gridBot->token_y_reserve = $this->grid['total_reserve_y'];
        $gridBot->upper_price = $this->upper_price;
        $gridBot->lower_price = $this->lower_price;
        $gridBot->tick_count = $this->steps;
        $gridBot->fee_collected = 0;
        $gridBot->liquidity_fee = $this->liquidity_fee;
        $gridBot->start_price = $this->price;
        $gridBot->grid = $this->grid;
        $gridBot->save();
        $gridBot->bootbot();
        $this->showform = false;

    }

}
