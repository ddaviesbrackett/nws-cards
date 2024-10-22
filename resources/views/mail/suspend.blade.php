<x-mail::message>
# Hi {{{$user->name}}},

<b>Your grocery card order has been suspended.</b> You will not be charged, and cards won't be delivered to you, until you resume your order.</p>

You can press this button when you're ready for more cards:
<x-mail::button :url="config('app.url') . '/email-resume?uid=' . $user->id . '&code=' . $user->reactivation_code">
Resume My Order
</x-mail::button>

You can also resume your order at <a href="https://grocerycards.nelsonwaldorf.org/Resume">https://grocerycards.nelsonwaldorf.org/Resume</a>.
Log in with this email address and the password you signed up with. (Forgot your password? You can reset it at the login screen.)

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
