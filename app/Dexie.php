<?php

namespace App;

use Illuminate\Support\Facades\Http;

class Dexie
{
    public $base_uri = 'https://api.dexie.space/v1/';


    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public static function getDexieCatAssets(){
        $page = 0;
        $page_size = 100;
        $assets = [];
        do{
            $page++;
            $uri = 'https://dexie.space/v1/assets?type=cat&page='.$page.'&page_size='.$page_size;
            $response = Http::get($uri)->json();
            $assets = array_merge($assets, $response['assets']);

        } while (count($response['assets']) > 0);

        return $assets;
    }

    public static function getDexieSwapAssets(){
        $uri = 'https://api.dexie.space/v1/swap/tokens';
        return Http::get($uri)->json();
    }

    public static function getDexieAsset($asset_id){
        $uri = 'https://api.dexie.space/v1/tokens?id='.$asset_id;
        return Http::get($uri)->json()['token'];
    }

    public static function getDexieQuoteForAsset($asset_id,$amount,$direction,$amount_is_offered){
        $base_uri = 'https://api.dexie.space/v1/swap/quote?';
        if($direction === 'buy') {
            $to = $asset_id;
            $from = 'XCH';
        } else {
            $from = $asset_id;
            $to = 'XCH';
        }
        if($amount_is_offered) {
            $uri = $base_uri.'from_amount='.$amount.'&from='.$from.'&to='.$to;
        } else {
            $uri = $base_uri.'to_amount='.$amount.'&from='.$from.'&to='.$to;
        }

        $response =  Http::get($uri)->json();
        if($direction==='buy'){
            $response['xch_is_input'] = true;
            $response['donation_fee'] = intval(round($response['quote']['from_amount'] * ($response['quote']['combination_fee'] / 10000) ,0));
            $response['offer']['offered'] = ['XCH'=>$response['quote']['from_amount']];
            $response['offer']['requested'] = [$response['quote']['to'] => $response['quote']['to_amount']];
        } else {
            $response['xch_is_input'] = false;
            $response['donation_fee'] = intval(round($response['quote']['to_amount'] * ($response['quote']['combination_fee'] / 10000) ,0));
            $response['offer']['requested'] = ['XCH'=>$response['quote']['to_amount']];
            $response['offer']['offered'] = [$response['quote']['from']=>$response['quote']['from_amount']];
        }

        return $response;
    }

    public static function submitMarketOrder(\App\Models\Order $order){

        $offer = $order->createSageOffer();
        if($offer) {
            $order->status = 'offerCreated';
            $order->offer_id = $offer['offer_id'];
            $order->offer = $offer['offer'];
            $order->save();

            $payload = [
                'fee_destination' =>'xch1xtn62vckj2dmpdlttewfgpsz6zluw8jpj57v308whcu5ty86xhlq3a0h0e',
                'offer'=>$offer['offer'],
            ];

            $uri = 'https://api.dexie.space/v1/swap';
            $response = Http::post($uri, $payload)->json();
            if($response['success']){
                $order->dexie_id = $response['id'];
                $order->status = 'submitted_to_dexie';
                $order->save();

            } else {
                $order->status = 'failed_to_submit_dexie';
                $order->save();
            }
            return $response;

        }
        $order->status = 'failed_to_create_offer';
        $order->save();
        return false;
    }

}
