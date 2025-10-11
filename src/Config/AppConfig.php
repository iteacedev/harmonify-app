<?php
namespace App\Config;

use Dotenv\Dotenv;

class AppConfig
{
    public static function init()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }
}
