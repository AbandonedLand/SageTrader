<div>
    <div class="row">
        <div class="col-10 offset-1">
        @if($showSelectX)
            <div class="card mt-4 col-8 offset-2">
                <div class="card-body ">

                    <label class="form-label">Pick your asset to buy/sell</label>
                    <input autofocus="autofocus" type="text" class="form-control mb-3" placeholder="Search for asset" wire:model.live.debounce.100ms="search">
                    <ul class="list-group">
                        @foreach($tokens as $token)
                            <div wire:key="{{$token->asset_id}}">
                                <a wire:click="setAssetX('{{$token->asset_id}}')" class="list-group-item list-group-item-action">
                                    <div class="row row-cols-2">
                                        <div class="col">
                                            <img src="{{$token->icon()}}" class="img-size-32" alt="">
                                        </div>
                                        <div class="col">
                                            {{$token->ticker}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </ul>
                </div>
            </div>
        @elseif($showSelectY)
            <div class="card mt-4 col-8 offset-2">
                <div class="card-body">

                    <label class="form-label">Pick your asset to buy/sell</label>
                    <input autofocus="autofocus" type="text" class="form-control mb-3" placeholder="Search for asset" wire:model.live.debounce.100ms="search">
                    <ul class="list-group">
                        @foreach($tokens as $token)
                            @if($token->asset_id !== $token_x_asset_id->asset_id && $token->asset_id != 'xch')
                            <div wire:key="{{$token->asset_id}}">
                                <a wire:click="setAssetY('{{$token->asset_id}}')" class="list-group-item list-group-item-action">
                                    <div class="row row-cols-2">
                                        <div class="col">
                                            <img src="{{$token->icon()}}" class="img-size-32" alt="">
                                        </div>
                                        <div class="col">
                                            {{$token->ticker}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        @elseif($showform)

            <div class="card mt-4">
                <div class="card-header text-center">
                    <h2>Create a Trading Grid</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label">Token Y </label>
                            @if($token_y_asset_id)
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button wire:click="showYAssets" class="input-group-text">
                                            <img src="{{$token_y_asset_id->icon()}}" style="height:24px;width:24px" alt="">
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" value="{{$token_y_asset_id->ticker}}">

                                </div>

                            @else
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button wire:click="showYAssets" class="input-group-text bg-primary">Select Asset</button>
                                    </div>
                                    <input type="text" class="form-control" value="">

                                </div>

                            @endif
                            <div>@error('selectedAsset')<p class="text-danger"> {{ $message }} </p>@enderror</div>

                        </div>

                        <div class="col-4 offset-1">

                             <label class="form-label">Token X</label>
                            @if($token_x_asset_id)
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button wire:click="showXAssets" class="input-group-text">
                                            <img src="{{$token_x_asset_id->icon()}}" style="height:24px;width:24px" alt="">
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" value="{{$token_x_asset_id->ticker}}">

                                </div>

                            @else
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button wire:click="showXAssets" class="input-group-text bg-primary">Select Asset</button>
                                    </div>
                                    <input type="text" class="form-control" value="">

                                </div>

                            @endif
                            <div>@error('selectedAsset')<p class="text-danger"> {{ $message }} </p>@enderror</div>

                        </div>

                        <div class="col-2 offset-1">
                            <label class="form-label">Current Price <span class="text-muted text-sm">( Y / X = P )</span></label>
                            @if($token_x_asset_id->ticker === "XCH")
                            <div class="input-group">
                                <input type="text" class="form-control" wire:model.live="price">
                                <div class="input-group-append">
                                    <span class="btn btn-info pt-1" wire:click="getPriceEstimate"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                                        </svg></span>
                                </div>
                            </div>
                            @else
                                <div class="input-group">
                                    <input type="text" class="form-control" wire:model.live="price">

                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-2">
                            <label class="form-label">Lower Price Range</label>
                            <input type="text" class="form-control" wire:model.live="lower_price">
                        </div>
                        <div class="col-2 offset-1">
                            <label class="form-label">Upper Price Range</label>
                            <input type="text" class="form-control" wire:model.live="upper_price">
                        </div>
                        <div class="col-2 offset-1">
                            <label class="form-label">Fee to charge <x-info-icon>fee</x-info-icon></label>
                            <input type="text" wire:model="liquidity_fee" class="form-control">
                        </div>
                        <div class="col-3 offset-1">
                            <label class="form-label">Collect Fee in Token <x-info-icon>fee</x-info-icon></label>
                            <div class="btn-group w-100">
                                <button wire:click="toggleFeeIsTokenX" @class(['btn','btn-primary'=>!$fee_is_token_x,'btn-outline-primary'=>$fee_is_token_x])>Token Y</button>
                                <button wire:click="toggleFeeIsTokenX" @class(['btn','btn-primary'=>$fee_is_token_x,'btn-outline-primary'=>!$fee_is_token_x])>Token X</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            @if($token_y_asset_id) <span class="text-muted mt-1 float-right text-sm">max: {{$token_y_asset_id->displayMax()}} </span>@endif
                            <label for="" class="form-label">Token Y Investment Amount</label>
                            <input type="text" class="form-control" wire:click="clearX" wire:model.live="token_y_reserve" wire:keyup.debounce.300ms="buildGrid" placeholder="{{$y_placeholder}}">
                        </div>
                        <div class="col-4 offset-1">
                            @if($token_x_asset_id) <span class="text-muted mt-1 float-right text-sm">max: {{$token_x_asset_id->displayMax()}} </span>@endif
                            <label for="" class="form-label">Token X Investment Amount</label>
                            <input type="text" class="form-control" wire:click="clearY" wire:model.live="token_x_reserve" wire:keyup.debounce.300ms="buildGrid" placeholder="{{$x_placeholder}}">
                        </div>
                        <div class="col-2 offset-1">
                            <label class="form-label">Step count</label>
                            <input type="text" wire:model="steps" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-success form-control mt-4" wire:click="createBot">Create Bot</button>
                        </div>
                    </div>
                </div>

                @if($grid)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Index</th>
                            <th>Price</th>
                            <th>Bid</th>
                            <th>Ask</th>
                            <th>Fee Collected / Trade</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($grid['grid'] as $row)
                            <tr>
                                <td>{{$row['loop']}}</td>
                                <td>{{number_format($row['price'],3)}}</td>
                                <td> {{($row['bid_y_offered']/$this->token_y_asset_id->decimals)}} <img src="{{$token_y_asset_id->icon()}}" width="18px" height="18px"> => {{($row['bid_x_requested'] / $token_x_asset_id->decimals)}}
                                    <img src="{{$token_x_asset_id->icon()}}" width="18px" height="18px" alt="">
                                </td>
                                <td> {{($row['ask_y_requested']/$this->token_y_asset_id->decimals)}} <img src="{{$token_y_asset_id->icon()}}" width="18px" height="18px"> => {{($row['ask_x_offered'] / $token_x_asset_id->decimals)}}
                                    <img src="{{$token_x_asset_id->icon()}}" width="18px" height="18px" alt="">
                                </td>
                                <td>
                                    @if($fee_is_token_x)
                                        {{number_format(($row['fee_collected']/$token_x_asset_id->decimals),12)}} <img src="{{$token_x_asset_id->icon()}}" width="18px" height="18px" alt="">
                                    @else
                                        {{number_format(($row['fee_collected']/$token_y_asset_id->decimals),3)}} <img src="{{$token_y_asset_id->icon()}}" width="18px" height="18px" alt="">
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @else
            <div class="card mt-4">
                <div class="card-body pb-0">
                    <span class="float-right"><button class="btn btn-primary" wire:click="toggleForm">Create Bot</button></span>
                    <h1>Grid Bot</h1>
                </div>
            </div>
            @if($gridBots)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Token Y</th>
                                            <th>Token X</th>
                                            <th>Lower Price</th>
                                            <th>Upper Price</th>
                                            <th>Fee Collected / Trade</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gridBots as $bot)
                                        <tr>
                                            <td>{{$bot->id}}</td>
                                            <td>{{($bot->token_y_reserve/$bot->tokenY->decimals)}} <img src="{{$bot->tokenY->icon()}}" width="18px" height="18px" alt=""></td>
                                            <td>{{($bot->token_x_reserve/$bot->tokenX->decimals)}} <img src="{{$bot->tokenX->icon()}}" width="18px" height="18px" alt=""></td>
                                            <td>{{$bot->lower_price}}</td>
                                            <td>{{$bot->upper_price}}</td>
                                            <td>{{$bot->fee_collected}}
                                            @if($bot->fee_is_token_x)
                                                <img src="{{$bot->tokenX->icon()}}" width="18px" height="18px" alt="">
                                                @else
                                                <img src="{{$bot->tokenY->icon()}}" width="18px" height="18px" alt="">
                                            @endif
                                            </td>
                                            <td>{{$bot->is_active ? "Active" : "Inactive"}}</td>
                                            <td><div class="btn-group">
                                                    <button class="btn btn-danger">Disable</button>
                                                    <a href="/market/grid/{{$bot->id}}" class="btn btn-info">View</a>
                                                </div></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        </div>
    </div>
</div>
