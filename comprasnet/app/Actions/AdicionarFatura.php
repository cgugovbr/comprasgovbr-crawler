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
            $fatura->TipoListaFaturaId = (isset($data['tipolistafatura_id']) && $data['tipolistafatura_id'] <> '') ? $data['tipolistafatura_id'] : null;
            $fatura->TxtJustificativaFaturaId = (isset($data['justificativafatura_id']) && $data['justificativafatura_id'] <> '') ? $data['justificativafatura_id'] : null;
            $fatura->TxtSfPadraoId = (isset($data['sfadrao_id']) && $data['sfadrao_id'] <> '') ? $data['sfadrao_id'] : null;
            $fatura->NumFatura = (isset($data['numero']) && $data['numero'] <> '') ? $data['numero'] : null;
            $fatura->DatEmissao = (isset($data['emissao']) && $data['emissao'] <> '') ? $data['emissao'] : null;
            $fatura->DatPrazo = (isset($data['prazo']) && $data['prazo'] <> '') ? $data['prazo'] : null;
            $fatura->DatVencimento = (isset($data['vencimento']) && $data['vencimento'] <> '') ? $data['vencimento'] : null;
            $fatura->ValValor = (isset($data['valor']) && $data['valor'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valor']) : null;
            $fatura->ValJuros = (isset($data['juros']) && $data['juros'] <> '') ? str_replace(['.', ','], ['', '.'], $data['juros']) : null;
            $fatura->ValMulta = (isset($data['multa']) && $data['multa'] <> '') ? str_replace(['.', ','], ['', '.'], $data['multa']) : null;
            $fatura->ValGlosa = (isset($data['glosa']) && $data['glosa'] <> '') ? str_replace(['.', ','], ['', '.'], $data['glosa']) : null;
            $fatura->ValValorLiquido = (isset($data['valorliquido']) && $data['valorliquido'] <> '') ? str_replace(['.', ','], ['', '.'], $data['valorliquido']) : null;
            $fatura->NumProcesso = (isset($data['processo']) && $data['processo'] <> '') ? $data['processo'] : null;
            $fatura->DatProtocolo = (isset($data['protocolo']) && $data['protocolo'] <> '') ? $data['protocolo'] : null;
            $fatura->DatAteste = (isset($data['ateste']) && $data['ateste'] <> '') ? $data['ateste'] : null;
            $fatura->SitRepactuacao = (isset($data['repactuacao']) && $data['repactuacao'] <> '') ? $data['repactuacao'] : null;
            $fatura->TxtInfComplementar = (isset($data['infcomplementar']) && $data['infcomplementar'] <> '') ? $data['infcomplementar'] : null;
            $fatura->TxtMesRef = (isset($data['mesref']) && $data['mesref'] <> '') ? $data['mesref'] : null;
            $fatura->TxtAnoRef = (isset($data['anoref']) && $data['anoref'] <> '') ? $data['anoref'] : null;
            $fatura->SitFatura = (isset($data['situacao']) && $data['situacao'] <> '') ? $data['situacao'] : null;
            $fatura->TxtChaveNfe = (isset($data['chave_nfe']) && $data['chave_nfe'] <> '') ? $data['chave_nfe'] : null;

            $fatura->save();

            if ($command) {
                $command->info('Fatura ' . $data['id'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar faturas - Número: ' . $data['numero'] . ' | IdFaturaOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}