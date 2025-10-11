<?php

namespace App\Services\Slack;

use App\Helpers\ApiClient;

class SlackService
{
    private ApiClient $http;

    public function __construct()
    {
        $token = $_ENV['SLACK_TOKEN_IPEWEB'];
        $this->http = new ApiClient([
            'base_uri' => 'https://slack.com/api/',
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Content-Type' => 'application/json;charset=utf-8'
            ]
        ]);
    }

    public function setStatus(string $status, string $emoji = ":gatinho_musica:"): void
    {
        $payload = [
            'json' => [
                'profile' => [
                    'status_text' => $status,
                    'status_emoji' => $emoji
                ]
            ],
        ];

        $this->http->request('POST', 'users.profile.set', $payload);
    }

    public function getCurrentStatus(): array
    {
        $data = $this->http->request('GET', 'users.profile.get');
        return $data['profile'] ?? [];
    }

    public function getPresence(): array
    {
        $response = $this->http->request('GET', 'users.getPresence');
        return $response ? $response : ['online' => false];
    }
}
