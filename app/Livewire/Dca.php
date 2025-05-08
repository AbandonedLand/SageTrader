<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class Dca extends Component
{
    public $alltokens;
    public $tokens;
    public $asset;


    #[Validate('required')]
    public $asset_id;
    public $is_active;

    public $buy_frequency;
    public $is_buy;
    public $showAssets;
    public $price_lt_gt;

    #[Validate('required|numeric|gt:0')]
    public $amount;
    #[Validate('required|numeric')]
    public $time;

    #[Validate('numeric|nullable|gt:0')]
    public $maxAmount;

    #[Validate('required_with:price_lt_gt|nullable|numeric')]
    public $price;
    public $max_orders;
    public $end_date;

    public $frequency;
    public $search;
    public $showform;
    public $dcas;

    public function mount(){
        $this->frequency = 'minutes';
        $this->time = 60;
        $this->is_buy = true;
        $this->showAssets = false;
        $this->alltokens = \App\Models\Asset::where('can_dexie_swap',true)->get();
        $this->showform = false;
        $this->dcas = \App\Models\dca::where('is_active', 1)->get();
    }


    public function updateDcas(){
        $this->dcas = \App\Models\dca::where('is_active', 1)->get();
    }

    public function toggleShowAssets(){
        $this->showAssets = !$this->showAssets;
    }

    public function changeFrequency(){

        if($this->frequency == 'minutes'){
            $this->frequency = 'hours';
        } elseif($this->frequency == 'hours'){
            $this->frequency = 'days';
        } elseif($this->frequency == 'days'){
            $this->frequency = 'minutes';
        }
    }

    public function setRestrictionOff(){
        $this->price_lt_gt = null;
        $this->price = null;
    }

    public function setRestrictionGt(){
        $this->price_lt_gt = '>';
    }

    public function setRestrictionLt(){
        $this->price_lt_gt = '<';
    }

    public function toggleBuy(){
        $this->is_buy = !$this->is_buy;
    }

    public function createbot(){
        $this->validate();
        $dca = new \App\Models\dca();
        $dca->asset_id = $this->asset_id;
        $dca->is_active=true;
        if($this->frequency == 'minutes'){
            $dca->buy_frequency = $this->time;
        }
        if($this->frequency == 'hours'){
            $dca->buy_frequency = $this->time * 60;
        }
        if($this->frequency == 'days'){
            $dca->buy_frequency = $this->time * 24 * 60;
        }
        $dca->price_lt_gt = $this->price_lt_gt;
        $dca->price = $this->price;

        $dca->current_amount = 0;
        $dca->end_date = $this->end_date;
        $dca->next_run = \Carbon\Carbon::now();
        if($this->is_buy){
            $dca->buy_sell = 'buy';
            $dca->max_amount = $this->maxAmount * 1000000000000;
            $dca->amount = $this->amount * 1000000000000;
        } else {
            $dca->buy_sell = 'sell';
            $dca->max_amount = $this->maxAmount * 1000;
            $dca->amount = $this->amount * 1000;
        }

        $dca->save();
        return redirect('/market/dca');


    }
    public function setAsset($asset_id){
        $this->showAssets = false;
        $this->asset = \App\Models\Asset::where('asset_id',$asset_id)->first();
        $this->asset_id = $asset_id;

    }
    public function toggleform(){
        $this->showform = !$this->showform;
    }
    public function getQuote(){
        if($this->is_buy){
            $direction = 'buy';
            $amt = $this->amount * 1000000000000;
        } else {
            $direction = 'sell';
            $amt = $this->amount * 1000;
        }

        $quote = \App\Dexie::getDexieQuoteForAsset($this->asset_id,$amt,$direction,true);
        if($quote)
        {
            $this->price = $quote['price'];
        }

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
        return view('livewire.dca');
    }


}
