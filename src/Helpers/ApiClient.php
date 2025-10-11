<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private Client $client;
    private Logger $logger;

    public function __construct(?array $config = [])
    {
        $this->client = new Client($config);
        $this->logger = new Logger();
    }

    public function request($method, $endpoint, ?array $options = null): ?array
    {
        try {
            $response = $this->client->request($method, $endpoint, $options);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            $this->logger->save("[ApiClient] {$method} {$endpoint} failed: {$e->getMessage()}");
            return null;
        }
    }
}