<?php

namespace App\Services\Mastodon;

class MastodonFormatter
{
    public function format(array $spotifyData): string
    {
        if ($spotifyData['currently_playing_type'] === 'episode') {
            return '';
        }

        if (!$spotifyData['is_playing']) {
            return '';
        }

        $track = $spotifyData['item']['name'];

        $artists = array_map(function ($artist) {
            return $artist['name'];
        }, $spotifyData['item']['artists']);

        $status = ":spotify: Iris está ouvindo...\n {$track} - " . implode(', ', $artists);

        return $status;
    }
}
