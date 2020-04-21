<?php

namespace template\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use template\Console\Commands\{
    GenerateSitemapCommand,
    Files\GetFileFromCloudCommand,
    Files\PushFileToCloudCommand,
    Files\RemoveFileFromCloudCommand,
    VersionCommand,
    TestLaravelEchoCommand
};

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateSitemapCommand::class,
        GetFileFromCloudCommand::class,
        PushFileToCloudCommand::class,
        RemoveFileFromCloudCommand::class,
        TestLaravelEchoCommand::class,
        VersionCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        if (!$this->app->environment('production')) {
            $this->registerCommand(new \checkCoverage\Console\Commands\CheckCoverageCommand());
        }

        require base_path('routes/console.php');
    }
}
