<?php

namespace App\Helpers;

class Logger
{
    private string $logFile;

    public function __construct()
    {
        $this->logFile = __DIR__ . '/../../storage/app.log';
    }

    public function save(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $logEntry = "[{$date}] {$message}\n";

        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}
