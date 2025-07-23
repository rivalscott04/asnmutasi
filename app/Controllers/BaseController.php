<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Response;
use Core\Support\Logger;

/**
 * Base Controller
 * Menyediakan fungsi dasar untuk semua controller
 */
abstract class BaseController
{
    protected $request;
    
    public function __construct(Request $request = null)
    {
        $this->request = $request ?: new Request();
        
        // Log incoming request
        $this->logRequest();
    }
    
    /**
     * Log incoming request
     */
    protected function logRequest()
    {
        try {
            $method = $this->request->method();
            $uri = $this->request->uri();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            
            // Log basic request info
            Logger::request($method, $uri, [], $userAgent);
            
            // Log POST data (excluding sensitive fields)
            if ($method === 'POST') {
                $postData = $this->request->input();
                $filteredData = $this->filterSensitiveData($postData);
                
                if (!empty($filteredData)) {
                    Logger::debug('POST Data', ['data' => $filteredData]);
                }
            }
        } catch (\Exception $e) {
            // Silent fail - don't break the application if logging fails
            error_log('Logging error: ' . $e->getMessage());
        }
    }
    
    /**
     * Filter sensitive data from logging
     */
    protected function filterSensitiveData($data)
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'csrf_token', 'api_key', 'secret'];
        
        if (!is_array($data)) {
            return $data;
        }
        
        $filtered = $data;
        foreach ($sensitiveFields as $field) {
            if (isset($filtered[$field])) {
                $filtered[$field] = '[FILTERED]';
            }
        }
        
        return $filtered;
    }
    
    /**
     * Render view dengan data
     */
    protected function view($view, $data = [])
    {
        $viewPath = $this->getViewPath($view);
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: {$viewPath}");
        }
        
        // Extract data untuk digunakan di view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include view file
        include $viewPath;
        
        // Get content
        $content = ob_get_clean();
        
        return Response::html($content);
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $status = 200)
    {
        return Response::json($data, $status);
    }
    
    /**
     * Return success JSON response
     */
    protected function success($data = null, $message = 'Success', $status = 200)
    {
        return Response::success($data, $message, $status);
    }
    
    /**
     * Return error JSON response
     */
    protected function error($message = 'Error', $status = 400, $errors = null)
    {
        return Response::error($message, $status, $errors);
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url, $status = 302)
    {
        return Response::redirect($url, $status);
    }
    
    /**
     * Redirect back
     */
    protected function back()
    {
        $referer = $this->request->header('Referer') ?? '/';
        return $this->redirect($referer);
    }
    
    /**
     * Get request input
     */
    protected function input($key = null, $default = null)
    {
        if ($key === null) {
            return $this->request->input();
        }
        
        return $this->request->input($key, $default);
    }
    
    /**
     * Get query parameter
     */
    protected function query($key = null, $default = null)
    {
        if ($key === null) {
            return $this->request->query();
        }
        
        return $this->request->query($key, $default);
    }
    
    /**
     * Get uploaded file
     */
    protected function file($key)
    {
        return $this->request->file($key);
    }
    
    /**
     * Validate request input
     */
    protected function validate($rules, $messages = [])
    {
        $validator = new \App\Validation\Validator($this->request->input(), $rules, $messages);
        
        if ($validator->fails()) {
            // Log validation errors
            Logger::validation($validator->errors(), [
                'uri' => $this->request->uri(),
                'method' => $this->request->method(),
                'input_data' => $this->filterSensitiveData($this->request->input()),
                'rules' => $rules
            ]);
            
            if ($this->request->expectsJson()) {
                return $this->error('Validation failed', 422, $validator->errors());
            } else {
                // For web requests, you might want to redirect back with errors
                // This is a simplified version
                throw new \Exception('Validation failed: ' . implode(', ', $validator->getErrorMessages()));
            }
        }
        
        // Log successful validation for debugging
        Logger::debug('Validation successful', [
            'uri' => $this->request->uri(),
            'validated_fields' => array_keys($validator->validated())
        ]);
        
        return $validator->validated();
    }
    
    /**
     * Get view file path
     */
    protected function getViewPath($view)
    {
        $view = str_replace('.', DIRECTORY_SEPARATOR, $view);
        return VIEWS_PATH . DIRECTORY_SEPARATOR . $view . '.php';
    }
    
    /**
     * Check if request is AJAX
     */
    protected function isAjax()
    {
        return $this->request->isAjax();
    }
    
    /**
     * Check if request expects JSON
     */
    protected function expectsJson()
    {
        return $this->request->expectsJson();
    }
    
    /**
     * Get request method
     */
    protected function method()
    {
        return $this->request->method();
    }
    
    /**
     * Check if request method is GET
     */
    protected function isGet()
    {
        return $this->method() === 'GET';
    }
    
    /**
     * Check if request method is POST
     */
    protected function isPost()
    {
        return $this->method() === 'POST';
    }
    
    /**
     * Check if request method is PUT
     */
    protected function isPut()
    {
        return $this->method() === 'PUT';
    }
    
    /**
     * Check if request method is DELETE
     */
    protected function isDelete()
    {
        return $this->method() === 'DELETE';
    }
    
    /**
     * Get current URL
     */
    protected function currentUrl()
    {
        return $this->request->uri();
    }
    
    /**
     * Get base URL
     */
    protected function baseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host;
    }
    
    /**
     * Generate URL
     */
    protected function url($path = '')
    {
        return rtrim($this->baseUrl(), '/') . '/' . ltrim($path, '/');
    }
    
    /**
     * Set flash message (for session-based apps)
     */
    protected function flash($key, $message)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Get flash message
     */
    protected function getFlash($key, $default = null)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $message = $_SESSION['flash'][$key] ?? $default;
        
        // Remove flash message after reading
        if (isset($_SESSION['flash'][$key])) {
            unset($_SESSION['flash'][$key]);
        }
        
        return $message;
    }
    
    /**
     * Get logo URL from database or default
     */
    protected function getLogoUrl()
    {
        try {
            $userId = $_SESSION['user_id'] ?? null;
            if ($userId) {
                $db = \Core\Database\Connection::getInstance();
                $kantorData = $db->fetch("SELECT logo_path FROM kantor WHERE user_id = ?", [$userId]);
                
                if ($kantorData && !empty($kantorData['logo_path'])) {
                    // Check if logo file exists
                    $logoPath = ROOT_PATH . '/public' . $kantorData['logo_path'];
                    if (file_exists($logoPath)) {
                        return $this->baseUrl() . $kantorData['logo_path'];
                    }
                }
            }
            
            // Try to get any logo from kantor table if user-specific not found
            $db = \Core\Database\Connection::getInstance();
            $anyKantor = $db->fetch("SELECT logo_path FROM kantor WHERE logo_path IS NOT NULL AND logo_path != '' LIMIT 1");
            if ($anyKantor && !empty($anyKantor['logo_path'])) {
                $logoPath = ROOT_PATH . '/public' . $anyKantor['logo_path'];
                if (file_exists($logoPath)) {
                    return $this->baseUrl() . $anyKantor['logo_path'];
                }
            }
            
        } catch (\Exception $e) {
            // Log error if needed, but continue with default logo
        }
        
        // Try to use any available logo from uploads
        $uploadsDir = ROOT_PATH . '/uploads/logos';
        if (is_dir($uploadsDir)) {
            $logoFiles = glob($uploadsDir . '/*.{png,jpg,jpeg,gif}', GLOB_BRACE);
            if (!empty($logoFiles)) {
                $logoFile = basename($logoFiles[0]);
                return $this->baseUrl() . '/uploads/logos/' . $logoFile;
            }
        }
        
        // Return default logo
        return $this->baseUrl() . '/images/logo-kemenag.png';
    }
    
    /**
     * Get office data from database
     */
    protected function getKantorData()
    {
        try {
            $userId = $_SESSION['user_id'] ?? null;
            if ($userId) {
                $db = \Core\Database\Connection::getInstance();
                $kantorData = $db->fetch("SELECT * FROM kantor WHERE user_id = ?", [$userId]);
                
                if ($kantorData) {
                    return $kantorData;
                }
            }
        } catch (\Exception $e) {
            // Log error if needed, but continue with default data
        }
        
        // Return default office data
        return [
            'kabupaten_kota' => 'LOMBOK TIMUR',
            'ibukota' => 'SELONG',
            'alamat' => 'Jl. TGH. Lopan No. 12 Selong',
            'telepon' => 'Telp. (0370) 654321',
            'fax' => 'Fax. (0370) 654322',
            'email' => 'kankemenag.lotim@kemenag.go.id',
            'website' => 'www.kankemenag.lotim.go.id',
            'logo_path' => null
        ];
    }
    
    /**
     * Abort with HTTP status
     */
    protected function abort($status = 404, $message = null)
    {
        $messages = [
            404 => 'Not Found',
            403 => 'Forbidden',
            401 => 'Unauthorized',
            500 => 'Internal Server Error'
        ];
        
        $message = $message ?? $messages[$status] ?? 'Error';
        
        if ($this->expectsJson()) {
            return $this->error($message, $status);
        } else {
            // For web requests, you might want to show an error page
            http_response_code($status);
            echo "<h1>{$status} - {$message}</h1>";
            exit;
        }
    }
}