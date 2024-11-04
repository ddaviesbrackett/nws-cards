<x-guest-layout>
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
    <div class="mx-auto text-center">
        <img class="mx-auto" src="/images/logo.png">
        <h1 class="text-3xl">Buy Grocery Cards<br/>Raise Money</h1>
            
        <h3 class="text-xl">
            This year we've raised {{$totalThisYear}}<br/>
            From July 2014 we've raised {{$total}}<br/>
            Help us raise more &mdash; <em>without spending any extra!</em>
        </h3>
        <br>
        <img class="mx-auto" src="/images/saveon-coop.png" title="Save On Foods and Coop">
        <p>Generously supported by our store partners The Kootenay Co-Op and Save-On Foods</p>
        
        @if(!$isBlackout)
            <br><span><b>Unfortunately, you can't order now while we process orders.<br>You will be able to make changes again from the next pick-up Wednesday until the following order deadline.</b></span>
        @else
            <div><x-button type="button"><a href="/register">Order Now</a></x-button></div>
            <x-link href="/account">(Checking on an existing order? Click here)</x-link>
        @endif 
        
    </div>
    <a name="about"></a>
    <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">About the fundraiser</h2>
    <p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras elit augue, aliquet ut velit eu, tincidunt iaculis odio. Sed rutrum leo non quam vulputate hendrerit. Quisque nibh purus, mattis eget viverra non, dignissim eget quam. Cras at nisl mollis, malesuada lorem ut, suscipit sapien. Quisque pellentesque ut dolor ac tincidunt. Ut porta, nisi eget efficitur pulvinar, est odio consectetur mi, id interdum nisl erat eu velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus et enim et justo lobortis euismod. Maecenas ut odio interdum, laoreet orci ac, hendrerit ex. Donec scelerisque tempus ex eget porttitor. Etiam iaculis accumsan ligula, eget pharetra leo ultricies sed. Donec tempus sapien vel lobortis feugiat. Vestibulum lorem velit, porttitor non nunc et, placerat eleifend nunc. Aenean et sagittis metus. Nulla leo ligula, rhoncus placerat lacus et, interdum varius magna. Nulla facilisi.
</p><p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque id porttitor mi. Integer gravida eu eros sed placerat. Vivamus hendrerit pulvinar semper. Ut ornare ultricies quam eget venenatis. Vestibulum vitae eleifend ligula, vel tincidunt turpis. Nulla ac nibh ipsum. In volutpat egestas ligula ac pulvinar. Praesent maximus turpis nisl. Mauris quis dolor et quam tristique porta in et tortor. Vestibulum eleifend condimentum mauris, eu elementum diam dapibus sed. Sed nec turpis vel nisi ullamcorper facilisis. Curabitur eget est non mi pretium accumsan. </p>
    <a name="faq"></a>
    <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">Frequently Asked Questions</h2>
    <p></p>
    <a name="contact"></a>
    <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">Got another question? We've got answers</h2>
    <div class="max-w-3xl">
        <p>
            We'd love to hear from you.
        </p>
        <p>
            If you have a question or a concern about your order, you can reach us at grocerycards{at}nelsonwaldorf.org or use the form below.
        </p>
        <form action="/contact" method="POST" class="mb-3">
            @csrf
            <x-label>
                Name
                <x-input type="text" name="nm" id="nm" placeholder="your name"/>
            </x-label>
            <x-label>
                Email Address
                <x-input type="text" name="em" id="em" placeholder="your email address"/>
            </x-label>
            <x-label>
                Message
                <textarea name="msg" placeholder="your message" rows="6"></textarea>
            </x-label>
            <x-button type="submit">Send</x-button>
        </form>
        <p>    We will get back to you as soon as possible – usually the same day, but definitely within two business days.</p>
        <p>
            Please do not phone the Nelson Waldorf School unless it has been much longer than that. 
            This fundraiser is being run by the Parent Council, not the school administration.
        </p>
        <p>
            Thanks for your understanding. 
        </p>
    </div>
</x-guest-layout>