<?php

require_once __DIR__ . '/vendor/autoload.php';

/** @var Lib\Core\Application $app */
$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    $client = new Lib\Core\Client();
    $client->handle();
} catch (Exception $e) {
    Log::error($e);
    die($e);
}
