<?php

namespace App;

class Assets
{
    public static function create (?int $xch=0, ?array $cats = [], ?array $nfts = []){
        return [
            'xch' => $xch,
            'cats' => $cats,
            'nfts' => $nfts
        ];
    }


}
