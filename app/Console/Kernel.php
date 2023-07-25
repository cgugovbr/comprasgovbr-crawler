<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;
use Comprasnet\App\Mail\DadosImportados;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Comprasnet\App\Console\Commands\ComprasnetContratosOrgao;
use Comprasnet\App\Console\Commands\ComprasnetFaturasContrato;
use Comprasnet\App\Console\Commands\ComprasnetEmpenhosContrato;
use Comprasnet\App\Console\Commands\ComprasnetHistoricoContrato;
use Comprasnet\App\Console\Commands\ComprasnetPrepostosContrato;
use Comprasnet\App\Console\Commands\ComprasnetCronogramaContrato;
use Comprasnet\App\Console\Commands\ComprasnetResponsaveisContrato;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ComprasnetContratosOrgao::class,
        ComprasnetEmpenhosContrato::class,
        ComprasnetHistoricoContrato::class,
        ComprasnetCronogramaContrato::class,
        ComprasnetPrepostosContrato::class,
        ComprasnetFaturasContrato::class,
        ComprasnetResponsaveisContrato::class,
    ];


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $current_date = Carbon::now()->toDateString();
        $file_path = './storage/logs/' . $current_date . '-compranet_crawler.log';

        $data = [
            'file_path' => $file_path
        ];

        $schedule->command('comprasnet:contratos')
            ->cron('* 1 * * *')
            ->sendOutputTo($file_path)
            ->onSuccess(function () use ($data) {
                Mail::send(new DadosImportados($data));
            })
            ->onFailure(function () use ($data) {
                Mail::send(new ErroImportacao($data));
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
        $this->load(__DIR__ . '/../../comprasnet/app/Console');
        $this->load(__DIR__ . '/../../comprasnet/app/Console/Commands');

        require base_path('routes/console.php');
    }
}
