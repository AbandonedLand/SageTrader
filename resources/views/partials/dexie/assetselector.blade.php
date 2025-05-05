<div class="card col-8 offset-2">
    <div class="card-body">

        <label class="form-label">Pick your asset to buy/sell</label>
        <input autofocus="autofocus" type="text" class="form-control mb-3" placeholder="Search for asset" wire:model.live.debounce.100ms="search">
        <ul class="list-group">
            @foreach($tokens as $token)
                <div wire:key="{{$token['id']}}">
                    <a wire:click="setAsset('{{$token['id']}}')" class="list-group-item list-group-item-action">
                        <div class="row row-cols-2">
                            <div class="col">
                                <img src="{{$token['icon']}}" class="img-size-32" alt="">
                            </div>
                            <div class="col">
                                {{$token['code']}}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </ul>
    </div>
</div>
