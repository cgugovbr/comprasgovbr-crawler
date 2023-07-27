<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Comprasnet\App\Models\Publicacao;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarPublicacao {

    /**
     * Adiciona/Atualiza Publicacao de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addPublicacaoContrato($data, $contrato_id, $command = null): void
    {
        try {
            $publicacao = Publicacao::firstOrNew(
                [
                    'IdPublicacaoOriginal' => $data['id'],
                    'IdContrato' => $contrato_id
                ]
            );

            $publicacao->IdPublicacaoOriginal = $data['id'];
            $publicacao->IdContrato = $contrato_id;
            $publicacao->IdHistoricoOriginal = (isset($data['contratohistorico_id']) && $data['contratohistorico_id'] <> '') ? $data['contratohistorico_id'] : null;
            $publicacao->DatPublicacao = (isset($data['data_publicacao']) && $data['data_publicacao'] <> '') ? $data['data_publicacao'] : null;
            $publicacao->IdStatusPublicacaoOriginal = (isset($data['status_publicacao_id']) && $data['status_publicacao_id'] <> '') ? $data['status_publicacao_id'] : null;
            $publicacao->SitStatus = (isset($data['status']) && $data['status'] <> '') ? $data['status'] : null;
            $publicacao->TxtTextoDOU = (isset($data['texto_dou']) && $data['texto_dou'] <> '') ? $data['texto_dou'] : null;
            $publicacao->UrlLinkPublicacao = (isset($data['link_publicacao']) && $data['link_publicacao'] <> '') ? $data['link_publicacao'] : null;

            $publicacao->save();

            if ($command) {
                $command->info('Publicacao ' . $data['id'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar publicação - IdPublicacaoOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}