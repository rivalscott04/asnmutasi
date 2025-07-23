<?php

/**
 * PSR-4 Autoloader
 * Simple autoloader untuk MVC Framework
 */
class Autoloader
{
    private static $namespaces = [];
    
    /**
     * Register autoloader
     */
    public static function register()
    {
        spl_autoload_register([self::class, 'load']);
        
        // Register default namespaces
        self::addNamespace('App\\', __DIR__ . '/../app/');
        self::addNamespace('Core\\', __DIR__ . '/');
    }
    
    /**
     * Add namespace mapping
     */
    public static function addNamespace($namespace, $directory)
    {
        $namespace = trim($namespace, '\\') . '\\';
        $directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        
        if (!isset(self::$namespaces[$namespace])) {
            self::$namespaces[$namespace] = [];
        }
        
        self::$namespaces[$namespace][] = $directory;
    }
    
    /**
     * Load class file
     */
    public static function load($className)
    {
        $className = ltrim($className, '\\');
        
        foreach (self::$namespaces as $namespace => $directories) {
            if (strpos($className, $namespace) === 0) {
                $relativeClass = substr($className, strlen($namespace));
                $file = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
                
                foreach ($directories as $directory) {
                    $fullPath = $directory . $file;
                    if (file_exists($fullPath)) {
                        require_once $fullPath;
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}

// Register autoloader
Autoloader::register();

// Load Composer autoloader if exists
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Always load global helpers
require_once __DIR__ . '/Support/Config.php';
require_once __DIR__ . '/Support/Env.php';
require_once __DIR__ . '/Support/Logger.php';
require_once __DIR__ . '/Support/helpers.php';

// Initialize error handling middleware
use App\Middleware\ErrorHandlingMiddleware;
ErrorHandlingMiddleware::init();