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
    function toLeaderboard(): RedirectResponse
    {
        return redirect()->route('leaderboard');
    }

    function leaderboard(): View
    {
        $buckets = [];
        $nf = new NumberFormatter('en-CA', NumberFormatter::CURRENCY);
        foreach (SchoolClass::where('displayorder', '>=', '-1')
            ->orderby('displayorder', 'asc')
            ->with('orders', 'pointsales', 'expenses')
            ->get() as $class) {
            $raised = $class->orders->getTotalProfit() + $class->pointsales->getTotalProfit();
            $spent = $class->expenses->sum('amount');
            $buckets[$class->bucketname] = [
                'nm' => $class->name,
                'spent' => $nf->format($spent),
                'raised' => $nf->format($raised),
                'available' => $nf->format($raised - $spent),
            ];
        }
        return view('tracking.leaderboard', [
            'total' => $nf->format(SchoolClass::profitSince(new Carbon('2010-01-01'))),
            'buckets' => $buckets
        ]);
    }

    function bucket($bucketname)
    {
        $sc = SchoolClass::where('bucketname', '=', $bucketname)
            ->with('orders', 'expenses', 'pointsales')
            ->withCount('users')
            ->first();
        if (is_null($sc)) {
            return $this->toLeaderboard();
        }

        $expenses = $sc->expenses->sortByDesc('expense_date');
        $pointsales = $sc->pointsales->sortByDesc('saledate');

        return view('tracking.bucket', [
            'name' => $sc->name,
            'byCutoff' => $sc->profitByCutoff(),
            'expenses' => $expenses,
            'pointsales' => $pointsales,
            'sum' => $sc->orders->getTotalProfit() + $sc->pointsales->getTotalProfit(),
            'supporters' => $sc->users_count,
        ]);
    }
}
