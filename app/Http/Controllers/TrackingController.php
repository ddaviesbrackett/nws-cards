<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        //watch this
        $classes_raw = DB::select(<<<EOQ
        with order_profit(class_id, profit) as (
            select co.class_id, sum(profit) from classes_orders co
            group by co.class_id
        ),
        pointsale_profit(class_id, profit) as (
            select cp.class_id, sum(profit) from classes_pointsales cp
            group by cp.class_id
        ),
        expenses(class_id, amount) as(
            select exp.class_id, sum(amount) from expenses exp
            group by exp.class_id
        )
        select c.id, c.name, c.bucketname, ifnull(op.profit,0) + ifnull(pp.profit,0) as raised, exp.amount as expenses from classes c
        left join order_profit op on c.id = op.class_id
        left join pointsale_profit pp on c.id = pp.class_id
        left join expenses exp on c.id = exp.class_id
        order by displayorder
        EOQ);

        foreach ($classes_raw as $class) {
            $buckets[$class->bucketname] = [
                'nm' => $class->name,
                'spent' => $nf->format($class->expenses),
                'raised' => $nf->format($class->raised),
                'available' => $nf->format($class->raised - $class->expenses),
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
        ]);
    }
}
