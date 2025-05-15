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
            <x-card
                title="Configure Sage Wallet to allow RPC"
            >

                <img src="/img/sagerpc.png" class="my-4" style="height:350px" alt="Sage RPC">
                <ol>

                    <li>1. Click Settings</li>
                    <li>2. Click Start</li>
                    <li>3. Turn on Run on Startup</li>
                </ol>
                <hr>
                <div class="flex justify-between mt-4 ">
                    <x-button label="Previous" wire:click="prev"/>
                    <x-button label="Next" class="btn-primary" wire:click="next"/>
                </div>
            </x-card>



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
            <x-card
                title="Allow Access to RPC SSL"
            >
                @if(!$crt && !$key)
                    <x-button label="Lookup RPC SSL Key" class="btn-primary" wire:click="lookup"/>
                @else
                    <x-button label="Load Keys into Sage Trader" class="btn-primary" wire:click="acceptssl"/>
                @endif
            </x-card>
        </x-step>
        <x-step step="3" text="Approve Fingerprint(s)" >
            <x-card
                title="Approve Fingerprints for SageTrader" >
                @if($step===3)
                    <livewire:wallet.fingerprints />
                    <div class="flex justify-end">
                        <x-button
                            label="Next"
                            class="btn-primary px-6"
                            wire:click="next"

                        >

                        </x-button>
                    </div>

                @endif

            </x-card>
        </x-step>
        <x-step step="4" text="Import Dexie Assets">
            <livewire:dexie.syncassetsbutton />

        </x-step>
    </x-steps>




</div>
