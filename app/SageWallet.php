<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;

class SageWallet
{
    protected $client;
    public function __construct()
    {
        if(Storage::disk('local')->exists('wallet.crt') && Storage::disk('local')->exists('wallet.key')){
            $walletConfig = [
                'base_uri' => 'https://localhost:9257', // Sage Wallet
                'verify' => false,
                'cert'=>Storage::disk('local')->path('wallet.crt'),
                'ssl_key'=>Storage::disk('local')->path('wallet.key')
            ];
        } else {
            if(PHP_OS == 'Linux'){

                $walletConfig = [
                    'base_uri' => 'https://localhost:9257', // Sage Wallet
                    'verify' => false,
                    'cert' => Storage::disk('user_home')->path('./local/share/com.rigidnetwork.sage/ssl/wallet.crt'), // Sage Wallet
                    'ssl_key' => Storage::disk('user_home')->path('./local/share/com.rigidnetwork.sage/ssl/wallet.key') // Sage Wallet
                ];
            } else {
                // Assuming Windows Location of Sage SSL Certs
                $walletConfig = [
                    'base_uri' => 'https://localhost:9257', // Sage Wallet
                    'verify' => false,
                    'cert' => Storage::disk('user_home')->path('\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.crt'), // Sage Wallet
                    'ssl_key' => Storage::disk('user_home')->path('\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.key') // Sage Wallet
                ];
            }
        }


        $this->client = new Client($walletConfig);

    }


    /**
     * @throws GuzzleException
     */
    public function post(string $endpoint, array $data = [], bool $returnAssoc = false)
    {
        if (sizeof($data)) {
            $data = ['json' => $data];
        }else{
            $data = ['json' => []];
        }

        $stream = (string)$this->client->post($endpoint, $data)->getBody();

        return json_decode($stream, $returnAssoc);
    }

    public static function get_sync_status(){
        $wallet = new SageWallet();
        return $wallet->post('get_sync_status',[],true);
    }

    public static function getFingerprint(){
        $wallet = new SageWallet();
        $fingerprint = $wallet->post('/get_key',[],true);

        if(isset($fingerprint['key'])){
            return $fingerprint['key']['fingerprint'];
        }
        return 'Logged Out';
    }


    public static function getOffers(){
        $wallet = new SageWallet();
        return $wallet->post('/get_offers',[],true)['offers'];

    }
    public static function getOffer($offer_id){
        $wallet = new SageWallet();
        return $wallet->post('/get_offer',['offer_id'=>$offer_id],true);
    }

    public static function deleteOffer($offer_id){
        $wallet = new SageWallet();
        return $wallet->post('/delete_offer',['offer_id'=>$offer_id],true);
}

    public static function login($fingerprint){
        $wallet = new SageWallet();
        $login = $wallet->post('/login', ['fingerprint' => $fingerprint], true);
        return redirect('/dashboard');
    }

    public static function getCats(){
        $wallet = new SageWallet();
        $cats = $wallet->post('/get_cats', [], true);
        return $cats['cats'];
    }

    public static function getCat($asset_id){
        $wallet = new SageWallet();
        return $wallet->post('/get_cat', ['asset_id' => $asset_id], true)['cat'];
    }

    public static function makeOffer(array $requested_assets, array $offered_assets, int $fee = 0, ?string $receiver_address = null, ?int $expires_at_second = null, ?bool $auto_import = true)
    {
        $fingerprint = self::getFingerprint();
        $approved = \App\Models\Fingerprint::where('fingerprint',$fingerprint)->where('is_approved',true)->get();
        if(!$approved){
            return false;
        }



        $offer = [
            'requested_assets' => $requested_assets,
            'offered_assets' => $offered_assets,
            'fee' => $fee,
            'auto_import' => $auto_import
        ];
        if($expires_at_second){
            $offer['expires_at_time'] = $expires_at_second;
        }
        if($receiver_address){
            $offer['receiver_address'] = $receiver_address;
        }

        $wallet = new SageWallet();
        $createdOffer =  $wallet->post('/make_offer', $offer, true);
        if($createdOffer){
            return $createdOffer;
        }
        return false;

    }
}
