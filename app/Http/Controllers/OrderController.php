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
        if(OrderController::IsBlackoutPeriod()) {
            return $this->edit($req);
        }
        $input = $req->input();
        $v = Validator::make($input, [
            'saveon'    => 'integer|digits_between:1,2',
            'coop'        => 'integer|digits_between:1,2',
            'payment'    => 'required|in:debit,credit,keep',
            'debit-transit'        => 'required_if:payment,debit|nullable|digits:5',
            'debit-institution'    => 'required_if:payment,debit|nullable|digits:3',
            'debit-account'     => 'required_if:payment,debit|nullable|digits_between:5,15',
            'debit-terms'     => 'required_if:payment,debit|nullable',
            'mailwaiver'    => 'required_if:deliverymethod,mail',
            'deliverymethod' => 'required',
            'ordertype' => 'required|in:monthly,onetime',
        ], [
            'debit-transit.required_if' => 'Branch number is required.',
            'debit-institution.required_if' => 'Institution is required.',
            'debit-account.required_if' => 'Account number is required.',
            'debit-terms.required_if' => 'You must agree to the terms to pay by pre-authorized debit.',
            'saveon.required' => 'Please order at least one card.',
            'coop.required' => 'Please order at least one card.',
            'saveon.min' => 'Please order at least one card.',
            'coop.min' => 'Please order at least one card.',
        ]);

        //order amounts can't both be zero
        $orderRequired = function ($field, $other) use ($v, $input) {
            if ($input[$other] == '' || $input[$other] == '0') {
                $v->addRules($field, 'required|min:1');
            } else {
                $v->addRules($field, 'min:0');
            }
        };

        $orderRequired('saveon', 'coop');
        $orderRequired('coop', 'saveon');
       
        $v->validate();

        $user = $req->user();
        DB::transaction(function () use ($user, $input) {
            $user->saveon = 0;
            $user->coop = 0;
            $user->saveon_onetime = 0;
            $user->coop_onetime  = 0;
            
            if($input['ordertype'] == 'monthly') {
                $user->saveon = $input['saveon'];
                $user->coop = $input['coop'];
            }
            else if($input['ordertype'] == 'onetime') {
                $user->saveon_onetime = $input['saveon'];
                $user->coop_onetime = $input['coop'];
            }
            $user->deliverymethod = $input['deliverymethod'] == 'mail' ? 1 : 0;
            $user->pickupalt = $input['pickupalt'] ?? '';
            $user->employee = array_key_exists('employee', $input);

            if ($input['payment'] != 'keep') {
                $user->payment = $input['payment'] == 'credit' ? 1 : 0;
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

                $customer = $this->stripe->customers->update($user->stripe_id, $stripeCustomerAttributes);
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
