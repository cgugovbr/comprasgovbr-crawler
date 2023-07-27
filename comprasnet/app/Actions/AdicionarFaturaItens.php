<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Models\FaturaItens;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarFaturaItens {

    /**
     * Adiciona vínculo entre Fatura, Mês e Ano
     *
     * @param $data
     *
     * @return void
     */
    public static function addFaturaItens(int $fatura_id, array $data, $command = null): void
    {
        try {
            FaturaItens::where('IdFatura', '=', $fatura_id)->delete();

            FaturaItens::insert(
                array_map(function($arr) use ($fatura_id) {
                    return [
                        'IdFatura' => $fatura_id,
                        'IdItemContratoOriginal' => (isset($arr['id_item_contrato']) && $arr['id_item_contrato'] <> '') ? $arr['id_item_contrato'] : null,
                        'QtdQuantidadeFaturada' => (isset($arr['quantidade_faturado']) && $arr['quantidade_faturado'] <> '') ? str_replace(['.', ','], ['', '.'], $arr['quantidade_faturado']) : null,
                        'ValValorUnitarioFaturado' => (isset($arr['valorunitario_faturado']) && $arr['valorunitario_faturado'] <> '') ? str_replace(['.', ','], ['', '.'], $arr['valorunitario_faturado']) : null,
                        'ValValorTotalFaturado' => (isset($arr['valortotal_faturado']) && $arr['valortotal_faturado'] <> '') ? str_replace(['.', ','], ['', '.'], $arr['valortotal_faturado']) : null,
                    ];
                }, $data)
            );

            if ($command) {
                $command->info('Fatura Itens adicionada para a fatura: ' . $fatura_id);
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao adicionar vínculo da fatura itens - Fatura: ' . $fatura_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}