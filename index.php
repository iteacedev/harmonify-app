<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\SlackController;
use App\Controllers\SpotifyController;
use App\Controllers\MastodonController;
use App\Helpers\Logger;
use App\Config\AppConfig;

$config = new AppConfig();
$config->init();

try {
    $spotifyController = new SpotifyController();
    $spotifyData = $spotifyController->handle();

    if ($spotifyData) {
        $slackController = new SlackController();
        $mastodonController = new MastodonController();
        $slackController->handle($spotifyData);
        $mastodonController->handle($spotifyData);
    }
} catch (Throwable $e) {
    $log = new Logger();
    $type = get_class($e);
    $message = $e->getMessage();
    $file = $e->getFile();
    $line = $e->getLine();
    $trace = $e->getTraceAsString();
    $log->save("[{$type}] {$message} in {$file} on line {$line}\nStack trace:\n{$trace}");
}
