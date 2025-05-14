<?php

use Livewire\Volt\Component;

new class extends Component {
    public $fingerprint;
    public $is_authorized;

    public function mount(){
        $this->fingerprint->is_authorized === 1 ? $this->is_authorized = true: $this->is_authorized = false;
    }

    public function changeStatus(){

        $this->fingerprint->is_authorized=$this->is_authorized;
        $this->fingerprint->save();
    }

}; ?>

<div>
    <x-toggle
        label="{{$fingerprint->name}}"
        wire:model="is_authorized"
        wire:change="changeStatus()"
    ></x-toggle>
</div>
