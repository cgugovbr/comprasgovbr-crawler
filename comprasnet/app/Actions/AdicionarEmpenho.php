<?php

namespace Comprasnet\App\Actions;

use Comprasnet\App\Models\Empenho;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarEmpenho {

    /**
     * Adiciona/Atualiza Empenho de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addEmpenhoContrato($data, $contrato_id, $command = null)
    {
        try {
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

            if ($command) {
                $command->info('Empenho ' . $data['numero'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar empenho - Número: ' . $data['numero'] . ' | IdEmpenhoOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}