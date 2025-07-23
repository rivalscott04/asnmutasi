<?php

namespace App\Models;

use Core\Database\Connection;
use Core\Support\Logger;

/**
 * Base Model
 * Active Record pattern untuk database operations
 */
abstract class BaseModel
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';
    
    protected $attributes = [];
    protected $original = [];
    protected $exists = false;
    
    protected static $db;
    protected $dbInstance;
    
    public function __construct($attributes = [])
    {
        $this->fill($attributes);
        
        $this->dbInstance = self::getDb();
    }
    
    /**
     * Fill model dengan attributes
     */
    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        
        return $this;
    }
    
    /**
     * Check if attribute is fillable
     */
    protected function isFillable($key)
    {
        return empty($this->fillable) || in_array($key, $this->fillable);
    }
    
    /**
     * Set attribute value
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }
    
    /**
     * Get attribute value
     */
    public function getAttribute($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }
    
    /**
     * Magic getter
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }
    
    /**
     * Magic setter
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }
    
    /**
     * Find record by ID
     */
    public static function find($id)
    {
        $instance = new static();
        $table = $instance->getTable();
        $primaryKey = $instance->getPrimaryKey();
        
        $sql = "SELECT * FROM {$table} WHERE {$primaryKey} = ? LIMIT 1";
        $data = self::getDb()->fetch($sql, [$id]);
        
        if ($data) {
            $model = new static($data);
            $model->exists = true;
            $model->original = $data;
            return $model;
        }
        
        return null;
    }
    
    /**
     * Find all records
     */
    public static function all()
    {
        $instance = new static();
        $table = $instance->getTable();
        
        $sql = "SELECT * FROM {$table}";
        $results = self::getDb()->fetchAll($sql);
        
        $models = [];
        foreach ($results as $data) {
            $model = new static($data);
            $model->exists = true;
            $model->original = $data;
            $models[] = $model;
        }
        
        return $models;
    }
    
    /**
     * Create new record
     */
    public static function create($attributes)
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }
    
    /**
     * Update record by ID
     */
    public static function update($id, $attributes)
    {
        $model = static::find($id);
        if ($model) {
            $model->fill($attributes);
            return $model->save();
        }
        return false;
    }
    
    /**
     * Save model to database
     */
    public function save()
    {
        if ($this->exists) {
            return $this->performUpdate();
        } else {
            return $this->insert();
        }
    }
    
    /**
     * Insert new record
     */
    protected function insert()
    {
        $attributes = $this->getAttributesForInsert();
        
        if ($this->timestamps) {
            $now = date($this->dateFormat);
            $attributes['created_at'] = $now;
            $attributes['updated_at'] = $now;
        }
        
        $columns = array_keys($attributes);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = "INSERT INTO {$this->getTable()} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        // Log database query
        $startTime = microtime(true);
        $id = $this->dbInstance->insert($sql, array_values($attributes));
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        Logger::query($sql, array_values($attributes), $executionTime . 'ms');
        
        if ($id) {
            $this->setAttribute($this->primaryKey, $id);
            $this->exists = true;
            $this->original = $this->attributes;
            
            Logger::info('Model created', [
                'model' => static::class,
                'table' => $this->getTable(),
                'id' => $id
            ]);
            
            return true;
        }
        
        Logger::error('Failed to insert record', [
            'model' => static::class,
            'table' => $this->getTable(),
            'sql' => $sql
        ]);
        
        return false;
    }
    
    /**
     * Update existing record
     */
    protected function performUpdate()
    {
        $attributes = $this->getAttributesForUpdate();
        
        if (empty($attributes)) {
            return true; // No changes
        }
        
        if ($this->timestamps) {
            $attributes['updated_at'] = date($this->dateFormat);
        }
        
        $sets = [];
        foreach (array_keys($attributes) as $column) {
            $sets[] = "{$column} = ?";
        }
        
        $sql = "UPDATE {$this->getTable()} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = ?";
        
        $values = array_values($attributes);
        $values[] = $this->getAttribute($this->primaryKey);
        
        // Log database query
        $startTime = microtime(true);
        $affected = $this->dbInstance->execute($sql, $values);
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        Logger::query($sql, $values, $executionTime . 'ms');
        
        if ($affected > 0) {
            $this->original = $this->attributes;
            
            Logger::info('Model updated', [
                'model' => static::class,
                'table' => $this->getTable(),
                'id' => $this->getAttribute($this->primaryKey),
                'changed_fields' => array_keys($attributes)
            ]);
            
            return true;
        }
        
        Logger::warning('No rows affected in update', [
            'model' => static::class,
            'table' => $this->getTable(),
            'id' => $this->getAttribute($this->primaryKey)
        ]);
        
        return false;
    }
    
    /**
     * Delete record
     */
    public function delete()
    {
        if (!$this->exists) {
            Logger::warning('Attempted to delete non-existent model', [
                'model' => static::class,
                'table' => $this->getTable()
            ]);
            return false;
        }
        
        $sql = "DELETE FROM {$this->getTable()} WHERE {$this->primaryKey} = ?";
        $id = $this->getAttribute($this->primaryKey);
        
        // Log database query
        $startTime = microtime(true);
        $affected = $this->dbInstance->execute($sql, [$id]);
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        Logger::query($sql, [$id], $executionTime . 'ms');
        
        if ($affected > 0) {
            $this->exists = false;
            
            Logger::info('Model deleted', [
                'model' => static::class,
                'table' => $this->getTable(),
                'id' => $id
            ]);
            
            return true;
        }
        
        Logger::warning('No rows affected in delete', [
            'model' => static::class,
            'table' => $this->getTable(),
            'id' => $id
        ]);
        
        return false;
    }
    
    /**
     * Get attributes for insert
     */
    protected function getAttributesForInsert()
    {
        $attributes = $this->attributes;
        
        // Remove primary key if auto-increment
        if (isset($attributes[$this->primaryKey]) && empty($attributes[$this->primaryKey])) {
            unset($attributes[$this->primaryKey]);
        }
        
        return $attributes;
    }
    
    /**
     * Get attributes for update (only changed)
     */
    protected function getAttributesForUpdate()
    {
        $changed = [];
        
        foreach ($this->attributes as $key => $value) {
            if (!isset($this->original[$key]) || $this->original[$key] !== $value) {
                if ($key !== $this->primaryKey) {
                    $changed[$key] = $value;
                }
            }
        }
        
        return $changed;
    }
    
    /**
     * Get table name
     */
    public function getTable()
    {
        if ($this->table) {
            return $this->table;
        }
        
        // Generate table name from class name
        $class = get_class($this);
        $class = basename(str_replace('\\', '/', $class));
        return strtolower($class) . 's';
    }
    
    /**
     * Get primary key
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    
    /**
     * Get database connection
     */
    protected static function getDb()
    {
        if (!self::$db) {
            self::$db = Connection::getInstance();
        }
        
        return self::$db;
    }
    
    /**
     * Convert model to array
     */
    public function toArray()
    {
        $attributes = $this->attributes;
        
        // Remove hidden attributes
        foreach ($this->hidden as $hidden) {
            unset($attributes[$hidden]);
        }
        
        return $attributes;
    }
    
    /**
     * Convert model to JSON
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Check if model exists in database
     */
    public function exists()
    {
        return $this->exists;
    }
}