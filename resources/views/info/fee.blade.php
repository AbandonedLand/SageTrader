@extends('adminlte::page')

@section('body')
    <div class="row">
        <div class="col-10 offset-1">
            <h1>Fee to charge:</h1>
            <p>This is the fee you wish to charge for providing liquidity.</p>
            <h4>For example: 0.5%</h4>
            <p>If you were to create a grid trade at 10 wUSDC.b per 1 XCH with a 0.5% fee. You would create the following.</p>
            <table class="table">
                <tr>
                    <th>Step</th>
                    <th>Price</th>
                    <th>Bid Price</th>
                    <th>Ask Price</th>
                    <th>Fee Collected Per Trade</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>10.000</td>
                    <td>9.950</td>
                    <td>10.050</td>
                    <td>0.050</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>10.250</td>
                    <td>10.199</td>
                    <td>10.301</td>
                    <td>0.051</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
