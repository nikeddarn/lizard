<?php

namespace App\Console;

use App\Console\Commands\SyncNewVendorProducts;
use App\Console\Commands\SyncUpdatedVendorProducts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // check for new products
        $schedule->command(SyncNewVendorProducts::class)
            ->twiceDaily(7, 14)
            ->withoutOverlapping();

        // check for price updated products
        $schedule->command(SyncUpdatedVendorProducts::class)
            ->everyThirtyMinutes()
            ->between('8:00', '18:00')
            ->withoutOverlapping();

        // retry failed jobs
        $schedule->command('queue:retry all')
            ->dailyAt('4');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
