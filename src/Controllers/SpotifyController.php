<?php

namespace App\Controllers;

use App\Services\Spotify\SpotifyService;

class SpotifyController
{
    private SpotifyService $spotifyService;

    public function __construct()
    {
        $this->spotifyService = new SpotifyService();
    }

    public function handle(): array
    {
        if (isset($_GET['code'])) {
            $this->spotifyService->authenticate($_GET['code']);
        }

        $result = $this->spotifyService->getCurrentlyPlaying();

        if (isset($result['auth_required']) && $result['auth_required']) {
            $this->spotifyService->redirectForAuth();
        }

        return $result;
    }
}
