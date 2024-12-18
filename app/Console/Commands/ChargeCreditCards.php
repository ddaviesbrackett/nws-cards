<?php

namespace App\Console\Commands;

use App\Models\CutoffDate;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Stripe\Exception\CardException;
use Stripe\StripeClient;

class ChargeCreditCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:charge-credit-cards {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge credit cards for orders that are not yet paid for a given cutoff date';

    /**
     * Execute the console command.
     */
    public function handle(StripeClient $stripe)
    {
        $dt = (new Carbon($this->argument('date')))->format('Y-m-d');
        $cutoff = CutoffDate::whereRaw('cast(charge as date) = \'' . $dt . '\'')->first();
        
        if (empty($cutoff)) {
            if($this->argument('date') != 'now') $this->warn('no charge date on this date');
            return;
        }

        $charged = 0;
        foreach($cutoff->orders as $order)
        {
            $cardcount = $order->saveon_onetime +
                         $order->coop_onetime + 
                         $order->saveon +
                         $order->coop;
            if($order->isCreditCard() && $cardcount != 0 && !$order->paid) {
                try {
                    $stripe->charges->create([
                        'customer' => $order->user->stripe_id,
                        'amount' => $cardcount * 100 * 100,
                        'currency' => 'cad',
                        'description' => 'grocery card order for ' . $cutoff->deliverydate(),
                    ]);
                }
                catch(CardException $ex)
                {
                    //pass
                }
                $order->paid = true;
                $order->save();
                $charged += 1;
            }
        }
        $this->info('charged ' . $charged . ' users');
    }
}
