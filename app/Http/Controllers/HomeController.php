<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function home() : View
    {
        return view('home', ['total'=>SchoolClass::profitSince(new Carbon('2010-01-01')),
                             'totalThisYear'=>SchoolClass::profitSince(new Carbon('2023-09-01'))]);
    }

    public function contact(Request $request) : JsonResponse
    {
        $status = 'failure';
        $email = $request->input('em');
        $name = $request->input('nm');
		$data = $request->input()->only('msg', 'nm', 'em');
		Mail::send('emails.contact', $data, function($message) use ($email, $name){
			$message->subject('Home Page contact request');
			$message->to('nwsgrocerycards@gmail.com', 'Nelson Waldorf School Grocery Cards');
			$message->from($email, $name);
		});
		$status = 'success';
		return Response::json(['r' =>['status' => $status]]);
    }
}
