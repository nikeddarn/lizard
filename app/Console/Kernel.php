<?php

namespace App\Console;

use App\Console\Commands\SyncNewVendorProducts;
use App\Console\Commands\SyncUpdatedVendorProducts;
use App\Contracts\Vendor\SyncTypeInterface;
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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // check for new products
        $schedule->command(SyncNewVendorProducts::class)
            ->hourlyAt(27)
            ->withoutOverlapping()
            ->sendOutputTo(storage_path('logs/schedule.log'));

        // check for price updated products
        $schedule->command(SyncUpdatedVendorProducts::class)
            ->everyFifteenMinutes()
            ->withoutOverlapping()
            ->sendOutputTo(storage_path('logs/schedule.log'));


        // run queue worker
        $schedule->call(function () {
            // get queue worker pids
            $queueWorkerProcessesIds = $this->getQueueWorkerProcessIds();

            if (empty($queueWorkerProcessesIds)) {
                // command to run worker
                $command = 'php artisan queue:work --timeout=60 --sleep=3 --queue=' . SyncTypeInterface::INSERT_PRODUCT . ',' . SyncTypeInterface::UPDATE_PRODUCT;

                // run 'queue:work' command
                shell_exec($command);

                Log::channel('schedule')->info('Start queue worker: ' . $command);
            }
        })
            ->everyFifteenMinutes()
            ->after(function () {
                // get queue worker pids anew
                $queueWorkerProcessesIds = $this->getQueueWorkerProcessIds();

                // kill overlapping processes
                if (count($queueWorkerProcessesIds) > 1) {
                    $this->killOverlappedQueueWorkerProcesses();
                }
            });

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

    /**
     * Get processes ids of queue worker.
     *
     * @return array
     */
    private function getQueueWorkerProcessIds()
    {
        return array_filter(explode(PHP_EOL, shell_exec("ps aux -ww | grep '[q]ueue:work' | awk '{print $2}'")));
    }

    /**
     * Kill overlapping queue work processes.
     */
    private function killOverlappedQueueWorkerProcesses()
    {
        do {
            $processId = $this->getQueueWorkerProcessIds()[0];

            $command = 'kill -9 ' . $processId;

            shell_exec($command);

            Log::channel('schedule')->info('Stop multiply queue worker: ' . $command);
        } while (count($this->getQueueWorkerProcessIds()) > 1);
    }
}
