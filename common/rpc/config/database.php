<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_NAME', 'your_database_name'),
            'username' => env('DB_USER'),
            'password' => env('DB_PASS'),
            'tablePrefix' => env('DB_PREFIX'),
            'charset' => 'utf8',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => 'dd',
            'strict' => true,
            'engine' => null,
            'attributes' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache'
        ],
    ],
];