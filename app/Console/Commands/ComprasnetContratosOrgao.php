<?php

namespace App\Console\Commands;

use App\Console\ComprasnetCommand;
use App\Mail\DadosImportados;
use Illuminate\Support\Facades\Mail;

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

        dd($tipo, $orgao, $url);

        $importaEmpenho = $this->option('empenho');
        $importaCronograma = $this->option('cronograma');
        $importaHistorico = $this->option('historico');
        $importaInativos = $this->option('inativos');
        $enviarEmail = $this->option('email');
        $enviarEmailTo = $this->option('email_to');

        $this->line('');
        $this->line('----------------------------------------------------------------------');
        $this->line('Buscando todos os Contratos');
        $this->line('');
        $this->line('Orgao: ' . $orgao);
        $this->line('');
        $this->line('Empenhos: ' . ($importaEmpenho ? 'sim' : 'não'));
        $this->line('Cronograma: ' . ($importaCronograma ? 'sim' : 'não'));
        $this->line('Histórico: ' . ($importaHistorico ? 'sim' : 'não'));
        $this->line('Inativos: ' . ($importaInativos ? 'sim' : 'não'));
        $this->line('----------------------------------------------------------------------');
        $this->line('');
        $this->line('Isso pode demorar alguns minutos dependento da quantidade de dados');
        $this->line('e da velocidade de sua conexão');
        $this->line('');

        $this->getContratos($url, $importaEmpenho, $importaCronograma, $importaHistorico);

        if ($importaInativos) {
            $tipo = config('comprasnet.contratos.inativo_orgao');
            $orgao = null !== $this->argument('orgao') ? $this->argument('orgao') : config('comprasnet.orgao');
            $url = $tipo . $orgao;
            $situacaoContrato = 'inativo';

            $this->info('Buscando todos os Contratos INATIVOS');
            $this->getContratos($url, $importaEmpenho, $importaCronograma, $importaHistorico, $situacaoContrato);
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
