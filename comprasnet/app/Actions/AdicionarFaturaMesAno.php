<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Models\FaturaMesAno;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarFaturaMesAno {

    /**
     * Adiciona vínculo entre Fatura, Mês e Ano
     *
     * @param $data
     *
     * @return void
     */
    public static function addFaturaMesAno(int $fatura_id, array $data, $command = null): void
    {
        try {
            FaturaMesAno::where('IdFatura', '=', $fatura_id)->delete();

            FaturaMesAno::insert(
                array_map(function($arr) use ($fatura_id) {
                    return [
                        'IdFatura' => $fatura_id,
                        'TxtMesRef' => (isset($arr['mesref']) && $arr['mesref'] <> '') ? $arr['mesref'] : null,
                        'TxtAnoRef' => (isset($arr['anoref']) && $arr['anoref'] <> '') ? $arr['anoref'] : null,
                        'ValValorRef' => (isset($arr['valorref']) && $arr['valorref'] <> '') ? str_replace(['.', ','], ['', '.'], $arr['valorref']) : null,
                    ];
                }, $data)
            );

            if ($command) {
                $command->info('Fatura Mês Ano adicionada para a fatura: ' . $fatura_id);
            }

        } catch (\Exception $e) {
            $message = 'Erro ao adicionar vínculo da fatura mês e ano - Fatura: ' . $fatura_id;
            ActionsCommon::errorHandler(
                'adicionar_fatura_mes_ano',
                __METHOD__,
                $message,
                $e,
                $command
            );
        }
    }
}