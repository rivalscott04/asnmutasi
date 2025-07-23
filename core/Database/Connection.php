<?php

namespace Core\Database;

use PDO;
use PDOException;
use Core\Support\Config;

/**
 * Database Connection
 * PDO wrapper dengan prepared statements dan transaction support
 */
class Connection
{   
    private static $instance = null;
    private $pdo;
    private $inTransaction = false;
    
    private function __construct()
    {
        $this->connect();
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Connect to database
     */
    private function connect()
    {
        $defaultConnection = config('database.default', 'mysql');
        $connections = config('database.connections', []);
        
        if (!isset($connections[$defaultConnection])) {
            throw new \Exception("Database connection '{$defaultConnection}' not configured");
        }
        
        $config = $connections[$defaultConnection];
        $driver = $config['driver'];
        
        $options = $config['options'] ?? [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        if ($driver === 'sqlite') {
            $database = $config['database'];
            $dsn = "sqlite:{$database}";
            
            // Create database file if it doesn't exist
            if (!file_exists($database)) {
                $dir = dirname($database);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                touch($database);
            }
            
            $username = null;
            $password = null;
        } else {
            // MySQL/PostgreSQL connection
            $host = $config['host'];
            $port = $config['port'];
            $database = $config['database'];
            $username = $config['username'];
            $password = $config['password'];
            
            if ($driver === 'mysql') {
                $charset = $config['charset'] ?? 'utf8mb4';
                $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
                $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES {$charset}";
            } else {
                $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
            }
        }
        
        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Execute query dengan prepared statement
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new \Exception("Query failed: " . $e->getMessage());
        }
    }
    
    /**
     * Fetch single record
     */
    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Fetch all records
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Insert record dan return last insert ID
     */
    public function insert($sql, $params = [])
    {
        $this->query($sql, $params);
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Update/Delete dan return affected rows
     */
    public function execute($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        if (!$this->inTransaction) {
            $this->pdo->beginTransaction();
            $this->inTransaction = true;
        }
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        if ($this->inTransaction) {
            $this->pdo->commit();
            $this->inTransaction = false;
        }
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        if ($this->inTransaction) {
            $this->pdo->rollback();
            $this->inTransaction = false;
        }
    }
    
    /**
     * Check if in transaction
     */
    public function inTransaction()
    {
        return $this->inTransaction;
    }
    
    /**
     * Get PDO instance
     */
    public function getPdo()
    {
        return $this->pdo;
    }
    
    /**
     * Quote string untuk SQL
     */
    public function quote($string)
    {
        return $this->pdo->quote($string);
    }
    
    /**
     * Get last insert ID
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Get table columns
     */
    public function getColumns($table)
    {
        $sql = "DESCRIBE {$table}";
        $columns = $this->fetchAll($sql);
        
        return array_column($columns, 'Field');
    }
    
    /**
     * Check if table exists
     */
    public function tableExists($table)
    {
        $sql = "SHOW TABLES LIKE ?";
        $result = $this->fetch($sql, [$table]);
        
        return !empty($result);
    }
}