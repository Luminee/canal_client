<?php

Dotenv\Dotenv::create(base_path())->load();

$app = Lib\Core\Application::getInstance();

$app->singleton('console', Symfony\Component\Console\Application::class);

$app->singleton('config', Lib\Core\Config::class);

$app->singleton('log', Lib\Core\Log::class);

$providers = config('app.providers');
foreach ($providers as $key => $provider) {

    $app->provider(is_numeric($key) ? $provider : $key, $provider);
}

foreach ($providers as $key => $provider) {
    $app->provider(is_numeric($key) ? $provider : $key)->register();
}

foreach ($providers as $key => $provider) {
    $app->provider(is_numeric($key) ? $provider : $key)->boot();
}

return $app;

