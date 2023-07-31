<?php

namespace Comprasnet\App\Http\Controllers;

use Comprasnet\App\Models\Contrato;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class MonitorController extends Controller
{
    public function status()
    {
        // Verifica Brasil Cidadão
        $ch = curl_init(config('comprasnet.base_url') . '/auth/me');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Verifica URL
        $url = ($httpcode >= 200 && $httpcode < 400) ? true : false;

        try {
            // Verifica Banco de Dados
            Contrato::limit(1)->first();
            $db = true;
        } catch (\Exception $e) {
            $db = false;
        }

       $status_geral = ($db && $url) ? 'ok' : 'ERRO';

        $json = [
            'ambiente' => config('app.env'),
            'url' => $_SERVER['HTTP_HOST'],
            'banco_de_dados' => $db <> null ? 'ok' : 'ERRO',
            'compras_api' =>  $url === true ? 'ok' : 'ERRO',
            'Status Geral' => $status_geral,
        ];

        $response = Response::json($json, $status_geral <> 'ok' ? 503 : 200);
        $response->header('status_geral', $status_geral);

        return $response;
    }
}
