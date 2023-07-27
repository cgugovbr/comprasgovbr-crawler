<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Comprasnet\App\Models\Preposto;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarPreposto {

    /**
     * Adiciona/Atualiza Preposto de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addPrepostoContrato($data, $contrato_id, $command = null): void
    {
        try {
            $preposto = Preposto::firstOrNew(
                [
                    'IdPrepostoOriginal' => $data['id'],
                    'IdContrato' => $contrato_id
                ]
            );

            $preposto->IdPrepostoOriginal = $data['id'];
            $preposto->IdContrato = $contrato_id;
            $preposto->NomUsuario = (isset($data['usuario']) && $data['usuario'] <> '') ? $data['usuario'] : null;
            $preposto->EmlUsuario = (isset($data['email']) && $data['email'] <> '') ? $data['email'] : null;
            $preposto->TelFixo = (isset($data['telefonefixo']) && $data['telefonefixo'] <> '') ? $data['telefonefixo'] : null;
            $preposto->TelCelular = (isset($data['celular']) && $data['celular'] <> '') ? $data['celular'] : null;
            $preposto->TxtDocFormalizacao = (isset($data['doc_formalizacao']) && $data['doc_formalizacao'] <> '') ? $data['doc_formalizacao'] : null;
            $preposto->TxtInformacaoComplementar = (isset($data['informacao_complementar']) && $data['informacao_complementar'] <> '') ? $data['informacao_complementar'] : null;
            $preposto->DatInicio = (isset($data['data_inicio']) && $data['data_inicio'] <> '') ? $data['data_inicio'] : null;
            $preposto->DatFim = (isset($data['data_fim']) && $data['data_fim'] <> '') ? $data['data_fim'] : null;
            $preposto->SitPreposto = (isset($data['situacao']) && $data['situacao'] <> '') ? $data['situacao'] : null;

            $preposto->save();

            if ($command) {
                $command->info('Preposto ' . $data['id'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar preposto - Nome: ' . $data['usuario'] . ' | IdPrepostoOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}