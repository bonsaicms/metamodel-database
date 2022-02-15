<?php

return [
    'bind' => [
        'schemaManager' => true,
    ],
    'observeModels' => [
        'entity' => [
            'enabled' => true,
            'schema' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'migration' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
        'attribute' => [
            'enabled' => true,
            'schema' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'migration' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
        'relationship' => [
            'enabled' => true,
            'schema' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'migration' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
    ],

];
