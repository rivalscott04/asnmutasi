<?php
/**
 * Migration: Add ibukota column to kantor table
 * Date: 2025-01-22
 * Description: Menambahkan kolom ibukota untuk menyimpan nama ibu kota kabupaten/kota
 */

require_once dirname(__DIR__, 2) . '/core/autoload.php';

use Core\Database\Connection;
use Core\Support\Env;

class AddIbukotaToKantor
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
        echo "Running migration: Add ibukota column to kantor table...\n";
        
        try {
            // Add ibukota column to kantor table
            $sql = "ALTER TABLE kantor ADD COLUMN ibukota VARCHAR(100) AFTER kabupaten_kota";
            $this->db->query($sql);
            echo "✓ Kolom 'ibukota' berhasil ditambahkan ke tabel kantor\n";
            
            // Update existing records with default ibukota values (format yang mudah dibaca)
            $updateSql = "UPDATE kantor SET ibukota = CASE 
                WHEN kabupaten_kota LIKE '%LOMBOK TIMUR%' THEN 'Selong'
                WHEN kabupaten_kota LIKE '%LOMBOK BARAT%' THEN 'Gerung'
                WHEN kabupaten_kota LIKE '%LOMBOK TENGAH%' THEN 'Praya'
                WHEN kabupaten_kota LIKE '%LOMBOK UTARA%' THEN 'Tanjung'
                WHEN kabupaten_kota LIKE '%MATARAM%' THEN 'Mataram'
                WHEN kabupaten_kota LIKE '%SUMBAWA%' AND kabupaten_kota NOT LIKE '%BARAT%' THEN 'Sumbawa Besar'
                WHEN kabupaten_kota LIKE '%SUMBAWA BARAT%' THEN 'Taliwang'
                WHEN kabupaten_kota LIKE '%DOMPU%' THEN 'Dompu'
                WHEN kabupaten_kota LIKE '%BIMA%' AND kabupaten_kota LIKE '%KABUPATEN%' THEN 'Woha'
                WHEN kabupaten_kota LIKE '%BIMA%' AND kabupaten_kota LIKE '%KOTA%' THEN 'Bima'
                WHEN kabupaten_kota LIKE '%JAKARTA%' THEN 'Jakarta'
                ELSE 'Mataram'
            END
            WHERE ibukota IS NULL OR ibukota = ''";
            
            $this->db->query($updateSql);
            echo "✓ Data ibukota default berhasil diupdate dengan format yang mudah dibaca\n";
            
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function down()
    {
        echo "Rolling back migration: Remove ibukota column from kantor table...\n";
        
        try {
            $sql = "ALTER TABLE kantor DROP COLUMN ibukota";
            $this->db->query($sql);
            echo "✓ Kolom 'ibukota' berhasil dihapus dari tabel kantor\n";
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}

// Run migration if called directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    echo "Menjalankan migration: Add ibukota column to kantor table\n";
    echo "========================================================\n";
    
    try {
        $migration = new AddIbukotaToKantor();
        $migration->up();
        echo "\n✅ Migration berhasil dijalankan!\n";
    } catch (Exception $e) {
        echo "\n❌ Migration gagal: " . $e->getMessage() . "\n";
        exit(1);
    }
}

?>