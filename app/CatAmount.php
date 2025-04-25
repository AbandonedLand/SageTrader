<?php

namespace App;

class CatAmount
{
    public string $asset_id;
    public int $amount;
    public function __construct($asset_id, $amount)
    {
        $this->asset_id = $asset_id;
        $this->amount = $amount;

    }

    public function isValid() : bool{
        if(strlen($this->asset_id) == 64 && is_numeric($this->amount)){
            return true;
        }
        return false;
    }
}
