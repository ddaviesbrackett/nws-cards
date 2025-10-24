<?php

namespace App\Http\Controllers;

use App\Mail\Contact;
use App\Models\CutoffDate;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use NumberFormatter;

class HomeController extends Controller
{
    public function home(): View
    {
        $nf = new NumberFormatter('en-CA', NumberFormatter::CURRENCY);
        $startOfThisSchoolYear = $this->mostRecentSchoolYearStart(Carbon::today());
        $cutoffsThisSchoolYear =
            CutoffDate::where('cutoff', '>', $startOfThisSchoolYear)
                ->get()
                ->map(fn (CutoffDate $cu) => [
                    'cutoff' => $cu->cutoffdate()->addDays(-1)->format('l, F jS'),  // minus one because the time is midnight
                    'charge' => $cu->chargedate()->format('l, F jS'),
                    'delivery' => $cu->deliverydate()->format('l, F jS'),
                ]);

        return view('home', [
            'total' => $nf->format(SchoolClass::profitSince(new Carbon('2010-01-01'))),
            'totalThisYear' => $nf->format(SchoolClass::profitSince($startOfThisSchoolYear)),
            'isBlackout' => OrderController::IsBlackoutPeriod(),
            'cutoffs' => $cutoffsThisSchoolYear,
        ]);
    }

    public function contact(Request $request): JsonResponse
    {
        $status = 'failure';
        $email = $request->input('em');
        $name = $request->input('nm');
        $message = $request->input('msg');
        Mail::to('nwsgrocerycards@gmail.com', 'Nelson Waldorf School Grocery Cards')->send(new Contact($email, $name, $message));
        $status = 'success';

        return response()->json(['r' => ['status' => $status]]);
    }

    private function mostRecentSchoolYearStart(Carbon $reference): Carbon
    {
        $target = $reference->clone()->month(9)->day(1); // school year starts September 1
        if ($reference->month < 9) {
            $target->addYear(-1);
        }

        return $target;
    }
}
