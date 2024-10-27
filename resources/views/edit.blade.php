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

            <h4>your order</h4>
            <div x-data="{order:'recurring', schedule:'{{ old('schedule', $user->schedule) }}', schedule_onetime:'{{old('schedule_onetime', $user->schedule_onetime)}}'}"> 
                <a @click="order='recurring'" :class="order == 'recurring' && 'bg-red'">Edit your Recurring Order</a>
                <a @click="order='onetime'" :class="order == 'onetime' && 'bg-red'">Edit your One-Time Order</a>
                <div x-cloak x-show="order == 'recurring'">

                    <div>
                        <x-label>
                            Kootenay Co-op:
                            <x-input type="number" id="coop" name="coop" :value="old('coop', $user->coop)" /> x $100
                        </x-label>
                        <x-input-error for="coop" />
                    </div>

                    <div>
                        <x-label>
                            Save-On:
                            <x-input type="number" id="saveon" name="saveon" :value="old('saveon', $user->saveon)" /> x $100
                        </x-label>
                        <x-input-error for="saveon" />
                    </div>

                    <x-label>
                        <x-input type="radio" name="schedule" value="monthly" x-model="schedule"/>
                        Once a month, starting <span class="font-bold">{{$dates['delivery']}}</span>
                    </x-label>
                    <x-label>
                        <x-input type="radio" name="schedule" value="none" x-model="schedule"/>
                        I don't want a recurring order
                    </x-label>
                    <x-input-error for="schedule" />
                </div>

                
                <div x-cloak x-show="order == 'onetime'">
                    <div>
                        <x-label>
                            Kootenay Co-op:
                            <x-input type="number" id="coop_onetime" name="coop_onetime" :value="old('coop_onetime', $user->coop_onetime)" /> x $100
                        </x-label>
                        <x-input-error for="coop_onetime" />
                    </div>

                    <div>
                        <x-label>
                            Save-On:
                            <x-input type="number" id="saveon_onetime" name="saveon_onetime" :value="old('saveon_onetime', $user->saveon_onetime)" /> x $100
                        </x-label>
                        <x-input-error for="saveon_onetime" />
                    </div>

                    
                    <x-label>
                        <x-input type="radio" name="schedule_onetime" value="monthly" x-model="schedule_onetime"/>
                        On <span class="font-bold">{{$dates['delivery']}}</span>
                    </x-label>
                    <x-label>
                        <x-input type="radio" name="schedule_onetime" value="none" x-model="schedule_onetime"/>
                        I don't want a one-time order
                    </x-label>
                    <x-input-error for="schedule_onetime" />
                </div>
            </div>

            <div>
                <x-label>
                    Referring Family
                    <x-input type="text" name="referrer" :value="old('referrer', $user->referrer)" />
                </x-label>
                <x-input-error for="referrer" />
            </div>

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
                        I have read and agree to the <a href="#TODO">terms of the Payor's Personal Pre-Authorized Debit (PAD) Agreement</a>
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
                    Mail to the address above
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

            <div>
                <x-button>Edit order</x-button>
            </div>
        </form>
    </div>
</x-app-layout>