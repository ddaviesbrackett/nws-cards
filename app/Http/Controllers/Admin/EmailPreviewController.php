<?php

namespace App\Http\Controllers;

use App\Mail\ChargeReminder;
use App\Mail\Contact;
use App\Mail\DeadlineReminder;
use App\Mail\NewConfirmation;
use App\Mail\OrderBeg;
use App\Mail\PickupReminder;
use App\Mail\Suspend;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class EmailPreviewController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['admin'];
    }

    public function chargeReminder(int $id = 80){
        $testUser = User::find($id);
        return new ChargeReminder($testUser, $testUser->orders->last());
    }

    public function contact(){
        return new Contact("from@example.com", "name string", "message string - the whole thing\n\nincluding newlines.");
    }

    public function deadlineReminder(int $id = 80){
        $testUser = User::find($id);
        return new DeadlineReminder($testUser, $testUser->cutoffDates->last());
    }

    public function new(int $id = 80){
        $testUser = User::find($id);
        return new NewConfirmation($testUser, false);
    }

    public function edit(int $id = 80){
        $testUser = User::find($id);
        return new NewConfirmation($testUser, true);
    }

    public function orderBeg(int $id = 80){
        $testUser = User::find($id);
        return new OrderBeg($testUser, $testUser->cutoffDates->last());
    }

    public function pickupReminder(int $id = 80){
        $testUser = User::find($id);
        return new PickupReminder($testUser, (new Carbon('now'))->addDays(2));
    }

    public function suspend(int $id = 80){
        $testUser = User::find($id);
        return new Suspend($testUser);
    }
}
