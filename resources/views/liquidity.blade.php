@extends('app')

@section('content')
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a>Liquidity</a></li>
            <li><a>view</a></li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <a href="/liquidity/create" class="btn btn-accent">Provide Liquidity</a>
        </div>

        <div class="col-span-12">
            <table class="table bg-white mx-auto my-auto">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Trading Pair</th>
                        <th>Starting Value</th>
                        <th>Current Value</th>
                        <th>Earnings</th>
                        <th>Range?</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tibet</td>
                        <td>wUSDC.b / XCH</td>
                        <td>15.000 / 1.339</td>
                        <td>15.01 / 1.340</td>
                        <td>0.01 / 0.001</td>
                        <td>NA</td>
                        <td class="flex justify-between gap-1">
                            <a href="/bot/id/" class="btn btn-sm btn-info">View</a>
                            <a href="/bot/id/" class="btn btn-sm btn-error">Disable</a>
                        </td>

                    </tr>
                    <tr>
                        <td>UniswapV3 Alg</td>
                        <td>wUSDC.b / XCH</td>
                        <td>300.000 / 35.0</td>
                        <td>228.50 / 41.20</td>
                        <td>-71.5 / 6.2</td>
                        <td>10.00-14.00</td>
                        <td class="flex justify-between gap-1">
                            <a href="/bot/id/" class="btn btn-sm btn-info">View</a>
                            <a href="/bot/id/" class="btn btn-sm btn-error">Disable</a>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>


@endsection
