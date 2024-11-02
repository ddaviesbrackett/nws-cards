<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Account
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1>{{$profit}} raised so far</h1>
            @if(! is_null($mostRecentOrder) && $mostRecentOrder->created_at > (new \Carbon\Carbon())->addDays(-8))
                <h2>Your current order</h2>
                <p>The charge date for your current order is <b>{{$mostRecentOrder->cutoffdate->chargedate()->format('l, F jS')}}</b>.  </p>
                <p>Your cards will be available <b>{{$mostRecentOrder->cutoffdate->deliverydate()->diffInDays(\Carbon\Carbon::now('America/Los_Angeles')->startOfDay()) == 0?'today':$mostRecentOrder->cutoffdate->deliverydate()->format('l, F jS')}}</b>.</p>
                @if($mostRecentOrder->deliverymethod)
                    <p>Your cards will be mailed to you that day.  They generally arrive on Thursday or Friday.</p>
                @else
                    <p>You can pick your order up between 8AM and 8:30AM or 2:30PM and 3PM that day, at the bottom of the main stairs.</p>
                @endif
                <hr/>
            @endif

            @if( $user->saveon + $user->coop > 0 )
                <h2>Your recurring order</h2>
                <p>
                    You have a <b style="text-transform:capitalize;">monthly</b> order of<br/>
                    @if($user->coop > 0)
                        <b>${{$user->coop}}00 from Kootenay Co-op</b><br/>
                    @endif
                    @if($user->saveon > 0)
                        <b>${{$user->saveon}}00 from Save-On</b>
                    @endif
                </p>
                <p>You will be charged on <b>{{$dates['charge']}}</b>, by <b>{{$user->isCreditCard()?'credit card':'direct debit'}}</b> (last 4 digits {{$user->last_four}}).</p>
                <p>Your cards will be available on <b>{{$dates['delivery']}}</b>.</p>
            @else
                <p>You have no recurring order. You'll make more money for the school if you order more cards!</p>
            @endif
            @if($user->saveon_onetime + $user->coop_onetime > 0)
                <h2>Onetime order</h2>
                <p>
                    You have a <b style="text-transform:capitalize;">one-time</b> order of<br/>
                    @if($user->coop_onetime > 0)
                        <b>${{$user->coop_onetime}}00 from Kootenay Co-op</b><br/>
                    @endif
                    @if($user->saveon_onetime > 0)
                        <b>${{$user->saveon_onetime}}00 from Save-On</b>
                    @endif
                </p>
                <p>You will be charged on <b>{{$dates['charge']}}</b>, by <b>{{$user->isCreditCard()?'credit card':'direct debit'}}</b> (last 4 digits {{$user->last_four}}).</p>
                <p>Your cards will be available on <b>{{$dates['delivery']}}</b>.</p>
            @endif

            @if($user->saveon + $user->coop + $user->saveon_onetime + $user->coop_onetime > 0)
                <p>
                    Your cards are being
                    @if($user->deliverymethod)
                        <b>mailed to you</b> at<br/>
                        {{$user->name}}<br/>
                            {{$user->address1}}<br/>
                            {{$user->address2}}<br/>
                            {{$user->city}},
                            {{$user->province}}<br/>
                            {{$user->postal_code}}
                    @else
                        <b>picked up at the school</b> by you
                        @if(($user->pickupalt))
                                or by <b>{{$user->pickupalt}}</b>
                        @endif
                    @endif
                </p>
            @endif
            <h2>Your order history</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Cards</th>
                    <th>Class(es)</th>
                </tr>
                @foreach($user->orders as $order)
                    <tr>
                        <td>
                            {{$order->cutoffdate->deliverydate()->format('F jS Y')}}
                        </td>
                        <td>
                            @if($order->saveon + $order->saveon_onetime > 0)
                                {{$order->saveon + $order->saveon_onetime}} Save-On
                            @endif
                            @if($order->coop + $order->coop_onetime > 0)
                                {{$order->coop + $order->coop_onetime}} Co-Op
                            @endif
                        </td>
                        <td>
                            @foreach ($order->schoolclasses as $class)
                                {{$class->name}}: ${{$class->pivot->profit}}<br/>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-app-layout>
