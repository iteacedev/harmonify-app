<?php

namespace App\Services\Spotify;

use App\Helpers\ApiClient;
use App\Models\TokenStorage;

class SpotifyService
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private ApiClient $http;
    private TokenStorage $storage;

    public function __construct()
    {
        $this->clientId = $_ENV['SPOTIFY_CLIENT_ID'];
        $this->clientSecret = $_ENV['SPOTIFY_CLIENT_SECRET'];
        $this->redirectUri = $_ENV['REDIRECT_URI'];

        $this->http = new ApiClient();
        $this->storage = new TokenStorage();
    }

    public function redirectForAuth(): array
    {
        $authUrl = "https://accounts.spotify.com/authorize?" . http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'scope' => 'user-read-currently-playing'
        ]);
        header("Location: $authUrl");
        exit;
    }

    public function authenticate(string $code): void
    {
        $response = $this->http->request(
            'POST',
            'https://accounts.spotify.com/api/token',
            [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $this->redirectUri,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret
                ]
            ]
        );

        if ($response) {
            $response['expires_at'] = time() + $response['expires_in'];
            $this->storage->save($response);
        }
    }

    public function getCurrentlyPlaying(): array
    {
        $token = $this->storage->get();

        if (!$token) {
            return ['auth_required' => true];
        }

        if ($token['expires_at'] <= time()) {
            $token = $this->refreshToken($token['refresh_token']);
        }

        $data = $this->http->request(
            'GET',
            'https://api.spotify.com/v1/me/player/currently-playing',
            [
                'headers' => ['Authorization' => "Bearer {$token['access_token']}"]
            ]
        );

        return $data;
    }

    private function refreshToken(string $refreshToken): array
    {
        $data = $this->http->request(
            'POST',
            'https://accounts.spotify.com/api/token',
            [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret
                ]
            ]
        );

        if ($data) {
            $data['refresh_token'] = $data['refresh_token'] ?? $refreshToken;
            $data['expires_at'] = time() + $data['expires_in'];
            $this->storage->save($data);
        }

        return $data ?? [];
    }
}
