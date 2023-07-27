<?php

namespace Comprasnet\App\Actions;

use Comprasnet\App\Models\Arquivo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarArquivo {

    /**
     * Adiciona/Atualiza Arquivo de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addArquivoContrato($data, $contrato_id, $command = null)
    {
        try {
            $arquivo = Arquivo::firstOrNew(
                [
                    'IdArquivoOriginal' => $data['id'],
                    'IdContrato' => $contrato_id
                ]
            );

            $arquivo->IdArquivoOriginal = $data['id'];
            $arquivo->IdContrato = $contrato_id;
            $arquivo->TipArquivo = (isset($data['tipo']) && $data['tipo'] <> '') ? $data['tipo'] : null;
            $arquivo->NumProcesso = (isset($data['processo']) && $data['processo'] <> '') ? $data['processo'] : null;
            $arquivo->NumSequencialDocumento = (isset($data['sequencial_documento']) && $data['sequencial_documento'] <> '') ? $data['sequencial_documento'] : null;
            $arquivo->TxtDescricao = (isset($data['descricao']) && $data['descricao'] <> '') ? $data['descricao'] : null;
            $arquivo->TxtPathArquivo = (isset($data['path_arquivo']) && $data['path_arquivo'] <> '') ? $data['path_arquivo'] : null;
            $arquivo->OriArquivo = (isset($data['origem']) && $data['origem'] <> '') ? $data['origem'] : null;
            $arquivo->UrlLinkSei = (isset($data['link_sei']) && $data['link_sei'] <> '') ? $data['link_sei'] : null;

            $arquivo->save();

            if ($command) {
                $command->info('Arquivo ' . $data['id'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = '[ERRO] Erro ao criar/atualizar arquivo - Descrição: ' . $data['descricao'] . ' | IdArquivoOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            Log::error($message);
            Log::error($e);
            Mail::send(new ErroImportacao($message));
        }
    }
}