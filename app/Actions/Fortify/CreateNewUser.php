<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Utilities\OrderUtilities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Stripe\StripeClient;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    private OrderUtilities $utils;
    private StripeClient $stripe;

    public function __construct(OrderUtilities $utils, StripeClient $stripe)
    {
        $this->utils = $utils;
        $this->stripe = $stripe;
    }

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $v = $this->utils->getValidator($input, $this->passwordRules());
        $v->sometimes('schedule', 'in:biweekly,monthly,monthly-second', function ($input) {
            return $input->schedule_onetime == 'none';
        });
        $v->sometimes('schedule_onetime', 'in:monthly,monthly-second', function ($input) {
            return $input->schedule == 'none';
        });
        $v->setCustomMessages([
            'schedule.in' => 'We need either a recurring order or a one-time order.',
            'schedule_onetime.in' => 'We need either a recurring order or a one-time order.',
        ]);

        $v->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name'             => $input['name'],
                'email'            => $input['email'],
                'password'         => Hash::make($input['password']),
                'phone'            => $input['phone'],
                'address1'         => $input['address1'],
                'address2'         => $input['address2'],
                'city'             => $input['city'],
                'province'         => 'BC',
                'postal_code'      => $input['postal_code'],
                'saveon'           => $input['saveon'],
                'coop'             => $input['coop'],
                'schedule'         => $input['schedule'],
                'saveon_onetime'   => $input['saveon_onetime'],
                'coop_onetime'     => $input['coop_onetime'],
                'schedule_onetime' => $input['schedule_onetime'],
                'payment'          => $input['payment'] == 'credit' ? 1 : 0,
                'deliverymethod'   => $input['deliverymethod'] == 'mail' ? 1 : 0,
                'referrer'         => $input['referrer'] ?? '',
                'pickupalt'        => $input['pickupalt'] ?? '',
                'employee'         => array_key_exists('employee', $input),
            ]);


            $user->schoolclasses()->sync(collect($input['schoolclasslist'])->map(function ($value, $key) {
                return $this->utils->choosableClasses()[$value];
            }));

            $cardToken = null;
            if (isset($input['stripeToken'])) {
                $cardToken = $input['stripeToken'];
            }
            $stripeCustomerAttributes = [
                'email' => $user->email,
                'description' => $user->name,
            ];
            if (!isset($cardToken)) {
                $stripeCustomerAttributes['metadata'] = [
                    'debit-transit' => $input['debit-transit'],
                    'debit-institution' => $input['debit-institution'],
                    'debit-account' => $input['debit-account'],
                ];
            }

            $customer = $this->stripe->customers->create($stripeCustomerAttributes);
            if (isset($cardToken)) {
                $card = $customer->cards->create(['card' => $cardToken]);
                $customer->default_card = $card->id;
                \Stripe\Customer::update($customer->id, $customer);
            }

            $user->stripe_subscription = $customer->id;
            $user->save();
            return $user;
        });
    }
}
