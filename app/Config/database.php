<?php

return [
    'default' => Core\Support\Env::get('DB_CONNECTION', 'mysql'),
    
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => Core\Support\Env::get('DB_HOST', 'localhost'),
            'port' => Core\Support\Env::get('DB_PORT', 3306),
            'database' => Core\Support\Env::get('DB_DATABASE', ''),
            'username' => Core\Support\Env::get('DB_USERNAME', 'root'),
            'password' => Core\Support\Env::get('DB_PASSWORD', ''),
            'charset' => Core\Support\Env::get('DB_CHARSET', 'utf8mb4'),
            'collation' => Core\Support\Env::get('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
        

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => Core\Support\Env::get('DB_HOST', 'localhost'),
            'port' => Core\Support\Env::get('DB_PORT', 5432),
            'database' => Core\Support\Env::get('DB_DATABASE', 'asnmutasi'),
            'username' => Core\Support\Env::get('DB_USERNAME', 'postgres'),
            'password' => Core\Support\Env::get('DB_PASSWORD', ''),
            'charset' => Core\Support\Env::get('DB_CHARSET', 'utf8'),
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ]
    ]
];