#!/usr/bin/env php
<?php

/**
 * Log Management CLI Tool
 * 
 * Usage:
 *   php log.php tail [lines] [level]  - Show recent logs
 *   php log.php stats                 - Show log statistics
 *   php log.php clear [days]          - Clear old logs
 *   php log.php test                  - Test logging functionality
 *   php log.php help                  - Show help
 */

// Bootstrap the application
require_once __DIR__ . '/core/autoload.php';

use App\Commands\LogCommand;

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    echo "This script can only be run from command line.\n";
    exit(1);
}

// Handle the command
try {
    LogCommand::handle($argv);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);