<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;
use Comprasnet\App\Models\FaturaEmpenho;

class AdicionarFaturaEmpenho {

    /**
     * Adiciona vínculo entre Fatura e Empenho
     *
     * @param $data
     *
     * @return void
     */
    public static function addFaturaEmpenho(int $fatura_id, array $data, $command = null): void
    {
        try {
            FaturaEmpenho::where('IdFatura', '=', $fatura_id)->delete();

            FaturaEmpenho::insert(
                array_map(function($arr) use ($fatura_id) {
                    return [
                        'IdFatura' => $fatura_id,
                        'IdEmpenhoOriginal' => (isset($arr['id_empenho']) && $arr['id_empenho'] <> '') ? $arr['id_empenho'] : null,
                    ];
                }, $data)
            );

            if ($command) {
                $command->info('Fatura Empenho adicionada para a fatura: ' . $fatura_id);
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao adicionar vínculo da fatura empenho - Fatura: ' . $fatura_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}