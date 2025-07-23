<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * JenisSurat Model
 * Model untuk mengelola data jenis surat
 */
class JenisSurat extends BaseModel
{
    protected $table = 'jenis_surat';
    
    /**
     * Get total count of active letter types
     */
    public function getTotalActiveCount()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'aktif'";
        $result = $this->dbInstance->query($sql);
        $row = $result->fetch();
        return $row ? (int)$row['total'] : 0;
    }
    
    /**
     * Get all active letter types
     */
    public function getActiveTypes()
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'aktif' ORDER BY nama";
        $result = $this->dbInstance->query($sql);
        return $result->fetchAll();
    }
    
    /**
     * Get letter type by code
     */
    public function getByCode($code)
    {
        $sql = "SELECT * FROM {$this->table} WHERE kode = ? AND status = 'aktif'";
        $result = $this->dbInstance->query($sql, [$code]);
        return $result->fetch();
    }
    
    /**
     * Get letter type by id
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->dbInstance->query($sql, [$id]);
        return $result->fetch();
    }
}