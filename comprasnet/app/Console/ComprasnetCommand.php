<?php

namespace Comprasnet\App\Console;

use Comprasnet\App\Models\Fatura;
use Comprasnet\App\Models\Empenho;
use Comprasnet\App\Models\Contrato;
use Comprasnet\App\Models\Preposto;
use Comprasnet\App\Models\Historico;
use Comprasnet\App\Models\Cronograma;
use Comprasnet\App\Console\HttpCommand;

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

                $contrato = $this->addContrato($data, $situacaoContrato);

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

                $this->line('[Fim Contrato: ' . $data['id'] . ']--------------------------------------------------');
            }
        }
    }

    // Adiciona/Atualiza um Contrato
    public function addContrato($data, $situacaoContrato)
    {
        $contrato = Contrato::firstOrNew(['IdContrato' => $data['id']]);

        $contrato->IdContrato = $data['id'];
        $contrato->TxtReceitaDespesa = (isset($data['receita_despesa']) && $data['receita_despesa'] <> '') ? $data['receita_despesa'] : null;
        $contrato->NumContrato = (isset($data['numero' ]) && $data['numero'] <> '') ? $data['numero'] : null;

        $contrato->SitContrato = $situacaoContrato;

        // Contratante
        $contrato->CodOrgaoContratante = (isset($data['contratante']['orgao']['codigo'])) ? $data['contratante']['orgao']['codigo'] : null;
        $contrato->NomOrgaoContratante = (isset($data['contratante']['orgao']['nome'])) ? $data['contratante']['orgao']['nome'] : null;
        $contrato->CodUnidadeGestoraContratante = (isset($data['contratante']['orgao']['unidade_gestora']['codigo'])) ? $data['contratante']['orgao']['unidade_gestora']['codigo'] : null;
        $contrato->ResNomeUnidadeGestoraContratante = (isset($data['contratante']['orgao']['unidade_gestora']['nome_resumido'])) ? $data['contratante']['orgao']['unidade_gestora']['nome_resumido'] : null;
        $contrato->NomUnidadeGestoraContratante = (isset($data['contratante']['orgao']['unidade_gestora']['nome'])) ? $data['contratante']['orgao']['unidade_gestora']['nome'] : null;

        // Fornecedor
        $contrato->TpFornecedor = (isset($data['fornecedor']['tipo']) && $data['fornecedor']['tipo'] <> '') ? $data['fornecedor']['tipo'] : null;
        $contrato->NumCnpjCpf = (isset($data['fornecedor']['cnpj_cpf_idgener']) && $data['fornecedor']['cnpj_cpf_idgener'] <> '') ? str_replace(['.', '/', '-'], ['', '', ''], $data['fornecedor']['cnpj_cpf_idgener']) : null;
        $contrato->NomFornecedor = (isset($data['fornecedor']['nome']) && $data['fornecedor']['nome'] <> '') ? $data['fornecedor']['nome'] : null;

        $contrato->TpContrato = (isset($data['tipo']) && $data['tipo'] <> '') ? $data['tipo'] : null;
        $contrato->CatContrato = (isset($data['categoria']) && $data['categoria'] <> '') ? $data['categoria'] : null;
        $contrato->TxtSubcategoria = (isset($data['subcategoria']) && $data['subcategoria'] <> '') ? $data['subcategoria'] : null;
        $contrato->NomUnidadesRequisitantes = (isset($data['unidades_requisitantes']) && $data['unidades_requisitantes'] <> '') ? $data['unidades_requisitantes'] : null;
        $contrato->NumProcesso = (isset($data['processo']) && $data['processo'] <> '') ? $data['processo'] : null;
        $contrato->DescObjeto = (isset($data['objeto']) && $data['objeto'] <> '') ? $data['objeto'] : null;
        $contrato->TxtInformacaoComplementar = (isset($data['informacao_complementar']) && $data['informacao_complementar'] <> '') ? $data['informacao_complementar'] : null;
        $contrato->DescModalidade = (isset($data['modalidade']) && $data['modalidade'] <> '') ? $data['modalidade'] : null;
        $contrato->NumLicitacao = (isset($data['licitacao_numero']) && $data['licitacao_numero'] <> '') ? $data['licitacao_numero'] : null;
        $contrato->DatAssinatura = (isset($data['data_assinatura']) && $data['data_assinatura'] <> '') ? $data['data_assinatura'] : null;
        $contrato->DatPublicacao = (isset($data['data_publicacao']) && $data['data_publicacao'] <> '') ? $data['data_publicacao'] : null;
        $contrato->DatVigenciaInicio = (isset($data['data_publicacao']) && $data['data_publicacao'] <> '') ? $data['data_publicacao'] : null;
        $contrato->DatVigenciaFim = (isset($data['vigencia_fim']) && $data['vigencia_fim'] <> '') ? $data['vigencia_fim'] : null;
        $contrato->ValInicial = (isset($data['valor_inicial']) && $data['valor_inicial'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor_inicial']) : null;
        $contrato->ValGlobal = (isset($data['valor_global']) && $data['valor_global'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor_global']) : null;
        $contrato->NumParcelas = (isset($data['num_parcelas']) && $data['num_parcelas'] <> '') ? $data['num_parcelas'] : null;
        $contrato->ValParcela = (isset($data['valor_parcela']) && $data['valor_parcela'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor_parcela']) : null;
        $contrato->ValAcumulado = (isset($data['valor_acumulado']) && $data['valor_acumulado'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor_acumulado']) : null;
        $contrato->EndLinkEmpenhos = (isset($data['links']['empenhos']) && $data['links']['empenhos'] <> '') ? $data['links']['empenhos'] : null;
        $contrato->EndLinkCronograma = (isset($data['links']['cronograma']) && $data['links']['cronograma'] <> '') ? $data['links']['cronograma'] : null;
        $contrato->EndLinkHistorico = (isset($data['links']['historico']) && $data['links']['historico'] <> '') ? $data['links']['historico'] : null;
        $contrato->EndLinkPrepostos = (isset($data['links']['prepostos']) && $data['links']['prepostos'] <> '') ? $data['links']['prepostos'] : null;
        $contrato->EndLinkFaturas = (isset($data['links']['faturas']) && $data['links']['faturas'] <> '') ? $data['links']['faturas'] : null;
        $contrato->EndLinkResponsaveis = (isset($data['links']['responsaveis']) && $data['links']['responsaveis'] <> '') ? $data['links']['responsaveis'] : null;
        $contrato->EndLinkArquivos = (isset($data['links']['arquivos']) && $data['links']['arquivos'] <> '') ? $data['links']['arquivos'] : null;

        $contrato->save();

        $this->info('Contrato com id ' . $data['id'] . ' inserido/atualizado com sucesso!');

        return $contrato;
    }

    // Busca Empenhos de um Contrato
    public function getEmpenhosContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            foreach ($response as $data) {
                $this->addEmpenhoContrato($data, $contrato_id);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Empenho vinculado à este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Adiciona/Atualiza Empenho de um Contrato
    public function addEmpenhoContrato($data, $contrato_id)
    {
        $empenho = Empenho::firstOrNew(
            [
                'NumEmpenho' => $data['numero'],
                'IdContrato' => $contrato_id
            ]
        );

        $empenho->IdContrato = $contrato_id;
        $empenho->NumEmpenho = $data['numero'];
        $empenho->NomCredor = (isset($data['credor']) && $data['credor'] <> '') ? $data['credor'] : null;
        $empenho->TxtPlanoInterno = (isset($data['planointerno']) && $data['planointerno'] <> '') ? $data['planointerno'] : null;
        $empenho->DescNaturezaDepesa = (isset($data['naturezadespesa']) && $data['naturezadespesa'] <> '') ? $data['naturezadespesa'] : null;
        $empenho->ValEmpenhado = (isset($data['empenhado']) && $data['empenhado'] <> '') ? str_replace(['.', ','], ['', '.'], $data['empenhado']) : null;
        $empenho->ValALiquidar = (isset($data['aliquidar']) && $data['aliquidar'] <> '') ? str_replace(['.', ','], ['', '.'], $data['aliquidar']) : null;
        $empenho->ValLiquidado = (isset($data['liquidado']) && $data['liquidado'] <> '') ? str_replace(['.', ','], ['', '.'], $data['liquidado']) : null;
        $empenho->ValPago = (isset($data['pago']) && $data['pago'] <> '') ? str_replace(['.', ','], ['', '.'], $data['pago']) : null;
        $empenho->ValRPInscrito = (isset($data['rpinscrito']) && $data['rpinscrito'] <> '') ? str_replace(['.', ','], ['', '.'], $data['rpinscrito']) : null;
        $empenho->ValRPALiquidar = (isset($data['rpaliquidar']) && $data['rpaliquidar'] <> '') ? str_replace(['.', ','], ['', '.'], $data['rpaliquidar']) : null;
        $empenho->ValRPLiquidado = (isset($data['rpliquidado']) && $data['rpliquidado'] <> '') ? str_replace(['.', ','], ['', '.'], $data['rpliquidado']) : null;
        $empenho->ValRPPago = (isset($data['rppago']) && $data['rppago'] <> '') ? str_replace(['.', ','], ['', '.'], $data['rppago']) : null;

        $empenho->save();

        $this->info('Empenho ' . $data['numero'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
    }

    // Busca Cronogramas de um Contrato
    public function getCronogramasContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            // Delete cronograma do contrato
            Cronograma::where('IdContrato', '=', $contrato_id)->delete();

            foreach ($response as $data) {
                $this->addCronogramaContrato($data, $contrato_id);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Cronograma vinculado à este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Adiciona/Atualiza Cronograma de um Contrato
    public function addCronogramaContrato($data, $contrato_id)
    {
        $cronograma = new Cronograma;

        $cronograma->IdContrato = $contrato_id;
        $cronograma->TpCronograma = (isset($data['tipo']) && $data['tipo'] <> '') ? $data['tipo'] : null;
        $cronograma->NumCronograma = (isset($data['numero']) && $data['numero'] <> '') ? $data['numero'] : null;
        $cronograma->TxtReceitaDespesa = (isset($data['receita_despesa']) && $data['receita_despesa'] <> '') ? $data['receita_despesa'] : null;
        $cronograma->ObsCronograma = (isset($data['observacao']) && $data['observacao'] <> '') ? $data['observacao'] : null;
        $cronograma->MesReferencia = (isset($data['mesref']) && $data['mesref'] <> '') ? $data['mesref'] : null;
        $cronograma->AnoReferencia = (isset($data['anoref']) && $data['anoref'] <> '') ? $data['anoref'] : null;
        $cronograma->DatVencimento = (isset($data['vencimento']) && $data['vencimento'] <> '') ? $data['vencimento'] : null;
        $cronograma->FlgRetroativo = (isset($data['retroativo']) && $data['retroativo'] <> '') ? $data['retroativo'] : null;
        $cronograma->ValCronograma = (isset($data['valor']) && $data['valor'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor']) : null;

        $cronograma->save();

        $this->info('Cronograma ' . $data['numero'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
    }

    // Busca Históricos de um Contrato
    public function getHistoricosContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            // Deleta histórico do contrato
            Historico::where('IdContrato', '=', $contrato_id)->delete();

            foreach ($response as $data) {
                $this->addHistoricoContrato($data, $contrato_id);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Histórico vinculado à este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Adiciona/Atualiza Histórico de um Contrato
    public function addHistoricoContrato($data, $contrato_id)
    {
        $historico = new Historico;

        $historico->IdContrato = $contrato_id;

        $historico->TxtReceitaDespesa = (isset($data['receita_despesa']) && $data['receita_despesa'] <> '') ? $data['receita_despesa'] : null;
        $historico->NumContrato = (isset($data['numero']) && $data['numero'] <> '') ? $data['numero'] : null;
        $historico->ObsHistorico = (isset($data['observacao']) && $data['observacao'] <> '') ? $data['observacao'] : null;
        $historico->CodUG = (isset($data['ug']) && $data['ug'] <> '') ? $data['ug'] : null;

        // Fornecedor
        $historico->TpFornecedor = (isset($data['fornecedor']['tipo']) && $data['fornecedor']['tipo'] <> '') ? $data['fornecedor']['tipo'] : null;
        $historico->NumCnpjCpf = (isset($data['fornecedor']['cnpj_cpf_idgener']) && $data['fornecedor']['cnpj_cpf_idgener'] <> '') ? str_replace(['.', '/', '-'], ['', '', ''], $data['fornecedor']['cnpj_cpf_idgener']) : null;
        $historico->NomFornecedor = (isset($data['fornecedor']['nome']) && $data['fornecedor']['nome'] <> '') ? $data['fornecedor']['nome'] : null;

        $historico->TpContrato = (isset($data['tipo']) && $data['tipo'] <> '') ? $data['tipo'] : null;
        $historico->CatContrato = (isset($data['categoria']) && $data['categoria'] <> '') ? $data['categoria'] : null;
        $historico->NumProcesso = (isset($data['processo']) && $data['processo'] <> '') ? $data['processo'] : null;
        $historico->DescObjeto = (isset($data['objeto']) && $data['objeto'] <> '') ? $data['objeto'] : null;
        $historico->TxtInformacaoComplementar = (isset($data['informacao_complementar']) && $data['informacao_complementar'] <> '') ? $data['informacao_complementar'] : null;
        $historico->DescModalidade = (isset($data['modalidade']) && $data['modalidade'] <> '') ? $data['modalidade'] : null;
        $historico->NumLicitacao = (isset($data['licitacao_numero']) && $data['licitacao_numero'] <> '') ? $data['licitacao_numero'] : null;
        $historico->DatAssinatura = (isset($data['data_assinatura']) && $data['data_assinatura'] <> '') ? $data['data_assinatura'] : null;
        $historico->DatPublicacao = (isset($data['data_publicacao']) && $data['data_publicacao'] <> '') ? $data['data_publicacao'] : null;
        $historico->DatVigenciaInicio = (isset($data['data_publicacao']) && $data['data_publicacao'] <> '') ? $data['data_publicacao'] : null;
        $historico->DatVigenciaFim = (isset($data['vigencia_fim']) && $data['vigencia_fim'] <> '') ? $data['vigencia_fim'] : null;
        $historico->ValInicial = (isset($data['valor_inicial']) && $data['valor_inicial'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor_inicial']) : null;
        $historico->ValGlobal = (isset($data['valor_global']) && $data['valor_global'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor_global']) : null;
        $historico->NumParcelas = (isset($data['num_parcelas']) && $data['num_parcelas'] <> '') ? $data['num_parcelas'] : null;
        $historico->ValParcela = (isset($data['valor_parcela']) && $data['valor_parcela'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor_parcela']) : null;

        $historico->ValGlobalNovo = (isset($data['novo_valor_global']) && $data['novo_valor_global'] <> '') ? str_replace(['.', ','], ['', '.'], $data['novo_valor_global']) : null;
        $historico->NumParcelasNovo = (isset($data['novo_num_parcelas']) && $data['novo_num_parcelas'] <> '') ? $data['novo_num_parcelas'] : null;
        $historico->ValParcelaNovo = (isset($data['novo_valor_parcela']) && $data['novo_valor_parcela'] <> '') ? str_replace(['.', ','], ['', '.'], $data['novo_valor_parcela']) : null;
        $historico->DatInicioNovoValor = (isset($data['data_inicio_novo_valor']) && $data['data_inicio_novo_valor'] <> '') ? $data['data_inicio_novo_valor'] : null;

        $historico->FlgRetroativo = (isset($data['retroativo']) && $data['retroativo'] <> '') ? $data['retroativo'] : null;

        $historico->MesReferenciaRetroativoDE = (isset($data['retroativo_mesref_de']) && $data['retroativo_mesref_de'] <> '') ? $data['retroativo_mesref_de'] : null;
        $historico->AnoReferenciaRetroativoDE = (isset($data['retroativo_anoref_de']) && $data['retroativo_anoref_de'] <> '') ? $data['retroativo_anoref_de'] : null;
        $historico->MesReferenciaRetroativoATE = (isset($data['retroativo_mesref_ate']) && $data['retroativo_mesref_ate'] <> '') ? $data['retroativo_mesref_ate'] : null;
        $historico->AnoReferenciaRetroativoATE = (isset($data['retroativo_anoref_ate']) && $data['retroativo_anoref_ate'] <> '') ? $data['retroativo_anoref_ate'] : null;
        $historico->DatVencimentorRetroativo = (isset($data['retroativo_vencimento']) && $data['retroativo_vencimento'] <> '') ? $data['retroativo_vencimento'] : null;
        $historico->ValRetroativo = (isset($data['retroativo_valor']) && $data['retroativo_valor'] <> '') ? str_replace(['.', ','], ['', '.'], $data['retroativo_valor']) : null;

        $historico->save();

        $this->info('Historico para o contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
    }

    // Busca Empenhos de um Contrato
    public function getPrepostosContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            foreach ($response as $data) {
                $this->addPrepostoContrato($data, $contrato_id);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Preposto vinculado à este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Adiciona/Atualiza Preposto de um Contrato
    public function addPrepostoContrato($data, $contrato_id)
    {
        $preposto = Preposto::firstOrNew(
            [
                'IdPrepostoOriginal' => $data['id'],
                'IdContrato' => $contrato_id
            ]
        );

        $preposto->IdPrepostoOriginal = $data['id'];
        $preposto->IdContrato = $contrato_id;
        $preposto->NomUsuario = (isset($data['usuario']) && $data['usuario'] <> '') ? $data['usuario'] : null;
        $preposto->EmlUsuario = (isset($data['email']) && $data['email'] <> '') ? $data['email'] : null;
        $preposto->TelFixo = (isset($data['telefonefixo']) && $data['telefonefixo'] <> '') ? $data['telefonefixo'] : null;
        $preposto->TelCelular = (isset($data['celular']) && $data['celular'] <> '') ? $data['celular'] : null;
        $preposto->TxtDocFormalizacao = (isset($data['doc_formalizacao']) && $data['doc_formalizacao'] <> '') ? $data['doc_formalizacao'] : null;
        $preposto->TxtInformacaoComplementar = (isset($data['informacao_complementar']) && $data['informacao_complementar'] <> '') ? $data['informacao_complementar'] : null;
        $preposto->DatInicio = (isset($data['data_inicio']) && $data['data_inicio'] <> '') ? $data['data_inicio'] : null;
        $preposto->DatFim = (isset($data['data_fim']) && $data['data_fim'] <> '') ? $data['data_fim'] : null;
        $preposto->SitPreposto = (isset($data['situacao']) && $data['situacao'] <> '') ? $data['situacao'] : null;

        $preposto->save();

        $this->info('Preposto ' . $data['id'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
    }

    // Busca Empenhos de um Contrato
    public function getFaturasContrato($url, $contrato_id)
    {
        $response = $this->getData($url, true);

        if ($response) {
            foreach ($response as $data) {
                $this->addFaturaContrato($data, $contrato_id);
            }
        } else {
            $this->warn('----------------------------------------------------------------------');
            $this->warn('Não existe Fatura para este contrato.');
            $this->warn('----------------------------------------------------------------------');
        }
    }

    // Adiciona/Atualiza Fatura de um Contrato
    public function addFaturaContrato($data, $contrato_id)
    {
        $preposto = Fatura::firstOrNew(
            [
                'IdFaturaOriginal' => $data['id'],
                'IdContrato' => $contrato_id
            ]
        );

        $preposto->IdFaturaOriginal = $data['id'];
        $preposto->IdContrato = $contrato_id;
        $preposto->TipoListaFaturaId = (isset($data['tipolistafatura_id']) && $data['tipolistafatura_id'] <> '') ? $data['tipolistafatura_id'] : null;
        $preposto->TxtJustificativaFaturaId = (isset($data['justificativafatura_id']) && $data['justificativafatura_id'] <> '') ? $data['justificativafatura_id'] : null;
        $preposto->TxtSfPadraoId = (isset($data['sfadrao_id']) && $data['sfadrao_id'] <> '') ? $data['sfadrao_id'] : null;
        $preposto->NumFatura = (isset($data['numero']) && $data['numero'] <> '') ? $data['numero'] : null;
        $preposto->DatEmissao = (isset($data['emissao']) && $data['emissao'] <> '') ? $data['emissao'] : null;
        $preposto->DatPrazo = (isset($data['prazo']) && $data['prazo'] <> '') ? $data['prazo'] : null;
        $preposto->DatVencimento = (isset($data['vencimento']) && $data['vencimento'] <> '') ? $data['vencimento'] : null;
        $preposto->ValValor = (isset($data['valor']) && $data['valor'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor']) : null;
        $preposto->ValJuros = (isset($data['juros']) && $data['juros'] <> '') ? str_replace(['.', ','], ['', '.'], $data['juros']) : null;
        $preposto->ValMulta = (isset($data['multa']) && $data['multa'] <> '') ? str_replace(['.', ','], ['', '.'], $data['multa']) : null;
        $preposto->ValGlosa = (isset($data['glosa']) && $data['glosa'] <> '') ? str_replace(['.', ','], ['', '.'], $data['glosa']) : null;
        $preposto->ValValorLiquido = (isset($data['valorliquido']) && $data['valorliquido'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valorliquido']) : null;
        $preposto->NumProcesso = (isset($data['processo']) && $data['processo'] <> '') ? $data['processo'] : null;
        $preposto->DatProtocolo = (isset($data['protocolo']) && $data['protocolo'] <> '') ? $data['protocolo'] : null;
        $preposto->DatAteste = (isset($data['ateste']) && $data['ateste'] <> '') ? $data['ateste'] : null;
        $preposto->SitRepactuacao = (isset($data['repactuacao']) && $data['repactuacao'] <> '') ? $data['repactuacao'] : null;
        $preposto->TxtInfComplementar = (isset($data['infcomplementar']) && $data['infcomplementar'] <> '') ? $data['infcomplementar'] : null;
        $preposto->TxtMesRef = (isset($data['mesref']) && $data['mesref'] <> '') ? $data['mesref'] : null;
        $preposto->TxtAnoRef = (isset($data['anoref']) && $data['anoref'] <> '') ? $data['anoref'] : null;
        $preposto->SitFatura = (isset($data['situacao']) && $data['situacao'] <> '') ? $data['situacao'] : null;
        $preposto->TxtChaveNfe = (isset($data['chave_nfe']) && $data['chave_nfe'] <> '') ? $data['chave_nfe'] : null;

        $preposto->save();

        $this->info('Fatura ' . $data['id'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
    }
}
