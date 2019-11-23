<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('queue:work --daemon')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('digest:daily')->dailyAt('00:01')->withoutOverlapping();
        $schedule->command('digest:daily')->dailyAt('00:02')->withoutOverlapping();
        $schedule->command('digest:daily')->dailyAt('00:03')->withoutOverlapping();
        $schedule->command('digest:daily')->dailyAt('00:04')->withoutOverlapping();
        $schedule->command('digest:daily')->dailyAt('00:05')->withoutOverlapping();
        $schedule->command('digest:weekly')->cron('1 0 * * 1')->withoutOverlapping();
        $schedule->command('digest:weekly')->cron('2 0 * * 1')->withoutOverlapping();
        $schedule->command('digest:weekly')->cron('3 0 * * 1')->withoutOverlapping();
        $schedule->command('digest:weekly')->cron('4 0 * * 1')->withoutOverlapping();
        $schedule->command('digest:weekly')->cron('5 0 * * 1')->withoutOverlapping();
        $schedule->command('digest:monthly')->monthlyOn(1,'00:01')->withoutOverlapping();
        $schedule->command('digest:monthly')->monthlyOn(1,'00:02')->withoutOverlapping();
        $schedule->command('digest:monthly')->monthlyOn(1,'00:03')->withoutOverlapping();
        $schedule->command('digest:monthly')->monthlyOn(1,'00:04')->withoutOverlapping();
        $schedule->command('digest:monthly')->monthlyOn(1,'00:05')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');

        Artisan::command('digest:daily',function(\App\Digest $digest){
                        $users=\App\User::whereHas('preferences',function($q){
                            $q->where('digest_freq','daily');
                        })->get();
			$digest->send($users);
        })->describe('Sends daily property digest to users who activated the service');
        Artisan::command('digest:weekly',function(\App\Digest $digest){
                        $users=\App\User::whereHas('preferences',function($q){
                            $q->where('digest_freq','weekly');
                        })->get();
			$digest->send($users);
        })->describe('Sends weekly property digest to users who activated the service');
        Artisan::command('digest:monthly',function(\App\Digest $digest){
                        $users=\App\User::whereHas('preferences',function($q){
                            $q->where('digest_freq','monthly');
                        })->get();
			$digest->send($users);
        })->describe('Sends monthly property digest to users who activated the service');
    }
}
