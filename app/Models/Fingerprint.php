<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{

    protected $fillable = [
        'is_approved'
    ];

    public static function loadFingerprints(){

        $wallet = new \App\ChiaWallet();
        $keys = $wallet->post('/get_keys',[],false)->keys;
        foreach($keys as $key){
            $fingerprint = new \App\Models\Fingerprint();
            $fingerprint->name = $key->name;
            $fingerprint->fingerprint = $key->fingerprint;
            $fingerprint->save();
        }
    }
}
