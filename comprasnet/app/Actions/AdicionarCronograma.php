<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Models\Cronograma;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarCronograma {

    /**
     * Adiciona/Atualiza Cronograma de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addCronogramaContrato($data, $contrato_id, $command = null): void
    {
        try {
            $cronograma = Cronograma::firstOrNew(
                [
                    'IdCronogramaOriginal' => $data['id'],
                    'IdContrato' => $contrato_id
                ]
            );

            $cronograma->IdCronogramaOriginal = $data['id'];
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

            if ($command) {
                $command->info('Cronograma ' . $data['numero'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar cronograma - Número: ' . $data['numero'] . ' | IdCronogramaOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}