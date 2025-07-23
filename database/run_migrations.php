<?php
/**
 * Migrations Runner
 * Date: 2025-01-22
 * Description: Menjalankan migration terpisah sesuai struktur yang diinginkan
 * Urutan: Initial Tables -> Users -> Kantor
 */

require_once dirname(__DIR__) . '/core/autoload.php';

echo "🚀 Menjalankan Migrations untuk ASN Mutasi\n";
echo "==========================================\n";
echo "📅 Tanggal: " . date('Y-m-d H:i:s') . "\n\n";

try {
    // 1. Run Initial Tables Migration
    echo "1️⃣ Menjalankan Initial Tables Migration...\n";
    echo "------------------------------------------\n";
    require_once __DIR__ . '/migrations/001_create_initial_tables.php';
    echo "\n";
    
    // 2. Run Users Migration
    echo "2️⃣ Menjalankan Users Migration...\n";
    echo "----------------------------------\n";
    require_once __DIR__ . '/migrations/002_create_users_and_update_kantor.php';
    echo "\n";
    
    // 3. Run Kantor Migrations (ibukota dan kabkolower)
    echo "3️⃣ Menjalankan Kantor Migrations...\n";
    echo "-----------------------------------\n";
    require_once __DIR__ . '/migrations/003_add_ibukota_to_kantor.php';
    echo "\n";
    require_once __DIR__ . '/migrations/004_add_kabkolower_to_kantor.php';
    echo "\n";
    
    echo "🎉 SEMUA MIGRATION BERHASIL DIJALANKAN!\n";
    echo "=======================================\n\n";
    
    echo "📊 STRUKTUR DATABASE YANG DIBUAT:\n";
    echo "=================================\n";
    echo "📋 jenis_surat - Jenis-jenis surat yang tersedia\n";
    echo "📄 surat - Data surat yang dibuat\n";
    echo "👥 users - Data pengguna sistem\n";
    echo "🏢 kantor - Data kantor/instansi (dengan ibukota dan kabkolower)\n";
    echo "📝 log_aktivitas - Log aktivitas sistem\n\n";
    
    echo "🚀 LANGKAH SELANJUTNYA:\n";
    echo "======================\n";
    echo "1. Jalankan seeder: php database/run_seeders.php\n";
    echo "2. Sistem siap untuk deployment\n\n";
    
    echo "✅ Database schema siap digunakan!\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: Migration gagal dijalankan!\n";
    echo "Pesan error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>