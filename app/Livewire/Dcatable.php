<?php

namespace App\Livewire;

use Livewire\Component;

class Dcatable extends Component
{
    public $dcas;
    public $active;

    public function mount(){
        $this->active = true;
        $this->dcas = \App\Models\dca::where('is_active',true)->get();
    }


    public function updateDCAs(){
        if($this->active){
            $this->dcas = \App\Models\dca::where('is_active',true)->get();
        } else {
            $this->dcas = \App\Models\dca::where('is_active',false)->get();
        }
    }
    public function toggleDCAActivation($id){
        $dca = \App\Models\dca::find($id);
        $dca->is_active = !$dca->is_active;
        $dca->save();
        $dca->msg('Active status was set to '.$dca->is_active);
    }

    public function toggleActive(){
        $this->active = !$this->active;
    }


    public function render()
    {
        return view('livewire.dcatable');
    }
}
