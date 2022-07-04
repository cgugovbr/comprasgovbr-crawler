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
        $this->fillGuzzleClient();

        if (! $this->accessTokenIsValid()) {
            $this->generateNewAccessToken();
            $this->fillGuzzleClient();
        }

        if (! $this->accessTokenIsValid()) {
            /**
             * @TODO
             * - enviar email ao adm
             * - parar todas as execuções
             */
            dd('erro em updateAccessToken...');
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
        $this->comment('Gerando novo Access Token...');
        $url = config('comprasnet.base_url') . '/auth/login';
        $params = [
            'cpf' => config('comprasnet.usuario_sistema'),
            'password' => config('comprasnet.senha_usuario'),
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//        curl_setopt($curl, CURLOPT_HTTPHEADER, [
//            "Content-Type: application/json",
//        ]);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close ($curl);
        if ($httpcode == 200) {
            $json_response = json_decode($response, true);
            if (is_array($json_response) && isset($json_response['access_token'])) {
                $this->access_token = $json_response['access_token'];
                $this->info('Token gerado com sucesso!');
//                $this->info($this->access_token);
            }
        }
    }

    private function fillGuzzleClient(): void
    {
        $stack = HandlerStack::create();
        $stack->push(RateLimiterMiddleware::perMinute(50));

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
