<?php

namespace Comprasnet\App\Actions;

use Comprasnet\App\Models\Fatura;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarFatura {

    /**
     * Adiciona/Atualiza Fatura de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addFaturaContrato($data, $contrato_id, $command = null): void
    {
        try {
            $fatura = Fatura::firstOrNew(
                [
                    'IdFaturaOriginal' => $data['id'],
                    'IdContrato' => $contrato_id
                ]
            );

            $fatura->IdFaturaOriginal = $data['id'];
            $fatura->IdContrato = $contrato_id;
            $fatura->TipoListaFaturaId = ActionsCommon::validaDataArray($data,'tipolistafatura_id');
            $fatura->TxtJustificativaFaturaId = ActionsCommon::validaDataArray($data,'justificativafatura_id');
            $fatura->TxtSfPadraoId = ActionsCommon::validaDataArray($data,'sfadrao_id');
            $fatura->NumFatura = ActionsCommon::validaDataArray($data,'numero');
            $fatura->DatEmissao = ActionsCommon::corrigeDataSqlServer(ActionsCommon::validaDataArray($data,'emissao'));
            $fatura->DatPrazo = ActionsCommon::corrigeDataSqlServer(ActionsCommon::validaDataArray($data,'prazo'));
            $fatura->DatVencimento = ActionsCommon::corrigeDataSqlServer(ActionsCommon::validaDataArray($data,'vencimento'));
            $fatura->ValValor = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valor'));
            $fatura->ValJuros = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'juros'));
            $fatura->ValMulta = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'multa'));
            $fatura->ValGlosa = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'glosa'));
            $fatura->ValValorLiquido = str_replace(['.', ','], ['', '.'], ActionsCommon::validaDataArray($data,'valorliquido'));
            $fatura->NumProcesso = ActionsCommon::validaDataArray($data,'processo');
            $fatura->DatProtocolo = ActionsCommon::corrigeDataSqlServer(ActionsCommon::validaDataArray($data,'protocolo'));
            $fatura->DatAteste = ActionsCommon::corrigeDataSqlServer(ActionsCommon::validaDataArray($data,'ateste'));
            $fatura->SitRepactuacao = ActionsCommon::validaDataArray($data,'repactuacao');
            $fatura->TxtInfComplementar = ActionsCommon::validaDataArray($data,'infcomplementar');
            $fatura->TxtMesRef = ActionsCommon::validaDataArray($data,'mesref');
            $fatura->TxtAnoRef = ActionsCommon::validaDataArray($data,'anoref');
            $fatura->SitFatura = ActionsCommon::validaDataArray($data,'situacao');
            $fatura->TxtChaveNfe = ActionsCommon::validaDataArray($data,'chave_nfe');

            $fatura->save();

            if (static::validaArray($data, 'dados_empenho')) {
                AdicionarFaturaEmpenho::addFaturaEmpenho($fatura->IdFatura, $data['dados_empenho'], $command);
            }

            if (static::validaArray($data, 'dados_referencia')) {
                AdicionarFaturaMesAno::addFaturaMesAno($fatura->IdFatura, $data['dados_referencia'], $command);
            }

            if (static::validaArray($data, 'dados_item_faturado')) {
                AdicionarFaturaItem::addFaturaItem($fatura->IdFatura, $data['dados_item_faturado'], $command);
            }


            if ($command) {
                $command->info('Fatura ' . $data['id'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = 'Erro ao criar/atualizar fatura - Número: ' . $data['numero'] . ' | IdFaturaOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            ActionsCommon::errorHandler(
                'adicionar_fatura',
                __METHOD__,
                $message,
                $e,
                $command
            );
        }
    }

    private static function validaArray($data, $texto): bool
    {
        return isset($data[$texto]) && $data[$texto] != null && is_array($data[$texto]) && count($data[$texto]) > 0;
    }
}