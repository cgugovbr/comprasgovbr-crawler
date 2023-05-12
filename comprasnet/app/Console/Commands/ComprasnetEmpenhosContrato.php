<?php

namespace Comprasnet\App\Console\Commands;

use Comprasnet\App\Models\Contrato;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\DadosImportados;
use Comprasnet\App\Console\ComprasnetCommand;

class ComprasnetEmpenhosContrato extends ComprasnetCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comprasnet:empenhos
                            {contrato : Número do Contrato (IdContrato - ex: 2660)}
                            {--email : Enviar email com relatório da execução}
                            {--email_to= : Email a ser enviado}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar os Empenhos de um Contrato';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contrato_id = $this->argument('contrato');
        $enviarEmail = $this->option('email');
        $enviarEmailTo = $this->option('email_to');

        $contrato = Contrato::find($contrato_id);

        if (!$contrato) {
            $this->warn('----------------------------------------------------------------------');
            $this->error('Não encontramos o contrato ' . $contrato_id . ' na base de dados.');
            $this->line('');
            $this->warn('Busque os dados do contrato antes de importar os empenhos:');
            $this->warn('');
            $this->info('php artisan comprasnet:contrato ' . $contrato_id);
            $this->warn('');
            $this->warn('ou verifique o código do contrato e tente novamente.');
            $this->warn('----------------------------------------------------------------------');
        } elseif ($contrato->EndLinkEmpenhos == '') {
            $this->info('----------------------------------------------------------------------');
            $this->info('Não existe empenho vinculado à este contrato.');
            $this->info('----------------------------------------------------------------------');
        } else {
            $this->line('----------------------------------------------------------------------');
            $this->line('Importando os empenhos do contrato ' . $contrato_id);
            $this->getEmpenhosContrato($contrato->EndLinkEmpenhos, $contrato_id);
            $this->line('----------------------------------------------------------------------');

            if ($enviarEmail) {
                if ($enviarEmailTo) {
                    Mail::to($enviarEmailTo)->send(new DadosImportados);
                } else {
                    Mail::send(new DadosImportados);
                }
            }
        }
    }
}
