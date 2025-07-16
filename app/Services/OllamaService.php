<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    protected string $baseUrl;
    protected string $defaultModel;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('ollama.api_url'), '/');
        $this->defaultModel = config('ollama.default_model');
    }

    public function ask(string $model, string $prompt, bool $stream = false)
    {
        $url = "{$this->baseUrl}/api/generate";
        $model = $model ?: $this->defaultModel;

        $payload = [
            'model' => $model,
            'prompt' => $prompt,
            'stream' => $stream,
            'options' => [
                'num_ctx' => 4096
            ]
        ];

        try {
            if ($stream) {
                return $this->streamResponse($url, $payload);
            }

            $response = Http::timeout(300)->post($url, $payload);

            if ($response->failed()) {
                Log::error('Ollama API error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception("Ollama API error: " . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Ollama service error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function streamResponse(string $url, array $payload)
    {
        return response()->stream(function () use ($url, $payload) {
            $response = Http::timeout(0)
                ->withOptions(['stream' => true])
                ->post($url, $payload);

            foreach ($response->stream() as $chunk) {
                if ($data = json_decode($chunk, true)) {
                    echo "data: " . json_encode($data) . "\n\n";
                    ob_flush();
                    flush();
                }
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    public function getModels(): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/api/tags");
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to get models', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getModelInfo(string $model): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/api/show", ['name' => $model]);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to get model info', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getAgents(): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/api/agents");
            return $response->json()['agents'] ?? [];
        } catch (\Exception $e) {
            Log::error('Failed to get agents', ['error' => $e->getMessage()]);
            return [];
        }
    }
}
