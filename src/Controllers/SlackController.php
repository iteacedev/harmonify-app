<?php

namespace App\Controllers;

use App\Models\LastStatus;
use App\Services\Slack\SlackFormatter;
use App\Services\Slack\SlackService;

class SlackController
{
    private SlackFormatter $formatter;
    private SlackService $slackService;
    private LastStatus $lastStatus;

    public function __construct()
    {
        $this->formatter = new SlackFormatter();
        $this->slackService = new SlackService();
        $this->lastStatus = new LastStatus();
    }

    public function handle(array $spotifyData): void
    {
        if ($spotifyData['status_code'] === 204) {
            $this->clearStatus();
            return;
        }

        $status = $this->formatter->format($spotifyData);
        $lastStatus = $this->lastStatus->get() ?? '';

        if ($lastStatus !== $status) {
            $this->updateStatus($status);
        }
    }

    private function clearStatus(): void
    {
        $this->slackService->setStatus('', '');
        $this->lastStatus->save(['status' => '']);
    }

    private function updateStatus(string $status, ?string $emoji = null): void
    {
        $this->slackService->setStatus($status);
        $this->lastStatus->save(['status' => $status]);
    }
}
