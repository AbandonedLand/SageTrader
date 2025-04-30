@extends('app')

@section('breadcrumbs')
    <div class="breadcrumbs">
        <ul>
            <li><a href="/liquidity">Liquidity</a></li>

            <li><a href="/liquidity/create">Create</a></li>
            <li><a href="/liquidity/tibet">TibetSwap</a></li>
        </ul>
    </div>
@endsection

@section('content')
    @livewire('tibet-swap')
@endsection
