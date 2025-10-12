<?php

namespace App\Config;

use Dotenv\Dotenv;

class AppConfig
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = dirname(__DIR__, 2);
    }

    public function init(): void
    {
        $this->loadEnv();
        $this->setDefaultTimezone();
        $this->createStorageDirectory();
    }

    private function createStorageDirectory(): void
    {
        $storagePath = $this->basePath . '/storage';

        if (!is_dir($storagePath)) {
            mkdir($storagePath);
        }
    }

    private function setDefaultTimezone(): void
    {
        $timezone = $_ENV['TIMEZONE'];
        date_default_timezone_set($timezone);
    }

    private function loadEnv(): void
    {
        $envFilePath = $this->basePath . '/.env';

        if (file_exists($envFilePath)) {
            $dotenv = Dotenv::createImmutable($this->basePath);
            $dotenv->load();
        }
    }
}
