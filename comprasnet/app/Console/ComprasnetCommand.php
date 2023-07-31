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
use Comprasnet\App\Actions\AdicionarPublicacao;
use Comprasnet\App\Actions\AdicionarResponsavel;
use Comprasnet\App\Actions\AdicionarContratoItem;

class ComprasnetCommand extends HttpCommand
{
    public int $timeout = 5;

    public function __construct($timeout = null)
    {
        if ($timeout) {
            $this->timeout = $timeout;
        }

        HttpCommand::__construct($this->timeout);
    }

    // Busca Contratos
    public function getContratos($url, $importarArray = [])
    {
        $response = $this->getData($url);

        if ($response) {

            foreach ($response as $data) {

                $contrato_id = $data['id'];

                $this->line('');
                $this->line('----------------------------------------------------------------------');
                $this->info('Importando Contrato ' . $contrato_id);

                AdicionarContrato::addContrato($data, $this);

                $this->line('');
                $this->info('Importando itens do Contrato ' . $contrato_id);
                $this->getContratoItens($contrato_id);

                if (isset($importarArray['importarEmpenho']) && $importarArray['importarEmpenho'] == true) {
                    $this->info('');
                    $this->info('Importando Empenhos do contrato ' . $contrato_id);
                    $this->getEmpenhosContrato($contrato_id);
                }

                if (isset($importarArray['importarCronograma']) && $importarArray['importarCronograma'] == true) {
                    $this->info('');
                    $this->info('Importando Cronograma do contrato ' . $contrato_id);
                    $this->getCronogramasContrato($contrato_id);
                }

                if (isset($importarArray['importarHistorico']) && $importarArray['importarHistorico'] == true) {
                    $this->info('');
                    $this->info('Importando Histórico do contrato ' . $contrato_id);
                    $this->getHistoricosContrato($contrato_id);
                }

                if (isset($importarArray['importarPreposto']) && $importarArray['importarPreposto'] == true) {
                    $this->info('');
                    $this->info('Importando Prepostos do contrato ' . $contrato_id);
                    $this->getPrepostosContrato($contrato_id);
                }

                if (isset($importarArray['importarFatura']) && $importarArray['importarFatura'] == true) {
                    $this->info('');
                    $this->info('Importando Faturas do contrato ' . $contrato_id);
                    $this->getFaturasContrato($contrato_id);
                }

                if (isset($importarArray['importarResponsavel']) && $importarArray['importarResponsavel'] == true) {
                    $this->info('');
                    $this->info('Importando Responsaveis do contrato ' . $contrato_id);
                    $this->getResponsaveisContrato($contrato_id);
                }

                if (isset($importarArray['importarArquivo']) && $importarArray['importarArquivo'] == true) {
                    $this->info('');
                    $this->info('Importando Arquivos do contrato ' . $contrato_id);
                    $this->getArquivosContrato($contrato_id);
                }

                if (isset($importarArray['importarPublicacao']) && $importarArray['importarPublicacao'] == true) {
                    $this->info('');
                    $this->info('Importando Publicações do contrato ' . $contrato_id);
                    $this->getPublicacoesContrato($contrato_id);
                }

                $this->line('[Fim Contrato: ' . $contrato_id . ']--------------------------------------------------');
            }
        }
    }

    // Busca Empenhos de um Contrato
    public function getEmpenhosContrato($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/empenhos';
        $response = $this->getData($url);

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
    public function getCronogramasContrato($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/cronograma';
$this->info('aqui..');
        $response = $this->getData($url);
        $this->info('depois..');
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
    public function getHistoricosContrato($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/historico';

        $response = $this->getData($url);

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
    public function getPrepostosContrato($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/prepostos';

        $response = $this->getData($url);

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
    public function getFaturasContrato($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/faturas';

        $response = $this->getData($url);

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
    public function getResponsaveisContrato($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/responsaveis';

        $response = $this->getData($url);

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
    public function getArquivosContrato($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/arquivos';

        $response = $this->getData($url);

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

    // Busca Publicações
    public function getPublicacoesContrato($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/publicacoes';

        $response = $this->getData($url);

        if ($response && is_array($response) && count($response) > 0) {
            foreach ($response as $data) {
                AdicionarPublicacao::addPublicacaoContrato($data, $contrato_id, $this);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Publicação para este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Busca Itens do contrato
    public function getContratoItens($contrato_id)
    {
        $url = config('comprasnet.contratos.contrato') . '/' . $contrato_id . '/itens';

        $response = $this->getData($url);

        if ($response && is_array($response) && count($response) > 0) {
            AdicionarContratoItem::addContratoItem($contrato_id, $response, $this);
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe item para este contrato: ' . $contrato_id);
            $this->warn('----------------------------------------------------------------------');
        }
    }
}
