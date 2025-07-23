<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * Surat Model
 * Model untuk mengelola data surat
 */
class Surat extends BaseModel
{
    protected $table = 'surat';
    
    /**
     * Get total count of all letters
     */
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->dbInstance->query($sql);
        $row = $result->fetch();
        return $row ? (int)$row['total'] : 0;
    }
    
    /**
     * Get count by status
     */
    public function getCountByStatus($status)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = ?";
        $result = $this->dbInstance->query($sql, [$status]);
        $row = $result->fetch();
        return $row ? (int)$row['total'] : 0;
    }
    
    /**
     * Get count of generated letters
     */
    public function getGeneratedCount()
    {
        return $this->getCountByStatus('generated');
    }
    
    /**
     * Get count of draft letters
     */
    public function getDraftCount()
    {
        return $this->getCountByStatus('draft');
    }
    
    /**
     * Get recent letters
     */
    public function getRecentLetters($limit = 10)
    {
        $sql = "SELECT s.*, js.nama as jenis_nama, p.nama as pegawai_nama 
                FROM {$this->table} s 
                LEFT JOIN jenis_surat js ON s.jenis_surat_id = js.id 
                LEFT JOIN pegawai p ON s.pegawai_nip = p.nip 
                ORDER BY s.created_at DESC 
                LIMIT ?";
        $result = $this->dbInstance->query($sql, [$limit]);
        return $result->fetchAll();
    }
    
    /**
     * Get letters by month
     */
    public function getLettersByMonth($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }
        
        $sql = "SELECT 
                    MONTH(tanggal_surat) as bulan,
                    COUNT(*) as jumlah
                FROM {$this->table} 
                WHERE YEAR(tanggal_surat) = ? 
                GROUP BY MONTH(tanggal_surat)
                ORDER BY bulan";
        $result = $this->dbInstance->query($sql, [$year]);
        return $result->fetchAll();
    }
    
    /**
     * Get letters by type
     */
    public function getLettersByType()
    {
        $sql = "SELECT 
                    js.nama as jenis_nama,
                    COUNT(*) as jumlah
                FROM {$this->table} s
                LEFT JOIN jenis_surat js ON s.jenis_surat_id = js.id
                GROUP BY s.jenis_surat_id, js.nama
                ORDER BY jumlah DESC";
        $result = $this->dbInstance->query($sql);
        return $result->fetchAll();
    }
}