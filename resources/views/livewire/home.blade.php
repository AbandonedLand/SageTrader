<?php

use Livewire\Volt\Component;

new class extends Component {
    public $assets;
    public $breadcrumbs = [
    ['label'=>'Home','link'=>'/']
    ];

    public function mount()
    {
        $this->assets = \App\Models\Asset::where('balance','>',0)->get();
        if(! $this->assets) {
            return $this->redirect('/setup', navigate: true);
        }

    }
}; ?>

<div>
    <x-breadcrumbs :items="$breadcrumbs" class="mb-4"/>


</div>
