<?php

namespace Core\Support {
    /**
     * Configuration Manager
     * Load dan manage configuration files
     */
    class Config
    {
        private static $config = [];
        private static $loaded = false;
        
        /**
         * Load all configuration files
         */
        public static function load($configPath = null)
        {
            if (self::$loaded) {
                return;
            }
            
            $configPath = $configPath ?: dirname(__DIR__, 2) . '/app/Config';
            
            if (!is_dir($configPath)) {
                return;
            }
            
            $files = glob($configPath . '/*.php');
            
            foreach ($files as $file) {
                $key = basename($file, '.php');
                $config = require $file;
                
                if (is_array($config)) {
                    self::$config[strtolower($key)] = $config;
                }
            }
            
            self::$loaded = true;
        }
        
        /**
         * Get configuration value using dot notation
         * Example: Config::get('database.host')
         */
        public static function get($key, $default = null)
        {
            if (!self::$loaded) {
                self::load();
            }
            
            $keys = explode('.', $key);
            $value = self::$config;
            
            foreach ($keys as $segment) {
                if (!is_array($value) || !array_key_exists($segment, $value)) {
                    return $default;
                }
                
                $value = $value[$segment];
            }
            
            return $value;
        }
        
        /**
         * Set configuration value
         */
        public static function set($key, $value)
        {
            $keys = explode('.', $key);
            $config = &self::$config;
            
            foreach ($keys as $segment) {
                if (!isset($config[$segment]) || !is_array($config[$segment])) {
                    $config[$segment] = [];
                }
                $config = &$config[$segment];
            }
            
            $config = $value;
        }
        
        /**
         * Check if configuration key exists
         */
        public static function has($key)
        {
            return self::get($key) !== null;
        }
        
        /**
         * Get all configuration
         */
        public static function all()
        {
            if (!self::$loaded) {
                self::load();
            }
            
            return self::$config;
        }
    }
}

namespace {
    if (!function_exists('config')) {
        function config($key, $default = null)
        {
            return \Core\Support\Config::get($key, $default);
        }
    }
}