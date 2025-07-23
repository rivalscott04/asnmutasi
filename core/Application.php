<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response;
use Core\Support\Env;
use Core\Support\Config;
use Core\Support\Container;
use Core\Database\Connection;

require_once __DIR__ . '/Support/Config.php';

/**
 * Application
 * Bootstrap dan manage aplikasi
 */
class Application
{
    private static $instance = null;
    private $router;
    private $booted = false;
    
    private function __construct()
    {
        $this->router = new Router();
    }
    
    /**
     * Get application instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Bootstrap application
     */
    public function boot($basePath = null)
    {
        if ($this->booted) {
            return $this;
        }
        
        $basePath = $basePath ?: dirname(__DIR__);
        
        // Load environment variables
        Env::load($basePath . '/.env');
        
        // Load configuration
        Config::load($basePath . '/app/Config');
        
        // Register core services
        $this->registerCoreServices();
        
        // Load routes
        $this->loadRoutes($basePath . '/app/Config/Routes.php');
        
        // Set error handling
        $this->setupErrorHandling();
        
        $this->booted = true;
        
        return $this;
    }
    
    /**
     * Register core services ke container
     */
    private function registerCoreServices()
    {
        // Register database connection
        Container::singleton('Core\\Database\\Connection', function() {
            return Connection::getInstance();
        });
        
        // Register router
        Container::instance('Core\\Router', $this->router);
        
        // Register application instance
        Container::instance('Core\\Application', $this);
    }
    
    /**
     * Load routes file
     */
    private function loadRoutes($routesFile)
    {
        if (file_exists($routesFile)) {
            $router = $this->router;
            require $routesFile;
        }
    }
    
    /**
     * Setup error handling
     */
    private function setupErrorHandling()
    {
        $debug = \Core\Support\Config::get('app.debug', false);
        
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
        
        // Set custom error handler
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }
    
    /**
     * Handle PHP errors
     */
    public function handleError($severity, $message, $file, $line)
    {
        if (!(error_reporting() & $severity)) {
            return;
        }
        
        $debug = \Core\Support\Config::get('app.debug', false);
        
        if ($debug) {
            $error = [
                'type' => 'Error',
                'message' => $message,
                'file' => $file,
                'line' => $line
            ];
            
            Response::json($error, 500)->send();
        } else {
            Response::error('Internal Server Error', 500)->send();
        }
        
        exit;
    }
    
    /**
     * Handle uncaught exceptions
     */
    public function handleException($exception)
    {
        $debug = \Core\Support\Config::get('app.debug', false);
        
        if ($debug) {
            $error = [
                'type' => 'Exception',
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
            
            Response::json($error, 500)->send();
        } else {
            Response::error('Internal Server Error', 500)->send();
        }
        
        exit;
    }
    
    /**
     * Handle incoming request
     */
    public function handle(Request $request = null)
    {
        if (!$this->booted) {
            $this->boot();
        }
        
        $request = $request ?: new Request();
        
        try {
            $response = $this->router->dispatch($request);
            
            if (!($response instanceof Response)) {
                $response = Response::json($response);
            }
            
            return $response;
        } catch (\Exception $e) {
            return $this->handleRequestException($e);
        }
    }
    
    /**
     * Handle request exceptions
     */
    private function handleRequestException(\Exception $e)
    {
        $debug = \Core\Support\Config::get('app.debug', false);
        
        if ($debug) {
            return Response::error($e->getMessage(), 500, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return Response::error('Internal Server Error', 500);
    }
    
    /**
     * Run application
     */
    public function run()
    {
        $response = $this->handle();
        $response->send();
    }
    
    /**
     * Get router instance
     */
    public function getRouter()
    {
        return $this->router;
    }
    
    /**
     * Get application version
     */
    public function version()
    {
        return \Core\Support\Config::get('app.version', '1.0.0');
    }
    
    /**
     * Get application environment
     */
    public function environment()
    {
        return \Core\Support\Config::get('app.env', 'production');
    }
    
    /**
     * Check if application is in debug mode
     */
    public function isDebug()
    {
        return \Core\Support\Config::get('app.debug', false);
    }
    
    /**
     * Get base path
     */
    public function basePath($path = '')
    {
        return dirname(__DIR__) . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}