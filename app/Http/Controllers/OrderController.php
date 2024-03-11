<?php

namespace App\Http\Controllers;

use App\Models\CutoffDate;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
