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
            ->hourlyAt(27)
            ->withoutOverlapping();

        // check for price updated products
        $schedule->command(SyncUpdatedVendorProducts::class)
            ->everyFifteenMinutes()
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
