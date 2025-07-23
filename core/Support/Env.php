<?php

namespace Core\Support;

/**
 * Environment Variable Loader
 * Parse dan load .env file
 */
class Env
{
    private static $variables = [];
    private static $loaded = false;
    
    /**
     * Load .env file
     */
    public static function load($path = null)
    {
        if (self::$loaded) {
            return;
        }
        
        $path = $path ?: dirname(__DIR__, 2) . '/.env';
        
        if (!file_exists($path)) {
            return;
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip comments
            if (strpos($line, '#') === 0) {
                continue;
            }
            
            // Parse key=value
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes
                if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
                    $value = $matches[2];
                }
                
                // Type casting
                $value = self::castValue($value);
                
                self::$variables[$key] = $value;
                
                // Set as environment variable
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                }
            }
        }
        
        self::$loaded = true;
    }
    
    /**
     * Get environment variable
     */
    public static function get($key, $default = null)
    {
        // Check loaded variables first
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }
        
        // Check $_ENV
        if (isset($_ENV[$key])) {
            return self::castValue($_ENV[$key]);
        }
        
        // Check getenv()
        $value = getenv($key);
        if ($value !== false) {
            return self::castValue($value);
        }
        
        return $default;
    }
    
    /**
     * Get required environment variable
     */
    public static function require($key)
    {
        $value = self::get($key);
        
        if ($value === null) {
            throw new \Exception("Required environment variable '{$key}' is not set");
        }
        
        return $value;
    }
    
    /**
     * Cast string value to appropriate type
     */
    private static function castValue($value)
    {
        if ($value === '') {
            return '';
        }
        
        $lower = strtolower($value);
        
        // Boolean values
        if (in_array($lower, ['true', '(true)'])) {
            return true;
        }
        
        if (in_array($lower, ['false', '(false)'])) {
            return false;
        }
        
        // Null values
        if (in_array($lower, ['null', '(null)'])) {
            return null;
        }
        
        // Numeric values
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float) $value : (int) $value;
        }
        
        return $value;
    }
}

/**
 * Helper function untuk mengakses environment variables
 */
function env($key, $default = null)
{
    return \Core\Support\Env::get($key, $default);
}