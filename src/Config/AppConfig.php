<?php

namespace App\Config;

use Dotenv\Dotenv;

class AppConfig
{
    public function __construct()
    {
        define('BASE_DIR', dirname(__DIR__, 2));
    }

    public function init(): void
    {
        $this->loadEnv();
        $this->setDefaultTimezone();
        $this->createStorageDirectory();
    }

    private function createStorageDirectory(): void
    {
        $storagePath = BASE_DIR . '/storage';

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
        $envFilePath = BASE_DIR . '/.env';

        if (file_exists($envFilePath)) {
            $dotenv = Dotenv::createImmutable(BASE_DIR);
            $dotenv->load();
        }
    }
}
