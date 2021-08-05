<?php

return [

    'server' => [

        'host' => env('SERVER_HOST', '127.0.0.1'),
        'port' => env('SERVER_PORT', '11111'),
        'client_id' => env('SERVER_CLIENT_ID', '1001'),
        'destination' => env('SERVER_DESTINATION', 'example'),
        'filter' => env('CLIENT_FILTER', ".*\\..*"),

    ]

];