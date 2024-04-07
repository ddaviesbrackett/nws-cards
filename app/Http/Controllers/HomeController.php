<?php

namespace App\Http\Controllers;

use App\Mail\Contact;
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
        return view('home', [
                                'total'=>$nf->format(SchoolClass::profitSince(new Carbon('2010-01-01'))),
                                'totalThisYear'=>$nf->format(SchoolClass::profitSince(new Carbon('2023-09-01'))),
                                'isBlackout'=>OrderController::IsBlackoutPeriod()
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
