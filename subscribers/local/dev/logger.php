<?php

return [
    'driver' => 'logger',

    'connection' => [],

    'rule' => [
        'extend' => ['_base'],

        'schema' => 'schema_',

        'custom' => [
            'table_' => [
                'method' => 'copy_fully',
                'table' => 'table_c',
                'where' => [
                    'c', '<>' , '1'
                ]
            ],
        ]
    ]
];