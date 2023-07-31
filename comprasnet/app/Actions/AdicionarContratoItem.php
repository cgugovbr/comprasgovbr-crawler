<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Models\ContratoItem;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarContratoItem {

    /**
     * Adiciona itens do Contrato
     *
     * @param $data
     *
     * @return void
     */
    public static function addContratoItem(int $contrato_id, array $data, $command = null): void
    {
        try {
            ContratoItem::where('IdContrato', '=', $contrato_id)->delete();

            ContratoItem::insert(
                array_map(function($arr) use ($contrato_id) {
                    return [
                        'IdContratoItemOriginal' => $arr['id'],
                        'IdContrato' => $contrato_id,
                        'TipId' => (isset($arr['tipo_id']) && $arr['tipo_id'] <> '') ? $arr['tipo_id'] : null,
                        'TipMaterial' => (isset($arr['tipo_material']) && $arr['tipo_material'] <> '') ? $arr['tipo_material'] : null,
                        'GrpId' => (isset($arr['grupo_id']) && $arr['grupo_id'] <> '') ? $arr['grupo_id'] : null,
                        'CatMatSerItemId' => (isset($arr['catmatseritem_id']) && $arr['catmatseritem_id'] <> '') ? $arr['catmatseritem_id'] : null,
                        'DescComplementar' => (isset($arr['descricao_complementar']) && $arr['descricao_complementar'] <> '') ? $arr['descricao_complementar'] : null,
                        'QtdItem' => (isset($arr['quantidade']) && $arr['quantidade'] <> '') ? $arr['quantidade'] : null,
                        'ValUnitario' => (isset($arr['valorunitario']) && $arr['valorunitario'] <> '') ? str_replace(['.', ','], ['', '.'], $arr['valorunitario']) : null,
                        'ValTotal' => (isset($arr['valortotal']) && $arr['valortotal'] <> '') ? str_replace(['.', ','], ['', '.'], $arr['valortotal']) : null,
                        'NumItemCompra' => (isset($arr['numero_item_compra']) && $arr['numero_item_compra'] <> '') ? $arr['numero_item_compra'] : null,
                        'datInicioItem' => (is_array($arr['data_inicio_item']) && isset($arr['data_inicio_item']['date']) && $arr['data_inicio_item']['date'] <> '') ? $arr['data_inicio_item']['date'] : null,
                    ];
                }, $data)
            );

            if ($command) {
                $command->info('Itens adicionados para o contrato: ' . $contrato_id . ' com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao adicionar itens do contrato: ' . $contrato_id;
            if ($command) {
                $command->error($message);
            }
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}