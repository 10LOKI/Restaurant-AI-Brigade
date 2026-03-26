<?php

namespace App\Services;

use LucianoTonet\GroqPHP\Groq;

class GroqService
{
    protected Groq $client;

    public function __construct()
    {
        $this->client = new Groq(config('services.groq.api_key'));
    }

    public function chat(string $prompt): string
    {
        $response = $this->client->chat()->completions()->create([
            'model'    => 'llama3-8b-8192',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $response['choices'][0]['message']['content'];
    }
}
