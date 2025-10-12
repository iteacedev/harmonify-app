<?php

namespace App\Services\Slack;

class SlackFormatter
{
    private int $maxLength = 100;

    public function format(array $spotifyData): string
    {
        $track = $spotifyData['item']['name'];

        $artists = array_map(function ($artist) {
            return $artist['name'];
        }, $spotifyData['item']['artists']);

        $status = "Listening to {$track} – " . implode(', ', $artists);
        $status = $this->sanitize($status);

        if (mb_strlen($status) > $this->maxLength) {
            $status = mb_substr($status, 0, $this->maxLength - 3) . '...';
        }

        return $status;
    }

    private function sanitize(string $text): string
    {
        $text = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', '', $text);
        return $text;
    }
}
