<?php

return [

    '_base' => [
        'table1' => [
            'method' => 'copy_partly',
            'table' => 'table_1',
            'ignore' => ['column1'],
        ],
        'table2' => [
            'method' => 'copy_fully',
            'table' => 'table_2'
        ],
        'table3' => [
            'method' => 'copy_partly',
            'table' => 'table_3',
            'ignore' => ['column2'],
            'append' => [
                'age' => function ($rows) {
                    return $rows['id'] * 2;
                }
            ],
        ],
    ],

    'schema_11' => [
        'extend' => ['_base'],

        'ignore' => ['schema1.table1'],

        'custom' => [
            'schema1.table2' => [
                'method' => 'copy_fully',
                'table' => 'table_2'
            ],
        ]
    ],

];