<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Surat;
use App\Models\JenisSurat;

/**
 * Home Controller
 * Menangani halaman utama aplikasi
 */
class HomeController extends BaseController
{
    /**
     * Halaman utama
     */
    public function index()
    {
        $data = [
            'title' => 'ASN Mutasi - Sistem Pengelolaan Surat',
            'description' => 'Sistem untuk mengelola dan menghasilkan berbagai jenis surat untuk keperluan mutasi ASN'
        ];
        
        return $this->view('home.index', $data);
    }
    
    /**
     * Dashboard
     */
    public function dashboard()
    {
        // Check if user is authenticated (simple session check)
        if (!isset($_SESSION['user_id'])) {
            return $this->redirect('/login');
        }
        
        $userRole = $_SESSION['user_role'] ?? 'daerah';
        
        // Get dynamic statistics
        $suratModel = new Surat();
        $jenisSuratModel = new JenisSurat();
        
        $stats = [
            'total_surat' => $suratModel->getTotalCount(),
            'surat_dibuat' => $suratModel->getGeneratedCount(),
            'total_template' => $jenisSuratModel->getTotalActiveCount(),
            'recent_letters' => $suratModel->getRecentLetters(5),
            'total_users' => $this->getTotalUsers()
        ];
        
        $data = [
            'title' => 'Dashboard - ASN Mutasi',
            'user' => $_SESSION['user_name'] ?? 'User',
            'role' => $userRole,
            'surat_types' => $this->getSuratTypesByRole($userRole),
            'stats' => $stats
        ];
        
        // Route to role-specific dashboard view
        $dashboardView = $this->getDashboardViewByRole($userRole);
        
        return $this->view($dashboardView, $data);
    }
    
    /**
     * Settings page
     */
    public function settings()
    {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            return $this->redirect('/login');
        }
        
        $kantorData = null;
        
        // Get existing office data if user is daerah
        if ($_SESSION['user_role'] === 'daerah') {
            try {
                $db = \Core\Database\Connection::getInstance();
                $kantorData = $db->fetch(
                    "SELECT * FROM kantor WHERE user_id = ?", 
                    [$_SESSION['user_id']]
                );
            } catch (\Exception $e) {
                // Handle error silently
            }
        }
        
        $data = [
            'title' => 'Settings - ASN Mutasi',
            'user' => $_SESSION['user_name'] ?? 'User',
            'role' => $_SESSION['user_role'] ?? 'daerah',
            'kantor' => $kantorData
        ];
        
        return $this->view('home.settings', $data);
    }
    
    /**
     * Save office settings
     */
    public function saveKantorSettings()
    {
        // Set JSON content type
        header('Content-Type: application/json');
        
        // Check if user is authenticated and has daerah role
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'daerah') {
            return $this->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
        
        try {
            $db = \Core\Database\Connection::getInstance();
            
            // Get form data
            $kabkota = $_POST['kabkota'] ?? '';
            $ibukota = $_POST['ibukota'] ?? '';
            $jln = $_POST['jln'] ?? '';
            $telfon = $_POST['telfon'] ?? '';
            $fax = $_POST['fax'] ?? '';
            $email = $_POST['email'] ?? '';
            $website = $_POST['website'] ?? '';
            
            // Validate required fields
            if (empty($kabkota) || empty($ibukota) || empty($jln) || empty($telfon) || empty($fax) || empty($email)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Semua field harus diisi!'
                ], 400);
            }
            
            // Handle logo upload
            $logoPath = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logoPath = $this->handleLogoUpload($_FILES['logo']);
                if (!$logoPath) {
                    return $this->json([
                        'success' => false,
                        'message' => 'Gagal mengupload logo. Pastikan file berformat JPG, PNG, atau GIF dan ukuran maksimal 2MB.'
                    ], 400);
                }
            }
            
            // Check if office data exists for this user
            $existingKantor = $db->fetch(
                "SELECT id, logo_path FROM kantor WHERE user_id = ?", 
                [$_SESSION['user_id']]
            );
            
            if ($existingKantor) {
                // Update existing office data
                if ($logoPath) {
                    // Delete old logo if exists
                    if (!empty($existingKantor['logo_path']) && file_exists($existingKantor['logo_path'])) {
                        unlink($existingKantor['logo_path']);
                    }
                    $db->query(
                        "UPDATE kantor SET kabupaten_kota = ?, ibukota = ?, alamat = ?, telepon = ?, fax = ?, email = ?, website = ?, logo_path = ?, updated_at = NOW() WHERE user_id = ?",
                        [$kabkota, $ibukota, $jln, $telfon, $fax, $email, $website, $logoPath, $_SESSION['user_id']]
                    );
                } else {
                    $db->query(
                        "UPDATE kantor SET kabupaten_kota = ?, ibukota = ?, alamat = ?, telepon = ?, fax = ?, email = ?, website = ?, updated_at = NOW() WHERE user_id = ?",
                        [$kabkota, $ibukota, $jln, $telfon, $fax, $email, $website, $_SESSION['user_id']]
                    );
                }
            } else {
                // Insert new office data
                $db->query(
                    "INSERT INTO kantor (nama, kabupaten_kota, ibukota, alamat, telepon, fax, email, website, logo_path, user_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                    ['Kantor Kementerian Agama', $kabkota, $ibukota, $jln, $telfon, $fax, $email, $website, $logoPath, $_SESSION['user_id']]
                );
            }
            
            return $this->json([
                'success' => true,
                'message' => 'Data kantor berhasil disimpan!'
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle logo file upload
     */
    private function handleLogoUpload($file)
    {
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        // Create upload directory if not exists
        $uploadDir = __DIR__ . '/../../uploads/logos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Return relative path for web access
            return '/uploads/logos/' . $filename;
        }
        
        return false;
    }
    
    /**
     * Get dashboard view based on user role
     */
    private function getDashboardViewByRole($role) {
        switch ($role) {
            case 'kanwil':
                return 'home.dashboard-kanwil'; // Full access + user management
            case 'pusat':
                return 'home.dashboard-pusat'; // Read-only monitoring
            case 'daerah':
            default:
                return 'home.dashboard-daerah'; // Limited access
        }
    }
    
    /**
     * Get available letter types based on user role
     */
    private function getSuratTypesByRole($role)
    {
        $allSuratTypes = $this->getAllSuratTypes();
        
        switch ($role) {
            case 'kanwil':
                // Kanwil has full access to all letter types (superadmin)
                return $allSuratTypes;
                
            case 'pusat':
                // Pusat has read-only access to all letter types (monitoring only)
                return $allSuratTypes;
                
            case 'daerah':
            default:
                // Daerah has access to all letter types for generating letters and managing office data
                return $allSuratTypes;
        }
    }
    
    /**
     * Get all available letter types
     */
    private function getAllSuratTypes()
    {
        return [
            [
                'id' => 'pengalaman-mengajar',
                'title' => 'Surat Keterangan Pengalaman Mengajar',
                'description' => 'Surat keterangan untuk pengalaman mengajar',
                'icon' => 'fas fa-chalkboard-teacher',
                'url' => '/surat/pengalaman-mengajar'
            ],
            [
                'id' => 'permohonan-skbt',
                'title' => 'Surat Permohonan SKBT',
                'description' => 'Surat permohonan Surat Keterangan Bebas Tugas',
                'icon' => 'fas fa-file-alt',
                'url' => '/surat/permohonan-skbt'
            ],
            [
                'id' => 'pernyataan-disiplin',
                'title' => 'Surat Pernyataan Disiplin',
                'description' => 'Surat pernyataan tidak pernah dijatuhi hukuman disiplin',
                'icon' => 'fas fa-shield-alt',
                'url' => '/surat/pernyataan-disiplin'
            ],
            [
                'id' => 'pernyataan-pidana',
                'title' => 'Surat Pernyataan Pidana',
                'description' => 'Surat pernyataan tidak pernah dipidana',
                'icon' => 'fas fa-gavel',
                'url' => '/surat/pernyataan-pidana'
            ],
            [
                'id' => 'pernyataan-tugas-belajar',
                'title' => 'Surat Pernyataan Tugas Belajar',
                'description' => 'Surat pernyataan tidak sedang menjalankan tugas belajar',
                'icon' => 'fas fa-graduation-cap',
                'url' => '/surat/pernyataan-tugas-belajar'
            ],
            [
                'id' => 'persetujuan-pelepasan',
                'title' => 'Surat Persetujuan Pelepasan',
                'description' => 'Surat persetujuan pelepasan pegawai',
                'icon' => 'fas fa-sign-out-alt',
                'url' => '/surat/persetujuan-pelepasan'
            ],
            [
                'id' => 'persetujuan-penerimaan',
                'title' => 'Surat Persetujuan Penerimaan',
                'description' => 'Surat persetujuan penerimaan pegawai',
                'icon' => 'fas fa-user-plus',
                'url' => '/surat/persetujuan-penerimaan'
            ],
            [
                'id' => 'sptjm',
                'title' => 'Surat SPTJM',
                'description' => 'Surat Pernyataan Tanggung Jawab Mutlak',
                'icon' => 'fas fa-file-signature',
                'url' => '/surat/sptjm'
            ],
            [
                'id' => 'anjab-abk',
                'title' => 'Surat Keterangan Analisis Jabatan dan Analisis Beban Kerja',
                'description' => 'Surat keterangan ANJAB ABK untuk PNS',
                'icon' => 'fas fa-chart-bar',
                'url' => '/surat/anjab-abk'
            ]
        ];
    }
    
    /**
     * Get total number of users
     */
    private function getTotalUsers()
    {
        try {
            $db = \Core\Database\Connection::getInstance();
            $result = $db->fetch("SELECT COUNT(*) as total FROM users");
            return $result['total'] ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}