<?php

namespace Cortex\Foundation\Console;

use Illuminate\Foundation\Console\Kernel as BaseKernel;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;

class Kernel extends BaseKernel
{
    /**
     * The Artisan commands provided by the application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule($schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Register composer service directly to prevent binding errors
        if (!$this->app->has('composer')) {
            $this->app->singleton('composer', function ($app) {
                return new \Illuminate\Support\Composer($app['files'], $app->basePath());
            });
        }

        // Register all Laravel's built-in commands from ArtisanServiceProvider
        $provider = new ArtisanServiceProvider($this->app);
        $provider->register();

        // Load additional custom commands from application
        $this->load(__DIR__.'/../Commands');

        // Load console routes if they exist
        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }
}
