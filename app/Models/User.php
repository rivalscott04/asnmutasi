<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * User Model
 * Model untuk mengelola data pengguna
 */
class User extends BaseModel
{
    protected $table = 'users';
    
    protected $fillable = [
        'username',
        'name',
        'password',
        'role'
    ];
    
    protected $hidden = [
        'password'
    ];
    
    /**
     * Get user by username
     */
    public function getByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = ?";
        $result = $this->dbInstance->query($sql, [$username]);
        return $result->fetch();
    }
    
    /**
     * Get users by role
     */
    public function getByRole($role)
    {
        $sql = "SELECT * FROM {$this->table} WHERE role = ? AND is_active = 1";
        $result = $this->dbInstance->query($sql, [$role]);
        return $result->fetchAll();
    }
    
    /**
     * Create new user
     */
    public function createUser($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        return $this->dbInstance->insert($sql, array_values($data));
    }
    

    
    /**
     * Verify password
     */
    public function verifyPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword);
    }
    
    /**
     * Get user's kantor data
     */
    public function getKantorData($userId)
    {
        $sql = "SELECT * FROM kantor WHERE user_id = ?";
        $result = $this->dbInstance->query($sql, [$userId]);
        return $result->fetch();
    }
    
    /**
     * Update user's kantor data
     */
    public function updateKantorData($userId, $kantorData)
    {
        // Check if user already has kantor data
        $existingKantor = $this->getKantorData($userId);
        
        if ($existingKantor) {
            // Update existing kantor data
            $setClause = [];
            $values = [];
            
            foreach ($kantorData as $key => $value) {
                $setClause[] = "{$key} = ?";
                $values[] = $value;
            }
            
            $values[] = $userId;
            $sql = "UPDATE kantor SET " . implode(', ', $setClause) . ", updated_at = NOW() WHERE user_id = ?";
            
            return $this->dbInstance->execute($sql, $values);
        } else {
            // Create new kantor data
            $kantorData['user_id'] = $userId;
            $columns = array_keys($kantorData);
            $placeholders = array_fill(0, count($columns), '?');
            
            $sql = "INSERT INTO kantor (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
            
            return $this->dbInstance->insert($sql, array_values($kantorData));
        }
    }
}