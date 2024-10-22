<x-mail::message>
# Hi {{{$user->name}}},

Please pick-up your cards on Wednesday at the bottom of the stairs between 8-8:30 am or between 2:30-3 pm. You
@if(($user->pickupalt))
or your designated alternate ({{{$user->pickupalt}}})
@endif
will need to sign for the cards.

Thank you for your support,<br>
{{ config('app.name') }}
</x-mail::message>
