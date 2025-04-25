@extends('app')

@section('content')
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-4">
            <div class="bg-white shadow-md rounded-md p-4">
                <a href="/cat/xch">
                    <h1 class="text-xl font-bold mb-2">XCH</h1>
                    <p class="text-3xl font-bold">{{$xch['balance']/1000000000000}}</p>
                </a>
            </div>
        </div>
    @foreach($cats as $cat)
        <div class="col-span-4">
            <div class="bg-white shadow-md rounded-md p-4">
                <a href="/cat/{{$cat->asset_id}}">
                    <h1 class="text-xl font-bold mb-2">{{$cat->name}}</h1>
                    <p class="text-3xl font-bold">{{$cat->balance / 1000}}</p>
                </a>
            </div>
        </div>
    @endforeach
    </div>

@endsection
