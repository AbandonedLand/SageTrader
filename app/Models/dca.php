<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dca extends Model
{

    public static function RunSchedule(){
        $dcas = \App\Models\dca::where('is_active',true)->where('next_run',"<",\Carbon\Carbon::now())->orderBy('next_run','ASC')->get();
        foreach($dcas as $dc){
            $dc->execute();
        }
    }

    public function msg($message){
        $log = \App\Models\log::create(['message' => $message]);
        $this->log()->save($log);
    }

    public function orders(){
        return $this->morphMany('App\Models\Order', 'orderable');
    }

    public function log(){
        return $this->morphMany('App\Models\Log', 'logable');
    }

    public function setNextRuntime(){
        $next = \Carbon\Carbon::now()->addMinutes($this->buy_frequency);
        $this->last_run = \Carbon\Carbon::now();
        $this->next_run = $next;
        $this->save();
    }
    public function isExpired(){
        if($this->end_date){
            return (\Carbon\Carbon::now() > \Carbon\Carbon::parse($this->end_date));
        }
        return false;
    }

    public function asset(){
        return $this->hasOne(\App\Models\Asset::class, 'asset_id','asset_id');
    }


    public function execute(){
        if(\Carbon\Carbon::now() < \Carbon\Carbon::parse($this->next_run)){
            $this->msg('Attempted to run DCA bot before next run time.');
            return false;
        }

        if(!$this->is_active){
            $this->msg('Attempted to run inactive DCA bot');
            return false;
        }
        if($this->isExpired()){
            $this->is_active = 0;
            $this->save();
            $this->msg('DCA bot has expired.  Deactivating bot.');
            return false;
        }



        $quote = \App\Dexie::getDexieQuoteForAsset($this->asset_id,$this->amount,$this->buy_sell,true);
        if(!$quote){
            $this->msg('Failed to get quote from dexie');
            return false;
        }
        if($this->price && $this->price_lt_gt){
            if($this->price_lt_gt == '>'){
                if(! ($quote['price'] > $this->price)){
                    $this->msg('Quote Price:'.$quote['price'].' is less than '. $this->price.'. Not executing this run.');
                    $this->setNextRuntime();
                    return false;
                }
            }
            if($this->price_lt_gt == '<'){
                if(! ($quote['price'] < $this->price)){
                    $this->msg('Quote Price:'.$quote['price'].' is greater than '. $this->price.'. Not executing this run.');
                    $this->setNextRuntime();
                    return false;
                }
            }
            $this->msg('Quote price is in range.  Creating Order.');
        }

        $order = \App\Models\Order::fromDexieQuote($quote,'DCABot');
        if($order){
            $this->orders()->save($order);
            $this->msg('Successfully created order with id='.$order->id);

            if($order->createSageOffer()){
                if($order->submitOrder()){
                    $this->current_amount = $this->current_amount + $order->offered_amount;
                    $this->successful_orders ++;

                    $this->save();
                } else {
                    $this->failed_orders ++;
                    $order->status='Error: Failed to submit order';
                    $order->save();
                    $this->save();
                }

            }
        } else {
            $this->msg('Failed to create order');
        }
        $this->setNextRuntime();
        return true;
    }



    public function submitMarketOrder($offer){
        $dexieResponse = \App\Dexie::submitMarketOrder($offer);
        if($dexieResponse){
            $this->last_run = \Carbon\Carbon::now();
            $this->successful_orders ++;
            $this->current_amount = $this->current_amount + $this->amount;
            $this->next_run = \Carbon\Carbon::now()->addMinutes($this->buy_frequency);
            $this->save();
        } else {
            $this->last_run = \Carbon\Carbon::now();
            $this->failed_orders ++;
            $this->next_run = \Carbon\Carbon::now()->addMinutes($this->buy_frequency);
            $this->save();
        }
    }

}
