<?php

use Livewire\Volt\Component;

new class extends Component {
    function syncDexieAssets(){
        \App\Dexie::syncDexieAssets();
        \App\TibetSwap::syncTibetAssets();
        $wallet = \App\Models\SageWallet::first();
        $wallet->syncTokensBalances();
    }
}; ?>

<div>
    <x-button
        label="Sync Dexie Assets"
        wire:click="syncDexieAssets"
        wire:loading.class="opacity-50"
        class="btn-primary px-10"
    ></x-button>
    <div wire:loading>
        <p>Loading Assets.</p>
    </div>
</div>
