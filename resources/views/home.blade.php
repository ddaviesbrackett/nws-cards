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
        <x-slot:drop>
            <x-dropdown-link href="#about">About</x-dropdown-link>
            <x-dropdown-link href="#faq">FAQ</x-dropdown-link>
            <x-dropdown-link href="#contact">Contact</x-dropdown-link>
        </x-slot:drop>
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
</x-guest-layout>