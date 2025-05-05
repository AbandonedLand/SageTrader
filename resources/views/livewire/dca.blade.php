<div>

    @if($showAssets)
        @include('partials.dexie.assetselector')
    @elseif($showform)
        @include('partials.dca.createform')
    @else
        @include('partials.dca.dcatable')
    @endif

</div>
