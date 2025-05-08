<?php

namespace App\Console;

use App\Models\Company;
use App\Models\Crm;
use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\ScheduleModel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run the command every day at a specific time
        // $schedule->command('customers:inactivate')->dailyAt('02:00');

        // $schedule->command('sendEmail:appointmentReminder')->everyMinute();
        $schedule->command('sendEmail:appointmentReminder')->dailyAt('15:00');
        // $schedule->command('app:renewal-auto-reminder-email')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        // ...
        // Commands\InactivateCustomers::class,
        Commands\AppointmentReminderSendEmail::class,
        // Commands\RenewalAutoReminderEmail::class,
    ];
}
