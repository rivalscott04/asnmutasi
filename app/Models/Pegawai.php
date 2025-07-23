<?php

namespace App\Models;

use App\Models\BaseModel;

class Pegawai extends BaseModel
{
    protected $table = 'pegawai';
    protected $primaryKey = 'nip';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'nip',
        'nama',
        'golongan',
        'tmt_pensiun',
        'unit_kerja',
        'induk_unit',
        'jabatan'
    ];
    
    protected $hidden = [];
    
    /**
     * Search pegawai by nama or NIP
     * 
     * @param string $query
     * @param int $limit
     * @return array
     */
    public static function search($query, $limit = 10)
    {
        $db = static::getDb();
        
        $sql = "SELECT nip, nama, golongan, jabatan, unit_kerja 
                FROM pegawai 
                WHERE (nama LIKE ? OR nip LIKE ?) 
                ORDER BY nama ASC 
                LIMIT ?";
        
        return $db->fetchAll($sql, ['%' . $query . '%', '%' . $query . '%', $limit]);
    }
    
    /**
     * Get pegawai by NIP
     * 
     * @param string $nip
     * @return array|null
     */
    public static function getByNip($nip)
    {
        $db = static::getDb();
        
        $sql = "SELECT * FROM pegawai WHERE nip = ?";
        return $db->fetch($sql, [$nip]);
    }
    
    /**
     * Find pegawai by NIP (alias for getByNip)
     * 
     * @param string $nip
     * @return array|null
     */
    public static function findByNIP($nip)
    {
        return static::getByNip($nip);
    }
    
    /**
     * Get all pegawai with pagination
     * 
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function paginate($page = 1, $perPage = 20)
    {
        $db = static::getDb();
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT nip, nama, golongan, jabatan, unit_kerja 
                FROM pegawai 
                ORDER BY nama ASC 
                LIMIT ? OFFSET ?";
        
        return $db->fetchAll($sql, [$perPage, $offset]);
    }
    
    /**
     * Count total pegawai
     * 
     * @return int
     */
    public static function count()
    {
        $db = static::getDb();
        
        $sql = "SELECT COUNT(*) as total FROM pegawai";
        $result = $db->fetch($sql);
        return (int) $result['total'];
    }
    
    /**
     * Count search results
     * 
     * @param string $query
     * @return int
     */
    public static function countSearch($query)
    {
        $db = static::getDb();
        
        $sql = "SELECT COUNT(*) as total FROM pegawai 
                WHERE (nama LIKE ? OR nip LIKE ?)";
        
        $result = $db->fetch($sql, ['%' . $query . '%', '%' . $query . '%']);
        return (int) $result['total'];
    }
    
    /**
     * Format pegawai for display
     * 
     * @param array $pegawai
     * @return string
     */
    public static function formatForDisplay($pegawai)
    {
        return $pegawai['nama'] . ' (' . $pegawai['nip'] . ')';
    }
}