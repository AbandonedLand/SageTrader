<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public $tibetQuote = null;
    public $dexieQuote = null;
    protected $fillable = [
        'requested_asset',
        'requested_code',
        'requested_amount',
        'offered_asset',
        'offered_code',
        'offered_amount',
        'market_fee_paid',
        'transaction_fee_paid',
        'price',
        'offer_id',
        'dexie_id',
        'status',
        'initiated_by'
    ];


    protected $casts = [
        'meta_data' => 'array',
    ];


    public function updateAssetInfo(){
        if(!$this->requested_code){
            $this->requested_code = \App\Models\Asset::where('asset_id',$this->requested_asset)->first()->ticker;
            $this->save();
        }
        if(!$this->offered_code){
            $this->offered_code = \App\Models\Asset::where('asset_id',$this->offered_asset)->first()->ticker;
            $this->save();
        }

    }

    public function orderable(){
        return $this->morphTo();
    }

    public function requested(){
        return $this->belongsTo(Asset::class, 'requested_asset','asset_id');
    }
    public function offered(){
        return $this->belongsTo(Asset::class, 'offered_asset','asset_id');
    }

    public function log(){
        return $this->morphMany(Log::class, 'logable');
    }

    public function msg($message){
        $log = \App\Models\log::create(['message' => $message]);
        $this->log()->save($log);
    }

    public static function fromDexieQuote($quote,$initiated_by){
        if($quote['quote']['to'] == 'xch'){
            $price = ($quote['quote']['from_amount'] / 1000) / ($quote['quote']['to_amount'] / 1000000000000);
        }
        else {
            $price = ($quote['quote']['to_amount'] / 1000) / ($quote['quote']['from_amount'] / 1000000000000);
        }
        $order = \App\Models\Order::create([
            'requested_asset' => $quote['quote']['to'],
            'requested_amount' => $quote['quote']['to_amount'],
            'offered_asset' => $quote['quote']['from'],
            'offered_amount' => $quote['quote']['from_amount'],
            'market_fee_paid' => $quote['donation_fee'],
            'price'=> $price,
            'initiated_by' => $initiated_by,
        ]);
        $order->updateAssetInfo();
        return $order;
    }

    public function createSageOffer(?int $fee=0) : bool
    {
        if($this->requested_asset == 'xch'){
            $requested = [
                'xch'=>$this->requested_amount,
                'cats'=>[
                ],
                'nfts' =>[]
            ];
            $offered = [
                'xch'=>0,
                'cats'=>[
                    [
                        'asset_id'=> $this->offered_asset,
                        'amount'=>$this->offered_amount
                    ]
                ],
                'nfts'=>[]
            ];
        } else {
            $requested = [
                'xch'=>0,
                'cats'=>[
                    [
                        'asset_id'=>$this->requested_asset,
                        'amount'=>$this->requested_amount
                    ]
                ],
                'nfts'=>[]
            ];
            $offered = [
                'xch'=>$this->offered_amount,
                'cats'=>[
                ],
                'nfts'=>[]
            ];
        }
        $this->msg("Attempting to make offer for this order");
        $offer =  \App\ChiaWallet::makeOffer($requested,$offered,$fee);
        if($offer){
            $this->is_created = true;
            $this->offer_id = $offer['offer_id'];
            $this->offer = $offer['offer'];
            $this->save();
            $this->msg("Offer with id: ".$offer['offer_id']." created");
            return true;
        } else {
            $this->is_created = false;
            $this->save();
            $this->msg("Failed to create offer");
            return false;
        }

    }

    public function submitOffer(){
        $dexieResponse = \App\Dexie::submitOffer($this->offer);
        if($dexieResponse['success']){
            $this->dexie_id = $dexieResponse['id'];
            $this->is_submitted = true;
            $this->save();
            $this->msg("Successfully submitted order to Dexie");
            return true;

        } else {
            $this->is_submitted = false;
            $this->save();
            $this->msg("Failed to submit order to Dexie");
            return false;
        }
    }

    public function submitMarketOrder() : bool
    {
        $dexieResponse = \App\Dexie::submitMarketOrder($this->offer);
        if($dexieResponse['success']){
            $this->dexie_id = $dexieResponse['id'];
            $this->is_submitted = true;
            $this->save();
            $this->msg("Successfully submitted order to DexieSwap");
            return true;

        } else {
            $this->is_submitted = false;
            $this->save();
            $this->msg("Failed to submit order to DexieSwap");
            return false;
        }
    }

    public static function checkOrders(){
        $orders = \App\Models\order::where('is_submitted',true)->whereNot('is_filled',true)->whereNot('is_cancelled',true)->get();

        if($orders->count() > 0){
            foreach($orders as $order){
                $order->checkStatus();
            }
        }
    }


    public function checkAgainstTibetSwap(){
        if($this->requested->asset_id ==="xch" || $this->offered->asset_id ==='xch') {
            // If Requested is Swappable
            if ($this->requested->tibetswap_pair_id) {
                $this->msg("Checking against Tibetswap");
                $quote = \App\TibetSwap::getTibetQuoteForAsset($this->requested->asset_id, $this->requested_amount, 'buy', false);
                $diff = $this->offered_amount - $quote['amount_in'];
                if ($diff > 0) {
                    $this->msg("TibetSwap will accept this offer.  Submitting to TibetSwap");
                    $tibetSwapResponse = \App\TibetSwap::submitOffer($this->requested->tibetswap_pair_id, $this->offer, \App\TibetAction::SWAP, $diff);
                    if ($tibetSwapResponse['success']) {
                        $this->msg("Successfully submitted offer to TibetSwap");
                        $this->dexie_id = $tibetSwapResponse['offer_id'];
                        $this->is_submitted = true;
                        $this->save();
                    } else {
                        $this->msg("Failed to submit offer to TibetSwap");
                    }
                }

            }
            // If Offered is Swappable
            if ($this->offered->tibetswap_pair_id) {
                $this->msg("Checking against Tibetswap");
                $quote = \App\TibetSwap::getTibetQuoteForAsset($this->offered->asset_id, $this->offered_amount, 'sell', true);
                $diff = $this->requested_amount - $quote['amount_out'];
                if ($diff > 0) {
                    $this->msg("TibetSwap will accept this offer.  Submitting to TibetSwap");
                    $tibetSwapResponse = \App\TibetSwap::submitOffer($this->offered->tibetswap_pair_id, $this->offer, \App\TibetAction::SWAP, $diff);
                    if ($tibetSwapResponse['success']) {
                        $this->msg("Successfully submitted offer to TibetSwap");
                        $this->dexie_id = $tibetSwapResponse['offer_id'];
                        $this->is_submitted = true;
                        $this->save();
                    } else {
                        $this->msg("Failed to submit offer to TibetSwap");
                    }
                }
            }
        }
    }


    public function checkStatus(){
        $status = \App\Dexie::getDexieOffer($this->dexie_id);

        if($status['status']==4){
            $this->is_filled = true;
            $this->save();
            $this->msg("This order has been completed");
            $this->handleAccept();

        }
        if($status['status']==3){
            $this->is_cancelled = true;
            $this->save();
            $this->msg("This order has been cancelled");
        }
        if($status['status']==6){
            $this->is_cancelled = true;
            $this->save();
            $this->msg("The order has expired");
        }
    }

    public function handleAccept(){
        // Run GridBot Rules.
        if($this->initiated_by === "GridBot" && $this->is_filled && !isset($this->meta_data['processed'])){
            $metadata = $this->meta_data;
            $grid = $this->orderable()->first();
            $ask = $grid->makeOrder($metadata['next']['ask']['side'],$metadata['next']['ask']['index']);
            if($ask){
                $ask->createSageOffer();
                $ask->submitOrder();
            }
            $bid = $grid->makeOrder($metadata['next']['bid']['side'],$metadata['next']['bid']['index']);
            if($bid){
                $bid->createSageOffer();
                $bid->submitOrder();
            }
        }
    }

    public function status(){
        if($this->is_filled){
            return "Filled";
        }
        if($this->is_cancelled){
            return "Cancelled";
        }
        if($this->is_submitted){
            return "Submitted";
        }
        if($this->is_created){
            return "Created";
        }
        return "Pending create";
    }

    public function submitOrder(){
        # Check if TibetSwap will take it.
        $this->checkAgainstTibetSwap();

        if(!$this->is_submitted){
            # TibetSwap didn't take it.

            if($this->initiated_by === "DCABot" || $this->initiated_by === "MarketOrder"){
                $this->msg("Submitting offer to DexieSwap");
                return $this->submitMarketOrder();
            }
            return $this->submitOffer();
        }

        return true;
    }

    public function offeredDisplayAmount(){
        if(strtolower($this->offered_code)=='xch')
        {
            return round($this->offered_amount / 1000000000000,12);
        }
        return round($this->offered_amount / 1000,3);
    }

    public function requestedDisplayAmount(){
        if(strtolower($this->requested_code)=='xch')
        {
            return round($this->requested_amount / 1000000000000,12);
        }
        return round($this->requested_amount / 1000,3);

    }

}
