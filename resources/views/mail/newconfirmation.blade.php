<x-mail::message>
# Hi {{$user->name}},
Thank you very much for your grocery card order.  This email is to confirm {{$isChange?"the changes you just made to ":""}}your order{{$url != ''?" and to verify your email address":""}}. 
@if($url != '')
Please click the button below to verify your email address. 

<x-mail::button :url="$url">
Verify Email Address
</x-mail::button>

If you did not make an order or update your email address, no further action is required.
@endif

You can change your order at <a href="{{ config('app.url') }}/edit">{{ config('app.url') }}/edit</a>.
Log in with this email address and the password you signed up with. (Forgot your password? You can reset it at the login screen.)

@if($user->saveon + $user->coop > 0)
You are currently ordering<br>
@if($user->coop > 0)<b>${{{$user->coop}}}00 from Kootenay Co-op</b><br>@endif
@if($user->saveon > 0)<b>${{{$user->saveon}}}00 from Save-On</b><br>@endif
<p>You will be charged on <b>{{{$dates['charge']}}}</b>, by <b>{{{$user->isCreditCard()?'credit card':'direct debit'}}}</b> (last 4 digits {{{$user->last_four}}}).</p>
<p>Your cards will be available on <b>{{{$dates['delivery']}}}</b>.</p>
@else
You don't have a recurring order.

@endif
@if($user->saveon_onetime + $user->coop_onetime > 0)
You have a <b>one-time</b> order for <br>
@if($user->coop_onetime > 0)<b>${{{$user->coop_onetime}}}00 from Kootenay Co-op</b><br>@endif
@if($user->saveon_onetime > 0)<b>${{{$user->saveon_onetime}}}00 from Save-On</b><br>@endif
<p>You will be charged on <b>{{{$dates['charge']}}}</b>, by <b>{{{$user->isCreditCard()?'credit card':'direct debit'}}}</b> (last 4 digits {{{$user->last_four}}}).</p>
<p>Your cards will be available on <b>{{{$dates['delivery']}}}</b>.</p>
@else
You don't have a one-time order.

@endif

@if($user->saveon + $user->coop + $user->saveon_onetime + $user->coop_onetime > 0)

Your cards will be
@if($user->deliverymethod)
<b>mailed to you</b> at<br>
{{{$user->name}}}<br>
{{{$user->address1}}}<br>
{{{$user->address2?$user->address2 + '<br>':''}}}
{{{$user->city}}},
{{{$user->province}}}<br>
{{{$user->postal_code}}}
@else
<b>available to be picked up at the school</b>@if(($user->pickupalt))
by you or by <b>{{{$user->pickupalt}}}</b>
@endif.
@endif
@endif

@if(!$user->isCreditCard() && 
    $user->saveon + $user->coop + $user->saveon_onetime + $user->coop_onetime >  0)
The terms of your debit agreement with the school are attached to this email.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
