@extends('app')

@section('content')
<div class="grid grid-cols-12 gap-4">
    @foreach($keys as $key)
        <div class="col-span-4">
            <div class="bg-white shadow-md rounded-md p-4">
                <a href="/login/{{$key->fingerprint}}">
                <h1 class="text-xl font-bold mb-2">{{$key->name}}</h1>
                <p class="text-sm">{{$key->fingerprint}}</p>
                </a>
            </div>
        </div>
    @endforeach


</div>


@endsection
