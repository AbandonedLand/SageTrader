<?php

namespace App;

class Assets
{

    public int $xch = 0;


    /**
     * @var CatAmount[]
     */
    public array $cats = [];
    public array $nfts = [];

    public function __construct()
    {
        //
    }

    public function toArray(){
        return [
            'xch' => $this->xch,
            'cats' => ($this->catArray()),
            'nfts' => $this->nfts,
        ];
    }

    public function setXch(int $amount) : bool{
        $this->xch = $amount;
        return true;
    }

    public function addNft(string $nft_id) : bool{
        if(strlen($nft_id)==62){
            $this->nfts[] = $nft_id;
            return true;
        }
        return false;

    }

    public function addCat($asset_id, $amount) : bool{
        $cat = new CatAmount($asset_id, $amount);
        if($cat->isValid()){
            $this->cats[] = $cat;
            return true;
        }
        return false;
    }

    public function catArray() : array{
        $catArray = [];
        foreach($this->cats as $cat){
            $catArray[] = [$cat->asset_id=>$cat->amount];
        }
        return $catArray;
    }
}
