<?php

namespace App\Middleware;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Auth Middleware
 * Middleware untuk mengecek autentikasi pengguna
 */
class AuthMiddleware
{
    /**
     * Handle incoming request
     */
    public function handle(Request $request)
    {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            // If AJAX request, return JSON response
            if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return Response::json([
                    'success' => false,
                    'message' => 'Anda belum login',
                    'redirect' => '/login',
                    'show_login_modal' => true
                ], 401);
            }
            
            // For regular requests, redirect to login
            header('Location: /login');
            exit;
        }
        
        // User is authenticated, continue
        return null;
    }
}