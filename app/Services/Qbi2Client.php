<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Qbi2Client
{
    protected $http, $baseUrl, $token, $clientAppId;

    public function __construct()
    {
        $this->baseUrl     = rtrim(config('qbi2.base_url'), '/');
        $this->token       = config('qbi2.token');
        $this->clientAppId = config('qbi2.client_app_id');

        $this->http = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => (int) config('qbi2.timeout'),
            'headers'  => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer '.$this->token,
            ],
        ]);
    }

    public function buscarDiagnosticos(string $text)
    {
        return $this->get('/apirecipe/GetDiagnostico', [
            'query' => ['text' => $text, 'clienteAppId' => $this->clientAppId],
        ]);
    }

    public function buscarMedicamentos(string $value, int $numeroPagina = 0, array $extra = [])
    {
        $query = array_merge(['clienteAppId' => $this->clientAppId, 'numeroPagina' => $numeroPagina], $extra);
        return $this->get("/apirecipe/GetMedicamento/".urlencode($value), ['query' => $query]);
    }

    public function financiadores()
    {
        return $this->get('/apirecipe/GetFinanciadores');
    }

    public function crearReceta(array $payload)
    {
        // Asegurar clienteAppId en body
        $payload['clienteAppId'] = $this->clientAppId;
        return $this->post('/apirecipe/Receta', ['json' => $payload]);
    }

    public function anularReceta(string $hashId)
    {
        // La doc pide clienteAppId en el body del DELETE
        return $this->delete("/apirecipe/Receta/{$hashId}", ['json' => ['clienteAppId' => $this->clientAppId]]);
    }

    protected function get($uri, array $options = []) { return $this->request('GET', $uri, $options); }
    protected function post($uri, array $options = []) { return $this->request('POST', $uri, $options); }
    protected function delete($uri, array $options = []) { return $this->request('DELETE', $uri, $options); }

    protected function request($method, $uri, array $options = [])
    {
        try {
            $res = $this->http->request($method, $uri, $options);
            return [
                'ok'     => true,
                'status' => $res->getStatusCode(),
                'data'   => json_decode($res->getBody(), true)
            ];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $status = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
            $body   = $e->getResponse() ? (string) $e->getResponse()->getBody() : '';
            // LOG detallado
            Log::error('[QBI2] HTTP ERROR', [
                'method'  => $method,
                'uri'     => $uri,
                'options' => $options,
                'status'  => $status,
                'body'    => $body,
                'msg'     => $e->getMessage(),
            ]);
            return [ 'ok' => false, 'status' => $status, 'error' => $body ?: $e->getMessage() ];
        }
    }


    public function buscarPracticas(array $params = [])
    {
        $query = array_merge(['clienteAppId' => $this->clientAppId], $params);
        return $this->get('/apirecipe/GetPracticas', ['query' => $query]);
    }


}
