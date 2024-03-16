<?php

namespace App\Providers;

use App\Models\CutoffDate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /*
	* cutoff dates are the last day on which we can accept an order.
	*/
    private function getDates()
    {
        $target = (new \Carbon\Carbon('America/Los_Angeles'))->format('Y-m-d');

        $upcoming = CutoffDate::where('cutoff', '>=', $target)->orderBy('cutoff', 'asc')->first();
        return array_map(function ($d) {
            return $d->format('l, F jS');
        }, $upcoming->dates());
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Facades\View::composer('*', function (View $view) {
            $view->with('dates', $this->getDates());
        });
    }
}
