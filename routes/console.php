<?php

use App\Console\Commands\ChargeCreditCards;
use App\Console\Commands\DoDeadlineReminder;
use App\Console\Commands\DoPickupReminder;
use App\Console\Commands\GenerateOrders;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;

Schedule::command(DoDeadlineReminder::class,
                 [Carbon::now()->format('Y-m-d')])->timezone('America/Los_Angeles')->dailyAt('01:30');

Schedule::command(GenerateOrders::class,
                 [Carbon::now()->format('Y-m-d')])->timezone('America/Los_Angeles')->dailyAt('02:30');

Schedule::command(ChargeCreditCards::class,
                 [Carbon::now()->format('Y-m-d')])->timezone('America/Los_Angeles')->dailyAt('05:00');

Schedule::command(DoPickupReminder::class,
                 [Carbon::now()->format('Y-m-d')])->timezone('America/Los_Angeles')->dailyAt('07:00');
