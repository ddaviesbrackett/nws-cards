<?php

namespace App\Http\Controllers;

use App\Models\CutoffDate;
use App\Models\Order;
use App\Models\SchoolClass;
use App\Models\User;
use App\Utilities\OrderUtilities;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use Stripe\StripeClient;
use Closure;

class OrderController extends Controller implements HasMiddleware
{

    private OrderUtilities $utils;
    private StripeClient $stripe;

    public function __construct(OrderUtilities $utils, StripeClient $stripe)
    {
        $this->utils = $utils;
        $this->stripe = $stripe;
    }

    public static function middleware(): array
    {
        return ['auth:sanctum'];
    }

    // Blackout period is from cutoff wednesday just before midnight until card pickup wednesday morning.
    public static function GetBlackoutEndDate(): Carbon
    {
        return new Carbon(CutoffDate::find(Order::max('cutoff_date_id'))->delivery, 'America/Los_Angeles');
    }

    // Blackout period is from cutoff wednesday just before midnight until card pickup wednesday morning.
    public static function IsBlackoutPeriod(): bool
    {
        return ((new Carbon('America/Los_Angeles')) < OrderController::GetBlackoutEndDate());
    }

    public function account(Request $req): View
    {
        $user = $req->user();
        $nf = new NumberFormatter('en-CA', NumberFormatter::CURRENCY);
        return view('account', [
            'user' => $user,
            'mostRecentOrder' => $user->orders()->first(),
            'profit' => $nf->format($user->orders->sum('profit'))
        ]);
    }

    public function edit(Request $req): View
    {
        $user = $req->user();
        return view('edit', [
            'user' => $user,
            'classes' => SchoolClass::choosable(),
        ]);
    }

    public function postEdit(Request $req)
    {
        $input = $req->input();
        $v = Validator::make($input, [
            'schedule'    => 'in:none,biweekly,monthly,monthly-second',
            'schedule_onetime'    => 'in:none,monthly,monthly-second',
            'saveon'    => 'integer|digits_between:1,2',
            'coop'        => 'integer|digits_between:1,2',
            'saveon_onetime'    => 'integer|digits_between:1,2',
            'coop_onetime'        => 'integer|digits_between:1,2',
            'payment'    => 'required|in:debit,credit,keep',
            'debit-transit'        => 'required_if:payment,debit|nullable|digits:5',
            'debit-institution'    => 'required_if:payment,debit|nullable|digits:3',
            'debit-account'     => 'required_if:payment,debit|nullable|digits_between:5,15',
            'debit-terms'     => 'required_if:payment,debit|nullable',
            'mailwaiver'    => 'required_if:deliverymethod,mail',
            'deliverymethod' => 'required',
            'schoolclasslist.*' => 'string|in:tuitionreduction,pac,' . $this->utils->choosableBuckets()->join(','),
        ], [
            'schedule.in' => 'We need either a recurring order or a one-time order.',
            'schedule_onetime.in' => 'We need either a recurring order or a one-time order.',
            'debit-transit.required_if' => 'Branch number is required.',
            'debit-institution.required_if' => 'Institution is required.',
            'debit-account.required_if' => 'Account number is required.',
            'debit-terms.required_if' => 'You must agree to the terms to pay by pre-authorized debit.',
            'saveon.required' => 'Please order at least one card.',
            'coop.required' => 'Please order at least one card.',
            'saveon_onetime.required' => 'Please order at least one card.',
            'coop_onetime.required' => 'Please order at least one card.',
            'saveon.min' => 'Please order at least one card.',
            'coop.min' => 'Please order at least one card.',
            'saveon_onetime.min' => 'Please order at least one card.',
            'coop_onetime.min' => 'Please order at least one card.',
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

        if ($v->fails()) {
            return $this->edit($req);
        }

        $user = $req->user();
        DB::transaction(function () use ($user, $input) {
            $user->saveon = $input['saveon'];
            $user->coop = $input['coop'];
            $user->schedule = $input['schedule'];
            $user->saveon_onetime = $input['saveon_onetime'];
            $user->coop_onetime = $input['coop_onetime'];
            $user->schedule_onetime = $input['schedule_onetime'];
            $user->payment = $input['payment'] == 'credit' ? 1 : 0;
            $user->deliverymethod = $input['deliverymethod'] == 'mail' ? 1 : 0;
            $user->referrer = $input['referrer'] ?? '';
            $user->pickupalt = $input['pickupalt'] ?? '';
            $user->employee = array_key_exists('employee', $input);

            $classlist = collect($input['schoolclasslist'] ?? []);
            $classlist->add('tuitionreduction');
            $classlist->add('pac');
            $user->schoolclasses()->sync($classlist->map(function ($value, $key) {
                return $this->utils->idFromBucket($value);
            }));
            if ($input['payment'] != 'keep') {
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

                $customer = $this->stripe->customers->update($user->stripe_subscription, $stripeCustomerAttributes);
                if (isset($cardToken)) {
                    $card = $this->stripe->customers->createSource($customer->id, ['source' => $cardToken]);
                    $customer->default_source = $card;
                }
            }
            $user->save();
            return $user;
        });
        return redirect('account');
    }
}
