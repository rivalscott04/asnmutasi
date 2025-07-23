<?php

/**
 * Global Helper Functions
 * Fungsi-fungsi helper yang dapat digunakan di seluruh aplikasi
 */

if (!function_exists('logger')) {
    /**
     * Get logger instance or log a message
     */
    function logger($message = null, array $context = [], $level = 'info')
    {
        $logger = \Core\Support\Logger::getInstance();
        
        if ($message === null) {
            return $logger;
        }
        
        return $logger->log($level, $message, $context);
    }
}

if (!function_exists('log_info')) {
    /**
     * Log info message
     */
    function log_info($message, array $context = [])
    {
        return \Core\Support\Logger::info($message, $context);
    }
}

if (!function_exists('log_error')) {
    /**
     * Log error message
     */
    function log_error($message, array $context = [])
    {
        return \Core\Support\Logger::error($message, $context);
    }
}

if (!function_exists('log_warning')) {
    /**
     * Log warning message
     */
    function log_warning($message, array $context = [])
    {
        return \Core\Support\Logger::warning($message, $context);
    }
}

if (!function_exists('log_debug')) {
    /**
     * Log debug message
     */
    function log_debug($message, array $context = [])
    {
        return \Core\Support\Logger::debug($message, $context);
    }
}

if (!function_exists('log_request')) {
    /**
     * Log HTTP request
     */
    function log_request($method, $uri, array $data = [], $userAgent = null)
    {
        return \Core\Support\Logger::request($method, $uri, $data, $userAgent);
    }
}

if (!function_exists('log_auth')) {
    /**
     * Log authentication event
     */
    function log_auth($event, $userId = null, array $context = [])
    {
        return \Core\Support\Logger::auth($event, $userId, $context);
    }
}

if (!function_exists('log_file_operation')) {
    /**
     * Log file operation
     */
    function log_file_operation($operation, $filename, array $context = [])
    {
        return \Core\Support\Logger::file($operation, $filename, $context);
    }
}

if (!function_exists('log_exception')) {
    /**
     * Log exception with full context
     */
    function log_exception(\Exception $exception, array $context = [])
    {
        $context = array_merge($context, [
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
        
        return \Core\Support\Logger::error($exception->getMessage(), $context);
    }
}

if (!function_exists('log_performance')) {
    /**
     * Log performance metrics
     */
    function log_performance($operation, $startTime, array $context = [])
    {
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        $context['execution_time'] = $executionTime . 'ms';
        $context['memory_usage'] = memory_get_usage(true);
        $context['memory_peak'] = memory_get_peak_usage(true);
        
        return \Core\Support\Logger::info("Performance: {$operation}", $context);
    }
}

if (!function_exists('start_timer')) {
    /**
     * Start a timer for performance logging
     */
    function start_timer($name = 'default')
    {
        global $_timers;
        if (!isset($_timers)) {
            $_timers = [];
        }
        $_timers[$name] = microtime(true);
        return $_timers[$name];
    }
}

if (!function_exists('end_timer')) {
    /**
     * End a timer and optionally log the result
     */
    function end_timer($name = 'default', $log = true, $operation = null)
    {
        global $_timers;
        
        if (!isset($_timers[$name])) {
            return null;
        }
        
        $startTime = $_timers[$name];
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        unset($_timers[$name]);
        
        if ($log) {
            $operation = $operation ?: "Timer: {$name}";
            log_performance($operation, $startTime);
        }
        
        return $executionTime;
    }
}

if (!function_exists('format_bytes')) {
    /**
     * Format bytes to human readable format
     */
    function format_bytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Get client IP address
     */
    function get_client_ip()
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }
}

if (!function_exists('get_user_agent')) {
    /**
     * Get user agent string
     */
    function get_user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }
}

if (!function_exists('is_development')) {
    /**
     * Check if application is in development mode
     */
    function is_development()
    {
        return (getenv('APP_ENV') ?? 'production') === 'development';
    }
}

if (!function_exists('is_production')) {
    /**
     * Check if application is in production mode
     */
    function is_production()
    {
        return (getenv('APP_ENV') ?? 'production') === 'production';
    }
}