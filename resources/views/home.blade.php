<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Home
        </h2>
    </x-slot>
    <x-slot name="nav">
        <x-nav-link href="#about">About</x-nav-link>
        <x-nav-link href="#faq">FAQ</x-nav-link>
        <x-nav-link href="#contact">Contact</x-nav-link>
        <x-slot:responsive>
            <x-responsive-nav-link href="#about">About</x-responsive-nav-link>
            <x-responsive-nav-link href="#faq">FAQ</x-responsive-nav-link>
            <x-responsive-nav-link href="#contact">Contact</x-responsive-nav-link>
        </x-slot:responsive>
    </x-slot>
    <div>
        <img src="/images/logo.png">
        <h1>Buy Grocery Cards<br/>Raise Money</h1>
            
        <h3>
            This year we've raised {{$totalThisYear}}<br/>
            From July 2014 we've raised {{$total}}<br/>
            Help us raise more &mdash; <em>without spending any extra!</em>
        </h3>
        <br>
        <img src="https://grocerycards.nelsonwaldorf.org/images/saveon-coop.png" title="Save On Foods and Coop">
        
        <br>
            
        <p>Generously supported by our store partners The Kootenay Co-Op and Save-On Foods</p>
        
        @auth
            <a href="/edit">Change Order</a>
        @else
            <a href="/new">Order Now</a>
        @endauth
        <a href="/account">Checking on an existing order? Click here</a>

            @if($isBlackout)
            <br><span><b>Unfortunately, you can't order now while we process orders.<br>You will be able to make changes again from the next pick-up Wednesday until the following order deadline.</b></span>
            @endif 
    </div>
    <a name="about"></a>
    <a name="faq"></a>
    <a name="contact"></a>
    <h2>Got another question? We've got answers</h2>
	<p>
		We'd love to hear from you.
	</p>
	<p>
		If you have a question or a concern about your order, you can reach us at nwsgrocerycards{AT}gmail.com or use the form below.
	</p>
    <form action="/contact" method="POST">
        @csrf
        <label>
            Name
            <x-input type="text" name="nm" id="nm" placeholder="your name"/>
        </label>
        <label>
            Email Address
            <x-input type="text" name="em" id="em" placeholder="your email address"/>
        </label>
        <label>
            Message
            <textarea name="msg" placeholder="your message" rows="6"></textarea>
        </label>
        <x-button type="submit">Send</x-button>
    </form>
    <p>	We will get back to you as soon as possible – usually the same day, but definitely within two business days.</p>
	<p>
		Please do not phone the Nelson Waldorf School unless it has been much longer than that. 
		This fundraiser is being run by the Parent Council, not the school administration.
	</p>
	<p>
		Thanks for your understanding. 
	</p>
</x-guest-layout>