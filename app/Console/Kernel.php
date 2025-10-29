<?php

namespace App\Console;

use App\Http\Controllers\Admin\BorrowController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
{
//     $schedule->call(function () {
//         app(\App\Http\Controllers\Admin\BorrowController::class)->notifyDueDate();
//     })->dailyAt('10:00'); // kirim tiap jam  pagi
// }
    // Jalankan fungsi notifyDueDate() setiap menit
    $schedule->call(function () {
        // app(BorrowController::class)->notifyDueDate();
        app(BorrowController::class)->denda();
    })->everyMinute();
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
    }
}
