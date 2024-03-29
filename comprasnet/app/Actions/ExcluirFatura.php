<?php

namespace Comprasnet\App\Actions;

use Comprasnet\App\Models\Fatura;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;
use Comprasnet\App\Models\FaturaEmpenho;

class ExcluirFatura {

    /**
     * Exccluir faturas e dados relacionados
     *
     * @param $contrato_id
     *
     * @return void
     */
    public static function excluirFaturaRelacionadosPorContrato(int $contrato_id): void
    {
        try {
            $faturas_id = Fatura::where('IdContrato', '=', $contrato_id)
                ->pluck('IdFatura')
                ->toArray() ?? [];
            FaturaEmpenho::whereIn('IdFatura', $faturas_id)->delete();
            Fatura::whereIn('IdFatura', $faturas_id)->delete();

        } catch (\Exception $e) {
            $message = 'Erro ao excluir fatura para o contrato: ' . $contrato_id;
            ActionsCommon::errorHandler(
                'adicionar_',
                __METHOD__,
                $message,
                $e
            );
        }
    }
}