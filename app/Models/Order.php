<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

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


    public function updateAssetInfo(){
        if(!$this->requested_code){
            $this->requested_code = \App\Dexie::getDexieAsset($this->requested_asset)['code'];
            $this->save();
        }
        if(!$this->offered_code){
            $this->offered_code = \App\Dexie::getDexieAsset($this->offered_asset)['code'];
            $this->save();
        }

    }

    public function requested(){
        return $this->belongsTo(Asset::class, 'requested_asset','asset_id');
    }
    public function offfered(){
        return $this->belongsTo(Asset::class, 'offered_asset','asset_id');
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

    public function createSageOffer(?int $fee=0){
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

        return \App\ChiaWallet::makeOffer($requested,$offered,$fee);

    }

}
