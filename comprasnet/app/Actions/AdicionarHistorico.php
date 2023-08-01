<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Models\Historico;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarHistorico extends ActionsCommon {

    /**
     * Adiciona/Atualiza Responsável de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addHistoricoContrato($data, $contrato_id, $command = null): void
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

            $historico->TxtReceitaDespesa = ActionsCommon::validaDataArray($data,'receita_despesa');
            $historico->NumContrato = ActionsCommon::validaDataArray($data,'numero');
            $historico->ObsHistorico = ActionsCommon::validaDataArray($data,'observacao');
            $historico->CodUG = ActionsCommon::validaDataArray($data,'ug');

            // Fornecedor
            $historico->TpFornecedor = ActionsCommon::validaDataArray($data,'fornecedor.tipo');
            $historico->NumCnpjCpf = str_replace(['.', '/', '-'], ['', '', ''], ActionsCommon::validaDataArray($data,'fornecedor.cnpj_cpf_idgener'));
            $historico->NomFornecedor = ActionsCommon::validaDataArray($data,'fornecedor.nome');

            $historico->TpContrato = ActionsCommon::validaDataArray($data,'tipo');
            $historico->CatContrato = ActionsCommon::validaDataArray($data,'categoria');
            $historico->NumProcesso = ActionsCommon::validaDataArray($data,'processo');
            $historico->DescObjeto = ActionsCommon::validaDataArray($data,'objeto');
            $historico->TxtInformacaoComplementar = ActionsCommon::validaDataArray($data,'informacao_complementar');
            $historico->DescModalidade = ActionsCommon::validaDataArray($data,'modalidade');
            $historico->NumLicitacao = ActionsCommon::validaDataArray($data,'licitacao_numero');
            $historico->DatAssinatura = ActionsCommon::validaDataArray($data,'data_assinatura');
            $historico->DatPublicacao = ActionsCommon::validaDataArray($data,'data_publicacao');
            $historico->DatVigenciaInicio = ActionsCommon::validaDataArray($data,'vigencia_inicio');
            $historico->DatVigenciaFim = ActionsCommon::validaDataArray($data,'vigencia_fim');
            $historico->ValInicial = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valor_inicial'));
            $historico->ValGlobal = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valor_global'));
            $historico->NumParcelas = intval(ActionsCommon::validaDataArray($data,'num_parcelas'));
            $historico->ValParcela = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valor_parcela'));

            $historico->ValGlobalNovo = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'novo_valor_global'));
            $historico->NumParcelasNovo = ActionsCommon::validaDataArray($data,'novo_num_parcelas');
            $historico->ValParcelaNovo = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'novo_valor_parcela'));
            $historico->DatInicioNovoValor = ActionsCommon::validaDataArray($data,'data_inicio_novo_valor');

            $historico->FlgRetroativo = ActionsCommon::validaDataArray($data,'retroativo');

            $historico->MesReferenciaRetroativoDE = ActionsCommon::validaDataArray($data,'retroativo_mesref_de');
            $historico->AnoReferenciaRetroativoDE = ActionsCommon::validaDataArray($data,'retroativo_anoref_de');
            $historico->MesReferenciaRetroativoATE = ActionsCommon::validaDataArray($data,'retroativo_mesref_ate');
            $historico->AnoReferenciaRetroativoATE = ActionsCommon::validaDataArray($data,'retroativo_anoref_ate');
            $historico->DatVencimentorRetroativo = ActionsCommon::validaDataArray($data,'retroativo_vencimento');
            $historico->ValRetroativo = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'retroativo_valor'));

            $historico->TxtFundamentoLegalAditivo = ActionsCommon::validaDataArray($data,'fundamento_legal_aditivo');

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