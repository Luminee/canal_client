<?php

namespace Lib\Provider;

class DriverProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->make('column', \Lib\Driver\Columns::class);

        $this->app->make('sql', \Lib\Driver\MySQL\SQL::class);
        $this->app->make('mysql', \Lib\Driver\MySQL\MySQL::class);
        $this->app->make('logger', \Lib\Driver\Logger\Logger::class);
    }
}