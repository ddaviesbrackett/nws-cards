<?php

namespace App\Console\Commands;

use App\Models\CutoffDate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class DoDeadlineReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:do-deadline-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind userbase that the order deadline is coming';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = (new Carbon($this->argument('date')))->addDays(2);
        $cutoff = CutoffDate::where('cutoff', $date)->orderby('cutoff', 'desc')->first();
        if (!isset($cutoff)) return;

        //remind people with orders of the deadline to change their order
        $users = User::where('stripe_active', 1)
            ->where(function (Builder $q)
            {
                $q->where('schedule', 'monthly')
                    ->orWhere('schedule_onetime', 'monthly');
            })
            ->get();
        
        foreach($users as $user) {
            //TODO send deadline reminder email
        }

        //politely beg users without orders to make one
        $usersToBeg = User::where('stripe_active', 1)
            ->where('schedule', 'none')
            ->where('schedule_onetime', 'none')
            ->where('no_beg', '<>', 1)
            ->get();

        foreach($usersToBeg as $user) {
            //TODO send order beg  email
        }
    }
}
