<?php

require_once dirname(__DIR__, 2) . '/core/autoload.php';
require_once dirname(__DIR__, 2) . '/core/Database/Connection.php';

use Core\Database\Connection;
use Core\Support\Env;

/**
 * User Seeder
 * Description: Seeder untuk membuat 12 user (1 pusat, 1 kanwil, 10 daerah) dengan password yang di-hash
 */
class UserSeeder
{
    private $db;
    
    public function __construct()
    {
        // Load environment variables
        Env::load(dirname(__DIR__, 2) . '/.env');
        
        // Initialize database connection
        $this->db = Connection::getInstance();
    }
    
    /**
     * Run the seeder
     */
    public function run()
    {
        echo "Running User Seeder...\n";
        
        // Clear existing users
        $this->db->query("DELETE FROM users");
        echo "Cleared existing users\n";
        
        // Data user yang akan diinsert (12 user: 1 pusat, 1 kanwil, 10 daerah)
        $users = [
            [
                'username' => 'adminpusat',
                'name' => 'Administrator Pusat',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'pusat'
            ],
            [
                'username' => 'adminkanwil',
                'name' => 'Administrator Kanwil NTB',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'kanwil'
            ],
            [
                'username' => 'adminmataram',
                'name' => 'Administrator Mataram',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'adminlombokbarat',
                'name' => 'Administrator Lombok Barat',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'adminlomboktengah',
                'name' => 'Administrator Lombok Tengah',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'adminlomboktimur',
                'name' => 'Administrator Lombok Timur',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'adminlombokutara',
                'name' => 'Administrator Lombok Utara',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'adminsumbawa',
                'name' => 'Administrator Sumbawa',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'adminsumbawabarat',
                'name' => 'Administrator Sumbawa Barat',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'admindompu',
                'name' => 'Administrator Dompu',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'adminbima',
                'name' => 'Administrator Bima',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ],
            [
                'username' => 'adminkotabima',
                'name' => 'Administrator Kota Bima',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'daerah'
            ]
        ];
        
        $insertedCount = 0;
         foreach ($users as $user) {
             $this->db->query(
                 "INSERT INTO users (username, password, name, role, is_active, last_login, created_at, updated_at) VALUES (?, ?, ?, ?, 1, NULL, NOW(), NOW())",
                 [$user['username'], $user['password'], $user['name'], $user['role']]
             );
             echo "Created user: {$user['username']} (role: {$user['role']})\n";
             $insertedCount++;
         }
        
        echo "\n✅ Seeder user berhasil dijalankan!\n";
        echo "📊 Total user yang ditambahkan: {$insertedCount}\n";
        echo "\n🔑 Kredensial Login (10 Kabupaten/Kota):\n";
        echo "=======================================\n";
        echo "1. Username: adminmataram | Password: password | Role: daerah\n";
        echo "2. Username: adminlombokbarat | Password: password | Role: daerah\n";
        echo "3. Username: adminlomboktimur | Password: password | Role: daerah\n";
        echo "4. Username: adminlombokutara | Password: password | Role: daerah\n";
        echo "5. Username: adminlomboktengah | Password: password | Role: daerah\n";
        echo "6. Username: adminsumbawa | Password: password | Role: daerah\n";
        echo "7. Username: adminsumbawabarat | Password: password | Role: daerah\n";
        echo "8. Username: admindompu | Password: password | Role: daerah\n";
        echo "9. Username: adminbima | Password: password | Role: daerah\n";
        echo "10. Username: adminkotabima | Password: password | Role: daerah\n";
    }
}

// Run seeder if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $seeder = new UserSeeder();
    $seeder->run();
}