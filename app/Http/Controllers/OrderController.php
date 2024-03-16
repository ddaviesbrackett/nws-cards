<?php

namespace App\Http\Controllers;

use App\Models\CutoffDate;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use NumberFormatter;

class OrderController extends Controller
{
	// Blackout period is from cutoff wednesday just before midnight until card pickup wednesday morning.
    public static function GetBlackoutEndDate() : Carbon
	{
		return new Carbon(CutoffDate::find(Order::max('cutoff_date_id'))->delivery, 'America/Los_Angeles');
	}

	// Blackout period is from cutoff wednesday just before midnight until card pickup wednesday morning.
	public static function IsBlackoutPeriod() : bool
	{
		return ((new Carbon('America/Los_Angeles')) < OrderController::GetBlackoutEndDate());
	}

	public function account(Request $req) : View
	{
		$user = $req->user();
		$nf = new NumberFormatter('en-CA', NumberFormatter::CURRENCY);
		return view('account', [
			'user' => $user,
			'mostRecentOrder' => $user->orders()->first(),
			'profit' => $nf->format($user->orders->sum('profit'))
		]);
	}
}
