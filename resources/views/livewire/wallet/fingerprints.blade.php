<?php

use Livewire\Volt\Component;

new class extends Component {
    public \Illuminate\Support\Collection $fingerprints;

    public function mount(){
        $this->fingerprints = \App\Models\Fingerprint::all();
    }

}; ?>

<div>
    @if($fingerprints->isNotEmpty())
        @foreach($fingerprints as $fingerprint)
        <div wire:key="{{$fingerprint->id}}">
            <livewire:wallet.fingerprintisactive :fingerprint="$fingerprint" />
        </div>
        @endforeach
    @endif
</div>
