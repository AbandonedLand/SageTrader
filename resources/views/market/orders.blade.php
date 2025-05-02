@extends('adminlte::page')


@section('content_header')
    <div class="card">
        <div class="card-body">
            <h2 class="text-center">Orders</h2>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order Type</th>
                        <th>Requested Asset</th>
                        <th>Offered Asset</th>
                        <th>Market Fee</th>
                        <th>Status</th>
                        <th></th>
                    </tr>

                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                {{$order->initiated_by}}
                            </td>
                            <td class="">
                                {{$order->requested_code=='XCH' ? number_format($order->requested_amount / 1000000000000,12) : number_format($order->requested_amount / 1000,3)}}
                                <img src="https://icons.dexie.space/{{$order->requested_asset}}.webp" class="img-size-32" title="{{$order->requested_code}}">
                            </td>
                            <td class="">
                                {{$order->offered_code=='XCH' ? number_format($order->offered_amount / 1000000000000,12) : number_format($order->offered_amount / 1000,3)}}
                                <img src="https://icons.dexie.space/{{$order->offered_asset}}.webp" class="img-size-32" title="{{$order->offered_code}}">
                            </td>
                            <td>{{number_format($order->market_fee_paid / 1000000000000,12)}}</td>
                            <td>{{$order->status}}</td>
                            <td>@if($order->dexie_id)
                                    <a class="btn btn-outline-primary" href="https://dexie.space/offers/{{$order->dexie_id}}" target="_blank">View on dexie.space</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
