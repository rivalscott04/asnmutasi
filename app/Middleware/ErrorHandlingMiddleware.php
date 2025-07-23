<?php

namespace App\Middleware;

use Core\Support\Logger;
use Exception;
use Throwable;

/**
 * Error Handling Middleware
 */
class ErrorHandlingMiddleware
{
    /**
     * Initialize error handling middleware
     */
    public static function init()
    {
        $instance = new self();
        $instance->setErrorHandler();
        $instance->setExceptionHandler();
        $instance->setShutdownHandler();
    }
    
    /**
     * Set up error handlers
     */
    public function setErrorHandler()
    {
        set_error_handler([self::class, 'handleError']);
    }
    
    /**
     * Set up exception handlers
     */
    public function setExceptionHandler()
    {
        set_exception_handler([self::class, 'handleException']);
    }
    
    /**
     * Set up shutdown handlers
     */
    public function setShutdownHandler()
    {
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    
    /**
     * Handle PHP errors
     */
    public static function handleError($severity, $message, $file, $line)
    {
        // Don't handle errors that are suppressed with @
        if (!(error_reporting() & $severity)) {
            return false;
        }
        
        $context = [
            'severity' => self::getSeverityName($severity),
            'file' => $file,
            'line' => $line,
            'url' => $_SERVER['REQUEST_URI'] ?? 'CLI',
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'ip' => get_client_ip()
        ];
        
        // Log based on severity
        switch ($severity) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                Logger::error("PHP Error: {$message}", $context);
                break;
                
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                Logger::warning("PHP Warning: {$message}", $context);
                break;
                
            case E_NOTICE:
            case E_USER_NOTICE:
                Logger::notice("PHP Notice: {$message}", $context);
                break;
                
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                Logger::debug("PHP Deprecated: {$message}", $context);
                break;
                
            default:
                Logger::info("PHP Info: {$message}", $context);
                break;
        }
        
        // Don't execute PHP internal error handler
        return true;
    }
    
    /**
     * Handle uncaught exceptions
     */
    public static function handleException($exception)
    {
        $context = [
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'url' => $_SERVER['REQUEST_URI'] ?? 'CLI',
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'ip' => get_client_ip()
        ];
        
        // Add previous exception if exists
        if ($exception->getPrevious()) {
            $context['previous_exception'] = [
                'class' => get_class($exception->getPrevious()),
                'message' => $exception->getPrevious()->getMessage(),
                'file' => $exception->getPrevious()->getFile(),
                'line' => $exception->getPrevious()->getLine()
            ];
        }
        
        Logger::critical("Uncaught Exception: {$exception->getMessage()}", $context);
        
        // Show user-friendly error page in production
        if (is_production()) {
            http_response_code(500);
            
            if (self::expectsJson()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'Internal Server Error',
                    'message' => 'Something went wrong. Please try again later.'
                ]);
            } else {
                echo self::getErrorPage(500, 'Internal Server Error');
            }
        } else {
            // Show detailed error in development
            if (self::expectsJson()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'Exception',
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTrace()
                ]);
            } else {
                echo self::getDetailedErrorPage($exception);
            }
        }
        
        exit(1);
    }
    
    /**
     * Handle fatal errors
     */
    public static function handleShutdown()
    {
        $error = error_get_last();
        
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $context = [
                'type' => self::getSeverityName($error['type']),
                'file' => $error['file'],
                'line' => $error['line'],
                'url' => $_SERVER['REQUEST_URI'] ?? 'CLI',
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                'ip' => get_client_ip(),
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true)
            ];
            
            Logger::emergency("Fatal Error: {$error['message']}", $context);
            
            // Show error page if headers not sent
            if (!headers_sent()) {
                http_response_code(500);
                
                if (self::expectsJson()) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => 'Fatal Error',
                        'message' => is_production() ? 'Something went wrong. Please try again later.' : $error['message']
                    ]);
                } else {
                    echo self::getErrorPage(500, 'Fatal Error');
                }
            }
        }
    }
    
    /**
     * Get severity name from error code
     */
    private static function getSeverityName($severity)
    {
        $severities = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED'
        ];
        
        return $severities[$severity] ?? 'UNKNOWN';
    }
    
    /**
     * Check if request expects JSON response
     */
    private static function expectsJson()
    {
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        return strpos($acceptHeader, 'application/json') !== false ||
               strpos($contentType, 'application/json') !== false ||
               (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }
    
    /**
     * Get simple error page for production
     */
    private static function getErrorPage($code, $message)
    {
        return "<!DOCTYPE html>
<html>
<head>
    <title>Error {$code}</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .error-container { max-width: 500px; margin: 0 auto; }
        h1 { color: #e74c3c; }
        p { color: #7f8c8d; }
    </style>
</head>
<body>
    <div class='error-container'>
        <h1>Error {$code}</h1>
        <p>{$message}</p>
        <p>Please try again later or contact support if the problem persists.</p>
    </div>
</body>
</html>";
    }
    
    /**
     * Get detailed error page for development
     */
    private static function getDetailedErrorPage($exception)
    {
        $trace = $exception->getTraceAsString();
        $trace = htmlspecialchars($trace);
        $message = htmlspecialchars($exception->getMessage());
        $file = htmlspecialchars($exception->getFile());
        $line = $exception->getLine();
        
        return "<!DOCTYPE html>
<html>
<head>
    <title>Exception: {$message}</title>
    <style>
        body { font-family: 'Courier New', monospace; margin: 20px; }
        .exception { background: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; border-radius: 5px; }
        .exception h1 { color: #dc3545; margin-top: 0; }
        .exception .details { background: #fff; padding: 15px; border-radius: 3px; margin: 10px 0; }
        .exception .trace { background: #f1f3f4; padding: 15px; border-radius: 3px; white-space: pre-wrap; }
        .file-line { color: #6c757d; }
    </style>
</head>
<body>
    <div class='exception'>
        <h1>Exception: {$message}</h1>
        <div class='details'>
            <strong>File:</strong> {$file}<br>
            <strong>Line:</strong> {$line}<br>
            <strong>Class:</strong> " . get_class($exception) . "
        </div>
        <h3>Stack Trace:</h3>
        <div class='trace'>{$trace}</div>
    </div>
</body>
</html>";
    }
}