<?php

namespace Lib\Provider;

use Lib\Core\Application;

abstract class ServiceProvider
{
    /**
     * @var Application $app
     */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function boot()
    {

    }

    public function register()
    {

    }
}