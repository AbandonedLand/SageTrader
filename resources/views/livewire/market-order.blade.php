<div>
    @if($showAssets)
        @include('partials.dexie.assetselector')
    @else
        <div class="card">
            <div class="card-body">

                <table class="table">
                    <tr>
                        <th>
                            Buy / Sell
                        </th>
                        <th>
                            Chia Asset Token
                        </th>
                        <th>
                            Amount (CAT / XCH)
                        </th>
                        <th>
                            Offered / Requested
                        </th>
                        <th>

                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div class="btn-group">
                                @if($is_buy)
                                    <button wire:click="toggleBuy" class="btn btn-circle btn-success">Buy</button>
                                    <button wire:click="toggleBuy" class="btn btn-circle btn-outline-danger">Sell</button>
                                @else
                                    <button wire:click="toggleBuy" class="btn btn-circle btn-outline-success">Buy</button>
                                    <button wire:click="toggleBuy" class="btn btn-circle btn-danger">Sell</button>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($asset)
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button wire:click="selectAsset" class="input-group-text">
                                            <img src="{{$asset->icon()}}" style="height:24px;width:24px" alt="">
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" value="{{$asset->ticker}}">

                                </div>

                            @else
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span wire:click="selectAsset" class="input-group-text bg-primary">Select Asset</span>
                                    </div>
                                    <input type="text" class="form-control" value="">

                                </div>

                            @endif
                            <div>@error('selectedAsset')<p class="text-danger"> {{ $message }} </p>@enderror</div>
                        </td>

                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    @if($is_offered)
                                        @if($is_buy)
                                            <span class="input-group-text">XCH Offered</span>
                                        @else
                                            <span class="input-group-text">CAT Offered</span>
                                        @endif
                                    @else
                                        @if($is_buy)
                                            <span class="input-group-text">CAT Requested</span>
                                        @else
                                            <span class="input-group-text">XCH Requested</span>
                                        @endif
                                    @endif
                                </div>
                                <input type="number" wire:keydown.debounce.300ms="getQuote" wire:model.live="amount" class="form-control" id="exampleFormControlInput1" placeholder="100.000">

                            </div>

                            @if($is_offered)
                                @if($is_buy)
                                <p>Max: <code class="text-muted">{{$xch->displayMax()}}</code></p>
                                @else
                                    <p>Max: <code class="text-muted">{{$asset->displayMax()}}</code></p>
                                @endif
                            @endif
                            <div>@error('amount')<p class="text-danger"> {{ $message }} </p>@enderror</div>
                        </td>
                        <td>
                            @if($is_offered)
                                @if($is_buy)
                                    <button wire:click="toggleOffered" class="input-group-text bg-info">XCH Offered</button>
                                @else
                                    <button wire:click="toggleOffered" class="input-group-text bg-info">CAT Offered</button>
                                @endif
                            @else
                                @if($is_buy)
                                    <button wire:click="toggleOffered" class="input-group-text bg-warning">CAT Requested</button>
                                @else
                                    <button wire:click="toggleOffered" class="input-group-text bg-warning">XCH Requested</button>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($asset && $amount)
                                <div class="input-group">
                                    <button class="btn btn-success form-control" wire:click="getQuote">Get Quote</button>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>

            </div>


        </div>
        @if($dexieOffer)
            <div class="card" >
                <div class="card-header">
                    <h2>Order Quote</h2>
                </div>
                <div class="card-body">
                    <div wire:loading>
                        <p>Fetching quote from dexie.</p>
                    </div>
                    <table class="table" wire:loading.remove>
                        <thead>
                        <tr>
                            <th>
                                Offered Asset
                            </th>
                            <th>Offered Amount</th>
                            <th>Requested Asset</th>
                            <th>Requested Amount</th>
                            <th>dexie Fee</th>
                            <th>suggested transaction fee</th>
                            <th>Price per XCH</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{ $dexieOffer['quote']['from'] == 'xch' ? 'xch' : $asset->ticker }}
                            </td>
                            <td>
                                {{ $dexieOffer['quote']['from'] == 'xch' ? number_format($dexieOffer['quote']['from_amount'] / 1000000000000,12) : number_format($dexieOffer['quote']['from_amount']/1000,3) }}
                            </td>
                            <td>
                                {{ $dexieOffer['quote']['to'] == 'xch' ? 'xch' : $asset->ticker }}
                            </td>
                            <td>
                                {{$dexieOffer['quote']['to'] == 'xch' ? number_format($dexieOffer['quote']['to_amount'] / 1000000000000,12) : number_format($dexieOffer['quote']['to_amount']/1000,3) }}
                            </td>
                            <td>
                                {{number_format($dexieOffer['donation_fee'] / 1000000000000,12)}}
                            </td>
                            <td>
                                {{number_format($dexieOffer['quote']['suggested_tx_fee'] / 1000000000000,12)}}
                            </td>
                            <td>
                                {{ number_format($dexieOffer['quote']['to'] == 'xch' ? (($dexieOffer['quote']['from_amount'] / 1000)/($dexieOffer['quote']['to_amount'] / 1000000000000)) : (($dexieOffer['quote']['to_amount'] / 1000)/($dexieOffer['quote']['from_amount'] / 1000000000000)),3 )}}
                            </td>

                        </tr>
                        </tbody>
                    </table>


                </div>
                <div class="card-footer">
                    <button class="btn btn-danger float-right btn-sm" wire:click="takeOffer">Confirm Order</button>
                </div>
            </div>
        @endif
    @endif

</div>


