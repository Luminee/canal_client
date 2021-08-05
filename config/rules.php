<?php

return [

    '_base' => [

    ],

    'project' => [
        'extend' => ['_base'],

        'custom' => [
            'table1' => [
                'method' => 'copy_partly',
                'table' => 'table_1',
                'ignore' => ['column1'],
            ],
            'table2' => [
                'method' => 'copy_fully',
                'table' => 'table_2'
            ],
        ]
    ],

];