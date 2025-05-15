<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required')]
    public $asset_y_id;
    #[Validate('required')]
    public ?float $amount = 0;
    public $pricecode = 1;
    public ?float $max_amount = null;
    public bool $amount_is_x = true;
    public bool $amount_is_offered = true;
    public $max_xch;
    public $max_cat;
    public ?int $price = null;
    #[Validate('required')]
    public int $frequency_minutes;
    public $end_date;
    public \App\Models\Asset $y_asset;
    public \App\Models\Asset $xch;
    public $breadcrumbs;
    public $assets;
    public $enteredTime = 60;
    public $timeUnit = 1;
    public $pricecheck = [
        ['id' => 1, 'name' => 'Above'],
        ['id' => 2, 'name' => 'Below']
    ];

    public $timeUnits = [
        ['id' => 1, 'name' => 'MIN'],
        ['id' => 60, 'name' => 'HR'],
        ['id' => 1440, 'name' => 'DAY']
    ];


    public function clearMinPrice()
    {
        $this->min_price = null;
    }

    public function clearMaxPrice()
    {
        $this->max_price = null;
    }

    public function calculateMinutes()
    {
        if ($this->enteredTime > 0) {
            $this->frequency_minutes = $this->timeUnit * $this->enteredTime;
        } else {
            $this->frequency_minutes = 15;
        }

    }

    public function mount()
    {
        $this->calculateMinutes();
        $this->breadcrumbs = [
            ['label' => 'Home', 'link' => '/'],
            ['label' => 'DCA Bot', 'link' => '/bots/dca'],
            ['label' => 'Create DCA Bot', 'link' => '/bots/dca/create']

        ];
        $this->xch = \App\Models\Asset::where('code', 'XCH')->first();

        $this->assets = \App\Models\Asset::whereNotNull('tibetswap_pair_id')->get();
        $this->max_xch = $this->xch->Maxdisplayamount;
    }

    public function createBot()
    {
        $this->validate();
        $dca_bot = new \App\Models\DcaBot();
        $dca_bot->asset_x_id = 1; // XCH
        $dca_bot->asset_y_id = $this->asset_y_id;
        $dca_bot->amount = $this->amount;
        $dca_bot->amount_is_x = $this->amount_is_x;
        $dca_bot->amount_is_offered = $this->amount_is_offered;
        if($this->price > 0){
            if($this->pricecode ==1){
                $dca_bot->max_price = $this->price;
            } else {
                $dca_bot->min_price = $this->price;
            }
        }
        $dca_bot->frequency_minutes = $this->frequency_minutes;
        $dca_bot->max_amount = $this->max_amount;
        $dca_bot->end_date = $this->end_date;
        $dca_bot->save();
        $this->redirect('/bots/dca/'.$dca_bot->id);
    }


    public function getDexieQuote(){

    }

    public function getAsset()
    {
        if ($this->asset_y_id) {

            $this->y_asset = \App\Models\Asset::find($this->asset_y_id);
            $this->max_cat = $this->y_asset->Maxdisplayamount;
        }


    }


}; ?>

<div>
    <x-breadcrumbs :items="$breadcrumbs" class="mb-4"/>
    <div>
        <x-card

        >
            <x-header
                title="Create: Dollar Cost Average Bot"
                subtitle="Dollar cost average bot will market buy/sell a token using the rules you specify below."
                shadow separator
            ></x-header>
            <div class="grid gap-5 lg:grid-cols-2">
                <!-- Row -->
                <p class="seperator">I am interested in Token: </p>
                <livewire:partials.selectasset :$assets wire:model.live="asset_y_id"/>
                <!-- Row -->
                <p>I want to:</p>
                <x-swap wire:model.live="amount_is_x" wire:click="getAsset" id="custom">
                    <x-slot:true class="bg-success rounded p-2 px-10 text-center">
                        Buy the token using XCH
                    </x-slot:true>
                    <x-slot:false class="bg-red-400 rounded text-center text-white p-2">
                        Sell the token for XCH
                    </x-slot:false>
                </x-swap>
                <!-- Row -->
                <div>
                    @if($amount_is_x)
                        <p class="mt-8">XCH to spend each order:</p>
                    @else
                        @if($y_asset)
                            <p class="mt-8">{{$y_asset->code}} to spend each order:</p>
                        @else
                            <p class="mt-8">Token to spend each order:</p>
                        @endif
                    @endif
                </div>

                <div>
                    @if($amount_is_x)
                        <span class="font-light">Max: {{$max_xch}}</span>
                    @else
                        <span class="font-light">Max: {{$max_cat}}</span>
                    @endif
                    <x-input
                        wire:model="amount"
                        wire:click="getAsset"
                        type="number"
                        placeholder="Amount per transaction"
                        clearable
                    />
                </div>

                <!-- Row -->
                <div>
                    <p>Time between trades: <span class="text-gray-400 text-xs">{{$frequency_minutes}} minutes</span>
                    </p>
                </div>
                <div>
                    <x-input
                        wire:model.live="enteredTime"
                        wire:keyup="calculateMinutes"
                        type="number"
                    >
                        <x-slot:prepend>
                            {{-- Add `join-item` to all prepended elements --}}
                            <x-select wire:model.live="timeUnit" wire:change="calculateMinutes" :options="$timeUnits"
                                      class="join-item mr-5 bg-base-200"/>
                        </x-slot:prepend>
                    </x-input>
                </div>
                <!-- Row -->

            </div>
            <x-header
                title="Optional Settings"
                class="mt-8"
                subtitle="If any of these rules are reached, the bot will stop processing orders."
                shadow separator
            ></x-header>
            <div class="grid gap-5 lg:grid-cols-2">
                <p>
                    Until I spend this amount:
                </p>
                <x-input
                    wire:model="max_amount"
                    placeholder="Set max spendable"
                    type="number"
                    clearable
                ></x-input>


                <div>
                    <p>Until Date is reached:</p>
                </div>
                <div>
                    <x-datetime
                        wire:model="end_date"
                        clearable
                    >

                    </x-datetime>
                </div>
                <div>
                    <p>If price is ABOVE:</p>
                </div>
                <x-input
                    wire:model="price"
                    type="number"
                    clearable
                >
                    <x-slot:prepend>
                        {{-- Add `join-item` to all prepended elements --}}
                        <x-select wire:model.live="pricecode" :options="$pricecheck" class="join-item mr-5 bg-base-200"/>
                    </x-slot:prepend>

                </x-input>
            </div>

            <x-button
                class="btn-primary mt-8"
                wire:click="createBot"
            >Create DCA Bot
            </x-button>
        </x-card>

    </div>

</div>
