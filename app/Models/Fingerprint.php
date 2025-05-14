<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    protected $fillable = ['is_authorized'];

    public function Assets(){
        return $this->hasMany(\App\Models\Asset::class);
    }
}
