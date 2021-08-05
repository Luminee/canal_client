<?php

namespace Lib\Provider;

class ConsoleProvider extends ServiceProvider
{
    public function register()
    {
        $commands = config('console.commands');
        $console = app()->make('console');
        foreach ($commands as $command) {
            $console->add(new $command());
        }
    }
}