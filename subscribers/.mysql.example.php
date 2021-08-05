<?php

return [
    'driver' => 'mysql',

    'connection' => [
        'host' => '127.0.0.1',
        'database' => 'slave_database',
        'username' => 'root',
        'password' => 'pwd',
    ],

    'rule' => [
        'extend' => ['base'],

        'ignore' => ['base.label'],

        'custom' => [
            'base.school' => [
                'method' => 'copy_fully',
                'table' => 'core_school'
            ],

            'base.user' => [
                'method' => 'copy_partly',
                'table' => 'user',
                'ignore' => ['username'],
                'append' => [
                    'age' => function ($rows) {
                        return $rows['id'] * 2;
                    }
                ]
            ],

        ]
    ]
];