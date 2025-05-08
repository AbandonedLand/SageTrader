<div>
    <div class="row " wire:poll="refresh">
        <div class="col-8 offset-2 mt-4 ">
            <div class="card">
                <div class="card-header">
                    <h1>Order: {{$order->id}}</h1>
                </div>
                <div class="card-body">
                    <table class="table no-border">
                        <tr>
                            <th>Offered:</th>
                            <td>{{$order->offeredDisplayAmount()." ".$order->offered_code}}</td>
                        </tr>
                        <tr>
                            <th>Requested:</th>
                            <td>{{$order->requestedDisplayAmount()." ".$order->requested_code}}</td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td>{{$order->price}}</td>
                        </tr>
                        @if($order->dexie_id)
                        <tr>
                            <th>Dexie:</th>
                            <td><a href="https://dexie.space/offers/{{$order->dexie_id}}" target="_blank">{{$order->dexie_id}}</a></td>
                        </tr>
                            <tr>
                                <th>Status:</th>
                                <td>@if($order->is_filled) Filled @else Unfilled @endif </td>
                            </tr>
                        @endif

                    </table>
                </div>
                @if(!$order->is_created)
                <div class="card-footer">
                    <button class="btn btn-danger" wire:click="cancel">Cancel</button>
                    <span class="float-right"><button class="btn btn-success" wire:click="confirm">Confirm</button></span>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($order->log)
        <div class="card mt-4 col-8 offset-2">
            <div class="card-header">
                <h1>Logs</h1>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Message</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->log as $log)
                        <tr>
                            <td>
                                {{\Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s')}}
                            </td>
                            <td>{{$log->message}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
