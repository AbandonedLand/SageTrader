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



    public static function checkOffersBatch(array $offer_ids){
        $uri = 'https://api.dexie.space/v1/offersBatch';
        $payload = ['ids' => $offer_ids];
        return Http::post($uri, $payload);
    }

    public static function submitOffer($offer){
        $payload = [
            'offer'=>$offer,
            'claim_rewards'=>true
        ];
        $uri = 'https://api.dexie.space/v1/offers';
        return Http::post($uri, $payload)->json();

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

        if($response['success'] === true){
            if($direction==='buy'){
                $response['price'] = ($response['quote']['to_amount']/1000)/($response['quote']['from_amount']/1000000000000);
                $response['xch_is_input'] = true;
                $response['donation_fee'] = intval(round($response['quote']['from_amount'] * ($response['quote']['combination_fee'] / 10000) ,0));
                $response['offer']['offered'] = ['XCH'=>$response['quote']['from_amount']];
                $response['offer']['requested'] = [$response['quote']['to'] => $response['quote']['to_amount']];
            } else {
                $response['price'] = ($response['quote']['from_amount']/1000)/($response['quote']['to_amount']/1000000000000);
                $response['xch_is_input'] = false;
                $response['donation_fee'] = intval(round($response['quote']['to_amount'] * ($response['quote']['combination_fee'] / 10000) ,0));
                $response['offer']['requested'] = ['XCH'=>$response['quote']['to_amount']];
                $response['offer']['offered'] = [$response['quote']['from']=>$response['quote']['from_amount']];
            }

            return $response;
        }
        return false;

    }

    public static function submitMarketOrder($offer){
        $payload = [
            'fee_destination' =>'xch1xtn62vckj2dmpdlttewfgpsz6zluw8jpj57v308whcu5ty86xhlq3a0h0e',
            'offer'=>$offer,
        ];
        $uri = 'https://api.dexie.space/v1/swap';
        return Http::post($uri, $payload)->json();

    }

    public static function getDexieOffer($dexie_id){
        $uri = 'https://api.dexie.space/v1/offers/'.$dexie_id;
        $response =  Http::get($uri)->json();
        if($response['success'] === true){
            $offer = $response['offer'];
            return $offer;
        }
        return false;
    }


}
