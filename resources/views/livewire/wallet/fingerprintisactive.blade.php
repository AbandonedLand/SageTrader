<?php

use Livewire\Volt\Component;

new class extends Component {
    public $fingerprint;
    public $is_authorized;

    public function mount(){
        $this->fingerprint->is_authorized ? $this->is_authorized = true: $this->is_authorized = false;
    }

    public function changeStatus(){
        $this->fingerprint->is_authorized=$this->is_authorized;
        $this->fingerprint->save();

    }

}; ?>

<div>
    @if(!$is_authorized)
    <x-toggle
        label="{{$fingerprint->name}}"
        wire:model.live="is_authorized"
        wire:click="changeStatus()"
        wire:confirm="Are you sure you wish to grant SageTrader access to {{$fingerprint->name}}?"
    ></x-toggle>
    @else
        <x-toggle
            label="{{$fingerprint->name}}"
            wire:model.live="is_authorized"
            wire:click="changeStatus()"
        ></x-toggle>
    @endif
</div>
