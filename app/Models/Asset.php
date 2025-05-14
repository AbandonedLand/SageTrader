<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = ['name', 'asset_id','denom','balance'];


    public function Fingerprint(){
        return $this->belongsTo(\App\Models\Fingerprint::class);
    }
}
