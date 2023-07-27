<?php

namespace Comprasnet\App\Actions;

use Comprasnet\App\Models\Contrato;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarContrato {

    /**
     * Adiciona/Atualiza Contrato de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addContratoContrato($data, $situacaoContrato, $command = null) : Contrato|bool
    {
        try {
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

            if ($command) {
                $command->info('Contrato com id ' . $data['id'] . ' inserido/atualizado com sucesso!');
            }

            return $contrato;

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar faturas - Número: ' . $data['numero'] . ' | IdContratoOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
            return false;
        }
    }
}