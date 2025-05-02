<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class TibetSwap extends Component
{
    public $tokens;
    public $response;
    public $asset;
    public $showAssets;
    public $search;

    #[Validate('required')]
    public $selectedAsset;

    public $alltokens;
    public bool $is_buy;
    public bool $is_offered;

    #[Validate('required|numeric|gt:0')]
    public $amount;
    public $tibetOffer;
    public $dexieOffer;



    public function mount(){
        $this->is_buy = true;
        $this->is_offered = true;
        $this->showAssets = false;
        $this->alltokens = \App\Dexie::getDexieSwapAssets()['tokens'];


    }

    public function toggleBuy(){
        $this->is_buy = !$this->is_buy;
        $this->getQuote();
    }

    public function toggleOffered(){

        $this->is_offered = !$this->is_offered;
        $this->getQuote();
    }

    public function selectAsset(){
        $this->showAssets = true;

    }

    public function setAsset($asset_id){
        $this->clearQuote();
        $this->showAssets = false;
        $this->asset = \App\Dexie::getDexieAsset($asset_id);
        $this->selectedAsset = $asset_id;
        if($this->amount > 0){
            $this->getQuote();
        }
    }




    public function getQuote(){
        $this->validate();
        $asset_id = $this->asset['id'];
        if($this->is_buy){
            if($this->is_offered){
                $direction = 'buy';
                $amount_is_offered = true;
                $amount = $this->amount * 1000000000000;
            } else {
                $direction = 'buy';
                $amount_is_offered = false;
                $amount = $this->amount * 1000;
            }
        } else {
            if($this->is_offered){
                $direction = 'sell';
                $amount_is_offered = true;
                $amount = $this->amount * 1000;
            } else {
                $direction = 'sell';
                $amount_is_offered = false;
                $amount = $this->amount * 1000000000000;
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

            $this->response = \App\Dexie::submitMarketOrder($order);
            if($this->response){
                return redirect('/orders/'.$order->id);
            }

        }
        return redirect('/market/market');
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
        return view('livewire.tibet-swap');
    }
}
