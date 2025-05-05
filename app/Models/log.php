<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class log extends Model
{
    protected $fillable = [
        'message'
    ];
    public function logable(){
        return $this->morphTo();
    }
}
