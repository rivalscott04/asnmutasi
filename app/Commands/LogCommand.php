<?php

namespace App\Commands;

use Core\Support\Logger;

/**
 * Log Command
 * Command untuk mengelola dan melihat log files
 */
class LogCommand
{
    /**
     * Show recent logs
     */
    public static function tail($lines = 50, $level = null)
    {
        echo "\n=== Recent Logs (Last {$lines} lines) ===\n\n";
        
        $logs = Logger::getRecentLogs($lines, $level);
        
        if (empty($logs)) {
            echo "No logs found.\n";
            return;
        }
        
        foreach ($logs as $log) {
            echo $log . "\n";
        }
        
        echo "\n=== End of Logs ===\n";
    }
    
    /**
     * Show log statistics
     */
    public static function stats()
    {
        echo "\n=== Log Statistics ===\n\n";
        
        $stats = Logger::getStats();
        
        echo "Log Directory: {$stats['log_path']}\n";
        echo "Total Files: {$stats['file_count']}\n";
        echo "Total Size: {$stats['total_size_human']}\n";
        echo "Raw Size: {$stats['total_size']} bytes\n";
        
        // Show individual file stats
        $files = glob($stats['log_path'] . '/app-*.log*');
        
        if (!empty($files)) {
            echo "\n=== Individual Files ===\n";
            
            foreach ($files as $file) {
                $filename = basename($file);
                $size = filesize($file);
                $sizeHuman = format_bytes($size);
                $modified = date('Y-m-d H:i:s', filemtime($file));
                
                echo sprintf("%-30s %10s %s\n", $filename, $sizeHuman, $modified);
            }
        }
        
        echo "\n=== End of Statistics ===\n";
    }
    
    /**
     * Clear old logs
     */
    public static function clear($days = 30)
    {
        echo "\nClearing logs older than {$days} days...\n";
        
        $stats = Logger::getStats();
        $logPath = $stats['log_path'];
        
        $files = glob($logPath . '/app-*.log*');
        $cutoffTime = time() - ($days * 24 * 60 * 60);
        $deletedCount = 0;
        $deletedSize = 0;
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                $size = filesize($file);
                $filename = basename($file);
                
                if (unlink($file)) {
                    echo "Deleted: {$filename} (" . format_bytes($size) . ")\n";
                    $deletedCount++;
                    $deletedSize += $size;
                } else {
                    echo "Failed to delete: {$filename}\n";
                }
            }
        }
        
        if ($deletedCount > 0) {
            echo "\nDeleted {$deletedCount} files, freed " . format_bytes($deletedSize) . "\n";
            
            // Log the cleanup
            Logger::info('Log cleanup completed', [
                'deleted_files' => $deletedCount,
                'freed_space' => $deletedSize,
                'days_threshold' => $days
            ]);
        } else {
            echo "\nNo old log files found to delete.\n";
        }
    }
    
    /**
     * Test logging functionality
     */
    public static function test()
    {
        echo "\n=== Testing Logging Functionality ===\n\n";
        
        $testData = [
            'test_id' => uniqid(),
            'timestamp' => date('Y-m-d H:i:s'),
            'user' => 'test_user'
        ];
        
        // Test different log levels
        echo "Testing log levels...\n";
        
        Logger::debug('Debug test message', $testData);
        echo "✓ Debug log written\n";
        
        Logger::info('Info test message', $testData);
        echo "✓ Info log written\n";
        
        Logger::notice('Notice test message', $testData);
        echo "✓ Notice log written\n";
        
        Logger::warning('Warning test message', $testData);
        echo "✓ Warning log written\n";
        
        Logger::error('Error test message', $testData);
        echo "✓ Error log written\n";
        
        Logger::critical('Critical test message', $testData);
        echo "✓ Critical log written\n";
        
        // Test specialized logging methods
        echo "\nTesting specialized logging methods...\n";
        
        Logger::request('GET', '/test', ['param' => 'value']);
        echo "✓ Request log written\n";
        
        Logger::auth('login_attempt', 123, ['ip' => '127.0.0.1']);
        echo "✓ Auth log written\n";
        
        Logger::validation(['field' => 'required'], ['form' => 'test']);
        echo "✓ Validation log written\n";
        
        Logger::file('upload', 'test.txt', ['size' => 1024]);
        echo "✓ File operation log written\n";
        
        // Test helper functions
        echo "\nTesting helper functions...\n";
        
        log_info('Helper function test', $testData);
        echo "✓ Helper function log written\n";
        
        log_error('Helper error test', $testData);
        echo "✓ Helper error log written\n";
        
        // Test performance logging
        echo "\nTesting performance logging...\n";
        
        $startTime = start_timer('test_operation');
        usleep(100000); // Sleep for 100ms
        end_timer('test_operation', true, 'Test operation performance');
        echo "✓ Performance log written\n";
        
        echo "\n=== All Tests Completed Successfully ===\n";
        
        // Show recent logs to verify
        echo "\n=== Recent Test Logs ===\n";
        $recentLogs = Logger::getRecentLogs(10);
        foreach (array_slice($recentLogs, -5) as $log) {
            echo $log . "\n";
        }
    }
    
    /**
     * Show help information
     */
    public static function help()
    {
        echo "\n=== Log Command Help ===\n\n";
        echo "Available commands:\n";
        echo "  tail [lines] [level]  - Show recent logs (default: 50 lines)\n";
        echo "  stats                 - Show log statistics\n";
        echo "  clear [days]          - Clear logs older than specified days (default: 30)\n";
        echo "  test                  - Test logging functionality\n";
        echo "  help                  - Show this help message\n";
        echo "\nExamples:\n";
        echo "  php log.php tail 100        - Show last 100 log entries\n";
        echo "  php log.php tail 50 error   - Show last 50 error logs\n";
        echo "  php log.php clear 7         - Clear logs older than 7 days\n";
        echo "  php log.php stats           - Show log statistics\n";
        echo "\n";
    }
    
    /**
     * Main command handler
     */
    public static function handle($args = [])
    {
        $command = $args[1] ?? 'help';
        
        switch ($command) {
            case 'tail':
                $lines = isset($args[2]) ? (int)$args[2] : 50;
                $level = $args[3] ?? null;
                self::tail($lines, $level);
                break;
                
            case 'stats':
                self::stats();
                break;
                
            case 'clear':
                $days = isset($args[2]) ? (int)$args[2] : 30;
                self::clear($days);
                break;
                
            case 'test':
                self::test();
                break;
                
            case 'help':
            default:
                self::help();
                break;
        }
    }
}