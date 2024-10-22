<x-mail::message>
# Hi {{$user->name}},
Thank you very much for your grocery card order.  This email is to confirm {{{$isChange?"the changes you just made to ":""}}}your order. 

You can change your order at <a href="{{ config('app.url') }}/edit">{{ config('app.url') }}/edit</a>.
Log in with this email address and the password you signed up with. (Forgot your password? You can reset it at the login screen.)</p>

@if($user->saveon + $user->coop > 0 && $user->schedule != 'none')
You are currently ordering<br>
@if($user->coop > 0)<b>${{{$user->coop}}}00 from Kootenay Co-op</b><br>@endif
@if($user->saveon > 0)<b>${{{$user->saveon}}}00 from Save-On</b><br>@endif
<p>You will be charged on <b>{{{$dates['charge']}}}</b>, by <b>{{{$user->isCreditCard()?'credit card':'direct debit'}}}</b> (last 4 digits {{{$user->last_four}}}).</p>
<p>Your cards will be available on <b>{{{$dates['delivery']}}}</b>.</p>
@endif
@if($user->saveon_onetime + $user->coop_onetime > 0 && $user->schedule_onetime != 'none')
You have a <b>one-time</b> order for <br>
@if($user->coop_onetime > 0)<b>${{{$user->coop_onetime}}}00 from Kootenay Co-op</b><br>@endif
@if($user->saveon_onetime > 0)<b>${{{$user->saveon_onetime}}}00 from Save-On</b><br>@endif
<p>You will be charged on <b>{{{$dates[$user->schedule_onetime]['charge']}}}</b>, by <b>{{{$user->isCreditCard()?'credit card':'direct debit'}}}</b> (last 4 digits {{{$user->last_four}}}).</p>
<p>Your cards will be available on <b>{{{$dates[$user->schedule_onetime]['delivery']}}}</b>.</p>
@endif

@if($user->saveon + $user->coop + $user->saveon_onetime + $user->coop_onetime > 0 && ($user->schedule != 'none' || $user->schedule_onetime != 'none'))

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

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
