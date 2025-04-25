<?php

namespace App;

class MakeOffer
{

    public \App\Assets $requested_assets;
    public \App\Assets $offered_assets;

    public int $fee=0;

    public ?string $receive_address;
    public \Carbon\Carbon $expires_at_second;
    public bool $auto_import=true;

    public function __construct()
    {
        //
    }
}
