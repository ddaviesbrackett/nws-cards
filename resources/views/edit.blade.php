<x-app-layout>
<!-- TODO prettify errors location -->
    @push('scripts')
    <script src="https://js.stripe.com/v2/" async defer></script>
    <script>
        const stripeResponseHandler = function(status, response) {
            const f = document.forms[0];
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
        const formSubmit = function(ev) {
            const f = document.forms[0];
            Stripe.setPublishableKey('{{config("app.stripe_key")}}');
            f.querySelector('#payment_error').textContent = '';
            f.querySelector('button[type="submit"]').disabled = true;
            if (f.querySelector('input[name="payment"][value="credit"]').checked) {
                Stripe.card.createToken(f, stripeResponseHandler);
                ev.preventDefault();
            }
        }
    </script>
    @endpush

    <x-slot name="header">
        Edit your order
    </x-slot>

    <x-validation-errors />
    <div x-data>
        <form method="POST" action="{{route('postEdit')}}" @submit="formSubmit" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @csrf
            <h4 class="text-3xl">Your Order</h4>
            <div x-data="{
                            coop:{{$user->coop_onetime > 0? $user->coop_onetime : $user->coop}}, 
                            saveon:{{$user->saveon_onetime > 0? $user->saveon_onetime : $user->saveon}}, 
                            ordertype:'{{$user->coop_onetime + $user->saveon_onetime > 0 ? 'onetime' : 'monthly'}}'
                        }"> 
                <x-label>
                    Kootenay Co-op:
                    <span class="text-left"><x-input type="number" id="coop" name="coop" :value="old('coop', $user->coop_onetime > 0? $user->coop_onetime : $user->coop)" x-model="coop"/> x $100</span>
                </x-label>
                <x-input-error for="coop" />
                <x-label>
                    Save-On:
                    <span class="text-left"><x-input type="number" id="saveon" name="saveon" :value="old('saveon', $user->saveon_onetime > 0? $user->saveon_onetime : $user->saveon)" x-model="saveon"/> x $100</span>
                </x-label>
                <x-input-error for="saveon" />
                <div x-cloak x-show='coop + saveon > 0'>
                    <x-label>
                        <span></span>
                        <span class="text-left"><x-input type="radio" name="ordertype" value="monthly" x-model="ordertype"/>
                        Once a month during the school year, starting <span class="font-bold">{{$dates['delivery']}}</span></span>
                    </x-label>
                    <x-label>
                        <span></span>
                        <span class="text-left"><x-input type="radio" name="ordertype" value="onetime" x-model="ordertype"/>
                        Just once, on <span class="font-bold">{{$dates['delivery']}}</span></span>
                    </x-label>
                    <x-input-error for="ordertype" />

                    <h4 class="text-3xl mt-6 mb-1">Payment</h4>
                    <div x-data="{payment:'keep'}">
                        <div class="grid grid-cols-3 gap-4 ml-4 mt-2">
                            <label class="text-left inline-block ml-4 mt-2">
                                <x-input type="radio" name="payment" value="keep" :checked="old('payment') != 'debit' && old('payment') != 'credit'" x-model="payment"/>
                                Leave payment details unchanged
                            </label>
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
                        <div x-cloak x-show="payment == 'debit'" class="px-8 py-4">
                            <div class="grid grid-cols-xlabel gap-4">
                                <span></span>
                                <img src="images/void_cheque.gif" alt="Void Cheque showing location of branch, institution, and account numbers" />
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
                        <div x-cloak x-show="payment == 'credit'">
                            <p class="text-sm text-red-600 dark:text-red-400" x-ref="payment_error" id="payment_error"></p>
                            <x-label>
                                Cardholder's Name:
                                <x-input type="text" data-stripe="name" value="" />
                            </x-label>
                            <x-label>
                                Card Number:
                                <x-input type="text" data-stripe="number" value="" />
                            </x-label>
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

                    <h4 class="text-3xl mt-6 mb-1">Choose Delivery</h4>
                    <div x-data="{delivery:'{{$user->deliverymethod == 1?'mail':'pickup'}}'}">
                        <div class="grid grid-cols-2 gap-4 ml-4 mt-2">
                            <label class="text-left inline-block ml-4 mt-2">
                            <x-input type="radio" name="deliverymethod" value="pickup" x-model="delivery" />
                            Pickup at the Nelson Waldorf School
                            </label>
                            <label class="text-left inline-block ml-4 mt-2">
                                <x-input type="radio" name="deliverymethod" value="mail" x-model="delivery"/>
                                Mail to my address on file (<x-link href="{{ route('profile.show') }}">edit your address</x-link>)
                            </label>
                        </div>
                        <x-input-error for="deliverymethod" />
                        <div x-cloak x-show="delivery == 'pickup'" class="px-8 py-4">
                            You'll have to sign for your cards. If someone else can sign for them, enter their name here.
                            <x-label>
                                Others who can pick up your cards:
                                <x-input type="text" name="pickupalt" :value="old('pickupalt', $user->pickupalt)" />
                            </x-label>
                            <x-input-error for="pickupalt" />
                            <x-label>
                                <span class="col-span-2"><x-input type="checkbox" name="employee" value="1" :checked="old('employee', $user->employee) == 1" />
                                I or my alternate am employed by the school</span>
                            </x-label>
                            <x-input-error for="employee" />
                        </div>
                        <div x-cloak x-show="delivery == 'mail'" class="px-8 py-4">
                            <x-label>
                                <span class="col-span-2"><x-input type="checkbox" name="mailwaiver" value="1" :checked="old('mailwaiver') == 1" />
                                I hereby release NWS PAC of any liability regarding sending my ordered grocery cards by regular mail.</span>
                            </x-label>
                            <x-input-error for="mailwaiver" />
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <x-button>Edit order</x-button>
            </div>
        </form>
    </div>
    <dialog id="debit-terms-dialog" class="border-solid border-2 border-gray-700 dark:border-gray-300 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 shadow px-4 py-4" x-data>
        <x-debit-terms />
        <x-button type="button" @click.prevent="document.querySelector('dialog#debit-terms-dialog').close()">
            OK
        </x-button>
    </dialog>
</x-app-layout>