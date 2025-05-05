@extends('adminlte::page')

@section('body')
    <div class="row">
        <div class="col-10 offset-1">
            <h1>Buy / Sell:</h1>
            <p>You are Buying or Selling the Chia Asset Token using XCH as the other side of the transaction. </p>
            <p>This can make transactions a bit confusing especially when dealing with USDC.  Buying USDC with XCH is what most people would consider selling XCH.  This bot treats the Chia Asset Tokens as the asset you are buying/selling.  XCH is the currency transactions are executed with.</p>
        </div>
    </div>
@endsection
