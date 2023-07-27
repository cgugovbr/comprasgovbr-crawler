<?php

namespace Comprasnet\App\Actions;

use Comprasnet\App\Models\Empenho;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class ExcluirEmpenho {

    /**
     * Exccluir empenhos e dados relacionados
     *
     * @param $contrato_id
     *
     * @return void
     */
    public static function excluirEmpenhoRelacionadoPorContrato(int $contrato_id): void
    {
        try {
            /**
             * Optamos por não excluir o vínculo com a fatura nesta função.
             * Se for removido a ligação entre fatura e empenho somente
             * seria refeita quando buscar as faturas novamente na API.
             */
            // $empenho_original_ids = Empenho::where('IdContrato', '=', $contrato_id)
            //     ->pluck('IdEmpenhoOriginal')
            //     ->toArray() ?? [];
            // FaturaEmpenhos::whereIn('IdEmpenhoOriginal', $empenho_original_ids)->delete();

            Empenho::where('IdContrato', '=', $contrato_id)->delete();

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao excluir empenho para o contrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}