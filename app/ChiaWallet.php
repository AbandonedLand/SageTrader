<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ChiaWallet
{
    protected $client;
    public function __construct()
    {
        $walletConfig = [
            'base_uri' => 'https://localhost:9257', // Sage Wallet
            'verify' => false,
            'cert' => \Illuminate\Support\Facades\Storage::disk('user_home')->path('\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.crt'), // Sage Wallet
            'ssl_key' => \Illuminate\Support\Facades\Storage::disk('user_home')->path('\AppData\Roaming\com.rigidnetwork.sage\ssl\wallet.key') // Sage Wallet
        ];
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

    public function get_sync_status(){
        return $this->post('get_sync_status',[],true);
    }

    public static function getFingerprint(){
        $wallet = new ChiaWallet();
        $fingerprint = $wallet->post('/get_key',[],true);

        if(isset($fingerprint['key'])){
            return $fingerprint['key']['fingerprint'];
        }
        return 'Logged Out';
    }

    public static function login($fingerprint){
        $wallet = new ChiaWallet();
        $login = $wallet->post('/login', ['fingerprint' => $fingerprint], true);
        return redirect('/dashboard');
    }

    public static function getCats(){
        $wallet = new ChiaWallet();
        $cats = $wallet->post('/get_cats', [], true);
        return $cats['cats'];
    }
}
