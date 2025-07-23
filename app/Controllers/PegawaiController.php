<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Pegawai;
use App\Models\Surat;

/**
 * Pegawai Controller
 * Menangani pengelolaan data pegawai
 */
class PegawaiController extends BaseController
{
    /**
     * Halaman list pegawai
     */
    public function index()
    {
        log_debug('Masuk ke PegawaiController::index');
        $data = [
            'title' => 'Data Pegawai - ASN Mutasi',
            'user' => $_SESSION['user_name'] ?? 'User',
            'role' => $_SESSION['user_role'] ?? 'daerah'
        ];
        
        $result = $this->view('pegawai.index', $data ?? []);
        log_debug('Selesai render PegawaiController::index', ['data' => $data ?? []]);
        return $result;
    }
    
    /**
     * API endpoint untuk datatable
     */
    public function datatable()
    {
        // Set JSON content type
        header('Content-Type: application/json');
        
        try {
            // Get DataTable parameters
            $draw = (int)($_GET['draw'] ?? 1);
            $start = (int)($_GET['start'] ?? 0);
            $length = (int)($_GET['length'] ?? 10);
            $searchValue = $_GET['search']['value'] ?? '';
            
            // Get order parameters
            $orderColumn = (int)($_GET['order'][0]['column'] ?? 0);
            $orderDir = $_GET['order'][0]['dir'] ?? 'asc';
            
            // Column mapping
            $columns = ['nip', 'nama', 'jabatan', 'unit_kerja'];
            $orderBy = $columns[$orderColumn] ?? 'nama';
            
            $db = \Core\Database\Connection::getInstance();
            
            // Build base query
            $baseQuery = "FROM pegawai";
            $whereClause = "";
            $params = [];
            
            // Add search filter
            if (!empty($searchValue)) {
                $whereClause = " WHERE (nama LIKE ? OR nip LIKE ? OR jabatan LIKE ? OR unit_kerja LIKE ?)";
                $searchParam = '%' . $searchValue . '%';
                $params = [$searchParam, $searchParam, $searchParam, $searchParam];
            }
            
            // Get total records
            $totalRecords = $db->fetch("SELECT COUNT(*) as total " . $baseQuery)['total'];
            
            // Get filtered records count
            $filteredRecords = $db->fetch("SELECT COUNT(*) as total " . $baseQuery . $whereClause, $params)['total'];
            
            // Get data with pagination
            $sql = "SELECT nip, nama, jabatan, unit_kerja, golongan " . $baseQuery . $whereClause . 
                   " ORDER BY " . $orderBy . " " . $orderDir . 
                   " LIMIT " . $length . " OFFSET " . $start;
            
            $pegawaiList = $db->fetchAll($sql, $params);
            
            // Format data for DataTable
            $data = [];
            foreach ($pegawaiList as $pegawai) {
                $data[] = [
                    'nip' => $pegawai['nip'],
                    'nama' => $pegawai['nama'],
                    'jabatan' => $pegawai['jabatan'] ?? '-',
                    'unit_kerja' => $pegawai['unit_kerja'] ?? '-',
                    'golongan' => $pegawai['golongan'] ?? '-',
                    'aksi' => $this->generateActionButtons($pegawai['nip'])
                ];
            }
            
            return $this->json([
                'draw' => $draw,
                'recordsTotal' => (int)$totalRecords,
                'recordsFiltered' => (int)$filteredRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate action buttons for each row
     */
    private function generateActionButtons($nip)
    {
        return '<div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Aksi
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="showDocumentCount(\'' . $nip . '\')">
                            <i class="fas fa-file-alt"></i> Lihat Dokumen
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="viewPegawai(\'' . $nip . '\')">
                            <i class="fas fa-eye"></i> Detail
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/surat/create?pegawai_nip=' . $nip . '">
                            <i class="fas fa-plus"></i> Buat Surat
                        </a></li>
                    </ul>
                </div>';
    }
    
    /**
     * API endpoint untuk mendapatkan jumlah dokumen per pegawai
     */
    public function getDocumentCount($nip)
    {
        header('Content-Type: application/json');
        
        try {
            $db = \Core\Database\Connection::getInstance();
            
            // Get pegawai data
            $pegawai = $db->fetch("SELECT * FROM pegawai WHERE nip = ?", [$nip]);
            
            if (!$pegawai) {
                return $this->json([
                    'success' => false,
                    'message' => 'Pegawai tidak ditemukan'
                ], 404);
            }
            
            // Get document count by type
            $sql = "SELECT 
                        js.nama as jenis_surat,
                        COUNT(*) as jumlah
                    FROM surat s
                    LEFT JOIN jenis_surat js ON s.jenis_surat_id = js.id
                    WHERE s.pegawai_nip = ?
                    GROUP BY s.jenis_surat_id, js.nama
                    ORDER BY jumlah DESC";
            
            $documentsByType = $db->fetchAll($sql, [$nip]);
            
            // Get total documents
            $totalDocuments = $db->fetch(
                "SELECT COUNT(*) as total FROM surat WHERE pegawai_nip = ?", 
                [$nip]
            )['total'];
            
            // Get recent documents
            $recentDocuments = $db->fetchAll(
                "SELECT s.*, js.nama as jenis_nama 
                 FROM surat s 
                 LEFT JOIN jenis_surat js ON s.jenis_surat_id = js.id 
                 WHERE s.pegawai_nip = ? 
                 ORDER BY s.created_at DESC 
                 LIMIT 5",
                [$nip]
            );
            
            return $this->json([
                'success' => true,
                'data' => [
                    'pegawai' => $pegawai,
                    'total_documents' => (int)$totalDocuments,
                    'documents_by_type' => $documentsByType,
                    'recent_documents' => $recentDocuments
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API endpoint untuk mendapatkan detail pegawai
     */
    public function getDetail($nip)
    {
        header('Content-Type: application/json');
        
        try {
            $pegawai = Pegawai::getByNip($nip);
            
            if (!$pegawai) {
                return $this->json([
                    'success' => false,
                    'message' => 'Pegawai tidak ditemukan'
                ], 404);
            }
            
            return $this->json([
                'success' => true,
                'data' => $pegawai
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        header('Content-Type: application/json');
        
        try {
            $db = \Core\Database\Connection::getInstance();
            
            // Get total employees
            $totalPegawai = $db->fetch("SELECT COUNT(*) as total FROM pegawai")['total'];
            
            // Get total documents
            $totalDokumen = $db->fetch("SELECT COUNT(*) as total FROM surat")['total'];
            
            // Get active employees (assuming all are active for now)
            $pegawaiAktif = $totalPegawai;
            
            // Get documents this month
            $currentMonth = date('Y-m');
            $dokumenBulanIni = $db->fetch(
                "SELECT COUNT(*) as total FROM surat WHERE created_at LIKE ?", 
                [$currentMonth . '%']
            )['total'];
            
            return $this->json([
                'success' => true,
                'total_pegawai' => (int)$totalPegawai,
                'total_dokumen' => (int)$totalDokumen,
                'pegawai_aktif' => (int)$pegawaiAktif,
                'dokumen_bulan_ini' => (int)$dokumenBulanIni
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}