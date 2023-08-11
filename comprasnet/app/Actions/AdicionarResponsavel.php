<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Models\Responsavel;
use Comprasnet\App\Mail\ErroImportacao;

class AdicionarResponsavel {

    /**
     * Adiciona/Atualiza Responsável de um Contrato
     *
     * @param $data
     * @param $contrato_id
     *
     * @return void
     */
    public static function addResponsavelContrato($data, $contrato_id, $command = null): void
    {
        try {
            $responsavel = Responsavel::firstOrNew(
                [
                    'IdResponsavelOriginal' => $data['id'],
                    'IdContrato' => $contrato_id
                ]
            );

            $responsavel->IdResponsavelOriginal = $data['id'];
            $responsavel->IdContrato = $contrato_id;
            $responsavel->NomUsuario = (isset($data['usuario']) && $data['usuario'] <> '') ? $data['usuario'] : null;
            $responsavel->TxtFuncaoId = (isset($data['funcao_id']) && $data['funcao_id'] <> '') ? $data['funcao_id'] : null;
            $responsavel->TxtInstalacaoId = (isset($data['instalacao_id']) && $data['instalacao_id'] <> '') ? $data['instalacao_id'] : null;
            $responsavel->TxtPortaria = (isset($data['portaria']) && $data['portaria'] <> '') ? $data['portaria'] : null;
            $responsavel->SitResponsavel = (isset($data['situacao']) && $data['situacao'] <> '') ? $data['situacao'] : null;
            $responsavel->DatInicio = (isset($data['data_inicio']) && $data['data_inicio'] <> '') ? $data['data_inicio'] : null;
            $responsavel->DatFim = (isset($data['data_fim']) && $data['data_fim'] <> '') ? $data['data_fim'] : null;

            $responsavel->save();

            if ($command) {
                $command->info('Responsável ' . $data['id'] . ' do contrato [' . $contrato_id . '] inserido/atualizado com sucesso!');
            }

        } catch (\Exception $e) {
            $message = 'Erro ao criar/atualizar responsável - Nome: ' . $data['usuario'] . ' | IdResponsavelOriginal: ' . $data['id'] . ' | IdContrato: ' . $contrato_id;
            ActionsCommon::errorHandler(
                'adicionar_responsavel',
                __METHOD__,
                $message,
                $e,
                $command
            );
        }
    }
}