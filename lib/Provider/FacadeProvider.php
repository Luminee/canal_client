<?php

namespace Lib\Provider;

class FacadeProvider extends ServiceProvider
{
    public function register()
    {
        $aliases = config('app.aliases');
        foreach ($aliases as $alias => $facade) {
            class_alias($facade, $alias);
        }
    }
}