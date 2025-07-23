<?php
/**
 * Run All Seeders
 * Date: 2025-01-22
 * Description: Menjalankan seeder user dan kantor secara terpisah
 * Urutan: User -> Kantor (Kementerian Agama)
 */

require_once dirname(__DIR__, 2) . '/core/autoload.php';

echo "🚀 Menjalankan Seeders untuk ASN Mutasi\n";
echo "======================================\n";
echo "📅 Tanggal: " . date('Y-m-d H:i:s') . "\n\n";

try {
    // 1. Run User Seeder
    echo "1️⃣ Menjalankan User Seeder...\n";
    echo "------------------------------\n";
    require_once __DIR__ . '/UserSeeder.php';
    $userSeeder = new UserSeeder();
    $userSeeder->run();
    echo "\n";
    
    // 2. Run Kantor Seeder
    echo "2️⃣ Menjalankan Kantor Seeder...\n";
    echo "-------------------------------\n";
    require_once __DIR__ . '/KantorCompleteSeeder.php';
    $kantorSeeder = new KantorCompleteSeeder();
    $kantorSeeder->run();
    echo "\n";
    
    echo "🎉 SEMUA SEEDER BERHASIL DIJALANKAN!\n";
    echo "====================================\n\n";
    
    echo "📊 RINGKASAN DATA YANG DIBUAT:\n";
    echo "==============================\n";
    echo "👥 Users: 10 user daerah (kabupaten/kota)\n";
    echo "🏢 Kantor: 10 kantor Kementerian Agama yang terhubung dengan user\n\n";
    
    echo "🔑 KREDENSIAL LOGIN:\n";
    echo "===================\n";
    echo "Semua user menggunakan password: 'password'\n\n";
    
    echo "📋 DAFTAR KANTOR KEMENTERIAN AGAMA:\n";
    echo "===================================\n";
    $regions = [
        'adminmataram' => 'Kota Mataram',
        'adminlombokbarat' => 'Kabupaten Lombok Barat', 
        'adminlomboktimur' => 'Kabupaten Lombok Timur',
        'adminlombokutara' => 'Kabupaten Lombok Utara',
        'adminlomboktengah' => 'Kabupaten Lombok Tengah',
        'adminsumbawa' => 'Kabupaten Sumbawa',
        'adminsumbawabarat' => 'Kabupaten Sumbawa Barat',
        'admindompu' => 'Kabupaten Dompu',
        'adminbima' => 'Kabupaten Bima',
        'adminkotabima' => 'Kota Bima'
    ];
    
    $index = 1;
    foreach ($regions as $username => $region) {
        echo "{$index}. {$username} → Kantor Kemenag {$region}\n";
        $index++;
    }
    
    echo "\n✅ Sistem siap digunakan!\n";
    echo "💡 Gunakan kredensial di atas untuk login ke sistem.\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: Seeder gagal dijalankan!\n";
    echo "Pesan error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

?>