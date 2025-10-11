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
        $status = $this->formatter->format($spotifyData);

        $lastStatus = $this->lastStatus->get() ?? '';

        if ($lastStatus != $status) {
            $this->slackService->setStatus($status);
            $this->lastStatus->save(['status' => $status]);
        }
    }
}
