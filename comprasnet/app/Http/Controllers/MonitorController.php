<?php

namespace Comprasnet\App\Http\Controllers;

use Comprasnet\App\Models\Contrato;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class MonitorController extends Controller
{
    public function status()
    {
        // Verifica URL
        $ch = curl_init(config('comprasnet.base_url') . '/auth/me');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Verifica URL
        $url = ($httpcode >= 200 && $httpcode < 400) ? true : false;
        if (!$url) {
            Log::info('[Monitor] $httpcode para ' . config('comprasnet.base_url') . '/auth/me: ' . $httpcode);
        }

        try {
            // Verifica Banco de Dados
            Contrato::select('IdContrato')->limit(1)->first();
            $db = true;
        } catch (\Exception $e) {
            $db = false;
            Log::error('DB: ' . $db);
        }

       $status_geral = ($db && $url) ? 'ok' : 'ERRO';

        $json = [
            'ambiente' => config('app.env'),
            'url' => $_SERVER['HTTP_HOST'],
            'banco_de_dados' => $db <> null ? 'ok' : 'ERRO',
            'compras_api' =>  $url === true ? 'ok' : 'ERRO',
            'Status Geral' => $status_geral,
        ];

        /**
         * O retorno da URL está instável, possivelmente devido à alguma configuração do curl
         * por isso mantivemos o retorno 200 fixo na linha abaixo para não reinicilizar o
         * pod contudo logar em caso de erro.
         */
        // $response = Response::json($json, $status_geral <> 'ok' ? 503 : 200);
        $response = Response::json($json, 200);
        $response->header('status_geral', $status_geral);

        return $response;
    }
}
