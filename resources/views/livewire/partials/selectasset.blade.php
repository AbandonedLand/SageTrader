<?php

use Livewire\Volt\Component;

new class extends Component {

    public $assets;
    public $search;

    #[\Livewire\Attributes\Modelable]
    public $selected;

    public function mount($assets){
        $this->assets = $assets;
    }

}; ?>

<div>
    <x-choices-offline

        wire:model="selected"
        :options="$assets"
        option-label="code"
        placeholder="Interested Token"
        single
        searchable />
</div>
