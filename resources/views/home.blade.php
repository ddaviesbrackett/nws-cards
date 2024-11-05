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
            @if($totalThisYear != "$0.00") 
            This year we've raised {{$totalThisYear}}<br/>
            @endif
            From July 2014 we've raised {{$total}}<br/>
            Help us raise more &mdash; <em>without spending any extra!</em>
        </h3>
        <br>
        <img class="mx-auto" src="/images/saveon-coop.png" title="Save On Foods and Coop">
        <p>Generously supported by our store partners The Kootenay Co-Op and Save-On Foods</p>
        
        @if($isBlackout)
            <br><span><b>Unfortunately, you can't order now while we process orders.<br>You will be able to make changes again from the next pick-up Wednesday until the following order deadline.</b></span>
        @else
            <div><x-button type="button"><a href="/register">Order Now</a></x-button></div>
            <x-link href="/account">(Checking on an existing order? Click here)</x-link>
        @endif 
        
    </div>
    <a name="about"></a>
    <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">All School Support and Enhancement</h2>
    <p>
        The Nelson Waldorf School Parent Council runs this school-wide grocery card fundraiser to help classes raise 
        money for their activities (like class trips or class play support) and to raise money for all school support and enhancement, 
        such as playgrounds and buildings maintenance and improvement, teachers' professional development and tuition assistance. 	 	
        Your grocery card order helps keep the Nelson Waldorf School vibrant and diverse. 	
        If you would like to donate to the school directly and receive a tax receipt:<br>
        <x-link href="http://www.canadahelps.org/CharityProfilePage.aspx?CharityID=d39625">click here to donate through CanadaHelps.org</x-link>.
    </p>
    <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">How we raise money from your groceries</h2>
    <p>
        When you purchase a grocery card through the Nelson Waldorf Parent Council, the store donates 7% of the card value to us. 
        So on each $100 card you buy, we make $7 if you pay by direct debit, and about $4.5 if you pay by credit card...
        We raise money and it doesn’t cost you a cent – the money comes out of the store’s pocket.
    </p>
    <a name="faq"></a>
    <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">Frequently Asked Questions</h2>
    <div class="px-8 py-4">
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Where does the money go?</h3>
        <p>
            The funds are being distributed between the classes (70%) and all school support and enhancement (30%) which is administered by Parent Council.  All of the profits will be held and managed by the Parent Council until donated to the school or distributed to an individual class.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">What stores can I buy cards from?</h3>
        <p>
            We are currently selling cards from the Kootenay Co-Op and Save-On Foods. 
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">How can I pay?</h3>
        <p>
            You can pay by direct debit or credit card. However because of the fees associated with using a credit card, we do not receive the full amount of what you are charged. This means your order raises less money than the same order paid with direct debit.
            But that doesn’t mean it’s always better to pay with debit. If paying by credit card allows you to make a larger order, then credit card is better. The larger the order, the more money is raised.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">When do I get charged?</h3>
        <p>
            Regardless of your method of payment, you will be charged 2 business days before your cards are available. If there is a problem with your payment, we will contact you. Credit card orders can be retried – or you may e-transfer or bring cash to pick-up. Debit orders cannot be retried and can only be picked up with cash or e-transfer.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">How do I get my cards?</h3>
        <p>
            Cards can be picked up at the Nelson Waldorf School or mailed to you.
            We will email you at the beginning of the week to remind you that cards will be ready for pick up on Wednesday. If you choose to have your cards mailed, they will be mailed on Wednesday following your charge date. We are not responsible for cards lost in the mail, but we haven’t had any issues with this since the fundraiser began.  
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">When do I pick up my cards?</h3>
        <p>
            You can pick up your cards on the Wednesday following your charge date (see homepage for list of charge dates and pickup dates).  We will be at the bottom of the main stairs between 8:15 – 8.45am and 2.45 – 3:15pm under the grocery cards banner.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Can someone else pick up my cards for me?</h3>
        <p>
            Yep. Just let us know who the other authorized person is by putting their name in the box that appears when you select ‘pick up’ on the order form.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">When do I order?</h3>
        <p>
            You can put in an order or change your current order anytime before Tuesday midnight the week before the next Wednesday pick-up. If you can't order now (while we process orders), you will be able to make changes again from the next Wednesday pick-up until the following order deadline.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">How do I change or cancel my order?</h3>
        <p>
            You can change an existing order with <x-link href="/edit">this form</x-link>. You have until Tuesday midnight the week before the next pick-up-Wednesday to change your order. Please note that you can't change your order while orders are being processed, but the form will become available again the Wednesday of delivery.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">How long is my order good for?</h3>
        <p>
            Your order is good for the whole school year. We’ll take a break over the summer holidays - so you’ll get no cards in July and August. With the start of the new school year your order will resume automatically with the amounts you’ve ordered - so you’ll get your ordered cards again in September.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Do I have to make an order - can I just buy cards when I need them?</h3>
        <p>
            Because we receive such a small amount for each card sold, the best way to raise money is to have recurring orders. However, we recognize that not everyone can make a monthly order.  If you need cards between orders you can buy them at the school office (e-transfer only) or by contacting grocerycards@nelsonwaldorf.org.  
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">How does the class get the money they have raised?</h3>
        <p>
            Your class teacher and class rep can request money from us when it’s needed. Requests can be made using <x-link href="#contact">this form</x-link>. Completed forms can be left at the School office or handed to one of the Grocery Card fairies (at pick-up-Wednesday).
            Because this fundraiser requires a large amount of cash to float each purchase of cards, all the money raised will be held by the Parent Council and used for that purpose until the class needs it.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">How do we know how much money the class has raised?</h3>
        <p>
            You can see how much each class has raised on the <x-link href="/tracking/leaderboard">main tracking page</x-link>. Each month the 70% raised for the classes will be divided among classes 1-9 in accordance with class size for them to use for class trips.  The other 30% will be held by parent council and allocated for all school support as needed.  The childcare and kinder programs can request money from all school support fund as needed. 
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Why can I no longer support my child's class directly?</h3>
        <p>
            We have decided to allocate the money by class size to simplify the process, to make the system more equitable, and so that all cards sold are benefiting the classes as well as parent council.  The more we can use this system to buy groceries, the more we help keep our school vibrant and diverse.
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">I can't make a regular order - how can I participate?</h3>
        <p>
            You can make a one-time-order, you can buy cards from the school office (e-transfer only) or by contacting grocerycards@nelsonwaldorf.org.  You can sign up other people to buy grocery cards (friends, neighbours, family members) You can buy cards for cash on pick-up Wednesdays. 
        </p>
        <h3 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Can I just donate?</h3>
        <p>
            Sure. You can make a tax-deductible donation to the Nelson Waldorf School – <x-link href="http://www.canadahelps.org/CharityProfilePage.aspx?CharityID=d39625">click here to donate through CanadaHelps.org</x-link>.
        </p>
    </div>
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