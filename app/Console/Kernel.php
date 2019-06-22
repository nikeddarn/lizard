<?php

namespace App\Console;

use App\Console\Commands\Backup\BackupApplication;
use App\Console\Commands\Backup\BackupDatabase;
use App\Console\Commands\Backup\BackupFiles;
use App\Console\Commands\SyncNewVendorProducts;
use App\Console\Commands\SyncUpdatedVendorProducts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {Log::info(date('H:i:s', time()));
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
            ->dailyAt('21:00');

        // backup db dump file
        $schedule->command(BackupDatabase::class)
            ->dailyAt('22:00');

        // backup application
//        $schedule->command(BackupApplication::class)
//            ->weeklyOn(7, '23:00');

        // backup files
        $schedule->command(BackupFiles::class)
            ->weeklyOn(6, '23:00');
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
