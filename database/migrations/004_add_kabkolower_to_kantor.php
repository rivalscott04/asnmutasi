<?php
/**
 * Migration: Add kabkolower column to kantor table
 * Date: 2025-01-22
 * Description: Menambahkan kolom kabkolower untuk menyimpan nama kabupaten/kota dalam format yang lebih bagus
 */

require_once dirname(__DIR__, 2) . '/core/autoload.php';

use Core\Database\Connection;
use Core\Support\Env;

class AddKabkolowerToKantor
{
    private $db;

    public function __construct()
    {
        // Load environment variables
        Env::load(dirname(__DIR__, 2) . '/.env');
        
        // Initialize database connection
        $this->db = Connection::getInstance();
    }

    public function up()
    {
        echo "Running migration: Add kabkolower column to kantor table...\n";
        
        try {
            // Add kabkolower column to kantor table
            $sql = "ALTER TABLE kantor ADD COLUMN kabkolower VARCHAR(100) AFTER ibukota";
            $this->db->query($sql);
            echo "✓ Kolom 'kabkolower' berhasil ditambahkan ke tabel kantor\n";
            
            // Update existing records with formatted kabkolower values (format yang mudah dibaca)
            $updateSql = "UPDATE kantor SET kabkolower = CASE 
                WHEN kabupaten_kota LIKE '%LOMBOK TIMUR%' THEN 'Kabupaten Lombok Timur'
                WHEN kabupaten_kota LIKE '%LOMBOK BARAT%' THEN 'Kabupaten Lombok Barat'
                WHEN kabupaten_kota LIKE '%LOMBOK TENGAH%' THEN 'Kabupaten Lombok Tengah'
                WHEN kabupaten_kota LIKE '%LOMBOK UTARA%' THEN 'Kabupaten Lombok Utara'
                WHEN kabupaten_kota LIKE '%MATARAM%' THEN 'Kota Mataram'
                WHEN kabupaten_kota LIKE '%SUMBAWA%' AND kabupaten_kota NOT LIKE '%BARAT%' THEN 'Kabupaten Sumbawa'
                WHEN kabupaten_kota LIKE '%SUMBAWA BARAT%' THEN 'Kabupaten Sumbawa Barat'
                WHEN kabupaten_kota LIKE '%DOMPU%' THEN 'Kabupaten Dompu'
                WHEN kabupaten_kota LIKE '%BIMA%' AND kabupaten_kota LIKE '%KABUPATEN%' THEN 'Kabupaten Bima'
                WHEN kabupaten_kota LIKE '%BIMA%' AND kabupaten_kota LIKE '%KOTA%' THEN 'Kota Bima'
                WHEN kabupaten_kota LIKE '%JAKARTA%' THEN 'DKI Jakarta'
                ELSE 'Kota Mataram'
            END
            WHERE kabkolower IS NULL OR kabkolower = ''";
            
            $this->db->query($updateSql);
            echo "✓ Data kabkolower default berhasil diupdate dengan format yang mudah dibaca\n";
            
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function down()
    {
        echo "Rolling back migration: Remove kabkolower column from kantor table...\n";
        
        try {
            $sql = "ALTER TABLE kantor DROP COLUMN kabkolower";
            $this->db->query($sql);
            echo "✓ Kolom 'kabkolower' berhasil dihapus dari tabel kantor\n";
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}

// Run migration if called directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    echo "Menjalankan migration: Add kabkolower column to kantor table\n";
    echo "=========================================================\n";
    
    try {
        $migration = new AddKabkolowerToKantor();
        $migration->up();
        echo "\n✅ Migration berhasil dijalankan!\n";
    } catch (Exception $e) {
        echo "\n❌ Migration gagal: " . $e->getMessage() . "\n";
        exit(1);
    }
}

?>