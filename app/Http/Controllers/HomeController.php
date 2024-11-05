<?php

namespace App\Http\Controllers;

use App\Mail\Contact;
use App\Models\CutoffDate;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use NumberFormatter;

class HomeController extends Controller
{
    public function home() : View
    {
        $nf = new NumberFormatter('en-CA', NumberFormatter::CURRENCY);
        $startOfThisYear = new Carbon('2024-09-01');
        $cutoffsThisYear = 
            CutoffDate::whereNotIn('id', Order::distinct()->pluck('cutoff_date_id'))
                            ->where('cutoff', '>', $startOfThisYear)
                            ->get()
                            ->map(fn (CutoffDate $cu) => [
                                'cutoff'=>$cu->cutoffdate()->format('l, F jS'),
                                'charge'=>$cu->chargedate()->format('l, F jS'),
                                'delivery'=>$cu->deliverydate()->format('l, F jS'),
                            ]);
        
        return view('home', [
                                'total'=>$nf->format(SchoolClass::profitSince(new Carbon('2010-01-01'))),
                                'totalThisYear'=>$nf->format(SchoolClass::profitSince($startOfThisYear)),
                                'isBlackout'=>OrderController::IsBlackoutPeriod(),
                                'cutoffs'=>$cutoffsThisYear,
                            ]);
    }

    public function contact(Request $request) : JsonResponse
    {
        $status = 'failure';
        $email = $request->input('em');
        $name = $request->input('nm');
        $message = $request->input('msg');
        Mail::to("nwsgrocerycards@gmail.com", "Nelson Waldorf School Grocery Cards")->send(new Contact($email, $name, $message));
        $status = 'success';
        return response()->json(['r' =>['status' => $status]]);
    }
}
