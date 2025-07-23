<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Surat;
use Core\Http\Response;
use Core\Database\Connection;

/**
 * Surat Controller
 * Controller utama untuk mengelola semua jenis surat
 */
class SuratController extends BaseController
{
    /**
     * Daftar semua jenis surat
     */
    public function index()
    {
        $data = [
            'title' => 'Daftar Jenis Surat - ASN Mutasi',
            'surat_types' => $this->getSuratTypes()
        ];
        
        return $this->view('surat.index', $data);
    }
    
    /**
     * Download generated surat
     */
    public function download($id)
    {
        $filename = base64_decode($id);
        $filepath = STORAGE_PATH . '/generated/' . $filename;
        
        if (!file_exists($filepath)) {
            return $this->abort(404, 'File tidak ditemukan');
        }
        
        return Response::download($filepath, $filename);
    }
    
    /**
     * Preview generated surat
     */
    public function preview($param1 = null, $param2 = null)
    {
        // Logging tipe dan isi parameter
        log_debug('PREVIEW PARAM', [
            'param1_type' => is_object($param1) ? get_class($param1) : gettype($param1),
            'param1' => $param1,
            'param2' => $param2
        ]);
        // Jika param1 adalah Request, ambil id dari param2
        if (is_object($param1) && $param2 !== null) {
            $id = $param2;
        } elseif (!is_object($param1)) {
            $id = $param1;
        } else {
            // Coba ambil dari params request jika ada
            $id = method_exists($param1, 'param') ? $param1->param('id') : null;
        }
        if (!is_string($id) || empty($id)) {
            throw new \Exception('ID surat tidak ditemukan untuk preview');
        }
        $filename = base64_decode($id);
        $filepath = STORAGE_PATH . '/generated/' . $filename;
        
        if (!file_exists($filepath)) {
            return $this->abort(404, 'File tidak ditemukan');
        }
        
        $content = file_get_contents($filepath);
        
        // Add print functionality to the content
        $printableContent = $this->addPrintFunctionality($content, $id);
        
        return Response::html($printableContent);
    }
    
    /**
     * Add print functionality to surat content
     */
    private function addPrintFunctionality($content, $id)
    {
        // Add print styles and button
        $printStyles = '
<style>
@media print {
    .no-print {
        display: none !important;
    }
    body {
        margin: 0;
        padding: 20px;
    }
}

.print-controls {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: 1px solid #ddd;
}

.print-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    margin-right: 10px;
    text-decoration: none;
    display: inline-block;
}

.print-btn:hover {
    background: #218838;
    color: white;
    text-decoration: none;
}

.back-btn {
    background: #6c757d;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
}

.back-btn:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
}
</style>';
        
        $printControls = '
<div class="print-controls no-print">
    <button onclick="window.print()" class="print-btn">
        <i class="fas fa-print"></i> Cetak
    </button>
    <a href="/surat/download/' . $id . '" class="print-btn" style="background: #007bff;">
        <i class="fas fa-download"></i> Download
    </a>
    <a href="/surat" class="back-btn">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>';
        
        // Insert styles in head if exists, otherwise add at the beginning
        if (strpos($content, '</head>') !== false) {
            $content = str_replace('</head>', $printStyles . '\n</head>', $content);
        } else {
            $content = $printStyles . '\n' . $content;
        }
        
        // Insert controls after body tag if exists, otherwise add at the beginning
        if (strpos($content, '<body') !== false) {
            $content = preg_replace('/(<body[^>]*>)/', '$1' . $printControls, $content);
        } else {
            $content = $printControls . '\n' . $content;
        }
        
        return $content;
    }
    
    /**
     * Get surat types for API
     */
    public function getSuratTypes()
    {
        return $this->success([
            [
                'id' => 'pengalaman-mengajar',
                'title' => 'Surat Keterangan Pengalaman Mengajar',
                'description' => 'Surat keterangan untuk pengalaman mengajar',
                'template' => 'surat_keterangan_pengalaman_mengajar',
                'controller' => 'SuratKeteranganPengalamanMengajarController',
                'icon' => 'fas fa-chalkboard-teacher',
                'url' => '/surat/pengalaman-mengajar'
            ],
            [
                'id' => 'permohonan-skbt',
                'title' => 'Surat Permohonan SKBT',
                'description' => 'Surat permohonan Surat Keterangan Bebas Tugas',
                'template' => 'surat_permohonan_skbt',
                'controller' => 'SuratPermohonanSkbtController',
                'icon' => 'fas fa-file-signature',
                'url' => '/surat/permohonan-skbt'
            ],
            [
                'id' => 'pernyataan-disiplin',
                'title' => 'Surat Pernyataan Tidak Sedang Dalam Proses Hukuman Disiplin',
                'description' => 'Surat pernyataan tidak pernah dijatuhi hukuman disiplin',
                'template' => 'surat_pernyataan_disiplin_new',
                'controller' => 'SuratPernyataanDisiplinController',
                'icon' => 'fas fa-shield-alt',
                'url' => '/surat/pernyataan-disiplin'
            ],
            [
                'id' => 'pernyataan-pidana',
                'title' => 'Surat Pernyataan Tidak Pernah Dipidana',
                'description' => 'Surat pernyataan tidak pernah dipidana',
                'template' => 'surat_pernyataan_pidana',
                'controller' => 'SuratPernyataanPidanaController',
                'icon' => 'fas fa-balance-scale',
                'url' => '/surat/pernyataan-pidana'
            ],
            [
                'id' => 'pernyataan-tugas-belajar',
                'title' => 'Surat Pernyataan Tidak Sedang Menjalankan Tugas Belajar',
                'description' => 'Surat pernyataan tidak sedang menjalankan tugas belajar',
                'template' => 'surat_pernyataan_tugas_belajar',
                'controller' => 'SuratPernyataanTugasBelajarController',
                'icon' => 'fas fa-graduation-cap',
                'url' => '/surat/pernyataan-tugas-belajar'
            ],
            [
                'id' => 'persetujuan-pelepasan',
                'title' => 'Surat Persetujuan Pelepasan Pegawai',
                'description' => 'Surat persetujuan pelepasan pegawai',
                'template' => 'surat_persetujuan_pelepasan',
                'controller' => 'SuratPersetujuanPelepasanController',
                'icon' => 'fas fa-sign-out-alt',
                'url' => '/surat/persetujuan-pelepasan'
            ],
            [
                'id' => 'persetujuan-penerimaan',
                'title' => 'Surat Persetujuan Penerimaan Pegawai',
                'description' => 'Surat persetujuan penerimaan pegawai',
                'template' => 'surat_persetujuan_penerimaan',
                'controller' => 'SuratPersetujuanPenerimaanController',
                'icon' => 'fas fa-user-plus',
                'url' => '/surat/persetujuan-penerimaan'
            ],
            [
                'id' => 'sptjm',
                'title' => 'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)',
                'description' => 'Surat Pernyataan Tanggung Jawab Mutlak',
                'template' => 'surat_sptjm',
                'controller' => 'SuratSptjmController',
                'icon' => 'fas fa-handshake',
                'url' => '/surat/sptjm'
            ],
            [
                'id' => 'anjab-abk',
                'title' => 'Surat Keterangan Analisis Jabatan dan Analisis Beban Kerja',
                'description' => 'Surat keterangan ANJAB ABK untuk PNS',
                'template' => 'surat_keterangan_anjab_abk',
                'controller' => 'SuratAnjabAbkController',
                'icon' => 'fas fa-chart-bar',
                'url' => '/surat/anjab-abk'
            ]
        ]);
    }
    
    /**
     * Validate form for AJAX
     */
    public function validateForm()
    {
        $template = $this->input('template');
        $data = $this->input('data', []);
        
        // Define validation rules based on template
        $rules = $this->getValidationRules($template);
        
        try {
            $validated = $this->validate($rules);
            return $this->success($validated, 'Validasi berhasil');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
    
    /**
     * Get validation rules for template
     */
    private function getValidationRules($template)
    {
        $commonRules = [
            'namapegawai' => 'required|string',
            'nippegawai' => 'required|string'
        ];
        
        $officialRules = [
            'nosrt' => 'required|string|max:100'
        ];
        
        $signatoryRules = [
            'namapejabat' => 'required|string',
            'nippejabat' => 'required|string',
            'pangkatgolpejabat' => 'required|string',
            'jabatanpejabat' => 'required|string'
        ];
        
        switch ($template) {
            case 'surat_keterangan_pengalaman_mengajar':
                return array_merge($commonRules, $officialRules, $signatoryRules, [
                    'blnno' => 'required|string',
                    'thnno' => 'required|string|max:4',
                    'sekolah' => 'required|string',
                    'tglmulai' => 'required|date',
                    'tempattugas' => 'required|string'
                ]);
                
            case 'surat_permohonan_skbt':
                return array_merge($commonRules, $officialRules, [
                    'blnsrt' => 'required|string',
                    'thnskrg' => 'required|string|max:4',
                    'tgl' => 'required|string|max:2',
                    'bln' => 'required|string',
                    'thn' => 'required|string|max:4',
                    'pangkatgolpegawai' => 'required|string',
                    'jabatanpegawai' => 'required|string',
                    'unitkerja' => 'required|string',
                    'namapejabat' => 'required|string',
                    'nippejabat' => 'required|string'
                ]);
                
            case 'surat_pernyataan_disiplin_new':
                return array_merge($commonRules, $officialRules, $signatoryRules, [
                    'blnno' => 'required|string',
                    'thnno' => 'required|string|max:4',
                    'tanggalsurat' => 'required|date',
                    'pangkatgolpegawai' => 'required|string',
                    'jabatanpegawai' => 'required|string',
                    'tempattugas' => 'required|string',
                    'ukerpejabat' => 'required|string'
                ]);
                
            case 'surat_sptjm':
                return array_merge($commonRules, $officialRules, $signatoryRules, [
                    'blnno' => 'required|string',
                    'thnno' => 'required|string|max:4',
                    'tanggalsurat' => 'required|date',
                    'ukerpejabat' => 'required|string'
                ]);
                

                
            case 'surat_pernyataan_pidana':
                return array_merge($commonRules, $officialRules, $signatoryRules, [
                    'blnno' => 'required|string',
                    'thnno' => 'required|string|max:4',
                    'tanggalsurat' => 'required|date',
                    'pangkatgolpegawai' => 'required|string',
                    'jabatanpegawai' => 'required|string',
                    'tempattugas' => 'required|string',
                    'ukerpejabat' => 'required|string'
                ]);
                
            case 'surat_pernyataan_tugas_belajar':
                return array_merge($commonRules, $officialRules, $signatoryRules, [
                    'blnno' => 'required|string',
                    'thnno' => 'required|string|max:4',
                    'tanggalsurat' => 'required|date',
                    'pangkatgolpegawai' => 'required|string',
                    'jabatanpegawai' => 'required|string',
                    'tempattugas' => 'required|string',
                    'ukerpejabat' => 'required|string'
                ]);
                
            case 'surat_persetujuan_pelepasan':
            case 'surat_persetujuan_penerimaan':
                return array_merge($commonRules, $officialRules, [
                    'blnno' => 'required|string',
                    'thnno' => 'required|string|max:4',
                    'tanggalsurat' => 'required|date',
                    'pangkatgolpegawai' => 'required|string',
                    'jabatanpegawai' => 'required|string',
                    'unitkerja' => 'required|string',
                    'namapejabat' => 'required|string',
                    'nipejabat' => 'required|string',
                    'pangkatgolpejabat' => 'required|string',
                    'jabatanpejabat' => 'required|string',
                    'ukerpejabat' => 'required|string'
                ]);
                
            default:
                return $commonRules;
        }
    }
    
    // ===== SHOW METHODS =====
    
    /**
     * Show Surat Keterangan Pengalaman Mengajar form
     */
    public function showPengalamanMengajar()
    {
        $data = [
            'title' => 'Surat Keterangan Pengalaman Mengajar - ASN Mutasi',
            'template' => 'surat_keterangan_pengalaman_mengajar'
        ];
        
        return $this->view('surat.pengalaman-mengajar', $data);
    }
    
    /**
     * Show Surat Permohonan SKBT form
     */
    public function showPermohonanSkbt()
    {
        $data = [
            'title' => 'Surat Permohonan SKBT - ASN Mutasi',
            'template' => 'surat_permohonan_skbt'
        ];
        
        return $this->view('surat.permohonan-skbt', $data);
    }
    
    /**
     * Show Surat Pernyataan Disiplin form
     */
    public function showPernyataanDisiplin()
    {
        $data = [
            'title' => 'Surat Pernyataan Tidak Sedang Dalam Proses Hukuman Disiplin - ASN Mutasi',
            'template' => 'surat_pernyataan_disiplin_new'
        ];
        
        return $this->view('surat.pernyataan-disiplin', $data);
    }
    
    /**
     * Show Surat Pernyataan Pidana form
     */
    public function showPernyataanPidana()
    {
        $data = [
            'title' => 'Surat Pernyataan Tidak Pernah Dipidana - ASN Mutasi',
            'template' => 'surat_pernyataan_pidana'
        ];
        
        return $this->view('surat.pernyataan-pidana', $data);
    }
    
    /**
     * Show Surat Pernyataan Tugas Belajar form
     */
    public function showPernyataanTugasBelajar()
    {
        $data = [
            'title' => 'Surat Pernyataan Tidak Sedang Menjalankan Tugas Belajar - ASN Mutasi',
            'template' => 'surat_pernyataan_tugas_belajar'
        ];
        
        return $this->view('surat.pernyataan-tugas-belajar', $data);
    }
    
    /**
     * Show Surat Persetujuan Pelepasan form
     */
    public function showPersetujuanPelepasan()
    {
        $data = [
            'title' => 'Surat Persetujuan Pelepasan Pegawai - ASN Mutasi',
            'template' => 'surat_persetujuan_pelepasan'
        ];
        
        return $this->view('surat.persetujuan-pelepasan', $data);
    }
    
    /**
     * Show Surat Persetujuan Penerimaan form
     */
    public function showPersetujuanPenerimaan()
    {
        $data = [
            'title' => 'Surat Persetujuan Penerimaan Pegawai - ASN Mutasi',
            'template' => 'surat_persetujuan_penerimaan'
        ];
        
        return $this->view('surat.persetujuan-penerimaan', $data);
    }
    
    /**
     * Show Surat SPTJM form
     */
    public function showSptjm()
    {
        $data = [
            'title' => 'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM) - ASN Mutasi',
            'template' => 'surat_sptjm'
        ];
        
        return $this->view('surat.sptjm', $data);
    }
    
    // ===== PREVIEW METHODS =====
    // Note: Preview methods have been moved to individual surat controllers
    // for better organization and maintainability
    
    // ===== GENERATE METHODS =====
    
    /**
     * Generate Surat Keterangan Pengalaman Mengajar
     */
    public function generatePengalamanMengajar()
    {
        return $this->generateSurat('surat_keterangan_pengalaman_mengajar');
    }
    
    /**
     * Generate Surat Permohonan SKBT
     */
    public function generatePermohonanSkbt()
    {
        return $this->generateSurat('surat_permohonan_skbt');
    }
    
    /**
     * Generate Surat Pernyataan Disiplin
     */
    public function generatePernyataanDisiplin()
    {
        return $this->generateSurat('surat_pernyataan_disiplin_new');
    }
    
    /**
     * Generate Surat Pernyataan Pidana
     */
    public function generatePernyataanPidana()
    {
        return $this->generateSurat('surat_pernyataan_pidana');
    }
    
    /**
     * Generate Surat Pernyataan Tugas Belajar
     */
    public function generatePernyataanTugasBelajar()
    {
        return $this->generateSurat('surat_pernyataan_tugas_belajar');
    }
    
    /**
     * Generate Surat Persetujuan Pelepasan
     */
    public function generatePersetujuanPelepasan()
    {
        return $this->generateSurat('surat_persetujuan_pelepasan');
    }
    
    /**
     * Generate Surat Persetujuan Penerimaan
     */
    public function generatePersetujuanPenerimaan()
    {
        return $this->generateSurat('surat_persetujuan_penerimaan');
    }
    
    /**
     * Generate Surat SPTJM
     */
    public function generateSptjm()
    {
        return $this->generateSurat('surat_sptjm');
    }
    

    
    /**
     * Format date from dd-mm-yyyy to Indonesian format (dd Month yyyy)
     */
    private function formatDateToIndonesian($dateString)
    {
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        // Parse date in dd-mm-yyyy format
        $parts = explode('-', $dateString);
        if (count($parts) === 3) {
            $day = $parts[0];
            $month = $parts[1];
            $year = $parts[2];
            
            if (isset($months[$month])) {
                return $day . ' ' . $months[$month] . ' ' . $year;
            }
        }
        
        // Return original if parsing fails
        return $dateString;
    }
    
    /**
     * Generic method to generate surat
     */
    private function generateSurat($template)
    {
        try {
            // Validate input
            $rules = $this->getValidationRules($template);
            $data = $this->validate($rules);
            
            // Get office data from settings for daerah role
            $userId = $_SESSION['user_id'] ?? null;
            if ($userId) {
                $db = Connection::getInstance();
                $kantorData = $db->fetch("SELECT * FROM kantor WHERE user_id = ?", [$userId]);
                
                if ($kantorData) {
                    $data['kabkota'] = $kantorData['kabupaten_kota'];
                    $data['ibukota'] = $kantorData['ibukota'] ?? 'SELONG';
                    $data['jln'] = $kantorData['alamat'];
                    $data['telfon'] = $kantorData['telepon'];
                    $data['fax'] = $kantorData['fax'];
                    $data['email'] = $kantorData['email'];
                    $data['website'] = $kantorData['website'] ?? 'www.kankemenag.lotim.go.id';
                }
            }
            
            // Load template
            $templatePath = TEMPLATE_PATH . '/' . $template . '.html';
            if (!file_exists($templatePath)) {
                throw new \Exception('Template tidak ditemukan: ' . $template);
            }
            
            $templateContent = file_get_contents($templatePath);
            
            // Replace placeholders with actual data
            foreach ($data as $key => $value) {
                $templateContent = str_replace('{{' . $key . '}}', $value, $templateContent);
            }
            
            // Handle special date placeholder {{dd-mm-yyyy}}
            if (strpos($templateContent, '{{dd-mm-yyyy}}') !== false) {
                $currentDate = date('d-m-Y');
                $formattedDate = $this->formatDateToIndonesian($currentDate);
                $templateContent = str_replace('{{dd-mm-yyyy}}', $formattedDate, $templateContent);
            }
            
            // Generate filename
            $filename = $template . '_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.html';
            
            // Ensure storage directory exists
            $storageDir = STORAGE_PATH . '/generated';
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0755, true);
            }
            
            // Save generated file
            $filepath = $storageDir . '/' . $filename;
            file_put_contents($filepath, $templateContent);
            
            // Return success response
            $downloadId = base64_encode($filename);
            
            if ($this->expectsJson()) {
                return $this->success([
                    'filename' => $filename,
                    'download_url' => '/surat/download/' . $downloadId,
                    'preview_url' => '/surat/preview/' . $downloadId
                ], 'Surat berhasil dibuat!');
            }
            
            $this->flash('success', 'Surat berhasil dibuat!');
            return $this->redirect('/surat/preview/' . $downloadId);
            
        } catch (\Exception $e) {
            if ($this->expectsJson()) {
                return $this->error($e->getMessage(), 422);
            }
            
            $this->flash('error', $e->getMessage());
            return $this->back();
        }
    }
    
    /**
     * Search pegawai for Select2 dropdown
     */
    public function searchPegawai()
    {
        try {
            $query = $this->input('q', '');
            $page = (int) $this->input('page', 1);
            $limit = 10;
            
            $results = \App\Models\Pegawai::search($query, $limit);
            $total = \App\Models\Pegawai::countSearch($query);
            
            $items = [];
            foreach ($results as $pegawai) {
                $items[] = [
                    'id' => $pegawai['nama'],
                    'text' => $pegawai['nama'] . ' (' . $pegawai['nip'] . ')',
                    'data' => $pegawai
                ];
            }
            
            return $this->success([
                'items' => $items,
                'total_count' => $total,
                'incomplete_results' => ($page * $limit) < $total
            ]);
            
        } catch (\Exception $e) {
            return $this->error('Gagal mencari data pegawai: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get pegawai by NIP
     */
    public function getPegawai($nip)
    {
        try {
            $pegawai = \App\Models\Pegawai::findByNIP($nip);
            
            if (!$pegawai) {
                return $this->error('Pegawai tidak ditemukan', 404);
            }
            
            return $this->success($pegawai);
            
        } catch (\Exception $e) {
            return $this->error('Gagal mengambil data pegawai: ' . $e->getMessage(), 500);
        }
    }
    

}