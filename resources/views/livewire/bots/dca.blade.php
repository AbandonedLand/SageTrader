<?php

use Livewire\Volt\Component;

new class extends Component {
    public $breadcrumbs;

    public function mount(){
        $this->breadcrumbs = [
            ['label'=>'Home','link'=>'/'],
            ['label'=>'DCA Bot','link'=>'/bots/dca']
        ];
    }

    public function create(){
        return $this->redirect('/bots/dca/create',navigate: true);
    }
}; ?>

<div>
    <x-breadcrumbs :items="$breadcrumbs" class="mb-4"/>
    <x-card
        title="Dollar Cost Averaging Bot"
    >
        <p>The DCA Bot is a tool to make repeated purchases or sales of a Chia Asset Token.</p>

        <x-slot:actions separator>
            <x-button label="Create New Bot" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-card>
</div>
