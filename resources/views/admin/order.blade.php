<x-app-layout>

    <h1 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">Card Pickup Sheet for {{$date}}</h1>

    <table class="ml-10 mr-6">
        <tr>
            <th class="px-2 text-left">Name (Alternate)</th>
            <th class="px-2 text-right">Save-On</th>
            <th class="px-2 text-right">Co-op</th>
            <th class="px-2 w-10"></th>
        </tr>
        @foreach($pickup as $order)
            <tr>
                <td class="px-2">
                    {{$order->user->name}} - {{$order->user->getPhone()}}
                    @if(!empty($order->user->pickupalt))
                        ({{$order->user->pickupalt}})
                    @endif
                </td>
                <td class="px-2 text-right">{{$order->saveon + $order->saveon_onetime}}</td>
                <td class="px-2 text-right">{{$order->coop + $order->coop_onetime}}</td>
                <td class="px-2"></td>
            </tr>
        @endforeach
    </table>

    <div style="page-break-before:always;">
        <h1 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">Card Mailing Sheet for {{$date}}</h1>
    </div>

    <table class="ml-10 mr-6">
        <tr>
            <th class="px-2 text-left" style="width:25%;">Name</th>
            <th class="px-2 text-left" style="width:51%;">Address</th>
            <th class="px-2 text-right" style="width:12%;">Save-On</th>
            <th class="px-2 text-right" style="width:12%;">Co-op</th>
        </tr>
        @foreach($mail as $order)
            <tr>
                <td class="px-2">{{$order->user->name}} - ({{$order->user->getPhone()}})</td>
                <td class="px-2">{{$order->user->address()}}</td>
                <td class="px-2 text-right">{{$order->saveon + $order->saveon_onetime}}</td>
                <td class="px-2 text-right">{{$order->coop + $order->coop_onetime}}</td>
            </tr>
        @endforeach
    </table>
</x-app-layout>