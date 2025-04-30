<?php

namespace App\Livewire;

use Livewire\Component;

class TibetSwap extends Component
{

    public array $cats;

    public ?string $cat = null;
    public string $tibetPair;
    public int $catAmount;
    public float $catDisplayAmount;
    public int $xchAmount;
    public float $xchDisplayAmount;
    public int $maxCatAmount;
    public int $maxXchAmount;

    public function mount(){
        $this->catAmount = 0;
        $this->xchAmount = 0;
        $this->cats = \App\ChiaWallet::getCats();
        $this->maxXchAmount = \App\ChiaWallet::get_sync_status()['balance'];
    }

    public function getQuote(){


    }

    public function render()
    {
        if($this->cat){
            $this->catAmount = (\App\ChiaWallet::getCat($this->cat)['balance']);
            $this->catDisplayAmount = $this->catAmount / 1000;
        } else {
            $this->catAmount = 0;
        }
        return view('livewire.tibet-swap');
    }
}
