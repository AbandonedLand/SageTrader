<?php

use Livewire\Volt\Component;

new class extends Component {
    public \App\Models\DcaBot $bot;

    public function mount($id){
        $this->bot = \App\Models\DcaBot::find($id);
        dd($this->bot);
    }

}; ?>

<div>
    //
</div>
