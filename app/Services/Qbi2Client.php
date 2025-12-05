<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Log;

class Qbi2Client
{
    protected Client $http;
    protected string $baseUrl;
    protected string $token;
    protected int $clientAppId;

    public function __construct()
    {
        $this->baseUrl     = rtrim((string) config('qbi2.base_url'), '/');
        $this->token       = (string) config('qbi2.token');
        $this->clientAppId = (int) (config('qbi2.client_app_id') ?? 510);

        $stack = HandlerStack::create();
        $stack->push(Middleware::retry(
            function ($retries, RequestInterface $request, $response = null, $exception = null) {
                if ($retries >= 2) {
                    return false;
                }

                if ($exception instanceof ConnectException) {
                    return true;
                }

                if ($response instanceof ResponseInterface) {
                    $code = $response->getStatusCode();
                    if (in_array($code, [429, 503, 504], true)) {
                        return true;
                    }
                }

                return false;
            },
            function ($retries) {
                // 0.5s, 2s, ...
                return 500 * ($retries + 1) * ($retries + 1);
            }
        ));

        $this->http = new Client([
            'base_uri'        => $this->baseUrl,
            'handler'         => $stack,
            'timeout'         => (float) config('qbi2.timeout', 45),
            'connect_timeout' => (float) config('qbi2.connect_timeout', 8),
            'headers'         => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ],
            'curl' => [
                \CURLOPT_TCP_KEEPALIVE => 1,
                \CURLOPT_TCP_KEEPIDLE  => 30,
                \CURLOPT_TCP_KEEPINTVL => 10,
            ],
        ]);
    }

    protected function request(string $method, string $uri, array $options = []): array
    {
        try {
            $res = $this->http->request($method, $uri, $options);

            return [
                'ok'     => true,
                'status' => $res->getStatusCode(),
                'data'   => json_decode((string) $res->getBody(), true),
            ];
        } catch (ConnectException $e) {
            Log::error('[QBI2] CONNECT TIMEOUT', [
                'method'  => $method,
                'uri'     => $uri,
                'options' => $options,
                'msg'     => $e->getMessage(),
            ]);

            return [
                'ok'     => false,
                'status' => 0,
                'error'  => 'TIMEOUT',
            ];
        } catch (RequestException $e) {
            $status = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
            $body   = $e->getResponse() ? (string) $e->getResponse()->getBody() : '';

            Log::error('[QBI2] HTTP ERROR', [
                'method'  => $method,
                'uri'     => $uri,
                'options' => $options,
                'status'  => $status,
                'body'    => $body,
                'msg'     => $e->getMessage(),
            ]);

            return [
                'ok'     => false,
                'status' => $status,
                'error'  => ($body !== '' ? $body : $e->getMessage()),
            ];
        }
    }

    protected function get(string $uri, array $options = []): array
    {
        return $this->request('GET', $uri, $options);
    }

    protected function post(string $uri, array $options = []): array
    {
        return $this->request('POST', $uri, $options);
    }

    protected function delete(string $uri, array $options = []): array
    {
        return $this->request('DELETE', $uri, $options);
    }

    protected function withClientId(array $params = []): array
    {
        return array_merge(['clienteAppId' => $this->clientAppId], $params);
    }

    // ==================== Endpoints públicos ====================

    public function buscarDiagnosticos(string $text): array
    {
        return $this->get('/apirecipe/GetDiagnostico', [
            'query' => $this->withClientId(['text' => $text]),
        ]);
    }

    public function buscarMedicamentos(string $value, int $numeroPagina = 0, array $extra = []): array
    {
        $query = $this->withClientId(array_merge(
            ['numeroPagina' => $numeroPagina],
            $extra
        ));

        return $this->get('/apirecipe/GetMedicamento/' . urlencode($value), [
            'query' => $query,
        ]);
    }

    public function financiadores(): array
    {
        return $this->get('/apirecipe/GetFinanciadores', [
            'query' => $this->withClientId(),
        ]);
    }

    public function crearReceta(array $payload): array
    {
        $payload['clienteAppId'] = $this->clientAppId;

        Log::info('[QBI2] crearReceta - POST JSON', [
            'json' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        return $this->post('/apirecipe/Receta', ['json' => $payload]);
    }

    public function anularReceta(string $hashId): array
    {
        return $this->delete('/apirecipe/Receta/' . $hashId, [
            'json' => ['clienteAppId' => $this->clientAppId],
        ]);
    }

    public function buscarPracticas(array $params = []): array
    {
        $query = $this->withClientId($params);

        return $this->get('/apirecipe/GetPracticas', [
            'query' => $query,
        ]);
    }
}
