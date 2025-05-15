<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DcaBot extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
        'amount_is_x'=>'boolean',
        'amount_is_offered'=>'boolean',
    ];
}
