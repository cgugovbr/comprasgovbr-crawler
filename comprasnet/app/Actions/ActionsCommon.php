<?php

namespace Comprasnet\App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Comprasnet\App\Mail\ErroImportacao;

class ActionsCommon {

    /**
     * Corrige retorno da data para limitar ano a '9999'
     *
     * @param string      $data
     * @param string|null $format
     *
     * @return string
     */
    public static function corrigeDataSqlServer(string $data, string $format = null): string
    {
        try {
            $format = $format ?? 'Y-m-d';
            $data_retorno = Carbon::createFromFormat($format, $data)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                $data_retorno = Carbon::createFromFormat('d-m-Y', $data)->format('Y-m-d');
                } catch (\Exception $e) {
                    try {
                        $datetime = mktime(
                            0,
                            0,
                            0,
                            substr($data, -5, 2),
                            substr($data, -2),
                            substr($data, 0, strlen($data)-6)
                        );

                        if (substr($data, 0, strlen($data)-6) > 9999) {
                            $data_retorno = date('Y-m-d', mktime(
                                0,
                                0,
                                0,
                                substr($data, -5, 2),
                                substr($data, -2),
                                9999
                            ));
                        } else {
                            $data_retorno = date('Y-m-d', $datetime);
                        }
                    } catch (\Exception $e) {
                        $data_retorno = null;
                    }
                }
        }

        return $data_retorno;
    }

    /**
     * Helper para extrair dados de um array
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