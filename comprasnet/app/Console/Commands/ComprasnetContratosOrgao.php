<?php

namespace Comprasnet\App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;
use Comprasnet\App\Mail\DadosImportados;
use Comprasnet\App\Actions\LogarAtividade;
use Comprasnet\App\Console\ComprasnetCommand;

class ComprasnetContratosOrgao extends ComprasnetCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comprasnet:contratos:orgao
                            {orgao? : Código do órgão (ex: 25000). Caso não seja informado será considerado a opção COMPRASNET_CODIGO_ORGAO no arquivo .env}
                            {--e|empenho : Importar empenhos do contrato}
                            {--c|cronograma : Importar cronograma do contrato}
                            {--i|historico : Importar histórico do contrato}
                            {--p|preposto : Importar prepostos do contrato}
                            {--f|fatura : Importar faturas do contrato}
                            {--r|responsavel : Importar responsáveis do contrato}
                            {--a|arquivo : Importar arquivos do contrato}
                            {--u|publicacao : Importar publicações do contrato}
                            {--all : Importar todos os dados relacionados do contrato}
                            {--email : Enviar email com relatório da execução}
                            {--email_to= : Email a ser enviado}
                            {--inativos : Importar os Contratos Inativos}
                            {--depois= : Importar os Contratos depois do ID indicado}
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
        try {
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

            $url = config('comprasnet.contratos.orgao') . $orgao;

            $importarEmpenho = $this->option('all') || $this->option('empenho');
            $importarCronograma = $this->option('all') || $this->option('cronograma');
            $importarHistorico = $this->option('all') || $this->option('historico');
            $importarPreposto = $this->option('all') || $this->option('preposto');
            $importarFatura = $this->option('all') || $this->option('fatura');
            $importarResponsavel = $this->option('all') || $this->option('responsavel');
            $importarArquivo = $this->option('all') || $this->option('arquivo');
            $importarPublicacao = $this->option('all') || $this->option('publicacao');
            $importarInativos = $this->option('inativos');
            $enviarEmail = $this->option('email');
            $enviarEmailTo = $this->option('email_to');

            $importarAposContratoId = $this->option('depois');

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
            $this->line('Responsável: ' . ($importarResponsavel ? 'sim' : 'não'));
            $this->line('Arquivo: ' . ($importarArquivo ? 'sim' : 'não'));
            $this->line('Publicação: ' . ($importarPublicacao ? 'sim' : 'não'));
            $this->line('Inativos: ' . ($importarInativos ? 'sim' : 'não'));
            if ($importarAposContratoId) {
                $this->line('Depois do contrato ID: ' . $importarAposContratoId);
            }
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
                'importarResponsavel' => $importarResponsavel,
                'importarArquivo' => $importarArquivo,
                'importarPublicacao' => $importarPublicacao,
                'importarAposContratoId' => $importarAposContratoId,
            ];

            $this->getContratos($url, $importarArray);

            if ($importarInativos) {
                $tipo = config('comprasnet.contratos.inativo_orgao');
                $orgao = null !== $this->argument('orgao') ? $this->argument('orgao') : config('comprasnet.orgao');
                $url = $tipo . $orgao;

                $this->info('Buscando todos os Contratos INATIVOS');
                $this->getContratos($url, $importarArray);
            }

            LogarAtividade::handle(__METHOD__, 'importar');

            if ($enviarEmail) {
                if ($enviarEmailTo) {
                    Mail::to($enviarEmailTo)->send(new DadosImportados);
                } else {
                    Mail::send(new DadosImportados);
                }
            }
        } catch (\Exception $e) {

            Log::error('[ERRO] executando o comando comprasnet:contratos:orgao');
            Log::error($e);

            LogarAtividade::handle(__METHOD__, 'importar', 'error', $e);

            Mail::send(new ErroImportacao());

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
