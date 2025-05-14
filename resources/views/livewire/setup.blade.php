<?php

use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;

new class extends Component {

    public int $step = 1;
    public ?string $crt = null;
    public ?string $key = null;
    public ?\App\Models\SageWallet $sageWallet = null;
    public ?\Illuminate\Database\Eloquent\Collection $fingerprints = null;
    public array $fingerprint_ids=[];

    public function mount(){
        $test = \App\Models\SageWallet::first();
        if($test){
            $this->sageWallet = $test;
            $this->step =3;
            $this->fingerprints = \App\Models\Fingerprint::all();
        }

    }

    public function saveFingerprints(){
        if(count($this->fingerprint_ids)>0){
            foreach($this->fingerprint_ids as $fp){
                \App\Models\Fingerprint::find($fp)->update(['is_authorized'=>true]);
            }
        }
        $this->step++;
    }

    public function next()
    {
        $this->step++;
    }

    public function prev()
    {
        $this->step--;
    }

    public function lookup()
    {
        if (PHP_OS === "Linux") {
            if(Storage::disk('user_home')->exists('./local/share/com.rigidnetwork.sage/ssl/wallet.crt')){
                $this->crt =  '\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.crt';
            }
            if(Storage::disk('user_home')->exists('./local/share/com.rigidnetwork.sage/ssl/wallet.key')){
                $this->key = './local/share/com.rigidnetwork.sage/ssl/wallet.key';
            }
        }
        if(PHP_OS==="WINNT") {
            if(Storage::disk('user_home')->exists('\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.crt')){
                $this->crt ='\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.crt';
            }
            if(Storage::disk('user_home')->exists('\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.key')){
                $this->key = '\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.key';
            }
        }
    }

    public function acceptssl(){
        $sage = new \App\Models\SageWallet();
        $sage->base_uri = 'https://localhost:9257';
        $sage->verify = false;
        $sage->cert = $this->crt;
        $sage->ssl_key = $this->key;
        $sage->save();
        $this->sageWallet = $sage;
        $fingerprints = $this->sageWallet->get_keys();
        $this->step++;
    }
}; ?>

<div>
    <x-steps wire:model="step" class="border-y border-base-content/10 my-5 py-5">
        <x-step step="1" text="Enable Sage RPC">
            <p class="font-bold mb-2 text-xl">Open Sage Wallet</p>
            <img src="/img/sagerpc.png" class="my-4" alt="Sage RPC">
            <ol>

                <li>1. Click Settings</li>
                <li>2. Click Start</li>
                <li>3. Turn on Run on Startup</li>
            </ol>
            <x-button label="Previous" wire:click="prev"/>
            <x-button label="Next" class="btn-primary" wire:click="next"/>
        </x-step>
        <x-step step="2" text="Connect to Sage">

            @if($crt && $key)
                <x-card
                title="Found SSL Keys"
                class="mb-4">
                    <p>{{$crt}}</p>
                    <p>{{$key}}</p>
                </x-card>
            @endif

            @if(!$crt && !$key)
            <x-button label="Lookup RPC SSL Key" class="btn-secondary" wire:click="lookup"/>
            @else
            <x-button label="Load Keys into Sage Trader" class="btn-secondary" wire:click="acceptssl"/>
            @endif
        </x-step>
        <x-step step="3" text="Approve Fingerprint(s)" >

        </x-step>
        <x-step step="3" text="Import Dexie Assets">
            <x-form wire:submit="saveFingerprints">
                @if($sageWallet)
                    <x-choices label="Approve Keys"
                               wire:model.live="fingerprint_ids"
                               :options="$fingerprints"
                               allow-all />

                @endif
                <x-button label="Approve Fingerprint(s)" class="btn-primary" wire:click="saveFingerprints"/>
            </x-form>


        </x-step>
    </x-steps>




</div>
