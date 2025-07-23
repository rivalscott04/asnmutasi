<?php
error_log('DEBUG: index.php loaded');

/**
 * ASN Mutasi Application
 * Entry Point
 */

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', ROOT_PATH . '/core');
define('PUBLIC_PATH', __DIR__);
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('TEMPLATE_PATH', ROOT_PATH . '/templates');
define('VIEWS_PATH', APP_PATH . '/Views');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// Load autoloader
require_once CORE_PATH . '/autoload.php';

// Autoloader already registered in autoload.php
// Just add additional namespace mappings if needed
Autoloader::addNamespace('Core\\', CORE_PATH);
Autoloader::addNamespace('App\\', APP_PATH);

// Load environment variables and helper functions
require_once CORE_PATH . '/Support/Env.php';
require_once CORE_PATH . '/Support/Config.php';
Core\Support\Env::load(ROOT_PATH . '/.env');

// Error reporting based on environment
if (Core\Support\Env::get('APP_DEBUG', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set(Core\Support\Env::get('APP_TIMEZONE', 'Asia/Jakarta'));

// Start session
if (!session_id()) {
    session_start();
}

try {
    // Create application instance
    $app = Core\Application::getInstance();
    
    // Load routes
    require_once ROOT_PATH . '/routes/web.php';
    
    // Handle request
    $app->run();
    
} catch (Exception $e) {
    // Handle exceptions
    if (Core\Support\Env::get('APP_DEBUG', false)) {
        echo '<h1>Application Error</h1>';
        echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';
        echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
        echo '<h3>Stack Trace:</h3>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        echo '<h1>500 - Internal Server Error</h1>';
        echo '<p>Something went wrong. Please try again later.</p>';
    }
}