<?php

namespace App\Http\Controllers;

use App\Models\CutoffDate;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
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

    public function order(int $id)// :View
    {

    }
}
