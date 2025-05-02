<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Prompts\Prompt;

class TibetSwap
{
    private static string $base_uri = 'https://api.v2.tibetswap.io/';
    private static $donation = 0.003;

    public static function getTokens() :array{
        $uri = self::$base_uri . 'tokens';
        return Http::get($uri)->json();
    }

    public static function getPairs(int $skip=0, int $limit=10) {
        $uri = self::$base_uri . 'pairs';
        return Http::get($uri)->json();
    }

    public static function getToken(string $asset_id) {
        $uri = self::$base_uri . 'token/' . $asset_id;
        return Http::get($uri)->json();
    }

    public static function getLauncherId(string $launcher_id) {
        $uri = self::$base_uri . 'pair/' . $launcher_id;
        return Http::get($uri)->json();
    }

    public static function getTibetQuoteForAsset($asset_id,$amount,$direction,$amount_is_offered){
        $pair = self::getToken($asset_id)['pair_id'];
        $donation = self::$donation;
        $donation_fee = 0;

        if($amount_is_offered){

            if($direction ==='buy'){
                $donation_fee = floor($amount * $donation);
                $amount = 'amount_in='.$amount-$donation_fee;
                $xch_is_input = 'xch_is_input=true';
            } else {
                $amount = 'amount_in='.$amount;
                $xch_is_input = 'xch_is_input=false';
            }
        } else {
            if($direction ==='buy'){
                $amount = 'amount_out='.$amount + $donation_fee;
                $xch_is_input = 'xch_is_input=true';
            } else {
                $amount = 'amount_out='.$amount;
                $xch_is_input = 'xch_is_input=false';
            }
        }

        $uri = self::$base_uri . 'quote/' . $pair . '?'.$amount.'&'.$xch_is_input;
        $response = Http::get($uri)->json();
        if($direction==='buy'){
            $response['xch_is_input'] = true;
            $response['donation_fee'] = intval($donation);
            $response['offer']['offered'] = ['XCH'=>$response['amount_in']];
            $response['offer']['requested'] = [$response['asset_id'] => $response['amount_out']];
        } else {
            $response['xch_is_input'] = false;
            $response['donation_fee'] = intval($donation);
            $response['offer']['requested'] = ['XCH'=>$response['amount_out']];
            $response['offer']['offered'] = [$response['asset_id']=>$response['amount_in']];
        }


        return $response;


    }



    public static function submitOffer(
        string $pair_id,
        string $offer,
        \App\TibetAction $action,
        int $total_donation_amount = 0,
        array $donation_addresses = ['xch1xtn62vckj2dmpdlttewfgpsz6zluw8jpj57v308whcu5ty86xhlq3a0h0e','xch1hm6sk2ktgx3u527kp803ex2lten3xzl2tpjvrnc0affvx5upd6mq75af8y'],
        array $donation_weights = [50,50])
    {
        $uri = self::$base_uri . 'offer/' . $pair_id;
        return Http::post($uri, [
            'action' => $action->name,
            'offer' => $offer,
            'total_donation_amount' => $total_donation_amount,
            'donation_addresses' => $donation_addresses,
            'donation_weights' => $donation_weights
        ])->json();
    }

}
