<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = ['name', 'asset_id','denom','balance'];

    public function getAvatarAttribute(){
        return 'https://icons.dexie.space/'.$this->asset_id.'.webp';
    }

    public function getMaxdisplayamountAttribute(){
        return $this->balance / $this->denom;
    }

    public function Fingerprint(){
        return $this->belongsTo(\App\Models\Fingerprint::class);
    }


}
