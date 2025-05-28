<?php

namespace App\Providers;

use App\Models\CutoffDate;
use App\Utilities\OrderUtilities;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /*
    * cutoff dates are the last day on which we can accept an order.
    */
    private function getDates() : Array
    {
        $target = (new \Carbon\Carbon('America/Los_Angeles'))->format('Y-m-d');

        $upcoming = CutoffDate::where('cutoff', '>=', $target)->orderBy('cutoff', 'asc')->first();
        if(!isset($upcoming)) //fallback: when there isn't a future cutoff, don't error
        {
            return ['cutoff'=>'', 'charge'=>'', 'delivery'=>''];
        }
        return array_map(fn ($d) => $d->format('l, F jS'), $upcoming->dates());
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Facades\View::composer('*', function (View $view) {
            $view->with('dates', $this->getDates());
        });

        //fix up impersonation to work with Sanctum
        Event::listen(
            \Lab404\Impersonate\Events\TakeImpersonation::class,
            function (\Lab404\Impersonate\Events\TakeImpersonation $event) {
                session()->put('password_hash_sanctum', $event->impersonated->getAuthPassword());
            }
        );
        Event::listen(
            \Lab404\Impersonate\Events\LeaveImpersonation::class,
            function (\Lab404\Impersonate\Events\LeaveImpersonation $event) {
                session()->put('password_hash_sanctum', $event->impersonator->getAuthPassword());
            }
        );

        //define a rate limiter for Resend queued emails
        RateLimiter::for('resend-emails', function (object $job) {
            return Limit::perSecond(2);
        });
    }
}
