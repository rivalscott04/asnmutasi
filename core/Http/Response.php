<?php

namespace Core\Http;

/**
 * HTTP Response Handler
 * Handle HTTP responses
 */
class Response
{
    private $content;
    private $statusCode;
    private $headers;
    
    public function __construct($content = '', $statusCode = 200, $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }
    
    /**
     * Set response content
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Set status code
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    
    /**
     * Set header
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }
    
    /**
     * Set multiple headers
     */
    public function setHeaders($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }
    
    /**
     * Send response
     */
    public function send()
    {
        // Set status code
        http_response_code($this->statusCode);
        
        // Set headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        
        // Output content
        echo $this->content;
        
        return $this;
    }
    
    /**
     * Create JSON response
     */
    public static function json($data, $statusCode = 200, $headers = [])
    {
        $headers['Content-Type'] = 'application/json';
        
        $content = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        return new self($content, $statusCode, $headers);
    }
    
    /**
     * Create success JSON response
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        
        return self::json($response, $statusCode);
    }
    
    /**
     * Create error JSON response
     */
    public static function error($message = 'Error', $statusCode = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        return self::json($response, $statusCode);
    }
    
    /**
     * Create redirect response
     */
    public static function redirect($url, $statusCode = 302)
    {
        $headers = ['Location' => $url];
        return new self('', $statusCode, $headers);
    }
    
    /**
     * Create HTML response
     */
    public static function html($content, $statusCode = 200)
    {
        $headers = ['Content-Type' => 'text/html; charset=utf-8'];
        return new self($content, $statusCode, $headers);
    }
    
    /**
     * Create plain text response
     */
    public static function text($content, $statusCode = 200)
    {
        $headers = ['Content-Type' => 'text/plain; charset=utf-8'];
        return new self($content, $statusCode, $headers);
    }
    
    /**
     * Create file download response
     */
    public static function download($filePath, $filename = null, $headers = [])
    {
        if (!file_exists($filePath)) {
            return self::error('File not found', 404);
        }
        
        $filename = $filename ?: basename($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        
        $defaultHeaders = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => filesize($filePath),
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => '0'
        ];
        
        $headers = array_merge($defaultHeaders, $headers);
        
        $content = file_get_contents($filePath);
        
        return new self($content, 200, $headers);
    }
    
    /**
     * Get response content
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Get status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    /**
     * Get headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * Get header
     */
    public function getHeader($key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }
}