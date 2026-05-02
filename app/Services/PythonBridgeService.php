<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class PythonBridgeService
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('PYTHON_SERVICE_URL', 'http://django:8001'), '/');
        $this->token   = hash_hmac('sha256', 'python-bridge', config('app.key'));
    }

    public function get(string $path, array $query = []): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout(10)
            ->get($this->url($path), $query);

        return $this->parse($response);
    }

    public function post(string $path, array $data = []): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout(10)
            ->post($this->url($path), $data);

        return $this->parse($response);
    }

    private function headers(): array
    {
        return [
            'X-Php-Token' => $this->token,
            'Accept'      => 'application/json',
        ];
    }

    private function url(string $path): string
    {
        return $this->baseUrl . '/' . ltrim($path, '/');
    }

    private function parse(Response $response): ?array
    {
        if ($response->failed()) {
            return null;
        }
        $body = $response->json();
        // Unwrap DRF envelope: { status, data, message }
        return $body['data'] ?? $body;
    }
}
