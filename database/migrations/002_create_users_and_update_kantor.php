<?php

/**
 * Migration: Create Users Table and Update Kantor Table
 * Membuat tabel users dan menambahkan relasi user_id ke tabel kantor
 * Tujuan: Setiap user memiliki data kantor masing-masing
 */

use Core\Database\Connection;
use Core\Support\Env;

class CreateUsersAndUpdateKantor
{
    private $db;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }
    
    /**
     * Run migration
     */
    public function up()
    {
        $this->createUsersTable();
        $this->updateKantorTable();
        $this->insertDefaultUser();
        $this->createIndexes();
    }
    
    /**
     * Rollback migration
     */
    public function down()
    {
        // Remove foreign key constraint first
        $this->db->query("ALTER TABLE kantor DROP FOREIGN KEY IF EXISTS fk_kantor_user");
        
        // Remove user_id column from kantor
        $this->db->query("ALTER TABLE kantor DROP COLUMN IF EXISTS user_id");
        
        // Drop users table
        $this->db->query("DROP TABLE IF EXISTS users");
    }
    
    /**
     * Create users table
     */
    private function createUsersTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INT PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                name VARCHAR(100) NOT NULL,
                role ENUM('pusat', 'kanwil', 'daerah') NOT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                last_login TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $this->db->query($sql);
        echo "✓ Tabel users berhasil dibuat\n";
    }
    
    /**
     * Update kantor table to add user_id
     */
    private function updateKantorTable()
    {
        // Add user_id column
        $sql = "ALTER TABLE kantor ADD COLUMN IF NOT EXISTS user_id INT AFTER id";
        $this->db->query($sql);
        
        // Add foreign key constraint
        $sql = "
            ALTER TABLE kantor 
            ADD CONSTRAINT fk_kantor_user 
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ";
        
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            // Foreign key might already exist, ignore error
        }
        
        echo "✓ Tabel kantor berhasil diupdate dengan user_id\n";
    }
    
    /**
     * Insert default users and update existing kantor data
     */
    private function insertDefaultUser()
    {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Insert default users for each role
         $defaultUsers = [
             [
                 'username' => 'pusat',
                 'password' => $hashedPassword,
                 'name' => 'User Pusat',
                 'role' => 'pusat'
             ],
             [
                 'username' => 'kanwil',
                 'password' => $hashedPassword,
                 'name' => 'User Kanwil',
                 'role' => 'kanwil'
             ],
             [
                 'username' => 'daerah',
                 'password' => $hashedPassword,
                 'name' => 'User Daerah',
                 'role' => 'daerah'
             ]
         ];
        
        foreach ($defaultUsers as $user) {
             $userSql = "
                 INSERT IGNORE INTO users (username, password, name, role) 
                 VALUES (?, ?, ?, ?)
             ";
             $this->db->query($userSql, [
                 $user['username'],
                 $user['password'],
                 $user['name'],
                 $user['role']
             ]);
         }
        
        // Get the daerah user ID for default kantor
        $daerahUser = $this->db->fetch("SELECT id FROM users WHERE username = 'daerah'");
        
        if ($daerahUser) {
            // Update existing kantor records to belong to daerah user
            $updateKantorSql = "UPDATE kantor SET user_id = ? WHERE user_id IS NULL";
            $this->db->query($updateKantorSql, [$daerahUser['id']]);
        }
        
        echo "✓ Data user default berhasil diinsert\n";
    }
    
    /**
     * Create indexes for performance
     */
    private function createIndexes()
    {
        $indexes = [
            "CREATE INDEX IF NOT EXISTS idx_users_username ON users(username)",
            "CREATE INDEX IF NOT EXISTS idx_users_role ON users(role)",
            "CREATE INDEX IF NOT EXISTS idx_kantor_user ON kantor(user_id)"
        ];
        
        foreach ($indexes as $sql) {
            $this->db->query($sql);
        }
        echo "✓ Index untuk users dan kantor berhasil dibuat\n";
    }
}