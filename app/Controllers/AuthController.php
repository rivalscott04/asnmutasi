<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

/**
 * Auth Controller
 * Menangani autentikasi pengguna
 */
class AuthController extends BaseController
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            $redirectUrl = $this->getDashboardUrlByRole($_SESSION['user_role'] ?? 'daerah');
            return $this->redirect($redirectUrl);
        }
        
        $data = [
            'title' => 'Login - ASN Mutasi'
        ];
        
        return $this->view('auth.login', $data);
    }
    
    /**
     * Proses login
     */
    public function login()
    {
        try {
            $validated = $this->validate([
                'username' => 'required|string',
                'password' => 'required|min:6'
            ]);
            
            $username = $validated['username'];
            $password = $validated['password'];
            
            // Get user from database
            $userModel = new User();
            $user = $userModel->getByUsername($username);
            
            if ($user && password_verify($password, $user['password']) && $user['is_active']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                
                // Update last_login
            User::update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
                
                // Determine redirect URL based on user role
                $redirectUrl = $this->getDashboardUrlByRole($user['role']);
                
                if ($this->expectsJson()) {
                    return $this->success([
                        'redirect' => $redirectUrl
                    ], 'Login berhasil');
                } else {
                    $this->flash('success', 'Login berhasil!');
                    return $this->redirect($redirectUrl);
                }
            } else {
                if ($this->expectsJson()) {
                    return $this->error('Username atau password salah', 401);
                } else {
                    $this->flash('error', 'Username atau password salah');
                    return $this->back();
                }
            }
            
        } catch (\Exception $e) {
            if ($this->expectsJson()) {
                return $this->error($e->getMessage(), 422);
            } else {
                $this->flash('error', $e->getMessage());
                return $this->back();
            }
        }
    }
    

    
    /**
     * Logout
     */
    public function logout()
    {
        // Clear session
        session_destroy();
        
        if ($this->expectsJson()) {
            return $this->success([
                'redirect' => '/'
            ], 'Logout berhasil');
        } else {
            return $this->redirect('/');
        }
    }
    
    /**
     * Check if user is authenticated
     */
    public function check()
    {
        $authenticated = isset($_SESSION['user_id']);
        
        if ($this->expectsJson()) {
            return $this->json([
                'authenticated' => $authenticated,
                'user' => $authenticated ? [
                    'id' => $_SESSION['user_id'],
                    'name' => $_SESSION['user_name'],
                    'username' => $_SESSION['username'],
                    'role' => $_SESSION['user_role']
                ] : null
            ]);
        }
        
        return $authenticated;
    }
    
    /**
     * Get current user
     */
    public function user()
    {
        if (!isset($_SESSION['user_id'])) {
            return $this->error('Unauthenticated', 401);
        }
        
        return $this->json([
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ]);
    }
    
    /**
     * Get dashboard URL based on user role
     */
    private function getDashboardUrlByRole($role)
    {
        switch ($role) {
            case 'kanwil':
                return '/dashboard-kanwil';
            case 'pusat':
                return '/dashboard-pusat';
            case 'daerah':
            default:
                return '/dashboard-daerah';
        }
    }
}