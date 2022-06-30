<?php

namespace App\Console;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiterMiddleware;

// use GuzzleHttp\Exception\RequestException;
// use GuzzleHttp\Exception\ClientException;

class HttpCommand extends Command
{
    /**
     * The Guzzle object
     *
     * @var guzzle
     */
    protected $client;

    /**
     * The API url
     *
     * @var string
     */
    protected $base_url;

    /**
     * The API token
     *
     * @var string
     */
    protected $access_token;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fillGuzzleClient();
    }

    /**
     * Busca dados
     *
     * @return mixed
     */
    public function getData($extended_url, $full_url = false)
    {
        if (! $this->accessTokenIsValid()) {
            $this->updateAccessToken();
            $this->fillGuzzleClient();
        }

        $this->base_url = $full_url ? $extended_url : config('comprasnet.base_url') . $extended_url;
        $this->comment(date('[Y-m-d H:i:s]'));

        try {
            $result = $this->client->get($this->base_url);

            if ($result && $result->getStatusCode() == 200) {
                $this->info('Requisição realizada com Sucesso!');
                $this->line('Código de resposta: ' . $result->getStatusCode());
                $this->line('Header: ' . $result->getHeader('content-type')[0]);

                $response = json_decode($result->getBody(), true);
                return $response;
            } else {
                $this->error('Houve algum erro na request...');
                $this->error('Código de resposta: ' . $result->getStatusCode());
                $this->line('----------------------------------------------------------------------');
                return false;
            }
//        } catch (GuzzleException $e) {
        } catch (ClientException $e) {
            $this->error('[getData] Erro ao buscar dados...');

            $this->error('getRequest:');
//            $this->error(Psr7\str($e->getRequest()));
            $this->error(Psr7\Message::toString($e->getRequest()));

            $this->error('getResponse:');
            if ($e->hasResponse()) {
//                $this->error(Psr7\str($e->getResponse()));
                $this->error(Psr7\Message::toString($e->getResponse()));
            }

            $this->error('fullError:');
            $this->error($e);

            $this->line('----------------------------------------------------------------------');
        }
    }

    private function accessTokenIsValid() : bool
    {
        $url = config('comprasnet.base_url') . '/auth/me';

        try {
            $result = $this->client->request('GET', $url);
            $response = json_decode($result->getBody(), true);
            if ($response && is_array($response) && isset($response['id'])) {
                return true;
            }
        } catch (GuzzleException $e) {
            $this->error('[accessTokenIsValid] Erro ao verificar token...');
            $this->error('getRequest:');
            $this->error(Psr7\Message::toString($e->getRequest()));

            if ($e->hasResponse()) {
                $this->error('getResponse:');
                $this->error(Psr7\Message::toString($e->getResponse()));
            }
            $this->error('fullError:');
            $this->error($e);
            $this->line('----------------------------------------------------------------------');
        }

        return false;
    }

    private function updateAccessToken() : void
    {
        $this->refreshAccessToken();

        if (! $this->accessTokenIsValid()) {
            $this->generateNewAccessToken();
        }

        if (! $this->accessTokenIsValid()) {
            /**
             * @TODO
             * - enviar email ao adm
             * - parar todas as execuções
             */
            die();
        }
    }

    /**
     * Gera token para o usuário do sistema
     *
     * @return void
     */
    private function refreshAccessToken() : void
    {
        $url = config('comprasnet.base_url') . '/auth/refresh';

        $this->comment('Atualizando token [' . $url . ']');

        try {
            $result = $this->client->request('POST', $url);
            $response = json_decode($result->getBody(), true);

            if ($response) {
                $this->access_token = $response['access_token'] ?? null;
                $this->comment('Access Token:');
                $this->comment($this->access_token);

                // Comentar linha abaixo:
                $this->comment('Access token:');
                $this->comment($this->access_token);
            }
        } catch (GuzzleException $e) {
            $this->error('[refreshAccessToken] Erro ao buscar dados...');
            $this->error('getRequest:');
            $this->error(Psr7\Message::toString($e->getRequest()));

            if ($e->hasResponse()) {
                $this->error('getResponse:');
                $this->error(Psr7\Message::toString($e->getResponse()));
            }
            $this->error('fullError:');
            $this->error($e);
            $this->line('----------------------------------------------------------------------');
        }
    }

    private function generateNewAccessToken() : void
    {
        dd('Gerar novo access token');
    }

    private function fillGuzzleClient(): void
    {
        $stack = HandlerStack::create();
        $stack->push(RateLimiterMiddleware::perMinute(50));

        $this->access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2NvbnRyYXRvcy5jb21wcmFzbmV0Lmdvdi5ici9hcGkvdjEvYXV0aC9sb2dpbiIsImlhdCI6MTY1NjYyMjM4NiwiZXhwIjoxNjU2NjI1OTg2LCJuYmYiOjE2NTY2MjIzODYsImp0aSI6IjNCT0ZjaDdZUVNZQ2VxR1QiLCJzdWIiOjEzMzcsInBydiI6Ijc5MzY2MzU2Nzk1ODQ0NTM0MDgzNGFlY2NlZmZhNjM3MjMzODllZDcifQ.W3sWZwR4xN1orFkKIqpD6e7fBH0wxDt9ykjAcIzkA68';

        $this->client = new Client([
            'verify' => false,
            'handler' => $stack,
            'headers' => [
                'Authorization' => "Bearer " . $this->access_token
            ],
//            'timeout' => 2
        ]);
    }
}


//protected function apiRestPostRequest($url, $json_params){
//    $curl = curl_init();
//    curl_setopt($curl, CURLOPT_URL, $url);
//    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_params);
//    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//        "Content-Type: application/json",
//        "chave-api:".$this->ws_chave_api
//    ));
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//    $response = curl_exec($curl);
//    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//    curl_close ($curl);
//    if($httpcode==200)
//        return $response;
//    else {
//        $response = "[".$httpcode . "] Erro ao informar os dados ao e-Aud (".$response.")";
//        throw new Exception($response, $httpcode);
//    }
//}
