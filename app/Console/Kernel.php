<?php

namespace App\Console;

use App\Mail\ErroImportacao;
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
        $current_date = Carbon::now()->toDateString();
        $file_path = './storage/logs/' . $current_date . '-compranetcontratos_importacao.log';

        $data = [
            'file_path' => $file_path
        ];

        $schedule->command('comprasnet:contratos -e -c -i --inativos')
            ->cron('0 5 * * *')
            ->sendOutputTo($file_path)
            // ->onSuccess(function () use ($data) {
            //     Mail::send(new DadosImportados($data));
            // })
            ->onFailure(function () use ($data) {
//                Mail::send(new ErroImportacao($data));
                // @todo - make new mailable with symfony mailable
            });
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
