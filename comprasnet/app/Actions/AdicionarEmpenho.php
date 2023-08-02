<?php

namespace Comprasnet\App\Actions;

use Comprasnet\App\Models\Empenho;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarEmpenho extends ActionsCommon {

    /**
     * Adiciona/Atualiza Empenho de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addEmpenhoContrato($data, $contrato_id, $command = null): void
    {
        try {
            $empenho = Empenho::firstOrNew(
                [
                    'NumEmpenho' => $data['numero'],
                    'IdContrato' => $contrato_id
                ]
            );

            $empenho->IdEmpenhoOriginal = $data['id'];
            $empenho->IdContrato = $contrato_id;
            $empenho->NumEmpenho = $data['numero'];
            $empenho->NomCredor = ActionsCommon::validaDataArray($data, 'credor');
            $empenho->TxtPlanoInterno = ActionsCommon::validaDataArray($data, 'planointerno');
            $empenho->DescNaturezaDepesa = ActionsCommon::validaDataArray($data, 'naturezadespesa');
            $empenho->ValEmpenhado = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data, 'empenhado'));
            $empenho->ValALiquidar = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data, 'aliquidar'));
            $empenho->ValLiquidado = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data, 'liquidado'));
            $empenho->ValPago = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data, 'pago'));
            $empenho->ValRPInscrito = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data, 'rpinscrito'));
            $empenho->ValRPALiquidar = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data, 'rpaliquidar'));
            $empenho->ValRPLiquidado = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data, 'rpliquidado'));
            $empenho->ValRPPago = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data, 'rppago'));

            $empenho->CodUnidadeGestora = ActionsCommon::validaDataArray($data, 'unidade_gestora');;
            $empenho->NumGestao = ActionsCommon::validaDataArray($data, 'gestao');;
            $empenho->DatEmissao = ActionsCommon::validaDataArray($data, 'data_emissao');;
            $empenho->TxtInformacaoComplementar = ActionsCommon::validaDataArray($data, 'informacao_complementar');;
            $empenho->TxtSisOrigem = ActionsCommon::validaDataArray($data, 'sistema_origem');;
            $empenho->TxtFonteRecurso = ActionsCommon::validaDataArray($data, 'fonte_recurso');;

            $empenho->save();

            if ($command) {
                $command->info('Empenho ' . $data['numero'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = 'Erro ao criar/atualizar empenho - Número: ' . $data['numero'] . ' | IdEmpenhoOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            ActionsCommon::errorHandler(
                'adicionar_empenho',
                __METHOD__,
                $message,
                $e,
                $command
            );
        }
    }
}