<?php

namespace Comprasnet\App\Console;

use Comprasnet\App\Models\Arquivo;
use Comprasnet\App\Models\Preposto;
use Comprasnet\App\Models\Cronograma;
use Comprasnet\App\Models\Responsavel;
use Comprasnet\App\Actions\ExcluirFatura;
use Comprasnet\App\Actions\ExcluirEmpenho;
use Comprasnet\App\Actions\AdicionarFatura;
use Comprasnet\App\Actions\AdicionarArquivo;
use Comprasnet\App\Actions\AdicionarEmpenho;
use Comprasnet\App\Actions\AdicionarPreposto;
use Comprasnet\App\Actions\AdicionarContrato;
use Comprasnet\App\Actions\AdicionarHistorico;
use Comprasnet\App\Actions\AdicionarCronograma;
use Comprasnet\App\Actions\AdicionarResponsavel;

class ComprasnetCommand extends HttpCommand
{
    public function __construct()
    {
        HttpCommand::__construct();
    }

    // Busca Contratos
    public function getContratos($url, $situacaoContrato = 'ativo', $importarArray = [])
    {
        $response = $this->getData($url);

        if ($response) {

            foreach ($response as $data) {

                $this->line('');
                $this->line('----------------------------------------------------------------------');
                $this->info('Importando Contrato ' . $data['id']);

                $contrato = AdicionarContrato::addContratoContrato($data, $situacaoContrato, $this);

                if (isset($importarArray['importarEmpenho']) && $importarArray['importarEmpenho'] == true && $contrato->EndLinkEmpenhos != '') {
                    $this->info('');
                    $this->info('Importando Empenhos do contrato ' . $data['id']);
                    $this->getEmpenhosContrato($contrato->EndLinkEmpenhos, $data['id']);
                }

                if (isset($importarArray['importarCronograma']) && $importarArray['importarCronograma'] == true && $contrato->EndLinkCronograma != '') {
                    $this->info('');
                    $this->info('Importando Cronograma do contrato ' . $data['id']);
                    $this->getCronogramasContrato($contrato->EndLinkCronograma, $data['id']);
                }

                if (isset($importarArray['importarHistorico']) && $importarArray['importarHistorico'] == true && $contrato->EndLinkHistorico != '') {
                    $this->info('');
                    $this->info('Importando Histórico do contrato ' . $data['id']);
                    $this->getHistoricosContrato($contrato->EndLinkHistorico, $data['id']);
                }

                if (isset($importarArray['importarPreposto']) && $importarArray['importarPreposto'] == true && $contrato->EndLinkPrepostos != '') {
                    $this->info('');
                    $this->info('Importando Prepostos do contrato ' . $data['id']);
                    $this->getPrepostosContrato($contrato->EndLinkPrepostos, $data['id']);
                }

                if (isset($importarArray['importarFatura']) && $importarArray['importarFatura'] == true && $contrato->EndLinkFaturas != '') {
                    $this->info('');
                    $this->info('Importando Faturas do contrato ' . $data['id']);
                    $this->getFaturasContrato($contrato->EndLinkFaturas, $data['id']);
                }

                if (isset($importarArray['importarResponsavel']) && $importarArray['importarResponsavel'] == true && $contrato->EndLinkResponsaveis != '') {
                    $this->info('');
                    $this->info('Importando Responsaveis do contrato ' . $data['id']);
                    $this->getResponsaveisContrato($contrato->EndLinkResponsaveis, $data['id']);
                }

                if (isset($importarArray['importarArquivo']) && $importarArray['importarArquivo'] == true && $contrato->EndLinkArquivos != '') {
                    $this->info('');
                    $this->info('Importando Arquivos do contrato ' . $data['id']);
                    $this->getArquivosContrato($contrato->EndLinkArquivos, $data['id']);
                }

                $this->line('[Fim Contrato: ' . $data['id'] . ']--------------------------------------------------');
            }
        }
    }

    // Busca Empenhos de um Contrato
    public function getEmpenhosContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            // Exclui dados antigos vinculados contrato
            ExcluirEmpenho::excluirEmpenhoRelacionadoPorContrato($contrato_id);

            foreach ($response as $data) {
                AdicionarEmpenho::addEmpenhoContrato($data, $contrato_id, $this);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Empenho vinculado à este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Busca Cronogramas de um Contrato
    public function getCronogramasContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            // Exclui dados antigos vinculados contrato
            Cronograma::where('IdContrato', '=', $contrato_id)->delete();

            foreach ($response as $data) {
                AdicionarCronograma::addCronogramaContrato($data, $contrato_id, $this);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Cronograma vinculado à este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Busca Históricos de um Contrato
    public function getHistoricosContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            foreach ($response as $data) {
                AdicionarHistorico::addHistoricoContrato($data, $contrato_id, $this);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Histórico vinculado à este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Busca Prepostos de um Contrato
    public function getPrepostosContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            // Exclui dados antigos vinculados contrato
            Preposto::where('IdContrato', '=', $contrato_id)->delete();

            foreach ($response as $data) {
                AdicionarPreposto::addPrepostoContrato($data, $contrato_id, $this);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Preposto vinculado à este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Busca Faturas de um Contrato
    public function getFaturasContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            // Exclui dados antigos vinculados contrato
            ExcluirFatura::excluirFaturaRelacionadosPorContrato($contrato_id);

            foreach ($response as $data) {
                AdicionarFatura::addFaturaContrato($data, $contrato_id, $this);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Fatura para este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Busca Responsáveis de um Contrato
    public function getResponsaveisContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            // Exclui dados antigos vinculados contrato
            Responsavel::where('IdContrato', '=', $contrato_id)->delete();

            foreach ($response as $data) {
                AdicionarResponsavel::addResponsavelContrato($data, $contrato_id, $this);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Responsavel para este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Busca Arquivos de um Contrato
    public function getArquivosContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            // Exclui dados antigos vinculados contrato
            Arquivo::where('IdContrato', '=', $contrato_id)->delete();

            foreach ($response as $data) {
                AdicionarArquivo::addArquivoContrato($data, $contrato_id, $this);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Arquivo para este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }
}
