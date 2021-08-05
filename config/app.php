<?php

return [

    'env' => env('APP_ENV', 'production'),

    'debug' => env('APP_DEBUG', false),

    'log' => env('APP_LOG', 'single'),

    'subscribers' => env('SUBSCRIBERS', 'local/dev'),

    'providers' => [

        Lib\Provider\FacadeProvider::class,
        Lib\Provider\ConsoleProvider::class,
        Lib\Provider\DriverProvider::class,
        Lib\Provider\SubscriberProvider::class,
    ],

    'aliases' => [

        'Log' => Lib\Facade\LogFacade::class,
    ]

];