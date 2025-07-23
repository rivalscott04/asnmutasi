<?php

namespace Core\Http;

/**
 * HTTP Request Handler
 * Handle incoming HTTP requests
 */
class Request
{
    private $method;
    private $uri;
    private $params;
    private $query;
    private $body;
    private $headers;
    private $files;
    
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->parseUri();
        $this->params = [];
        $this->query = $_GET;
        $this->body = $this->parseBody();
        $this->headers = $this->parseHeaders();
        $this->files = $_FILES;
    }
    
    /**
     * Parse request URI
     */
    private function parseUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        return rtrim($uri, '/');
    }
    
    /**
     * Parse request body
     */
    private function parseBody()
    {
        $body = [];
        
        if ($this->method === 'POST') {
            $body = $_POST;
            
            // Handle JSON input
            $contentType = $this->header('Content-Type', '');
            if (strpos($contentType, 'application/json') !== false) {
                $json = file_get_contents('php://input');
                $decoded = json_decode($json, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $body = $decoded;
                }
            }
        }
        
        return $body;
    }
    
    /**
     * Parse request headers
     */
    private function parseHeaders()
    {
        $headers = [];
        
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('_', '-', substr($key, 5));
                $headers[ucwords(strtolower($header), '-')] = $value;
            }
        }
        
        return $headers;
    }
    
    /**
     * Get request method
     */
    public function method()
    {
        return $this->method;
    }
    
    /**
     * Get request URI
     */
    public function uri()
    {
        return $this->uri ?: '/';
    }
    
    /**
     * Get all input data
     */
    public function input($key = null, $default = null)
    {
        $input = array_merge($this->query, $this->body);
        
        if ($key === null) {
            return $input;
        }
        
        return $input[$key] ?? $default;
    }
    
    /**
     * Get query parameter
     */
    public function query($key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }
        
        return $this->query[$key] ?? $default;
    }
    
    /**
     * Get body parameter
     */
    public function body($key = null, $default = null)
    {
        if ($key === null) {
            return $this->body;
        }
        
        return $this->body[$key] ?? $default;
    }
    
    /**
     * Get route parameter
     */
    public function param($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }
    
    /**
     * Set route parameters
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
    
    /**
     * Get all route parameters
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * Get header
     */
    public function header($key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }
    
    /**
     * Get all headers
     */
    public function headers()
    {
        return $this->headers;
    }
    
    /**
     * Get uploaded file
     */
    public function file($key)
    {
        return $this->files[$key] ?? null;
    }
    
    /**
     * Get all uploaded files
     */
    public function files()
    {
        return $this->files;
    }
    
    /**
     * Check if request has input
     */
    public function has($key)
    {
        $input = array_merge($this->query, $this->body);
        return isset($input[$key]);
    }
    
    /**
     * Check if request is AJAX
     */
    public function isAjax()
    {
        return $this->header('X-Requested-With') === 'XMLHttpRequest';
    }
    
    /**
     * Check if request is JSON
     */
    public function isJson()
    {
        $contentType = $this->header('Content-Type', '');
        return strpos($contentType, 'application/json') !== false;
    }
    
    /**
     * Get client IP address
     */
    public function ip()
    {
        $keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                return trim($ips[0]);
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Get user agent
     */
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    
    /**
     * Check if request expects JSON response
     */
    public function expectsJson()
    {
        $accept = $this->header('Accept', '');
        return $this->isAjax() || $this->isJson() || strpos($accept, 'application/json') !== false;
    }
}