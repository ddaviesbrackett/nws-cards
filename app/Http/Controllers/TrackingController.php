<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use NumberFormatter;

class TrackingController extends Controller
{
    function toLeaderboard() : RedirectResponse
    {
        return redirect('/tracking/leaderboard');
    }

    function leaderboard() : View
    {
        $buckets = [];
        $nf = new NumberFormatter('en-CA', NumberFormatter::CURRENCY);
        foreach(SchoolClass::where('displayorder', '>=', '-1')->orderby('displayorder', 'asc')->get() as $class)
		{
            $raised = $class->orders->getTotalProfit() + $class->pointsales->getTotalProfit();
            $spent = $class->expenses->sum('amount');
            $buckets[$class->bucketname] = [
                'nm' => $class->name,
                'spent' => $nf->format($spent),
                'count' => $class->users->count(),
                'raised' => $nf->format($raised),
                'available' => $nf->format($raised - $spent),
            ];
        }
        return view('tracking.leaderboard', [
            'total' => $nf->format(SchoolClass::profitSince(new Carbon('2010-01-01'))),
            'buckets' => $buckets]);
    }
    
}
