<?php

namespace App\Services\Slack;

class SlackFormatter
{
    private int $maxLength = 80;

    public function format(array $spotifyData): string
    {
        if ($spotifyData['currently_playing_type'] === 'episode') {
            return "Tamires está ouvando um podcast. O Spotify não informa qual. Isso é triste.";
        }

        if (!$spotifyData['is_playing']) {
            return 'Tamires';
        }

        $track = $spotifyData['item']['name'];

        $artists = array_map(function ($artist) {
            return $artist['name'];
        }, $spotifyData['item']['artists']);

        $status = "Tamires tá ouçano {$track} - " . implode(', ', $artists);
        $status = $this->sanitize($status);

        if (mb_strlen($status) > $this->maxLength) {
            $status = mb_substr($status, 0, $this->maxLength - 3) . '...';
        }

        return $status;
    }

    private function sanitize(string $text): string
    {
        $text = preg_replace("/[^\p{L}\p{N}\s._,'\-\/]/u", '', $text);
        return $text;
    }
}
