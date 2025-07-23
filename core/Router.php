<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response;
use Core\Support\Container;

/**
 * Router
 * Handle URL routing dengan middleware support
 */
class Router
{
    private $routes = [];
    private $middlewares = [];
    private $groupStack = [];
    
    /**
     * Register GET route
     */
    public function get($path, $handler)
    {
        return $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Register POST route
     */
    public function post($path, $handler)
    {
        return $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Register PUT route
     */
    public function put($path, $handler)
    {
        return $this->addRoute('PUT', $path, $handler);
    }
    
    /**
     * Register DELETE route
     */
    public function delete($path, $handler)
    {
        return $this->addRoute('DELETE', $path, $handler);
    }
    
    /**
     * Register route for multiple methods
     */
    public function match($methods, $path, $handler)
    {
        foreach ((array) $methods as $method) {
            $this->addRoute(strtoupper($method), $path, $handler);
        }
    }
    
    /**
     * Add route to collection
     */
    private function addRoute($method, $path, $handler)
    {
        $path = $this->getGroupPrefix() . $path;
        $middleware = $this->getGroupMiddleware();
        
        $route = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
            'pattern' => $this->compilePattern($path)
        ];
        
        $this->routes[] = $route;
        
        return $route;
    }
    
    /**
     * Group routes dengan attributes
     */
    public function group($attributes, $callback)
    {
        $this->groupStack[] = $attributes;
        
        call_user_func($callback, $this);
        
        array_pop($this->groupStack);
    }
    
    /**
     * Get group prefix
     */
    private function getGroupPrefix()
    {
        $prefix = '';
        
        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
        }
        
        return $prefix;
    }
    
    /**
     * Get group middleware
     */
    private function getGroupMiddleware()
    {
        $middleware = [];
        
        foreach ($this->groupStack as $group) {
            if (isset($group['middleware'])) {
                $groupMiddleware = (array) $group['middleware'];
                $middleware = array_merge($middleware, $groupMiddleware);
            }
        }
        
        return $middleware;
    }
    
    /**
     * Compile path pattern untuk regex matching
     */
    private function compilePattern($path)
    {
        // Convert {param} to regex capture groups
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        
        // Escape forward slashes
        $pattern = str_replace('/', '\/', $pattern);
        
        return '/^' . $pattern . '$/';
    }
    
    /**
     * Dispatch request
     */
    public function dispatch(Request $request)
    {
        $method = $request->method();
        $uri = $request->uri();
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Extract parameters
                $params = $this->extractParams($route['path'], $matches);
                $request->setParams($params);
                
                // Run middleware
                $response = $this->runMiddleware($route['middleware'], $request);
                if ($response !== null) {
                    return $response;
                }
                
                // Execute handler
                return $this->executeHandler($route['handler'], $request);
            }
        }
        
        // Route not found
        return Response::error('Route not found', 404);
    }
    
    /**
     * Extract parameters dari URL
     */
    private function extractParams($path, $matches)
    {
        $params = [];
        
        // Find parameter names
        preg_match_all('/\{([^}]+)\}/', $path, $paramNames);
        
        // Map values to parameter names
        for ($i = 1; $i < count($matches); $i++) {
            if (isset($paramNames[1][$i - 1])) {
                $params[$paramNames[1][$i - 1]] = $matches[$i];
            }
        }
        
        return $params;
    }
    
    /**
     * Run middleware stack
     */
    private function runMiddleware($middlewares, Request $request)
    {
        foreach ($middlewares as $middleware) {
            $middlewareInstance = $this->resolveMiddleware($middleware);
            
            if (method_exists($middlewareInstance, 'handle')) {
                $response = $middlewareInstance->handle($request);
                
                if ($response instanceof Response) {
                    return $response;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Resolve middleware instance
     */
    private function resolveMiddleware($middleware)
    {
        if (is_string($middleware)) {
            // Resolve dari container
            return Container::resolve($middleware);
        }
        
        if (is_callable($middleware)) {
            return $middleware;
        }
        
        throw new \Exception("Invalid middleware: {$middleware}");
    }
    
    /**
     * Execute route handler
     */
    private function executeHandler($handler, Request $request)
    {
        if (is_array($handler)) {
            // Array format [Controller::class, 'method']
            if (count($handler) === 2) {
                list($controller, $method) = $handler;
                
                // Create controller instance with request
                $controllerInstance = new $controller($request);
                
                if (!method_exists($controllerInstance, $method)) {
                    throw new \Exception("Method {$method} not found in {$controller}");
                }
                
                // Get route parameters
                $params = $request->getParams();
                
                // Call method with route parameters as individual arguments
                return call_user_func_array([$controllerInstance, $method], array_values($params));
            }
        }
        
        if (is_string($handler)) {
            // Controller@method format
            if (strpos($handler, '@') !== false) {
                list($controller, $method) = explode('@', $handler);
                
                $controllerInstance = Container::resolve($controller);
                
                if (!method_exists($controllerInstance, $method)) {
                    throw new \Exception("Method {$method} not found in {$controller}");
                }
                
                return $controllerInstance->$method($request);
            }
            
            // Single controller method
            $controllerInstance = Container::resolve($handler);
            return $controllerInstance($request);
        }
        
        if (is_callable($handler)) {
            return $handler($request);
        }
        
        throw new \Exception("Invalid route handler");
    }
    
    /**
     * Get all routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    
    /**
     * Register global middleware
     */
    public function middleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }
    
    /**
     * Get global middlewares
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
}