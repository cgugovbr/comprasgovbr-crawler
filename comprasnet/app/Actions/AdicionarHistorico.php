<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Models\Historico;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarHistorico {

    /**
     * Adiciona/Atualiza Responsável de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addHistoricoContrato($data, $contrato_id, $command = null)
    {
        try {
            $historico = Historico::firstOrNew(
                [
                    'IdHistoricoOriginal' => $data['id'],
                    'IdContrato' => $contrato_id
                ]
            );

            $historico->IdHistoricoOriginal = $data['id'];

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

            if ($command) {
                $command->info('Historico ' . $data['numero'] . ' para o contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar histórico - Numero: ' . $data['numero'] . ' | IdHistoricoOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}