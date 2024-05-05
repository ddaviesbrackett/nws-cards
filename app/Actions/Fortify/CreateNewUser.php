<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Utilities\OrderUtilities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Validator;
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
        $v = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'phone'        => 'phone:CA',
            'password_confirmation'    => 'same:password',
            'address1'    => 'required_if:deliverymethod,mail',
            'city'        => 'required_if:deliverymethod,mail',
            'postal_code'    => 'required_if:deliverymethod,mail|regex:/^\w\d\w ?\d\w\d$/',
            'schedule'    => 'in:none,biweekly,monthly,monthly-second',
            'schedule_onetime'    => 'in:none,monthly,monthly-second',
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
            'schoolclasslist.*' => 'string|in:tuitionreduction,pac,' . $this->utils->choosableBuckets()->keys()->join(','),
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
            'schedule.not_in' => 'Choose a delivery date',
            'schedule_onetime.not_in' => 'Choose a delivery date',
            'schoolclasslist' => 'Please correct your chosen supported classes',
        ]);
        $v->sometimes('schedule', 'not_in:none', function ($input) {
            return $input['saveon'] > 0 ||
                $input['coop'] > 0;
        });
        $v->sometimes('schedule_onetime', 'not_in:none', function ($input) {
            return $input['saveon_onetime'] > 0 ||
                $input['coop_onetime'] > 0;
        });

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

            $classlist = collect($input['schoolclasslist'] ?? []);
            $classlist->add('tuitionreduction');
            $classlist->add('pac');
            $user->schoolclasses()->sync($classlist->map(function ($value, $key) {
                return $this->utils->idFromBucket($value);
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
                $card = $this->stripe->customers->createSource($customer->id, ['source' => $cardToken]);
            }

            $user->stripe_subscription = $customer->id;
            $user->save();
            return $user;
        });
    }
}
