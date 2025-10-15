<?php

namespace App\Services\Mastodon;

use App\Helpers\ApiClient;
use App\Helpers\Env;

class MastodonService
{
    private ApiClient $http;

    public function __construct()
    {
        $url = Env::get('MASTODON_URL');
        $token = Env::get('MASTODON_ACCESS_TOKEN');
        $this->http = new ApiClient([
            'base_uri' => $url,
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Content-Type' => 'application/json;charset=utf-8'
            ]
        ]);
    }

    public function postStatus(string $status, string $visibility = "unlisted"): void
    {
        $payload = [
            'form_params' => [
                'status' => $status,
                'visibility' => $visibility
            ],
        ];

        $this->http->request('POST', 'api/v1/statuses', $payload);
    }
}
