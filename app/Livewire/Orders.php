<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Orders extends Component
{

    public $original_orders;
    public $orders;
    public $is_filled = false;
    public $price_sort_desc = true;

    public function mount($orders){

        $this->original_orders = $orders;
        $this->orders = $orders;
        $this->showFilled();
        $this->showFilled();


    }

    public function showFilled(){
        $this->is_filled = !$this->is_filled;
        if($this->is_filled){
            $this->orders = $this->original_orders->where('is_filled', true);
        } else {
            $this->orders = $this->original_orders->where('is_filled', false);
        }
    }

    public function sortPrice(){
        $this->price_sort_desc = !$this->price_sort_desc;
        if($this->price_sort_desc){
            $this->orders = $this->orders->sortByDesc('price');
        } else {
            $this->orders = $this->orders->sortBy('price');
        }

    }

    public function render()
    {

        return view('livewire.orders');
    }
}
