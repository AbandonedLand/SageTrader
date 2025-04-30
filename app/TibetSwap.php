<?php

namespace App;

use Illuminate\Support\Facades\Http;
use Laravel\Prompts\Prompt;

class TibetSwap
{
    private static string $base_uri = 'https://api.v2.tibetswap.io/';

    public static function getTokens() {
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

    public static function getQuote(string $pair_id, ?int $amount_in=null, ?int $amount_out=null, bool $xch_is_input=true, bool $estimate_fee = false) {
        $donation = 0.005;
        if($amount_in == null && $amount_out == null){
            throw new \Exception('amount_in or amount_out must be set');
        }
        if($amount_in && $amount_out){
            throw new \Exception('amount_in and amount_out cannot be set at the same time');
        }
        if($amount_in){
            $uri = self::$base_uri . 'quote/' . $pair_id. '?xch_is_input='.($xch_is_input? 'true':'false') .'&estimate_fee='.($estimate_fee ? 'true':'false').'&amount_in='.$amount_in;
        }
        if($amount_out){
            $uri = self::$base_uri . 'quote/' . $pair_id. '?xch_is_input='.($xch_is_input? 'true':'false').'&estimate_fee='.($estimate_fee ? 'true':'false').'&amount_out='.$amount_out;
        }
        $response = Http::get($uri)->json();
        if($xch_is_input){
            $response['xch_is_input'] = true;
            $response['donation_fee'] = intval(round($response['amount_in'] * $donation,0));
            $response['offer']['offered'] = ['XCH'=>$response['amount_in'] + $response['donation_fee']];
            $response['offer']['requested'] = [$response['asset_id'] => $response['amount_out']];
        } else {
            $response['xch_is_input'] = false;
            $response['donation_fee'] = intval(round($response['amount_out'] * $donation,0));
            $response['offer']['requested'] = ['XCH'=>$response['amount_out'] - $response['donation_fee']];
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
