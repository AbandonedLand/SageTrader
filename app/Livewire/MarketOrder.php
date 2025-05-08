<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class MarketOrder extends Component
{
    public $tokens;
    public $response;
    public $asset;
    public $showAssets;
    public $search;
    public $xch;
    #[Validate('required')]
    public $selectedAsset;


    public $alltokens;
    public bool $is_buy;
    public bool $is_offered;

    #[Validate('nullable|numeric|gt:0')]
    public $amount;
    public $tibetOffer;
    public $dexieOffer;



    public function mount(){
        $this->is_buy = true;
        $this->is_offered = true;
        $this->showAssets = false;
        $this->alltokens = \App\Models\Asset::where('can_dexie_swap',true)->get();
        $this->xch = \App\Models\Asset::where('asset_id','xch')->first();

    }

    public function toggleBuy(){
        $this->is_buy = !$this->is_buy;
        if($this->amount > 0){
            $this->getQuote();
        }

    }

    public function toggleOffered(){

        $this->is_offered = !$this->is_offered;
        if($this->amount > 0){
            $this->getQuote();
        }
    }

    public function selectAsset(){
        $this->showAssets = true;
    }

    public function setAsset($asset_id){
        $this->clearQuote();
        $this->showAssets = false;
        $this->asset = \App\Models\Asset::where('asset_id',$asset_id)->first();
        $this->selectedAsset = $asset_id;
        if($this->amount > 0){
            $this->getQuote();
        }
    }




    public function getQuote(){
        if(!$this->amount > 0){
            $this->clearQuote();
            return;
        }
        $this->validate();
        $asset_id = $this->asset->asset_id;
        if($this->is_buy){
            if($this->is_offered){
                $direction = 'buy';
                $amount_is_offered = true;
                $amount = $this->amount * $this->xch->decimals;
            } else {
                $direction = 'buy';
                $amount_is_offered = false;
                $amount = $this->amount * $this->asset->decimals;
            }
        } else {
            if($this->is_offered){
                $direction = 'sell';
                $amount_is_offered = true;
                $amount = $this->amount * $this->asset->decimals;
            } else {
                $direction = 'sell';
                $amount_is_offered = false;
                $amount = $this->amount * $this->xch->decimals;
            }
        }

        $this->dexieOffer = \App\Dexie::getDexieQuoteForAsset($asset_id,$amount,$direction,$amount_is_offered);


    }

    function clearQuote(){
        $this->dexieOffer = null;
    }

    public function takeOffer(){
        if($this->dexieOffer){
            $order = \App\Models\Order::fromDexieQuote($this->dexieOffer,'MarketOrder');
            if($order){
                return redirect('/order/'.$order->id);
            }
            $order->createSageOffer();
            $order->submitOrder();
            if($order->dexie_id){
                return redirect('/orders');
            }
        }
        return redirect('/market/market');
    }

    public function render()
    {
        if($this->search){
            $this->tokens = collect($this->alltokens)->filter(function($token){
                return false !== stripos($token['ticker'], $this->search);
            });
        } else {
            $this->tokens = collect($this->alltokens);
        }
        return view('livewire.market-order');
    }
}
