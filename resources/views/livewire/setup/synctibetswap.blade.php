<?php

use Livewire\Volt\Component;

new class extends Component {
    public $ran = false;
    public function sync(){
        $this->ran = true;
        \App\TibetSwap::syncTibetAssets();
    }
}; ?>

<div>
    <x-button
        class="btn-primary"
        wire:click="sync"
        spinner
    >Sync TibetSwap Tokens

    </x-button>
    @if($ran)
        Done..
    @endif
</div>
