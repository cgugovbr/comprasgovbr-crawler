<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class ActionsCommon {

    /**
     * Helper para extrai dados de um array
     *
     * @param $array
     * @param ...$keys
     * @return string|null
     */
    public static function validaDataArray($array, ...$keys) : string|null
    {
        foreach ($keys as $key) {
            $array_dot = Arr::dot($array);
            if (isset($array_dot[$key]) && !is_array($array_dot[$key]) && $array_dot[$key] <> '') {
                return $array_dot[$key];
            }
        }

        return null;
    }

    /**
     * Função para padronizar as saídas de erro da aplicação
     *
     * @param $tipo_erro
     * @param $origem_erro
     * @param $mensagem
     * @param $exception
     * @param $command
     *
     * @return void
     */
    public static function errorHandler($tipo_erro, $origem_erro, $mensagem = null, $exception = null, $command = null)
    {
        try {
            $local_message = $mensagem ? '[ERRO] ' . $mensagem : '[ERRO] Erro no método "' . $origem_erro . '"';

            Log::error($local_message);
            if ($exception) {
                Log::error($exception);
            }
            if ($command) {
                $command->error($local_message);
            }
            LogarAtividade::handle(
                $origem_erro,
                $tipo_erro,
                'error',
                '{' .
                'message: ' . $local_message . ', ' .
                'exception: ' . ($exception ?? 'N/A') .
                '}'
            );
            Mail::send(new ErroImportacao($local_message));
        } catch (\Exception $e) {

        }
    }
}