<x-app-layout>

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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit your order
        </h2>
    </x-slot>

    <x-validation-errors />
    <div x-data>
        <form method="POST" action="{{route('postEdit')}}" @submit="formSubmit" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @csrf

            <div x-data="{
                            coop:{{$user->coop_onetime > 0? $user->coop_onetime : $user->coop}}, 
                            saveon:{{$user->saveon_onetime > 0? $user->saveon_onetime : $user->saveon}}, 
                            ordertype:'{{$user->coop_onetime + $user->saveon_onetime > 0 ? 'onetime' : 'monthly'}}'
                        }"> 
                <div>
                    <x-label>
                        Kootenay Co-op:
                        <x-input type="number" id="coop" name="coop" :value="old('coop', $user->coop_onetime > 0? $user->coop_onetime : $user->coop)" x-model="coop"/> x $100
                    </x-label>
                    <x-input-error for="coop" />
                </div>

                <div>
                    <x-label>
                        Save-On:
                        <x-input type="number" id="saveon" name="saveon" :value="old('saveon', $user->saveon_onetime > 0? $user->saveon_onetime : $user->saveon)" x-model="saveon"/> x $100
                    </x-label>
                    <x-input-error for="saveon" />
                </div>
                <div x-cloak x-show='coop + saveon > 0'>
                    <x-label>
                        <x-input type="radio" name="ordertype" value="monthly" x-model="ordertype"/>
                        Once a month during the school year, starting <span class="font-bold">{{$dates['delivery']}}</span>
                    </x-label>
                    <x-label>
                        <x-input type="radio" name="ordertype" value="onetime" x-model="ordertype"/>
                        Just once, on <span class="font-bold">{{$dates['delivery']}}</span>
                    </x-label>
                    <x-input-error for="ordertype" />

                    <h4>Payment</h4>
                    <span class="help-block info">You will be charged 2 business days before delivery.</span>
                    <div x-data="{payment:'keep'}">
                        <x-label>
                            <x-input type="radio" name="payment" value="keep" :checked="old('payment') != 'debit' && old('payment') != 'credit'" x-model="payment"/>
                            Leave payment details unchanged
                        </x-label>
                        <x-label>
                            <x-input type="radio" name="payment" value="debit" x-model="payment"/>
                            Debit (we make more money with debit)
                        </x-label>
                        <x-input-error for="payment" />
                        <div x-cloak x-show="payment == 'debit'">
                            <img src="images/void_cheque.gif" alt="Void Cheque showing location of branch, institution, and account numbers" />
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
                            <x-label>
                                <x-input type="checkbox" name="debit-terms" value="1" :checked="old('debit-terms') == 1" />
                                I have read and agree to the <x-link @click.prevent="document.querySelector('dialog#debit-terms-dialog').showModal()" href="#">terms of the Payor's Personal Pre-Authorized Debit (PAD) Agreement</x-link>
                            </x-label>
                            <x-input-error for="debit-terms" />
                        </div>
                        <x-label>
                            <x-input type="radio" name="payment" value="credit" x-model="payment"/>
                            Credit Card
                        </x-label>
                        <x-input-error for="payment" />
                        <div x-cloak x-show="payment == 'credit'">
                            <p class="text-sm text-red-600 dark:text-red-400" x-ref="payment_error" id="payment_error"></p>
                            <div>
                                <x-label>
                                    Cardholder's Name
                                    <x-input type="text" data-stripe="name" value="" />
                                </x-label>
                            </div>
                            <div>
                                <x-label>
                                    Card Number
                                    <x-input type="text" data-stripe="number" value="" />
                                </x-label>
                            </div>
                            <div>
                                <div>
                                    <x-label>
                                        Exp Month
                                        <x-input type="text" placeholder="MM" data-stripe="exp-month" value="" />
                                    </x-label>
                                </div>
                                <div>
                                    <x-label>
                                        Exp Year
                                        <x-input type="text" placeholder="YYYY" data-stripe="exp-year" value="" />
                                    </x-label>
                                </div>
                                <div>
                                    <x-label>
                                        CVC
                                        <x-input type="text" placeholder="Eg. 331" data-stripe="cvc" value="" />
                                    </x-label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4>Choose Delivery</h4>
                    <div x-data="{delivery:'{{$user->deliverymethod == 1?'mail':'pickup'}}'}">
                        <x-label>
                            <x-input type="radio" name="deliverymethod" value="pickup" x-model="delivery" :checked="old('deliverymethod', $user->deliverymethod == 1?'mail':'pickup') == 'pickup'" />
                            Pickup at the Nelson Waldorf School
                        </x-label>
                        <x-input-error for="deliverymethod" />
                        <div x-cloak x-show="delivery == 'pickup'">
                            You'll have to sign for your cards. If someone else can sign for them, enter their name here.
                            <x-label>
                                Others who can pick up your cards:
                                <x-input type="text" name="pickupalt" :value="old('pickupalt', $user->pickupalt)" />
                            </x-label>
                            <x-input-error for="pickupalt" />
                            <x-label>
                                <x-input type="checkbox" name="employee" value="1" :checked="old('employee', $user->employee) == 1" />
                                I or my alternate am employed by the school
                            </x-label>
                            <x-input-error for="employee" />
                        </div>
                        <x-label>
                            <x-input type="radio" name="deliverymethod" value="mail" x-model="delivery" :checked="old('deliverymethod', $user->deliverymethod == 1?'mail':'pickup') == 'mail'" />
                            Mail to my address on file (<x-link href="{{ route('profile.show') }}">edit your address</x-link>)
                        </x-label>
                        <x-input-error for="deliverymethod" />
                        <div x-cloak x-show="delivery == 'mail'">
                            <x-label>
                                <x-input type="checkbox" name="mailwaiver" value="1" :checked="old('mailwaiver') == 1" />
                                I hereby release NWS PAC of any liability regarding sending my ordered grocery cards by regular mail.
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