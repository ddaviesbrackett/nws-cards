<?php

namespace App\Console\Commands;

use App\Models\CutoffDate;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DoPickupReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:do-pickup-reminder';

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
        $cutoff = CutoffDate::where('delivery', $date)->orderby('delivery', 'desc')->first();
        if (!isset($cutoff)) return;

        foreach($cutoff->orders as $order) {
            $user = $order->user;
            if(!$user->isMail)
            {
                //TODO send pickup reminder email
            }
        }
    }
}