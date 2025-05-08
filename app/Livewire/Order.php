<?php

namespace App\Livewire;

use Livewire\Component;

class Order extends Component
{
    public \App\Models\Order $order;

    public function mount($id)
    {
        $this->order = \App\Models\Order::findOrFail($id);
    }

    public function refresh(){
        if($this->order->is_submitted && $this->order->initiated_by =="MarketOrder"){
            if(!$this->order->is_filled && !$this->order->is_cancelled){
                $this->order->checkStatus();
            }
        }
        $this->order->refresh();
    }


    public function cancel(){
        $this->order->delete();
        return redirect()->to('/');
    }

    public function confirm(){
        $this->order->createSageOffer();
        $this->order->submitOrder();

    }

    public function render()
    {
        return view('livewire.order');
    }
}
