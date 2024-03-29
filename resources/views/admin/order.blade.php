<x-app-layout>

    <h1>Card Pickup Sheet for {{$date}}</h1>

    <table>
        <tr>
            <th style="width:45%;">Name <br />(Alternate)</th>
            <th style="width:12%;">Save-On</th>
            <th style="width:12%;">Co-op</th>
            <th style="width:31%;">Signature</th>
        </tr>
        @foreach($pickup as $order)
            <tr>
                <td>
                    {{$order->user->name}} - {{$order->user->getPhone()}}<br />
                    
                    @if(!empty($order->user->pickupalt))
                        ({{$order->user->pickupalt}})
                    @else
                        -
                    @endif
                </td>
                <td>{{$order->saveon + $order->saveon_onetime}}</td>
                <td>{{$order->coop + $order->coop_onetime}}</td>
                <td style="border-bottom:1px solid #000;"></td>
            </tr>
        @endforeach
    </table>

    <div style="page-break-before:always;">
        <h1>Card Mailing Sheet for {{$date}}</h1>
    </div>

    <table class='table'>
        <tr>
            <th style="width:25%;">Name</th>
            <th style="width:51%;">Address</th>
            <th style="width:12%;">Save-On</th>
            <th style="width:12%;">Co-op</th>
        </tr>
        @foreach($mail as $order)
            <tr>
                <td>{{$order->user->name}} - ({{$order->user->getPhone()}})</td>
                <td>{{$order->user->address()}}</td>
                <td>{{$order->saveon + $order->saveon_onetime}}</td>
                <td>{{$order->coop + $order->coop_onetime}}</td>
            </tr>
        @endforeach
    </table>
</x-app-layout>