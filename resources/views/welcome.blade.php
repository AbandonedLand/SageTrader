@extends('adminlte::page')


@section('content_header')
    <h2>Tokens</h2>
@endsection
@section('content')
    <div class="card">


    <table class="table">
        <thead>
        <tr>
            <th scope="col">Asset</th>
            <th scope="col">Amount</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td><img src="https://icons.dexie.space/xch.webp" class="img-size-32"> XCH</td>
                <td>{{$xch['balance'] / 1000000000000}}</td>
                <td>
                    <a href="/trade/xch" class="btn btn-sm btn-success">
                        <i class="fas fa-balance-scale"></i>
                        Trade
                    </a>
                    <a href="/bot/xch" class="btn btn-sm btn-info">
                        <i class="fa fa-robot"> </i>
                        New Bot
                    </a>

                </td>
            </tr>
        @foreach($cats as $cat)
            <tr>
                <td>
                    <img src="{{$cat['icon_url']}}" class="img-size-32"> {{$cat['ticker']}}
                </td>
                <td>
                    {{$cat['balance'] / 1000}}
                </td>
                <td>
                    <a href="/trade/{{$cat['asset_id']}}" class="btn btn-sm btn-success">
                        <i class="fas fa-balance-scale"></i>
                        Trade
                    </a>
                    <a href="/bot/{{$cat['asset_id']}}" class="btn btn-sm btn-info">
                        <i class="fa fa-robot"> </i>
                        New Bot
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
@endsection
