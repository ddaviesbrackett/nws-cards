<?php

namespace App\Console\Commands;

use App\Mail\DeadlineReminder;
use App\Mail\OrderBeg;
use App\Models\CutoffDate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class DoDeadlineReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:do-deadline-reminder {date}';

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
        $cutoff = CutoffDate::whereRaw('cast(cutoff as date) = \'' . $date->format('Y-m-d') . '\'')->first();

        if (empty($cutoff)) {
            if($this->argument('date') != 'now') $this->warn('no cutoff 2 days after given date');
            return;
        }

        //remind people with orders of the deadline to change their order
        $users = User::whereRaw('coop + saveon + coop_onetime + saveon_onetime > 0')
            ->get();
        
        foreach($users as $user) {
            Mail::to($user->email, $user->name)->send(new DeadlineReminder($user, $cutoff));
        }

        $this->info('sent reminders to ' . $users->count() . ' users');

        //politely beg users without orders to make one
        $usersToBeg = User::whereRaw('coop + saveon + coop_onetime + saveon_onetime = 0')
            ->where('no_beg', '<>', 1)
            ->get();

        foreach($usersToBeg as $user) {
            Mail::to($user->email, $user->name)->send(new OrderBeg($user, $cutoff));
        }
        
        $this->info('sent polite requests to ' . $usersToBeg->count() . ' users');
    }
}
