<?php

return [
    'bind' => [
        'schemaManager' => true,
    ],
    'generate' => [
        'migration' => [
            'entity' => [
                'folder' => database_path('migrations'),
                'fileSuffix' => '.php',
                'dependencies' => [
                    \Illuminate\Support\Facades\Schema::class,
                    \Illuminate\Database\Schema\Blueprint::class,
                    \Illuminate\Database\Migrations\Migration::class,
                ],
            ],
            'relationship' => [
                'folder' => database_path('migrations'),
                'fileSuffix' => '.php',
                'dependencies' => [
                    \Illuminate\Support\Facades\Schema::class,
                    \Illuminate\Database\Schema\Blueprint::class,
                    \Illuminate\Database\Migrations\Migration::class,
                ],
            ],
        ],
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
