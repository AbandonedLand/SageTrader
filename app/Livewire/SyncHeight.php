<?php

namespace App\Livewire;

use Livewire\Component;

class SyncHeight extends Component
{
    public $wallet;
    public $height;

    public function render()
    {
        return view('livewire.sync-height');
    }

    public function mount(){
        $this->wallet = new \App\ChiaWallet();
        $this->height = $this->wallet->getHeight();
    }
}
