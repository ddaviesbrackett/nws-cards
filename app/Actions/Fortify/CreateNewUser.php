<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct( private StripeClient $stripe)
    {
    }

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $v = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'phone'        => 'phone:CA',
            'password_confirmation'    => 'same:password',
            'address1'    => 'required_if:deliverymethod,mail',
            'city'        => 'required_if:deliverymethod,mail',
            'postal_code'    => 'required_if:deliverymethod,mail|regex:/^\w\d\w ?\d\w\d$/',
            'schedule'    => 'in:none,monthly',
            'schedule_onetime'    => 'in:none,monthly',
            'saveon'    => 'integer|digits_between:1,2',
            'coop'        => 'integer|digits_between:1,2',
            'saveon_onetime'    => 'integer|digits_between:1,2',
            'coop_onetime'        => 'integer|digits_between:1,2',
            'payment'    => 'required|in:debit,credit',
            'debit-transit'        => 'required_if:payment,debit|nullable|digits:5',
            'debit-institution'    => 'required_if:payment,debit|nullable|digits:3',
            'debit-account'     => 'required_if:payment,debit|nullable|digits_between:5,15',
            'debit-terms'     => 'required_if:payment,debit|nullable',
            'mailwaiver'    => 'required_if:deliverymethod,mail',
            'deliverymethod' => 'required',
        ], [
            'phone' => 'Please enter a valid phone number.',
            'debit-transit.required_if' => 'branch number is required.',
            'debit-institution.required_if' => 'institution is required.',
            'debit-account.required_if' => 'account number is required.',
            'debit-terms.required_if' => 'You must agree to the terms to pay by pre-authorized debit.',
            'saveon.required' => 'You need to order at least one card.',
            'coop.required' => 'You need to order at least one card.',
            'saveon_onetime.required' => 'You need to order at least one card.',
            'coop_onetime.required' => 'You need to order at least one card.',
            'saveon.min' => 'You need to order at least one card.',
            'coop.min' => 'You need to order at least one card.',
            'saveon_onetime.min' => 'You need to order at least one card.',
            'coop_onetime.min' => 'You need to order at least one card.',
        ]);

        //rules for order amounts are complicated.  They can't both be 0 if they have a schedule
        $orderRequired = function ($schedulefield, $field, $other) use ($v, $input) {
            if (($input[$schedulefield] == 'biweekly' ||
                    $input[$schedulefield] == 'monthly' ||
                    $input[$schedulefield] == 'monthly-second')
                &&
                ($input[$other] == '' || $input[$other] == '0')
            ) {
                $v->addRules($field, 'required|min:1');
            } else {
                $v->addRules($field, 'min:0');
            }
        };

        $orderRequired('schedule', 'saveon', 'coop');
        $orderRequired('schedule', 'coop', 'saveon');
        $orderRequired('schedule_onetime', 'saveon_onetime', 'coop_onetime');
        $orderRequired('schedule_onetime', 'coop_onetime', 'saveon_onetime');

        $v->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name'             => $input['name'],
                'email'            => $input['email'],
                'password'         => Hash::make($input['password']),
                'phone'            => $input['phone'],
                'address1'         => $input['address1'] ?? '',
                'address2'         => $input['address2'] ?? '',
                'city'             => $input['city'] ?? '',
                'province'         => 'BC',
                'postal_code'      => $input['postal_code'] ?? '',
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
                $user->last_four = substr($input['debit-account'], -4, 4);
            }

            $customer = $this->stripe->customers->create($stripeCustomerAttributes);
            if (isset($cardToken)) {
                $card = $this->stripe->customers->createSource($customer->id, ['source' => $cardToken]);
                $customer->default_source = $card;
                $user->last_four = $card->last4;
            }

            $user->stripe_subscription = $customer->id;
            $user->save();
            return $user;
        });
    }
}
