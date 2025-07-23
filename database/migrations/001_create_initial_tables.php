<?php

/**
 * Migration: Create Initial Tables
 * Membuat tabel-tabel dasar untuk sistem ASN Mutasi
 * Skema: 1 pegawai bisa memiliki banyak surat
 */

use Core\Database\Connection;
use Core\Support\Env;

class CreateInitialTables
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
        // Note: Tabel pegawai sudah ada dengan struktur yang telah ditentukan
        $this->createJenisSuratTable();
        $this->createSuratTable();
        $this->createKantorTable();
        $this->createLogAktivitasTable();
        $this->insertDefaultData();
        $this->createIndexes();
    }
    
    /**
     * Rollback migration
     */
    public function down()
    {
        // Note: Tabel pegawai tidak dihapus karena sudah ada sebelumnya
        $tables = ['log_aktivitas', 'surat', 'jenis_surat', 'kantor'];
        
        foreach ($tables as $table) {
            $this->db->query("DROP TABLE IF EXISTS {$table}");
        }
    }
    
    // Note: Tabel pegawai sudah ada dengan struktur yang telah ditentukan
    
    /**
     * Create jenis_surat table
     */
    private function createJenisSuratTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS jenis_surat (
                id INT PRIMARY KEY AUTO_INCREMENT,
                kode VARCHAR(20) UNIQUE NOT NULL,
                nama VARCHAR(100) NOT NULL,
                template_file VARCHAR(100),
                deskripsi TEXT,
                status ENUM('aktif', 'non_aktif') DEFAULT 'aktif',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $this->db->query($sql);
        echo "✓ Tabel jenis_surat berhasil dibuat\n";
    }
    
    /**
     * Create surat table
     */
    private function createSuratTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS surat (
                id INT PRIMARY KEY AUTO_INCREMENT,
                nomor_surat VARCHAR(50) UNIQUE NOT NULL,
                pegawai_nip VARCHAR(20) NOT NULL,
                jenis_surat_id INT NOT NULL,
                pejabat_penandatangan_nip VARCHAR(20),
                judul VARCHAR(200),
                tanggal_surat DATE NOT NULL,
                bulan INT NOT NULL,
                tahun VARCHAR(4),
                status ENUM('draft', 'generated', 'signed') DEFAULT 'draft',
                file_path VARCHAR(255),
                data_surat JSON,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (pegawai_nip) REFERENCES pegawai(nip) ON DELETE CASCADE,
                FOREIGN KEY (jenis_surat_id) REFERENCES jenis_surat(id) ON DELETE RESTRICT,
                FOREIGN KEY (pejabat_penandatangan_nip) REFERENCES pegawai(nip) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $this->db->query($sql);
        echo "✓ Tabel surat berhasil dibuat\n";
    }
    
    /**
     * Create kantor table
     */
    private function createKantorTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS kantor (
                id INT PRIMARY KEY AUTO_INCREMENT,
                nama VARCHAR(100) NOT NULL,
                kabupaten_kota VARCHAR(100),
                alamat TEXT,
                telepon VARCHAR(20),
                fax VARCHAR(20),
                email VARCHAR(100),
                website VARCHAR(100),
                logo_path VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $this->db->query($sql);
        echo "✓ Tabel kantor berhasil dibuat\n";
    }
    
    /**
     * Create log_aktivitas table
     */
    private function createLogAktivitasTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS log_aktivitas (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT,
                surat_id INT,
                aktivitas VARCHAR(100) NOT NULL,
                deskripsi TEXT,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                
                FOREIGN KEY (surat_id) REFERENCES surat(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $this->db->query($sql);
        echo "✓ Tabel log_aktivitas berhasil dibuat\n";
    }
    
    /**
     * Insert default data
     */
    private function insertDefaultData()
    {
        // Insert jenis surat
        $jenisSurat = [
            ['SKPM', 'Surat Keterangan Pengalaman Mengajar', 'surat_keterangan_pengalaman_mengajar', 'Surat keterangan pengalaman mengajar untuk keperluan sertifikasi'],
            ['SKBT', 'Surat Permohonan SKBT', 'surat_permohonan_skbt', 'Surat permohonan Surat Keterangan Bebas Temuan'],
            ['PD', 'Surat Pernyataan Disiplin', 'surat_pernyataan_disiplin_new', 'Surat pernyataan tidak pernah dijatuhi hukuman disiplin'],
            ['PTB', 'Surat Pernyataan Tugas Belajar', 'surat_pernyataan_tugas_belajar', 'Surat pernyataan tidak sedang menjalankan tugas belajar'],
            ['PP', 'Surat Pernyataan Pidana', 'surat_pernyataan_pidana', 'Surat pernyataan tidak pernah dipidana'],
            ['SPL', 'Surat Persetujuan Pelepasan', 'surat_persetujuan_pelepasan', 'Surat persetujuan pelepasan pegawai untuk mutasi'],
            ['SPN', 'Surat Persetujuan Penerimaan', 'surat_persetujuan_penerimaan', 'Surat persetujuan penerimaan pegawai untuk mutasi'],
            ['SPTJM', 'Surat Pernyataan Tanggung Jawab Mutlak', 'surat_sptjm', 'Surat pernyataan tanggung jawab mutlak untuk mutasi'],
            ['ANJAB', 'Surat Keterangan Analisis Jabatan dan Analisis Beban Kerja', 'surat_keterangan_anjab_abk', 'Surat keterangan ANJAB ABK untuk PNS']
        ];
        
        $sql = "INSERT IGNORE INTO jenis_surat (kode, nama, template_file, deskripsi) VALUES (?, ?, ?, ?)";
        foreach ($jenisSurat as $data) {
            $this->db->query($sql, $data);
        }
        echo "✓ Data jenis surat berhasil diinsert\n";
        
        // Insert kantor default
        $kantorSql = "
            INSERT IGNORE INTO kantor (nama, kabupaten_kota, alamat, telepon, fax, email) 
            VALUES ('Kantor Kementerian Agama', 'KABUPATEN LOMBOK BARAT', 'Jl. Raya Gerung No. 1', 'Telp. (0370) 681234', 'Fax. (0370) 681235', 'kankemenag@kemenag.go.id')
        ";
        $this->db->query($kantorSql);
        echo "✓ Data kantor default berhasil diinsert\n";
    }
    
    /**
     * Create indexes for performance
     */
    private function createIndexes()
    {
        $indexes = [
            "CREATE INDEX IF NOT EXISTS idx_pegawai_nip ON pegawai(nip)",
            "CREATE INDEX IF NOT EXISTS idx_pegawai_nama ON pegawai(nama)",
            "CREATE INDEX IF NOT EXISTS idx_surat_nomor ON surat(nomor_surat)",
            "CREATE INDEX IF NOT EXISTS idx_surat_pegawai ON surat(pegawai_nip)",
            "CREATE INDEX IF NOT EXISTS idx_surat_tanggal ON surat(tanggal_surat)",
            "CREATE INDEX IF NOT EXISTS idx_surat_jenis ON surat(jenis_surat_id)",
            "CREATE INDEX IF NOT EXISTS idx_log_surat ON log_aktivitas(surat_id)",
            "CREATE INDEX IF NOT EXISTS idx_log_tanggal ON log_aktivitas(created_at)"
        ];
        
        foreach ($indexes as $sql) {
            $this->db->query($sql);
        }
        echo "✓ Index berhasil dibuat\n";
    }
}

// Run migration if called directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    require_once dirname(__DIR__, 2) . '/core/autoload.php';
    
    // Load environment variables
    Env::load();
    
    $migration = new CreateInitialTables();
    
    echo "Menjalankan migration: Create Initial Tables\n";
    echo "==========================================\n";
    
    try {
        $migration->up();
        echo "\n✅ Migration berhasil dijalankan!\n";
    } catch (Exception $e) {
        echo "\n❌ Migration gagal: " . $e->getMessage() . "\n";
    }
}