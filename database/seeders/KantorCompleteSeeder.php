<?php
/**
 * Seeder: Complete Kantor Data
 * Date: 2025-01-22
 * Description: Seeder lengkap untuk data kantor dengan 10 kabupaten/kota
 * Menggabungkan semua logika kantor dalam satu file untuk deployment yang rapi
 */

require_once dirname(__DIR__, 2) . '/core/autoload.php';

use Core\Database\Connection;
use Core\Support\Env;

class KantorCompleteSeeder
{
    private $db;

    public function __construct()
    {
        // Load environment variables
        Env::load(dirname(__DIR__, 2) . '/.env');
        
        // Initialize database connection
        $this->db = Connection::getInstance();
    }

    public function run()
    {
        echo "Running seeder: Complete Kantor Data...\n";
        
        try {
            // Clear existing kantor data
            $this->db->query("DELETE FROM kantor");
            echo "✓ Data kantor lama berhasil dihapus\n";
            
            // Get user IDs for daerah users only (10 users)
            $users = $this->db->query("
                SELECT id, username 
                FROM users 
                WHERE role = 'daerah' 
                ORDER BY username
            ")->fetchAll();
            
            if (count($users) < 10) {
                throw new Exception("Tidak cukup user dengan role 'daerah'. Diperlukan minimal 10 user.");
            }
            
            // Data kantor Kementerian Agama untuk 10 kabupaten/kota
            $kantorData = [
                [
                    'username' => 'adminmataram',
                    'nama' => 'Kantor Kementerian Agama Kota Mataram',
                    'kabupaten_kota' => 'MATARAM',
                    'ibukota' => 'Mataram',
                    'kabkolower' => 'Kota Mataram',
                    'alamat' => 'Jl. Udayana No. 1, Mataram',
                    'telepon' => '(0370) 621234',
                    'email' => 'kemenag@mataram.go.id'
                ],
                [
                    'username' => 'adminlombokbarat',
                    'nama' => 'Kantor Kementerian Agama Kabupaten Lombok Barat',
                    'kabupaten_kota' => 'LOMBOK BARAT',
                    'ibukota' => 'Gerung',
                    'kabkolower' => 'Kabupaten Lombok Barat',
                    'alamat' => 'Jl. Raya Gerung No. 15, Gerung',
                    'telepon' => '(0370) 681234',
                    'email' => 'kemenag@lombokbarat.go.id'
                ],
                [
                    'username' => 'adminlomboktimur',
                    'nama' => 'Kantor Kementerian Agama Kabupaten Lombok Timur',
                    'kabupaten_kota' => 'LOMBOK TIMUR',
                    'ibukota' => 'Selong',
                    'kabkolower' => 'Kabupaten Lombok Timur',
                    'alamat' => 'Jl. TGH. Lopan No. 10, Selong',
                    'telepon' => '(0376) 21234',
                    'email' => 'kemenag@lomboktimur.go.id'
                ],
                [
                    'username' => 'adminlombokutara',
                    'nama' => 'Kantor Kementerian Agama Kabupaten Lombok Utara',
                    'kabupaten_kota' => 'LOMBOK UTARA',
                    'ibukota' => 'Tanjung',
                    'kabkolower' => 'Kabupaten Lombok Utara',
                    'alamat' => 'Jl. Raya Tanjung No. 25, Tanjung',
                    'telepon' => '(0370) 641234',
                    'email' => 'kemenag@lombokutara.go.id'
                ],
                [
                    'username' => 'adminlomboktengah',
                    'nama' => 'Kantor Kementerian Agama Kabupaten Lombok Tengah',
                    'kabupaten_kota' => 'LOMBOK TENGAH',
                    'ibukota' => 'Praya',
                    'kabkolower' => 'Kabupaten Lombok Tengah',
                    'alamat' => 'Jl. Selaparang No. 20, Praya',
                    'telepon' => '(0370) 654321',
                    'email' => 'kemenag@lomboktengah.go.id'
                ],
                [
                    'username' => 'adminsumbawa',
                    'nama' => 'Kantor Kementerian Agama Kabupaten Sumbawa',
                    'kabupaten_kota' => 'SUMBAWA',
                    'ibukota' => 'Sumbawa Besar',
                    'kabkolower' => 'Kabupaten Sumbawa',
                    'alamat' => 'Jl. Garuda No. 1, Sumbawa Besar',
                    'telepon' => '(0371) 21234',
                    'email' => 'kemenag@sumbawa.go.id'
                ],
                [
                    'username' => 'adminsumbawabarat',
                    'nama' => 'Kantor Kementerian Agama Kabupaten Sumbawa Barat',
                    'kabupaten_kota' => 'SUMBAWA BARAT',
                    'ibukota' => 'Taliwang',
                    'kabkolower' => 'Kabupaten Sumbawa Barat',
                    'alamat' => 'Jl. Lintas Sumbawa No. 5, Taliwang',
                    'telepon' => '(0372) 21234',
                    'email' => 'kemenag@sumbawabarat.go.id'
                ],
                [
                    'username' => 'admindompu',
                    'nama' => 'Kantor Kementerian Agama Kabupaten Dompu',
                    'kabupaten_kota' => 'DOMPU',
                    'ibukota' => 'Dompu',
                    'kabkolower' => 'Kabupaten Dompu',
                    'alamat' => 'Jl. Lintas Dompu No. 12, Dompu',
                    'telepon' => '(0373) 21234',
                    'email' => 'kemenag@dompu.go.id'
                ],
                [
                    'username' => 'adminbima',
                    'nama' => 'Kantor Kementerian Agama Kabupaten Bima',
                    'kabupaten_kota' => 'BIMA',
                    'ibukota' => 'Woha',
                    'kabkolower' => 'Kabupaten Bima',
                    'alamat' => 'Jl. Sultan Hasanuddin No. 8, Woha',
                    'telepon' => '(0374) 21234',
                    'email' => 'kemenag@bima.go.id'
                ],
                [
                    'username' => 'adminkotabima',
                    'nama' => 'Kantor Kementerian Agama Kota Bima',
                    'kabupaten_kota' => 'KOTA BIMA',
                    'ibukota' => 'Bima',
                    'kabkolower' => 'Kota Bima',
                    'alamat' => 'Jl. Sultan Ibrahim No. 1, Bima',
                    'telepon' => '(0374) 41234',
                    'email' => 'kemenag@kotabima.go.id'
                ]
            ];
            
            // Create mapping of username to user_id
            $userMap = [];
            foreach ($users as $user) {
                $userMap[$user['username']] = $user['id'];
            }
            
            // Insert kantor data
            $insertedCount = 0;
            foreach ($kantorData as $kantor) {
                if (!isset($userMap[$kantor['username']])) {
                    echo "⚠ Warning: User '{$kantor['username']}' tidak ditemukan, melewati data kantor\n";
                    continue;
                }
                
                $userId = $userMap[$kantor['username']];
                
                $this->db->query(
                    "INSERT INTO kantor (
                        user_id, nama, kabupaten_kota, ibukota, kabkolower, 
                        alamat, telepon, email, created_at, updated_at
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
                    )",
                    [
                        $userId,
                        $kantor['nama'],
                        $kantor['kabupaten_kota'],
                        $kantor['ibukota'],
                        $kantor['kabkolower'],
                        $kantor['alamat'],
                        $kantor['telepon'],
                        $kantor['email']
                    ]
                );
                
                $insertedCount++;
                echo "✓ Kantor {$kantor['nama']} berhasil ditambahkan\n";
            }
            
            echo "\n✅ Seeder kantor berhasil dijalankan!\n";
            echo "📊 Total kantor yang ditambahkan: {$insertedCount}\n";
            echo "\n📋 Ringkasan Data Kantor:\n";
            echo "=========================\n";
            
            foreach ($kantorData as $index => $kantor) {
                if (isset($userMap[$kantor['username']])) {
                    echo ($index + 1) . ". {$kantor['kabkolower']} - {$kantor['ibukota']}\n";
                }
            }
            
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}

// Run seeder if called directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    echo "Menjalankan seeder: Complete Kantor Data\n";
    echo "========================================\n";
    
    try {
        $seeder = new KantorCompleteSeeder();
        $seeder->run();
    } catch (Exception $e) {
        echo "\n❌ Seeder gagal: " . $e->getMessage() . "\n";
        exit(1);
    }
}

?>