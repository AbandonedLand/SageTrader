<div>
    <div class="card-header">

        <h2>{{$active ? "Active " : "Inactive " }}DCA Bots</h2>
        <p class="text-muted">The bots may take up to 60 seconds run after the next run time is met.</p>
        @if($active)
            <button class="btn btn-danger btn-xs" wire:click="toggleActive">
                Show Inactive
            </button>
        @else
            <button class="btn btn-success btn-xs" wire:click="toggleActive">
                Show Active
            </button>
        @endif

    </div>
    <div class="card-body" wire:poll="updateDCAs" >
        <table class="table" >
            <thead>
            <tr>
                <th>
                    Status
                </th>
                <th>
                    Chia Asset Token
                </th>
                <th>
                    Direction
                </th>
                <th>Amount</th>
                <th>Every</th>
                <th>Restrictions</th>
                <th>Total Amount</th>
                <th>Next Run</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($dcas as $dca)
                <tr wire:key="{{$dca->id}}">
                    <td>
                        <div class="btn-group">
                            @if($dca->is_active)
                                <button class="btn btn-success btn-sm" wire:click="toggleDCAActivation({{$dca->id}})">
                                    Active
                                </button>
                                <button class="btn btn-outline-danger btn-sm"  wire:click="toggleDCAActivation({{$dca->id}})">
                                    Deactivate
                                </button>
                            @else
                                <button class="btn btn-outline-success btn-sm" wire:click="toggleDCAActivation({{$dca->id}})">
                                    Activate
                                </button>
                                <button class="btn btn-danger btn-sm"  wire:click="toggleDCAActivation({{$dca->id}})">
                                    Inactive
                                </button>
                            @endif
                        </div>

                    </td>
                    <td>{{$dca->asset->ticker}}</td>
                    <td>{{$dca->buy_sell}}</td>
                    <td>
                        @if($dca->buy_sell == 'buy')
                            {{number_format(($dca->amount / 1000000000000),12)}}
                        @else
                            {{number_format(($dca->amount / 1000),3)}}
                        @endif
                    </td>
                    <td>{{$dca->buy_frequency}} Minutes</td>
                    <td>
                        @if($dca->price && $dca->price_lt_gt)
                            Market Price {{$dca->price_lt_gt ." ". $dca->price}}
                        @endif

                    </td>
                    <td>
                        @if($dca->buy_sell == 'buy' )
                            @if($dca->max_amount)
                                {{number_format(($dca->current_amount / 1000000000000),12)." / ". number_format(($dca->max_amount / 1000000000000),12) }}
                            @else
                                {{number_format(($dca->current_amount / 1000000000000),12)}}
                            @endif
                        @else
                            @if($dca->max_amount)
                                {{number_format(($dca->current_amount / 1000),3)." / ". number_format(($dca->max_amount / 1000),3) }}
                            @else
                                {{number_format(($dca->current_amount / 1000),3)}}
                            @endif
                        @endif
                    </td>
                    <td>
                        {{\Carbon\Carbon::parse($dca->next_run)->diffForHumans()}}
                    </td>
                    <td>
                        <button class="btn btn-warning btn-xs" wire:click="execute({{$dca->id}})">Run Early</button>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
</div>
