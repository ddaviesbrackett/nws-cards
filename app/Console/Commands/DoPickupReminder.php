<?php

namespace App\Console\Commands;

use App\Mail\PickupReminder;
use App\Models\CutoffDate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DoPickupReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:do-pickup-reminder {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind people to pick up their cards';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = (new Carbon($this->argument('date')))->addDays(2);
        $cutoff = CutoffDate::whereRaw('cast(delivery as date) = \'' . $date->format('Y-m-d') . '\'')->first();
        
        if (empty($cutoff) && $this->argument('date') != 'now') {
            $this->warn('no delivery 2 days after given date');
            return;
        }

        $sent = 0;
        foreach($cutoff->orders as $order) {
            $user = $order->user;
            if(!$user->isMail())
            {
                Mail::to($user->email, $user->name)->send(new PickupReminder($user, $date));
                $sent += 1;
            }
        }

        $this->info('pickup reminders sent to ' . $sent . ' users');
    }
}
