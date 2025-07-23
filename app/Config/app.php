<?php

return [
    'name' => Core\Support\Env::get('APP_NAME', 'ASN Mutasi'),
    'env' => Core\Support\Env::get('APP_ENV', 'production'),
    'debug' => Core\Support\Env::get('APP_DEBUG', false),
    'url' => Core\Support\Env::get('APP_URL', 'http://localhost'),
    'timezone' => Core\Support\Env::get('APP_TIMEZONE', 'Asia/Jakarta'),
    
    'key' => Core\Support\Env::get('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    
    'locale' => 'id',
    'fallback_locale' => 'en',
    
    'providers' => [
        // Service providers
    ],
    
    'aliases' => [
        // Class aliases
    ]
];