<x-mail::message>
# Hi {{{$user->name}}},

Please pick-up your cards on Wednesday at the bottom of the stairs between 8:15-8:45 am or between 3:00-3:30 pm. You
@if(($user->pickupalt))
or your designated alternate ({{{$user->pickupalt}}})
@endif
will need to sign for the cards.

Thank you for your support,<br>
{{ config('app.name') }}
</x-mail::message>
