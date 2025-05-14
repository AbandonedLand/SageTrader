<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SageWallet extends Model
{

    protected $client;
    protected $config;

    protected $casts = [
        'verify'=>'boolean',
        'approved_fingerprints'=>'array'
    ];

    public function connect() :void
    {

        $walletConfig = [
            'base_uri'=>$this->base_uri,
            'verify'=>false,
            'cert'=>Storage::disk('user_home')->path($this->cert),
            'ssl_key'=>Storage::disk('user_home')->path($this->ssl_key)
        ];

        $this->client = new \GuzzleHttp\Client($walletConfig);

    }


    public function post(string $endpoint, array $data = [], bool $returnAssoc = false)
    {
        $this->connect();
        if (sizeof($data)) {
            $data = ['json' => $data];
        }else{
            $data = ['json' => []];
        }

        $stream = (string)$this->client->post($endpoint, $data)->getBody();

        return json_decode($stream, $returnAssoc);
    }

    public function get_sync_status(){

        return $this->post('get_sync_status',[],true)['keys'];
    }


    public function get_keys(){
        $keys = \App\Models\Fingerprint::all();
        if($keys->isEmpty()){
            $fingerprints = $this->post('get_keys',[],false)->keys;
            foreach ($fingerprints as $fp){
                $fingerprint = new \App\Models\Fingerprint();
                $fingerprint->name = $fp->name;
                $fingerprint->fingerprint = $fp->fingerprint;
                $fingerprint->is_authorized = false;
                $fingerprint->save();
            }
            $keys = \App\Models\Fingerprint::all();
        }
        return $keys;
    }
}
