<x-mail::message>
# Hi {{$user->name}},

You're not currently ordering grocery cards. If you'd like more cards, please order them by
tomorrow, {{{$cutoff->cutoffdate()->format('l, F jS')}}}, at midnight. You can get cards just once, or sign up for a regular order.

Order cards at <a href="{{ config('app.url') }}/edit">{{ config('app.url') }}/edit</a>.
Log in with this email address and the password you signed up with. (Forgot your password? You can reset it at the login screen.) 


<x-mail::button :url="config('app.url') . '/edit'">
Order Cards
</x-mail::button>

Thank you for your support,<br>
{{ config('app.name') }}
</x-mail::message>
