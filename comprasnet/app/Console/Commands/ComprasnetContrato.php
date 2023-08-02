<?php

namespace Comprasnet\App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;
use Comprasnet\App\Mail\DadosImportados;
use Comprasnet\App\Actions\LogarAtividade;
use Comprasnet\App\Console\ComprasnetCommand;

class ComprasnetContrato extends ComprasnetCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comprasnet:contrato
                            {contrato : Número do Contrato (IdContrato - ex: 2660)}
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
                            {--timeout= : Tempo de resposta}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar um contrato e seus dados relacionados';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(4);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $contrato_id = $this->argument('contrato');
            $url = config('comprasnet.contratos.contrato') . '/id/' . $contrato_id;

            $importarEmpenho = $this->option('all') || $this->option('empenho');
            $importarCronograma = $this->option('all') || $this->option('cronograma');
            $importarHistorico = $this->option('all') || $this->option('historico');
            $importarPreposto = $this->option('all') || $this->option('preposto');
            $importarFatura = $this->option('all') || $this->option('fatura');
            $importarResponsavel = $this->option('all') || $this->option('responsavel');
            $importarArquivo = $this->option('all') || $this->option('arquivo');
            $importarPublicacao = $this->option('all') || $this->option('publicacao');
            $enviarEmail = $this->option('email');
            $enviarEmailTo = $this->option('email_to');

            $this->line('');
            $this->line('----------------------------------------------------------------------');
            $this->line('Buscando o contrato: ' . $contrato_id);
            $this->line('');
            $this->line('Empenhos: ' . ($importarEmpenho ? 'sim' : 'não'));
            $this->line('Cronograma: ' . ($importarCronograma ? 'sim' : 'não'));
            $this->line('Histórico: ' . ($importarHistorico ? 'sim' : 'não'));
            $this->line('Prepostos: ' . ($importarPreposto ? 'sim' : 'não'));
            $this->line('Fatura: ' . ($importarFatura ? 'sim' : 'não'));
            $this->line('Responsável: ' . ($importarResponsavel ? 'sim' : 'não'));
            $this->line('Arquivo: ' . ($importarArquivo ? 'sim' : 'não'));
            $this->line('Publicação: ' . ($importarPublicacao ? 'sim' : 'não'));
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
            ];

            $this->getContratos($url, $importarArray);

            LogarAtividade::handle(__METHOD__, 'importar');

            if ($enviarEmail) {
                if ($enviarEmailTo) {
                    Mail::to($enviarEmailTo)->send(new DadosImportados);
                } else {
                    Mail::send(new DadosImportados);
                }
            }
        } catch (\Exception $e) {
            Log::error('[ERRO] executando o comando comprasnet:contratos');
            Log::error($e);

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
