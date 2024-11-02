<?php

namespace App\Http\Controllers;

use App\Mail\NewConfirmation;
use App\Models\CutoffDate;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller implements HasMiddleware
{
    public function __construct(private StripeClient $stripe)
    {
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
            'user' => $user
        ]);
    }

    public function postEdit(Request $req)
    {
        $input = $req->input();
        $v = Validator::make($input, [
            'schedule'    => 'in:none,monthly',
            'schedule_onetime'    => 'in:none,monthly',
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
        ], [
            'schedule.in' => 'Invalid schedule.',
            'schedule_onetime.in' => 'Invalid one-time schedule.',
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
        ]);

        //rules for order amounts are complicated.  They can't both be 0 if they have a schedule
        $orderRequired = function ($schedulefield, $field, $other) use ($v, $input) {
            if (($input[$schedulefield] == 'biweekly' ||
                    $input[$schedulefield] == 'monthly' ||
                    $input[$schedulefield] == 'monthly-second')
                &&
                ($input[$other] == '' || $input[$other] == '0')
            ) {
                $v->addRules([$field => 'required|min:1']);
            } else {
                $v->addRules([$field => 'min:0']);
            }
        };

        $orderRequired('schedule', 'saveon', 'coop');
        $orderRequired('schedule', 'coop', 'saveon');
        $orderRequired('schedule_onetime', 'saveon_onetime', 'coop_onetime');
        $orderRequired('schedule_onetime', 'coop_onetime', 'saveon_onetime');
       
        $v->validate();

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
                    $user->last_four = substr($input['debit-account'], -4, 4);
                }

                $customer = $this->stripe->customers->update($user->stripe_subscription, $stripeCustomerAttributes);
                if (isset($cardToken)) {
                    $card = $this->stripe->customers->createSource($customer->id, ['source' => $cardToken]);
                    $customer->default_source = $card;
                    $user->last_four = $card->last4;
                }
            }
            $user->save();
            return $user;
        });
        Mail::to($user->email, $user->name)->send(new NewConfirmation($user, true));
        return redirect('account');
    }
}
