<x-mail::message>
# Hi {{$user->name}},

This is a reminder that you'll be charged for your grocery card order {{$order->cutoffdate->chargedate()->format('l, F jS')}}.

You have ordered<br>
@if($order->coop + $order->coop_onetime > 0)<b>${{$order->coop + $order->coop_onetime}}00 from Kootenay Co-op</b><br>@endif
@if($order->saveon + $order->saveon_onetime > 0)<b>${{$order->saveon + $order->saveon_onetime}}00 from Save-On</b><br>@endif

On {{$order->cutoffdate->deliverydate()->format('l, F jS')}}, your cards will be
@if($user->deliverymethod)
<b>mailed to you</b> at<br>
{{$user->name}}<br>
{{$user->address1}}<br>
{{$user->address2?$user->address2 + '<br>':''}}
{{$user->city}},
{{$user->province}}<br>
{{$user->postal_code}}
@else
<b>available to be picked up at the school</b>@if($user->pickupalt)
 by you or by <b>{{$user->pickupalt}}</b>
@endif.
@endif

Thank you for your support,<br>
{{ config('app.name') }}
</x-mail::message>
