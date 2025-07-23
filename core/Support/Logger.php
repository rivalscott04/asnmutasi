<?php

namespace Core\Support;

/**
 * Logger Class
 * Sistem logging yang rapi dan maintainable untuk aplikasi
 */
class Logger
{
    // Log levels
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';
    
    private static $instance = null;
    private $logPath;
    private $dateFormat;
    private $logFormat;
    private $maxFileSize;
    private $maxFiles;
    
    /**
     * Constructor
     */
    private function __construct()
    {
        // Get the root path (go up two levels from core/Support)
        $rootPath = dirname(dirname(__DIR__));
        $this->logPath = $rootPath . '/storage/logs';
        $this->dateFormat = 'Y-m-d H:i:s';
        $this->logFormat = '[{timestamp}] {level}: {message} {context}';
        $this->maxFileSize = 10 * 1024 * 1024; // 10MB
        $this->maxFiles = 10;
        
        // Ensure log directory exists
        $this->ensureLogDirectory();
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Log emergency message
     */
    public static function emergency($message, array $context = [])
    {
        return self::getInstance()->log(self::EMERGENCY, $message, $context);
    }
    
    /**
     * Log alert message
     */
    public static function alert($message, array $context = [])
    {
        return self::getInstance()->log(self::ALERT, $message, $context);
    }
    
    /**
     * Log critical message
     */
    public static function critical($message, array $context = [])
    {
        return self::getInstance()->log(self::CRITICAL, $message, $context);
    }
    
    /**
     * Log error message
     */
    public static function error($message, array $context = [])
    {
        return self::getInstance()->log(self::ERROR, $message, $context);
    }
    
    /**
     * Log warning message
     */
    public static function warning($message, array $context = [])
    {
        return self::getInstance()->log(self::WARNING, $message, $context);
    }
    
    /**
     * Log notice message
     */
    public static function notice($message, array $context = [])
    {
        return self::getInstance()->log(self::NOTICE, $message, $context);
    }
    
    /**
     * Log info message
     */
    public static function info($message, array $context = [])
    {
        return self::getInstance()->log(self::INFO, $message, $context);
    }
    
    /**
     * Log debug message
     */
    public static function debug($message, array $context = [])
    {
        return self::getInstance()->log(self::DEBUG, $message, $context);
    }
    
    /**
     * Log HTTP request
     */
    public static function request($method, $uri, array $data = [], $userAgent = null)
    {
        $context = [
            'method' => $method,
            'uri' => $uri,
            'data' => $data,
            'user_agent' => $userAgent ?: ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
        ];
        
        return self::info("HTTP Request: {$method} {$uri}", $context);
    }
    
    /**
     * Log database query
     */
    public static function query($sql, array $bindings = [], $executionTime = null)
    {
        $context = [
            'sql' => $sql,
            'bindings' => $bindings,
            'execution_time' => $executionTime
        ];
        
        return self::debug('Database Query', $context);
    }
    
    /**
     * Log authentication events
     */
    public static function auth($event, $userId = null, array $context = [])
    {
        $context['event'] = $event;
        $context['user_id'] = $userId;
        $context['ip'] = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        return self::info("Auth Event: {$event}", $context);
    }
    
    /**
     * Log validation errors
     */
    public static function validation($errors, array $context = [])
    {
        $context['validation_errors'] = $errors;
        
        return self::warning('Validation Failed', $context);
    }
    
    /**
     * Log file operations
     */
    public static function file($operation, $filename, array $context = [])
    {
        $context['operation'] = $operation;
        $context['filename'] = $filename;
        
        return self::info("File Operation: {$operation} - {$filename}", $context);
    }
    
    /**
     * Main log method
     */
    public function log($level, $message, array $context = [])
    {
        try {
            $timestamp = date($this->dateFormat);
            $contextString = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';
            
            $logEntry = str_replace(
                ['{timestamp}', '{level}', '{message}', '{context}'],
                [$timestamp, strtoupper($level), $message, $contextString],
                $this->logFormat
            );
            
            $filename = $this->getLogFilename($level);
            $this->writeToFile($filename, $logEntry . PHP_EOL);
            
            // Rotate log if needed
            $this->rotateLogIfNeeded($filename);
            
            return true;
        } catch (\Exception $e) {
            // Fallback to error_log if our logging fails
            error_log("Logger Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get log filename based on level and date
     */
    private function getLogFilename($level)
    {
        $date = date('Y-m-d');
        return $this->logPath . "/app-{$date}.log";
    }
    
    /**
     * Write to log file
     */
    private function writeToFile($filename, $content)
    {
        file_put_contents($filename, $content, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Ensure log directory exists
     */
    private function ensureLogDirectory()
    {
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
    }
    
    /**
     * Rotate log file if it exceeds max size
     */
    private function rotateLogIfNeeded($filename)
    {
        if (!file_exists($filename) || filesize($filename) < $this->maxFileSize) {
            return;
        }
        
        // Rotate existing files
        for ($i = $this->maxFiles - 1; $i > 0; $i--) {
            $oldFile = $filename . '.' . $i;
            $newFile = $filename . '.' . ($i + 1);
            
            if (file_exists($oldFile)) {
                if ($i === $this->maxFiles - 1) {
                    unlink($oldFile); // Delete oldest file
                } else {
                    rename($oldFile, $newFile);
                }
            }
        }
        
        // Rotate current file
        rename($filename, $filename . '.1');
    }
    
    /**
     * Get recent logs
     */
    public static function getRecentLogs($lines = 100, $level = null)
    {
        $instance = self::getInstance();
        $filename = $instance->getLogFilename($level ?: 'info');
        
        if (!file_exists($filename)) {
            return [];
        }
        
        $file = new \SplFileObject($filename);
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();
        
        $startLine = max(0, $totalLines - $lines);
        $file->seek($startLine);
        
        $logs = [];
        while (!$file->eof()) {
            $line = trim($file->fgets());
            if (!empty($line)) {
                $logs[] = $line;
            }
        }
        
        return $logs;
    }
    
    /**
     * Clear old logs
     */
    public static function clearOldLogs($days = 30)
    {
        $instance = self::getInstance();
        $logPath = $instance->logPath;
        
        $files = glob($logPath . '/app-*.log*');
        $cutoffTime = time() - ($days * 24 * 60 * 60);
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                unlink($file);
            }
        }
    }
    
    /**
     * Get log statistics
     */
    public static function getStats()
    {
        $instance = self::getInstance();
        $logPath = $instance->logPath;
        
        $files = glob($logPath . '/app-*.log*');
        $totalSize = 0;
        $fileCount = count($files);
        
        foreach ($files as $file) {
            $totalSize += filesize($file);
        }
        
        return [
            'file_count' => $fileCount,
            'total_size' => $totalSize,
            'total_size_human' => $instance->formatBytes($totalSize),
            'log_path' => $logPath
        ];
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}