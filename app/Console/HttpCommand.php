<?php

namespace App\Console;

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

        $stack = HandlerStack::create();
        $stack->push(RateLimiterMiddleware::perMinute(50));

        $this->access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2NvbnRyYXRvcy5jb21wcmFzbmV0Lmdvdi5ici9hcGkvdjEvYXV0aC9sb2dpbiIsImlhdCI6MTY1NjYwOTk0NiwiZXhwIjoxNjU2NjEzNTQ2LCJuYmYiOjE2NTY2MDk5NDYsImp0aSI6Inc0QkU4WDRlUFpJY2dWbzMiLCJzdWIiOjEzMzcsInBydiI6Ijc5MzY2MzU2Nzk1ODQ0NTM0MDgzNGFlY2NlZmZhNjM3MjMzODllZDcifQ.da0Fqfx7DYaXQoeiSZirjqHuxMPvMsvZzV37P7adOY0';

        $this->client = new Client([
            'verify' => false,
            'handler' => $stack,
            'headers' => [
                'Authorization' => "Bearer " . $this->access_token ?? 'not_valid'
            ],
//            'timeout' => 2
        ]);
    }

    /**
     * Busca dados
     *
     * @return mixed
     */
    public function getData($extended_url, $full_url = false)
    {
//        $this->getAccessToken('/auth/login');
//        die();

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
            $this->error('Houve algum erro na request...');

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

    /**
     * Gera token para o usuário do sistema
     *
     * @return void
     */
    public function getAccessToken($extended_url, $full_url = false) : void
    {
        $login_url = $full_url ? $extended_url : config('comprasnet.base_url') . $extended_url;
        $params = [
            'cpf' => config('comprasnet.usuario_sistema'),
            'passport' => config('comprasnet.senha_usuario')
        ];
        $this->comment('Gerando novo token [' . $login_url . ']');
        $this->comment(date('[Y-m-d H:i:s]'));

        try {
            $result = $this->client->post($login_url, [
                'form_params' => [
                    'cpf' => '922.096.531-34',
                    'passport' => '4iZUk7DKS3EAENmgfTk8zJhBn'
                ]
            ]);

            dd($result);
        } catch (GuzzleException $e) {
//        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
//        } catch (ClientException $e) {

            dd($e);
        }

//        $htppCall = Http::withOptions(['verify' => false])->withHeaders([
//            'Content-Type' => 'application/json',
//            'verify' => false,
//        ])->post($login_url, $params);
////        $htppCall= Http::withHeaders([
////            'Content-Type' => 'application/json',
////
////        ])->get("https://jsonplaceholder.typicode.com/todos",[]);
////        dd($htppCall->status());
////        dd($htppCall->object());
////        dd($htppCall->json());
////        dd($htppCall->clientError());
////        dd($htppCall->serverError());
//        dd($htppCall->body());

//        die();

        try {
            $stack = HandlerStack::create();
            $client = new Client([
                'curl' => [CURLOPT_SSL_VERIFYPEER => false],
//                'verify' => false,
                'handler' => $stack,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'multipart/form-data',
                ],
            ]);

            try {
                $response = $client->request('POST', $login_url, [
                    'form_params' => [
                        'cpf' => '922.096.531-34',
                        'passport' => '4iZUk7DKS3EAENmgfTk8zJhBn'
                    ]
                ]);

            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                dd($e->getMessage());
                dd(Psr7\Message::toString($e->getResponse()));
                dd(Psr7\Message::toString($e->getResponse()->getBody()->getContents()));
            }

            dd($response);
            return;

///            $result = $this->client->request();
//            $result = $this->client->request('POST', $login_url, [
//                'form_params' => [
//                    'cpf' => config('comprasnet.usuario_sistema'),
//                    'passport' => config('comprasnet.senha_usuario')
//                ]
//            ]);
            $result = $this->client->post($login_url, [
                'form_params' => [
                    'cpf' => config('comprasnet.usuario_sistema'),
                    'passport' => config('comprasnet.senha_usuario')
                ]
            ]);
//            dd($result);
        } catch (ClientException $e) {
            $this->error('Houve erro ao buscar novo token...');
            $this->error('getRequest:');
            $this->error(Psr7\Message::toString($e->getRequest()));
            $this->error('getResponse:');
            if ($e->hasResponse()) {
                $this->error(Psr7\Message::toString($e->getResponse()));
            }
            $this->error('fullError:');
            $this->error($e);
            $this->line('----------------------------------------------------------------------');
        }

        $this->access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2NvbnRyYXRvcy5jb21wcmFzbmV0Lmdvdi5ici9hcGkvdjEvYXV0aC9sb2dpbiIsImlhdCI6MTY1NjU5MjMzNSwiZXhwIjoxNjU2NTk1OTM1LCJuYmYiOjE2NTY1OTIzMzUsImp0aSI6IkhpUkFTMENKb2w4MXNpbWgiLCJzdWIiOjEzMzcsInBydiI6Ijc5MzY2MzU2Nzk1ODQ0NTM0MDgzNGFlY2NlZmZhNjM3MjMzODllZDcifQ.EFIdyDw3tIGdK_skoDkzMI-I9lh0JqffIP8uxlsX3Cg';
    }
}
