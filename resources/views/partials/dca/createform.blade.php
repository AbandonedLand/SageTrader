
<div class="card col-8 offset-2">
    <div class="card-header text-center">
        <h2>Create a Dollar Cost Average Bot</h2>
    </div>
    <div class="card-body">
        <div class="row pb-3">
            <div class="col-4">
                <h4>Asset:</h4>
            </div>
            <div class="col-8">
                @if($asset)
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button wire:click="toggleShowAssets" class="input-group-text">
                                <img src="{{$asset['icon']}}" style="height:24px;width:24px" alt="">
                            </button>
                        </div>
                        <input type="text" class="form-control" disabled value="{{$asset['code']}}">

                    </div>

                @else
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button wire:click="toggleShowAssets" class="input-group-text bg-primary">Select Asset</button>
                        </div>
                        <input type="text" class="form-control" value="">

                    </div>

                @endif
                    <div>@error('asset_id')<p class="text-danger"> {{ $message }} </p>@enderror</div>
            </div>

        </div>
        <div class="row pb-3">
            <div class="col-4">
                <h4>Buy / Sell: <x-info-icon>buysell</x-info-icon></h4>
            </div>
            <div class="col-8">
                <div class="btn-group w-100">
                @if($is_buy)
                    <button wire:click="toggleBuy" class="btn btn-circle btn-success">Buy</button>
                    <button wire:click="toggleBuy" class="btn btn-circle btn-outline-danger">Sell</button>
                @else
                    <button wire:click="toggleBuy" class="btn btn-circle btn-outline-success">Buy</button>
                    <button wire:click="toggleBuy" class="btn btn-circle btn-danger">Sell</button>
                @endif
                </div>
            </div>

        </div>
        <div class="row pb-3">
            <div class="col-4">
                <h4>Spend per transaction: </h4>
            </div>
            <div class="col-8">

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            @if(!$is_buy && $asset)
                                <img src="{{$asset['icon']}}" style="height:24px;width:24px" title="{{$asset['code']}}">
                            @else
                                <img src="https://icons.dexie.space/xch.webp" style="height:24px;width:24px" title="XCH">
                            @endif
                        </span>
                    </div>
                    <input type="text" class="form-control" wire:model.live="amount">

                </div>
                <span>@error('amount')<p class="text-danger"> {{ $message }} </p>@enderror</span>



            </div>

        </div>
        <div class="row pb-3">
            <div class="col-4">
                <h4>Frequency: <x-info-icon>frequency</x-info-icon></h4>
            </div>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            every
                        </span>
                    </div>
                    <input type="text" class="form-control" wire:model.live="time">
                    <div class="input-group-append">
                        <button class="input-group-text btn btn-primary" wire:click="changeFrequency">
                            {{$frequency}}
                        </button>
                    </div>

                </div>
                <span>@error('time')<p class="text-danger"> {{ $message }} </p>@enderror</span>
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-12">
                <hr>
                <h5>Optional</h5>
                <hr>
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-4">
                <h4>Price Restriction: <x-info-icon>pricerestriction</x-info-icon></h4>
            </div>
            <div class="col-1">
                <h4>Price:</h4>
            </div>
            <div class="col-3">
                <div class="btn-group">
                    <button wire:click="setRestrictionOff" @class([
                            'btn',
                            'btn-secondary'=> ! $price_lt_gt,
                            'btn-outline-secondary'=>$price_lt_gt,
                            'btn-sm'
                            ])> Off
                    </button>
                    <button wire:click="setRestrictionGt" @class([
                            'btn',
                            'btn-outline-secondary'=> ($price_lt_gt && ($price_lt_gt == '<')),
                            'btn-secondary' => ($price_lt_gt && ($price_lt_gt == '>'))
                            ])> Greater Than
                    </button>
                    <button wire:click="setRestrictionLt" @class([
                            'btn',
                            'btn-outline-secondary'=> ($price_lt_gt && ($price_lt_gt == '>')),
                            'btn-secondary' => ($price_lt_gt && ($price_lt_gt == '<'))
                            ])> Less Than
                    </button>

                </div>
            </div>
            <div class="col-4">
                <div class="input-group">
                    <input type="text" class="form-control" wire:model.live="price" placeholder="Price">
                    <div class="input-group-append">
                        <button class="btn btn-info" wire:click="getQuote">Show Current Price</button>
                    </div>
                </div>

                <span>@error('price')<p class="text-danger"> {{ $message }} </p>@enderror</span>
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-4">
                <h4>Max Spend: <x-info-icon>maxspend</x-info-icon></h4>
            </div>
            <div class="col-8">
                <div class="input-group">
                <div class="input-group-prepend">
                        <span class="input-group-text">
                            @if(!$is_buy && $asset)
                                <img src="{{$asset['icon']}}" style="height:24px;width:24px" title="{{$asset['code']}}">
                            @else
                                <img src="https://icons.dexie.space/xch.webp" style="height:24px;width:24px" title="XCH">
                            @endif
                        </span>
                    </div>
                    <input type="number" class="form-control" wire:model.live="maxAmount" placeholder="optional">
                    <div>
                        @error('maxAmount')<p class="text-danger"> {{ $message }} </p>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-4">
                <h4>End Date: <x-info-icon>endate</x-info-icon></h4>
            </div>
            <div class="col-8">
                <input type="date" class="form-control" wire:model.live="end_date" placeholder="optional">
            </div>
        </div>

    </div>
    <div class="card-footer">

            <div class="row pb-3">
                <div class="col-8">
                    @if($amount && $asset_id)
                    <p>
                        You will spend {{$amount}} @if($is_buy)XCH @else {{$asset['code']}}@endif to acquire @if($is_buy) {{$asset['code']}} @else XCH at Market Price @endif every {{$time." ".$frequency}} @if($price_lt_gt && $price), if the price is {{$price_lt_gt." ".$price }}@endif @if($end_date), until {{$end_date}} @endif @if($end_date && $maxAmount) or @endif @if($maxAmount), until {{$maxAmount}} @if($is_buy)XCH @else {{$asset['code']}}@endif is spent @endif .
                    </p>
                    @endif
                </div>
                <div class="col-4">
                    <button class="btn btn-success float-right" wire:click="createbot">Create Bot</button>
                </div>
            </div>


    </div>

</div>
