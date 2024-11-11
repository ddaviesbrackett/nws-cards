<x-guest-layout>
<!-- TODO prettify errors location -->
    @if(\App\Http\Controllers\OrderController::IsBlackoutPeriod())
    <div class="mx-auto text-center">  
        <h1 class="text-3xl">Orders are being processed</h1>
        <br><span><b>Unfortunately, you can't order now while we process orders.<br>
        You will be able to make changes again from the next pick-up Wednesday until the following order deadline.</b></span>
    </div>
    @else
    @push('scripts')
    <script src="https://js.stripe.com/v2/" async defer></script>
    <script>
        const makeStripeResponseHandler = function(f){
            return function(status, response) {
                if (response.error) {
                    f.querySelector('#payment_error').textContent = response.error.message;
                    f.querySelector('button[type="submit"]').disabled = false;
                } else {
                    // response contains id and card, which contains additional card details
                    var token = response.id;

                    // Insert the token into the form so it gets submitted to the server
                    const tokenInput = document.createElement('input');
                    tokenInput.type = "hidden";
                    tokenInput.name = "stripeToken";
                    tokenInput.value = token;

                    f.appendChild(tokenInput);
                    // and submit
                    f.submit();
                }
            };
        };
        const formSubmit = function(ev) {
            const f = ev.target;
            Stripe.setPublishableKey('{{config("app.stripe_key")}}');
            f.querySelector('#payment_error').textContent = '';
            f.querySelector('button[type="submit"]').disabled = true;
            if (f.querySelector('input[name="payment"][value="credit"]').checked) {
                Stripe.card.createToken(f, makeStripeResponseHandler(f));
                ev.preventDefault();
            }
        }
    </script>
    @endpush
    <x-slot name="header">
        New Order
    </x-slot>

    <x-validation-errors />
    <div x-data class="max-w-5xl pl-12">
        <form method="POST" action="{{ route('register') }}" @submit="formSubmit">
            @csrf
            <h4 class="text-3xl">Your Order</h4>
            <div class="" x-data="{ordertype:'{{old('ordertype', 'monthly')}}',
                        saveon:{{old('saveon', 0)}},
                        coop:{{old('coop', 0)}}}">
                <x-label>
                    Kootenay Co-op:
                    <span class="text-left"><x-input class="w-24" type="number" id="coop" name="coop" x-model="coop" /> x $100</span>
                </x-label>
                <x-input-error for="coop" />
                <x-label>
                    Save-On:
                    <span class="text-left"><x-input class="w-24" type="number" id="saveon" name="saveon" x-model="saveon" /> x $100</span>
                </x-label>
                <x-input-error for="saveon" />
                <x-label class="mt-2">
                    <span></span>
                    <span class="text-left"><x-input type="radio" name="ordertype" value="monthly" x-model="ordertype" />
                    Once a month, starting <span class="font-bold">{{$dates['delivery']}}</span></span>
                </x-label>
                <x-label>
                    <span></span>
                    <span class="text-left"><x-input type="radio" name="ordertype" value="onetime" x-model="ordertype" />
                    Just once, on <span class="font-bold">{{$dates['delivery']}}</span></span>
                </x-label>
                <x-input-error for="ordertype" />
            </div>
            <h4 class="text-3xl mt-6 mb-1">Your Details</h4>
            <x-label>
                Name:
                <x-input id="name" type="text" name="name" :value="old('name')" required autocomplete="name" />
            </x-label>
            <x-input-error for="name" />
            <x-label>
                Email:
                <x-input id="email" type="email" name="email" :value="old('email')" required />
            </x-label>
            <x-input-error for="email" />
            <x-label>
                Phone Numer:
                <x-input id="phone" type="tel" name="phone" :value="old('phone')" required placeholder="(250) 555-5555" />
            </x-label>
            <x-input-error for="phone" />
            <x-label>
                Address:
                <x-input id="address1" type="text" name="address1" :value="old('address1')" placeholder="your mailing address" />
            </x-label>
            <x-input-error for="address1" />
            <x-label>
                Address 2:
                <x-input id="address2" type="text" name="address2" :value="old('address2')" />
            </x-label>
            <x-input-error for="address2" />
            <x-label>
                City:
                <x-input id="city" type="text" name="city" :value="old('city')" placeholder="Nelson? Ymir? Salmo? Slocan?" />
            </x-label>
            <x-input-error for="city" />
            <x-label>
                Postal Code:
                <x-input id="postal_code" type="text" name="postal_code" :value="old('postal_code')" placeholder="V1A 1A1" />
            </x-label>
            <x-input-error for="postal_code" />
            <x-label>
                Password:
                <x-input id="password" type="password" name="password" required autocomplete="new-password" />
            </x-label>
            <x-input-error for="password" />
            <x-label>
                Confirm Password:
                <x-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            </x-label>
            <x-input-error for="password_confirmation" />

            <h4 class="text-3xl mt-6 mb-1">Payment</h4>
            <div x-data="{payment:'debit'}">
                <div class="grid grid-cols-2 gap-4 ml-4 mt-2">
                    <label class="text-left inline-block ml-4 mt-2">
                        <x-input type="radio" name="payment" value="debit" :checked="old('payment') == 'debit'" x-model="payment" />
                        Debit (we make more money with debit)
                    </label>
                    <label class="text-left inline-block ml-4 mt-2">
                    <x-input type="radio" name="payment" value="credit" :checked="old('payment') == 'credit'" x-model="payment" />
                        Credit Card
                    </label>
                </div>
                <x-input-error for="payment" />
                <div x-show="payment == 'debit'" class="px-8 py-4">
                    <div class="grid grid-cols-xlabel gap-4">
                        <span></span>
                        <img class="pb-2" src="images/void_cheque.gif" alt="Void Cheque showing location of branch, institution, and account numbers" />
                    </div>
                    <x-label>
                        Branch Number:
                        <x-input type="text" name="debit-transit" :value="old('debit-transit')" />
                    </x-label>
                    <x-input-error for="debit-transit" />
                    <x-label>
                        Institution Number:
                        <x-input type="text" name="debit-institution" :value="old('debit-institution')" />
                    </x-label>
                    <x-input-error for="debit-institution" />
                    <x-label>
                        Account Number:
                        <x-input type="text" name="debit-account" :value="old('debit-account')" />
                    </x-label>
                    <x-input-error for="debit-account" />
                    <div class="grid grid-cols-xlabel gap-4 my-4">
                        <span></span>
                        <span>You will be charged 2 business days before delivery.</span>
                    </div>
                    <x-label>
                        <span></span>
                        <span class="text-left"><x-input type="checkbox" name="debit-terms" value="1" :checked="old('debit-terms') == 1" />
                        I have read and agree to the <x-link @click.prevent="document.querySelector('dialog#debit-terms-dialog').showModal()" href="#">terms of the Payor's Personal Pre-Authorized Debit (PAD) Agreement</x-link></span>
                    </x-label>
                    <x-input-error for="debit-terms" />
                </div>
                <div x-cloak x-show="payment == 'credit'" class="px-8 py-4">
                    <p class="text-sm text-red-600 dark:text-red-400" x-ref="payment_error" id="payment_error"></p>
                    <x-label>
                        Cardholder's Name:
                        <x-input type="text" data-stripe="name" value="" />
                    </x-label>
                    <x-label>
                        Card Number:
                        <x-input type="text" data-stripe="number" value="" />
                    </x-label>
                    <div>
                    <x-label>
                        Exp Month:
                        <x-input class="w-24" type="text" placeholder="MM" data-stripe="exp-month" value="" />
                    </x-label>
                    <x-label>
                        Exp Year:
                        <x-input class="w-24" type="text" placeholder="YYYY" data-stripe="exp-year" value="" />
                    </x-label>
                    <x-label>
                        CVC:
                        <x-input class="w-24" type="text" placeholder="Eg. 331" data-stripe="cvc" value="" />
                    </x-label>
                    <div class="grid grid-cols-xlabel gap-4 my-4">
                        <span></span>
                        <span>You will be charged 2 business days before delivery.</span>
                    </div>
                </div>
            </div>

            <h4 class="text-3xl mt-6 mb-1">Delivery</h4>
            <div x-data="{delivery:'{{old('deliverymethod','pickup')}}'}">
                <div class="grid grid-cols-2 gap-4 ml-4 mt-2">
                    <label class="text-left inline-block ml-4 mt-2">
                        <x-input type="radio" name="deliverymethod" value="pickup" x-model="delivery" />
                        Pickup at the Nelson Waldorf School
                    </label>
                    <label class="text-left inline-block ml-4 mt-2">
                        <x-input type="radio" name="deliverymethod" value="mail" x-model="delivery" />
                        Mail to the address above
                    </label>
                </div>
                <x-input-error for="deliverymethod" />
                <div x-cloak x-show="delivery == 'pickup'" class="px-8 py-4">
                    You'll have to pick up your cards. If someone else can pick them up, enter their name here.
                    <x-label>
                        Others who can pick up your cards:
                        <x-input type="text" name="pickupalt" :value="old('pickupalt')" />
                    </x-label>
                    <x-input-error for="pickupalt" />
                    <x-label>
                        <span class="col-span-2"><x-input type="checkbox" name="employee" value="1" :checked="old('employee') == 1" />
                        I or my alternate am employed by the school</span>
                    </x-label>
                    <x-input-error for="employee" />
                </div>
                <x-input-error for="deliverymethod" />
                    <div x-cloak x-show="delivery == 'mail'" class="px-8 py-4">
                    <x-label>
                        <span class="col-span-2"><x-input type="checkbox" name="mailwaiver" value="1" :checked="old('mailwaiver') == 1" />
                        I hereby release NWS PAC of any liability regarding sending my ordered grocery cards by regular mail.</span>
                    </x-label>
                    <x-input-error for="mailwaiver" />
                </div>
            </div>

            <div class="py-4">
                <x-link href="{{ route('login') }}">
                    Already have an account?
                </x-link>

                <x-button class="ml-4 text-3xl">
                    Sign me up!
                </x-button>
            </div>
        </form>
    </div>
    <dialog id="debit-terms-dialog" class="border-solid border-2 border-gray-700 dark:border-gray-300 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 shadow px-4 py-4" x-data>
        <x-debit-terms />
        <x-button type="button" @click.prevent="document.querySelector('dialog#debit-terms-dialog').close()">
            OK
        </x-button>
    </dialog>
    @push('latescripts')
    <script>
        //
    </script>
    @endpush
    @endif
</x-guest-layout>