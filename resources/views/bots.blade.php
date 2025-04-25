@extends('app')

@section('content')
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a>BOTS</a></li>
            <li><a>view</a></li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <a href="/bot/create" class="btn btn-accent">Create a bot</a>
        </div>

        <div class="col-span-12">
            <table class="table bg-white mx-auto my-auto">
                <thead>
                    <tr>
                        <th>Bot Type</th>
                        <th>Offered</th>
                        <th>Requested</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Rules</th>

                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-green-200">
                        <td>DCA-Standard</td>
                        <td>[1.00] wUSDC.b</td>
                        <td>[market] XCH</td>
                        <td>Every 90 minutes</td>
                        <td>Running</td>
                        <td>Price is below 15.000 (wUSDC.b/XCH)</td>

                        <td class="flex justify-between gap-1">
                            <a href="/bot/id/" class="btn btn-sm btn-info">View</a>
                            <a href="/bot/id/" class="btn btn-sm btn-error">Disable</a>
                        </td>

                    </tr>
                    <tr class="bg-yellow-100">
                        <td>DCA-Standard</td>
                        <td>[0.05] XCH</td>
                        <td>[market] wUSDC.b</td>
                        <td>Every 90 minutes</td>
                        <td>Running (Out of Range)</td>
                        <td>Price is above 15.000 (wUSDC.b/XCH)</td>

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
