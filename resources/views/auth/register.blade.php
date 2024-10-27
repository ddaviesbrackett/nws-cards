<x-guest-layout>

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
            New Order
        </h2>
    </x-slot>

    <x-validation-errors />
    <div x-data class="px-12">
        <form method="POST" action="{{ route('register') }}" @submit="formSubmit">
            @csrf
            <div x-data="{order:'recurring'}">
                <a @click="order='recurring'" :class="order == 'recurring' && 'bg-red'">Make a Recurring Order</a>
                <a @click="order='onetime'" :class="order == 'onetime' && 'bg-red'">Make a One-Time Order</a>
                <div x-show="order=='recurring'">
                    <div>
                        <x-label>
                            Kootenay Co-op:
                            <x-input type="number" id="coop" name="coop" :value="old('coop', 0)" /> x $100
                        </x-label>
                        <x-input-error for="coop" />
                    </div>

                    <div>
                        <x-label>
                            Save-On:
                            <x-input type="number" id="saveon" name="saveon" :value="old('saveon', 0)" /> x $100
                        </x-label>
                        <x-input-error for="saveon" />
                    </div>

                    <div>
                        <x-label>
                            <x-input type="radio" name="schedule" value="monthly" :checked="old('schedule') == 'monthly' || (old('schedule') == null)" />
                            Once a month, starting <span class="font-bold">{{$dates['delivery']}}</span>
                        </x-label>
                        <x-label>
                            <x-input type="radio" name="schedule" value="none" :checked="old('schedule') == 'none'" />
                            I don't want a recurring order
                        </x-label>
                        <x-input-error for="schedule" />
                    </div>
                </div>
                <div x-show="order=='onetime'">
                    <div>
                        <x-label>
                            Kootenay Co-op:
                            <x-input type="number" id="coop_onetime" name="coop_onetime" :value="old('coop_onetime', 0)" /> x $100
                        </x-label>
                        <x-input-error for="coop_onetime" />
                    </div>

                    <div>
                        <x-label>
                            Save-On:
                            <x-input type="number" id="saveon_onetime" name="saveon_onetime" :value="old('saveon_onetime', 0)" /> x $100
                        </x-label>
                        <x-input-error for="saveon_onetime" />
                    </div>

                    <div>
                        <x-label>
                            <x-input type="radio" name="schedule_onetime" value="monthly" :checked="old('schedule_onetime') == 'monthly'" />
                            On <span class="font-bold">{{$dates['delivery']}}</span>
                        </x-label>
                        <x-label>
                            <x-input type="radio" name="schedule_onetime" value="none" :checked="old('schedule_onetime') == 'none' || (old('schedule_onetime') == null)" />
                            I don't want a one-time order
                        </x-label>
                        <x-input-error for="schedule_onetime" />
                    </div>
                </div>
            </div>
            <h4>Your Details</h4>
            <div>
                <x-label>
                    Name:
                    <x-input id="name" type="text" name="name" :value="old('name')" required autocomplete="name" />
                </x-label>
                <x-input-error for="name" />
            </div>

            <div>
                <x-label>
                    Email:
                    <x-input id="email" type="email" name="email" :value="old('email')" required />
                </x-label>
                <x-input-error for="email" />
            </div>

            <div>
                <x-label>
                    Phone Numer:
                    <x-input id="phone" type="tel" name="phone" :value="old('phone')" required placeholder="(250) 555-5555" />
                </x-label>
                <x-input-error for="phone" />
            </div>

            <div>
                <x-label>
                    Address:
                    <x-input id="address1" type="text" name="address1" :value="old('address1')" placeholder="your mailing address" />
                </x-label>
                <x-input-error for="address1" />
            </div>

            <div>
                <x-label>
                    Address 2:
                    <x-input id="address2" type="text" name="address2" :value="old('address2')" />
                </x-label>
                <x-input-error for="address2" />
            </div>

            <div>
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
            </div>

            <div>
                <x-label>
                    Password:
                    <x-input id="password" type="password" name="password" required autocomplete="new-password" />
                </x-label>
                <x-input-error for="password" />
            </div>

            <div>
                <x-label>
                    Confirm Password:
                    <x-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                </x-label>
                <x-input-error for="password_confirmation" />
            </div>
            <div>
                <x-label>
                    Referring Family
                    <x-input type="text" name="referrer" :value="old('referrer')" />
                </x-label>
                <x-input-error for="referrer" />
            </div>

            <h4>Payment</h4>
            <span class="help-block info">You will be charged 2 business days before delivery.</span>
            <div x-data="{payment:'debit'}">
                <x-input-error for="payment" />
                <x-label>
                    <x-input type="radio" name="payment" value="debit" :checked="old('payment') ==  'debit'" x-model="payment" />
                    Debit (we make more money with debit)
                </x-label>
                <div x-show="payment == 'debit'">
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
                        I have read and agree to the <a @click.prevent="document.querySelector('dialog#debit-terms-dialog').showModal()" href="#">terms of the Payor's Personal Pre-Authorized Debit (PAD) Agreement</a>
                    </x-label>
                    <x-input-error for="debit-terms" />
                </div>
                <x-label>
                    <x-input type="radio" name="payment" value="credit" :checked="old('payment') == 'credit'" x-model="payment" />
                    Credit Card
                </x-label>
                <div x-show="payment == 'credit'">
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
            <div x-data="{delivery:'pickup'}">
                <x-label>
                    <x-input type="radio" name="deliverymethod" value="pickup" x-model="delivery" :checked="old('deliverymethod') == 'pickup'" />
                    Pickup at the Nelson Waldorf School
                </x-label>
                <x-input-error for="deliverymethod" />
                <div x-cloak x-show="delivery == 'pickup'">
                    You'll have to sign for your cards. If someone else can sign for them, enter their name here.
                    <x-label>
                        Others who can pick up your cards:
                        <x-input type="text" name="pickupalt" :value="old('pickupalt')" />
                    </x-label>
                    <x-input-error for="pickupalt" />
                    <x-label>
                        <x-input type="checkbox" name="employee" value="1" :checked="old('employee') == 1" />
                        I or my alternate am employed by the school
                    </x-label>
                    <x-input-error for="employee" />
                </div>
                <x-label>
                    <x-input type="radio" name="deliverymethod" value="mail" x-model="delivery" :checked="old('deliverymethod') == 'mail'" />
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
                <a href="{{ route('login') }}">
                    Already have an account?
                </a>

                <x-button>
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
</x-guest-layout>