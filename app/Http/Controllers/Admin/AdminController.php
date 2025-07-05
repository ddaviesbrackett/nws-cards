<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CutoffDate;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Stripe\StripeClient;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['admin'];
    }
    
    public function impersonate(): View
    {
        $users = User::orderBy('name')->get();
        return view('admin.impersonation', ['users' => $users]);
    }

    private function generateProfits($date) {
        $saveon = 0.0;
        $coop = 0.0;
        if( ! empty($date->saveon_cheque_value) && ! empty($date->saveon_card_value))
        {
            $saveon = ($date->saveon_card_value - $date->saveon_cheque_value) / $date->saveon_card_value;
        }

        if( ! empty($date->coop_cheque_value) && ! empty($date->coop_card_value))
        {
            $coop = ($date->coop_card_value - $date->coop_cheque_value) / $date->coop_card_value;
        }

        return ['saveon'=>$saveon * 100, 'coop' => $coop * 100];
    }

    public function orders(): View
    {
        $viewmodel = [];
        $dates = CutoffDate::has('orders')->orderby('cutoff', 'desc')->with('orders')->get();
        $dates->each(function($date) use (&$viewmodel) {
            $profits = $this->generateProfits($date);
            $dt = new \Carbon\Carbon($date->cutoff);
            $viewmodel[] = [
                'id' => $date->id,
                'delivery' => (new \Carbon\Carbon($date->delivery))->format('F jS Y'),
                'orders' => $date->orders->count(),
                'saveon' => $date->orders->sum('saveon') + $date->orders->sum('saveon_onetime'),
                'coop' => $date->orders->sum('coop') + $date->orders->sum('coop_onetime'),
                'saveon_profit' => $profits['saveon'],
                'coop_profit' => $profits['coop'],
            ];
        });

        return view('admin.orders', ['model' => $viewmodel]);
    }

    public function order(int $id) :View
    {
        $orders = Order::where('cutoff_date_id', '=', $id)
            ->select('orders.*')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->orderBy('users.name', 'asc')
            ->with('user')
            ->get();
        $pickup = $orders->filter(function($order){ return ! $order->deliverymethod;});
        $mail = $orders->filter(function($order){ return $order->deliverymethod;});
        return view('admin.order', ['pickup'=>$pickup, 'mail'=>$mail, 'date' =>CutoffDate::find($id)->deliverydate()->format('F jS Y')]);
    }

    public function caft(StripeClient $stripeClient, $cutoffId)
    {
        $orders = Order::join('users as u', 'u.id', '=', 'orders.user_id')
            ->where('orders.cutoff_date_id','=',$cutoffId) //only this cutoff
            ->where('orders.payment', '=', 0) //only debit
            ->orderby('u.updated_at', 'desc') //sort by date
            ->select('orders.*') //only select orders, so that the users columns don't confuse eloquent (sigh)
            ->with('user') //eagerload user
            ->get();
        

        $viewmodel = [
            'New' => [],
            'Updated' => [],
            'Unchanged' => [],
        ];
        $total = 0;

        foreach($orders as $order) {
            $user = $order->user;    
            $stripeCustomer = $stripeClient->customers->retrieve($user->stripe_id);
            $total += $order->totalCards();
            if($cutoffId > 2) {
                $prevcutoff = CutoffDate::find($cutoffId - 1)->cutoffdate()->tz('UTC');
                $bucket = ($prevcutoff->lt($user->created_at) ? 'New' : ($prevcutoff->lt($user->updated_at) ? 'Updated' : 'Unchanged'));
            }
            else {
                $bucket = 'New';
            }

            $viewmodel[$bucket][] = [
                'order' => $order,
                'acct' =>$stripeCustomer->metadata['debit-account'],
                'transit' =>$stripeCustomer->metadata['debit-transit'],
                'institution' =>$stripeCustomer->metadata['debit-institution'],
            ];
        }

        return view('admin.caft', ['model'=>$viewmodel, 'total' => $total, 'cutoff'=>$cutoffId]); 

    }
}
