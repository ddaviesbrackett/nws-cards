<?php

namespace Tests\Feature;

use App\Mail\DeadlineReminder;
use App\Models\CutoffDate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DoDeadlineReminderTest extends TestCase
{

    //TODO this leaves the Testing database with Cutoffdates in it. That's not very nice.
    use RefreshDatabase;

    public function test_sends_deadline_reminders_to_users_with_orders(): void
    {
        Mail::fake();

        // Command adds 2 days to the input date to find the cutoff
        CutoffDate::factory()->create([
            'cutoff' => '2026-04-03 23:59:59',
        ]);

        $userWithOrder = User::factory()->create([
            'saveon' => 2,
            'coop' => 0,
            'saveon_onetime' => 0,
            'coop_onetime' => 0,
        ]);

        $userWithoutOrder = User::factory()->create([
            'saveon' => 0,
            'coop' => 0,
            'saveon_onetime' => 0,
            'coop_onetime' => 0,
        ]);

        $this->artisan('app:do-deadline-reminder', ['date' => '2026-04-01'])
            ->expectsOutput('sent reminders to 1 users')
            ->assertExitCode(0);

        Mail::assertQueued(DeadlineReminder::class, function ($mail) use ($userWithOrder) {
            return $mail->hasTo($userWithOrder->email);
        });

        Mail::assertNotQueued(DeadlineReminder::class, function ($mail) use ($userWithoutOrder) {
            return $mail->hasTo($userWithoutOrder->email);
        });
    }

    public function test_warns_when_no_cutoff_two_days_after_date(): void
    {
        $this->artisan('app:do-deadline-reminder', ['date' => '2099-01-01'])
            ->expectsOutput('no cutoff 2 days after given date')
            ->assertExitCode(0);
    }

    public function test_does_not_warn_when_date_is_now_and_no_cutoff(): void
    {
        $this->artisan('app:do-deadline-reminder', ['date' => 'now'])
            ->doesntExpectOutput('no cutoff 2 days after given date')
            ->assertExitCode(0);
    }

    public function test_sends_reminders_to_multiple_users(): void
    {
        Mail::fake();

        CutoffDate::factory()->create([
            'cutoff' => '2026-04-03 23:59:59',
        ]);

        User::factory()->count(3)->create([
            'saveon' => 1,
            'coop' => 0,
            'saveon_onetime' => 0,
            'coop_onetime' => 0,
        ]);

        $this->artisan('app:do-deadline-reminder', ['date' => '2026-04-01'])
            ->expectsOutput('sent reminders to 3 users')
            ->assertExitCode(0);

        Mail::assertQueuedCount(3);
    }
}
