<?php

namespace App\Utilities;

use App\Models\SchoolClass;
use Illuminate\Support\Facades\Validator;

class OrderUtilities {
    public  function getValidator(array $in, array $passwordRules)
    {

        $v = Validator::make($in, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $passwordRules,
            'phone'        => 'phone:CA',
            'password_confirmation'    => 'same:password',
            'address1'    => 'required_if:deliveyrmethod,mail',
            'city'        => 'required_if:deliverymethod,mail',
            'postal_code'    => 'required_if:deliverymethod,mail|regex:/^\w\d\w ?\d\w\d$/',
            'schedule'    => 'in:none,biweekly,monthly,monthly-second',
            'schedule_onetime'    => 'in:none,monthly,monthly-second',
            'saveon'    => 'integer|digits_between:1,2',
            'coop'        => 'integer|digits_between:1,2',
            'saveon_onetime'    => 'integer|digits_between:1,2',
            'coop_onetime'        => 'integer|digits_between:1,2',
            'payment'    => 'required|in:debit,credit,keep',
            'debit-transit'        => 'required_if:payment,debit|digits:5',
            'debit-institution'    => 'required_if:payment,debit|digits:3',
            'debit-account'     => 'required_if:payment,debit|digits_between:5,15',
            'debit-terms'     => 'required_if:payment,debit',
            'mailwaiver'    => 'required_if:deliverymethod,mail',
            'deliverymethod' => 'required',
            'schoolclasslist.*' => 'string|in:tuitionreduction,pac,' . $this->choosableClasses()->keys()->join(','),
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
        $orderRequired = function ($schedulefield, $field, $other) use ($v, $in) {
            if (($in[$schedulefield] == 'biweekly' ||
                    $in[$schedulefield] == 'monthly' ||
                    $in[$schedulefield] == 'monthly-second')
                &&
                ($in[$other] == '' || $in[$other] == '0')
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

        return $v;
    }

    public function choosableClasses()
    {
        $c = SchoolClass::choosable();
        return $c->mapWithKeys(function(SchoolClass $item, int $k){
            return [$item["bucketname"] => $item["id"]];
        });
    }
}