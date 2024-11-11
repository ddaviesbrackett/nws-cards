<?php

namespace App\Console\Commands;

use App\Mail\ChargeReminder;
use App\Models\CutoffDate;
use App\Models\Order;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;

class GenerateOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-orders {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the orders for a given cutoff date';

    /**
     * Execute the console command.
     */
    public function handle(StripeClient $stripe)
    {
        $cutoff = CutoffDate::whereRaw('cast(cutoff as date) = \'' . $this->argument('date') . '\'')->first();
        
        if (!isset($cutoff)) {
            $this->warn('no cutoff date on this date');
            return;
        }

        if (! $cutoff->orders->isEmpty()) {
            $this->warn('orders already generated for this date');
            return;
        }

        $users = User::where('stripe_active', '=', 1)
            ->whereRaw('coop + saveon + coop_onetime + saveon_onetime > 0')
            ->get();

        foreach($users as $user){
            $order = new Order([
                'paid' => 0,
                'payment' => $user->payment,
                'deliverymethod' => $user->deliverymethod,
                'profit' => 0,
                'coop' => $user->coop,
                'saveon' => $user->saveon,
                'coop_onetime' => $user->coop_onetime,
                'saveon_onetime' => $user->saveon_onetime,
            ]);
            
            $user->coop_onetime = 0;
            $user->saveon_onetime = 0;
            $user->save();

            $order->cutoffdate()->associate($cutoff);
            $user->orders()->save($order);
            $order->schoolclasses()->sync(SchoolClass::current());

            Mail::to($user->email, $user->name)->send(new ChargeReminder($user, $order));

        }
        $this->info('orders generated for ' . $this->argument('date'));
    }
}
