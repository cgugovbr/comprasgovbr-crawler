<?php

namespace Comprasnet\App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\DadosImportados;
use Comprasnet\App\Console\ComprasnetCommand;

class ComprasnetContratosOrgao extends ComprasnetCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comprasnet:contratos
                            {orgao? : Código do órgão (ex: 25000). Caso não seja informado será considerado a opção COMPRASNET_CODIGO_ORGAO no arquivo .env}
                            {--e|empenho : Importar empenhos do contrato}
                            {--c|cronograma : Importar cronograma do contrato}
                            {--i|historico : Importar histórico do contrato}
                            {--p|preposto : Importar prepostos do contrato}
                            {--f|fatura : Importar faturas do contrato}
                            {--email : Enviar email com relatório da execução}
                            {--email_to= : Email a ser enviado}
                            {--inativos : Importar os Contratos Inativos}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar TODOS os contratos de um órgão';

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
        $tipo = config('comprasnet.contratos.orgao');
        $orgao = null !== $this->argument('orgao') ? $this->argument('orgao') : config('comprasnet.orgao');

        // Verifica se existe o órgão ou retorna erro
        if (!$orgao) {
            $this->line('');
            $this->line('----------------------------------------------------------------------');
            $this->error('.: ERRO :.');
            $this->info('Parece que o órgão não foi configurado corretamente.');
            $this->info('Verifique o arquivo .env ou insira o órgão manualmente no comando:');
            $this->info('');
            $this->line('php artisan comprasnet:contratos 25000');
            $this->info('');
            $this->line('----------------------------------------------------------------------');
            return;
        }

        $url = $tipo . $orgao;

        $importarEmpenho = $this->option('empenho');
        $importarCronograma = $this->option('cronograma');
        $importarHistorico = $this->option('historico');
        $importarPreposto = $this->option('preposto');
        $importarFatura = $this->option('fatura');
        $importarInativos = $this->option('inativos');
        $enviarEmail = $this->option('email');
        $enviarEmailTo = $this->option('email_to');

        $this->line('');
        $this->line('----------------------------------------------------------------------');
        $this->line('Buscando todos os Contratos');
        $this->line('');
        $this->line('Orgao: ' . $orgao);
        $this->line('');
        $this->line('Empenhos: ' . ($importarEmpenho ? 'sim' : 'não'));
        $this->line('Cronograma: ' . ($importarCronograma ? 'sim' : 'não'));
        $this->line('Histórico: ' . ($importarHistorico ? 'sim' : 'não'));
        $this->line('Prepostos: ' . ($importarPreposto ? 'sim' : 'não'));
        $this->line('Fatura: ' . ($importarFatura ? 'sim' : 'não'));
        $this->line('Inativos: ' . ($importarInativos ? 'sim' : 'não'));
        $this->line('----------------------------------------------------------------------');
        $this->line('');
        $this->line('Isso pode demorar alguns minutos dependento da quantidade de dados');
        $this->line('e da velocidade de sua conexão');
        $this->line('');

        $importarArray = [
            'importarEmpenho' => $importarEmpenho,
            'importarCronograma' => $importarCronograma,
            'importarHistorico' => $importarHistorico,
            'importarPreposto' => $importarPreposto,
            'importarFatura' => $importarFatura,
        ];

        $this->getContratos($url, 'ativo', $importarArray);

        if ($importarInativos) {
            $tipo = config('comprasnet.contratos.inativo_orgao');
            $orgao = null !== $this->argument('orgao') ? $this->argument('orgao') : config('comprasnet.orgao');
            $url = $tipo . $orgao;

            $this->info('Buscando todos os Contratos INATIVOS');
            $this->getContratos($url, 'inativo', $importarArray);
        }

        if ($enviarEmail) {
            if ($enviarEmailTo) {
                Mail::to($enviarEmailTo)->send(new DadosImportados);
            } else {
                Mail::send(new DadosImportados);
            }
        }
    }
}
