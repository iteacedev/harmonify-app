<?php

namespace App\Controllers;

use App\Models\LastToot;
use App\Services\Mastodon\MastodonService;
use App\Services\Mastodon\MastodonFormatter;

class MastodonController
{
    private MastodonFormatter $formatter;
    private MastodonService $mastodonService;
    private LastToot $lastToot;

    public function __construct()
    {
        $this->formatter = new MastodonFormatter();
        $this->mastodonService = new MastodonService();
        $this->lastToot = new LastToot();
    }

    public function handle(array $spotifyData): void
    {
        if ($spotifyData['status_code'] === 204) {
            return;
        }

        $status = $this->formatter->format($spotifyData);

        if (!$status) {
            return;
        }

        $lastToot = $this->lastToot->get() ?? '';

        if ($lastToot !== $status) {
            $this->postStatus($status);
        }
    }

    private function postStatus(string $status, ?string $visibility = null): void
    {
        $this->mastodonService->postStatus($status);
        $this->lastToot->save(['status' => $status]);
    }
}
