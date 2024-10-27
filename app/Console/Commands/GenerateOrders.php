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
        $cutoff = CutoffDate::where('cutoff', '=', $this->argument('date'))->orderby('cutoff', 'desc')->first();
        
        if(! isset($cutoff)) return;
        if(! $cutoff->orders->isEmpty()) return 'orders already generated for this date';

        $users = User::where('stripe_active', '=', 1)
            ->orWhere(function(Builder $q) {
                $q->where('schedule', '=', 'monthly')
                  ->orWhere('scheduleonetime', '=', 'monthly');
            })
            ->orWhere(function(Builder $q) {
                $q->where('saveon', '>', 0)
                  ->orWhere('coop', '>', 0)
                  ->orWhere('saveon_onetime', '>', 0)
                  ->orWhere('coop_onetime', '>', 0);
            })
            ->get();

        foreach($users as $user){
            $order = new Order([
                'paid' => 0,
                'payment' => $user->payment,
                'deliverymethod' => $user->deliverymethod,
            ]);
            if($user->schedule == 'monthly') {
                $order->coop = $user->coop;
                $order->saveon = $user->saveon;
            }
            if($user->schhedule_onetime == 'monthly') {
                $order->coop_onetime = $user->coop_onetime;
                $order->saveon_onetime = $user->saveon_onetime;
                $user->coop_onetime = 0;
                $user->saveon_onetime = 0;
                $user->schedule_onetime = 'none';
                $user->save();
            }
            $order->cutoffdate()->associate($cutoff);
            $user->orders()->save($order);
            $order->schoolclasses()->sync(SchoolClass::current());

            Mail::to($user->email, $user->name)->send(new ChargeReminder($user, $order));

        }
        return 'orders generated for ' . $this->argument('date');
    }
}
