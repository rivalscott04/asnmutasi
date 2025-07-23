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
     * Riwayat surat yang sudah digenerate
     */
    public function history()
    {
        $db = Connection::getInstance();
        
        // Get pagination parameters
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Get total count
        $totalResult = $db->query("SELECT COUNT(*) as total FROM surat");
        $total = $totalResult->fetch()['total'];
        $totalPages = ceil($total / $limit);
        
        // Get surat data with pagination
        $sql = "SELECT s.*, js.nama as jenis_nama, p.nama as pegawai_nama, pj.nama as pejabat_nama
                FROM surat s 
                LEFT JOIN jenis_surat js ON s.jenis_surat_id = js.id 
                LEFT JOIN pegawai p ON s.pegawai_nip = p.nip 
                LEFT JOIN pegawai pj ON s.pejabat_penandatangan_nip = pj.nip
                ORDER BY s.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $result = $db->query($sql, [$limit, $offset]);
        $suratList = $result->fetchAll();
        
        // Generate download and preview URLs for each surat
        foreach ($suratList as &$surat) {
            if ($surat['file_path']) {
                $filename = basename($surat['file_path']);
                $downloadId = base64_encode($filename);
                $surat['download_url'] = '/surat/download/' . $downloadId;
                $surat['preview_url'] = '/surat/preview/' . $downloadId;
            }
        }
        
        $data = [
            'title' => 'Riwayat Surat - ASN Mutasi',
            'surat_list' => $suratList,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_records' => $total
        ];
        
        return $this->view('surat.history', $data);
    }
    
    /**
     * Download generated surat as PDF
     */
    public function download($id)
    {
        $filename = base64_decode($id);
        $filepath = STORAGE_PATH . '/generated/' . $filename;
        
        if (!file_exists($filepath)) {
            return $this->abort(404, 'File tidak ditemukan');
        }
        
        // Read HTML content
        $htmlContent = file_get_contents($filepath);
        
        // Generate PDF using TCPDF
        $pdfContent = $this->convertHtmlToPdf($htmlContent);
        
        // Generate PDF filename
        $pdfFilename = str_replace('.html', '.pdf', $filename);
        
        // Set headers for PDF download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFilename . '"');
        header('Content-Length: ' . strlen($pdfContent));
        
        echo $pdfContent;
        exit;
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
            $content = str_replace('</head>', $printStyles . '</head>', $content);
        } else {
            $content = $printStyles . $content;
        }
        
        // Insert controls after body tag if exists, otherwise add at the beginning
        if (strpos($content, '<body') !== false) {
            $content = preg_replace('/(<body[^>]*>)/', '$1' . $printControls, $content);
        } else {
            $content = $printControls . $content;
        }
        
        return $content;
    }
    
    /**
     * Convert HTML to PDF using TCPDF
     */
    private function convertHtmlToPdf($htmlContent)
    {
        // Clean HTML for PDF conversion first
        $cleanHtml = $this->cleanHtmlForPdf($htmlContent);
        
        // Create new PDF document with A4 configuration
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Disable header and footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins to match A4 paper with proper spacing
        $pdf->SetMargins(15, 10, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add a page
        $pdf->AddPage();
        
        // Set default font to match HTML
        $pdf->SetFont('helvetica', '', 10);
        
        // Enable better HTML rendering
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // TCPDF doesn't have setCSSArray method, CSS should be embedded in HTML
        
        // Write HTML content with full rendering options
        $pdf->writeHTML($cleanHtml, true, false, true, false, '');
        
        // Return PDF content as string
        return $pdf->Output('', 'S');
    }
    
    /**
     * Clean HTML content for PDF generation
     */
    private function cleanHtmlForPdf($htmlContent)
    {
        // Only remove print controls and scripts, keep everything else
        $htmlContent = preg_replace('/<div class="print-controls[^>]*>.*?<\/div>/s', '', $htmlContent);
        $htmlContent = preg_replace('/<script[^>]*>.*?<\/script>/s', '', $htmlContent);
        $htmlContent = preg_replace('/<[^>]*class="[^"]*no-print[^"]*"[^>]*>.*?<\/[^>]+>/s', '', $htmlContent);
        
        // Convert external image URLs to local paths for PDF
        $htmlContent = preg_replace('/src="http:\/\/localhost:8000\//i', 'src="', $htmlContent);
        
        // Convert flexbox data tables to proper HTML tables for PDF compatibility
        $htmlContent = $this->convertFlexboxToTable($htmlContent);
        
        // Fix CSS for better PDF compatibility
        $htmlContent = $this->fixCssForPdf($htmlContent);
        
        // Keep all CSS and styling intact for proper formatting
        // Only convert pt units to px for better PDF compatibility
        $htmlContent = str_replace('14pt', '13px', $htmlContent);
        $htmlContent = str_replace('12pt', '11px', $htmlContent);
        $htmlContent = str_replace('11pt', '10px', $htmlContent);
        $htmlContent = str_replace('9pt', '8px', $htmlContent);
        
        return $htmlContent;
    }
    
    /**
     * Fix CSS for better PDF compatibility
     */
    private function fixCssForPdf($htmlContent)
    {
        // Replace problematic CSS positioning with table-based layout for header
        $headerPattern = '/<div class="header"[^>]*>(.*?)<\/div>/s';
        $htmlContent = preg_replace_callback($headerPattern, function($matches) {
            $headerContent = $matches[1];
            
            // Extract logo and header content
            $logoPattern = '/<img[^>]*class="logo"[^>]*>/i';
            $headerContentPattern = '/<div class="header-content"[^>]*>(.*?)<\/div>/s';
            
            preg_match($logoPattern, $headerContent, $logoMatch);
            preg_match($headerContentPattern, $headerContent, $contentMatch);
            
            $logo = isset($logoMatch[0]) ? $logoMatch[0] : '';
            $content = isset($contentMatch[1]) ? $contentMatch[1] : '';
            
            // Create table-based header layout
            return '<div class="header" style="text-align: center; margin-bottom: 25px; border-bottom: 3px solid #000; padding-bottom: 12px;">' .
                   '<table style="width: 100%; border-collapse: collapse;">' .
                   '<tr>' .
                   '<td style="width: 90px; vertical-align: top;">' . str_replace('class="logo"', 'style="width: 80px; height: 80px;"', $logo) . '</td>' .
                   '<td style="text-align: center; vertical-align: middle;">' . $content . '</td>' .
                   '<td style="width: 90px;"></td>' .
                   '</tr>' .
                   '</table>' .
                   '</div>';
        }, $htmlContent);
        
        // Fix signature section positioning
        $signaturePattern = '/<div class="signature-section"[^>]*>(.*?)<\/div>/s';
        $htmlContent = preg_replace_callback($signaturePattern, function($matches) {
            $signatureContent = $matches[1];
            return '<div style="margin-top: 30px; text-align: right;">' .
                   '<div style="display: inline-block; text-align: left; width: 200px;">' .
                   $signatureContent .
                   '</div>' .
                   '</div>';
        }, $htmlContent);
        
        return $htmlContent;
    }
    
    /**
     * Convert flexbox data tables to proper HTML tables
     */
    private function convertFlexboxToTable($htmlContent)
    {
        // Pattern to match data-table divs with data-row children
        $pattern = '/<div class="data-table"[^>]*>(.*?)<\/div>/s';
        
        $htmlContent = preg_replace_callback($pattern, function($matches) {
            $tableContent = $matches[1];
            
            // Extract data rows
            $rowPattern = '/<div class="data-row"[^>]*>(.*?)<\/div>/s';
            $tableRows = '';
            
            preg_match_all($rowPattern, $tableContent, $rowMatches);
            
            foreach ($rowMatches[1] as $rowContent) {
                // Extract label, colon, and value from each row
                $labelPattern = '/<div class="data-label"[^>]*>(.*?)<\/div>/s';
                $colonPattern = '/<div class="data-colon"[^>]*>(.*?)<\/div>/s';
                $valuePattern = '/<div class="data-value"[^>]*>(.*?)<\/div>/s';
                
                preg_match($labelPattern, $rowContent, $labelMatch);
                preg_match($colonPattern, $rowContent, $colonMatch);
                preg_match($valuePattern, $rowContent, $valueMatch);
                
                $label = isset($labelMatch[1]) ? trim($labelMatch[1]) : '';
                $colon = isset($colonMatch[1]) ? trim($colonMatch[1]) : ':';
                $value = isset($valueMatch[1]) ? trim($valueMatch[1]) : '';
                
                $tableRows .= '<tr>';
                $tableRows .= '<td style="width: 150px; vertical-align: top; padding: 3px 0;">' . $label . '</td>';
                $tableRows .= '<td style="width: 20px; vertical-align: top; padding: 3px 0;">' . $colon . '</td>';
                $tableRows .= '<td style="vertical-align: top; padding: 3px 0;">' . $value . '</td>';
                $tableRows .= '</tr>';
            }
            
            // Return proper HTML table
            return '<table style="width: 100%; margin: 15px 0; border-collapse: collapse;">' . $tableRows . '</table>';
        }, $htmlContent);
        
        return $htmlContent;
    }
    
    /**
     * Get surat types for API
     */
    public function getSuratTypes()
    {
        return [
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
        ];
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
            
            // Debug: Log filepath before saving to database
            log_info("Debug filepath before saving to database", [
                'filepath' => $filepath,
                'filename' => $filename,
                'file_exists' => file_exists($filepath)
            ]);
            
            // Save to database
            $this->saveToDatabase($template, $data, $filename, $filepath);
            
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
     * Save surat data to database
     */
    private function saveToDatabase($template, $data, $filename, $filepath)
    {
        try {
            // Debug: Log received parameters
            log_info("Debug saveToDatabase parameters", [
                'template' => $template,
                'filename' => $filename,
                'filepath' => $filepath,
                'filepath_length' => strlen($filepath ?? ''),
                'filepath_is_null' => is_null($filepath)
            ]);
            
            $db = Connection::getInstance();
            
            // Generate nomor surat based on template
            $nomorSurat = $this->generateNomorSurat($template, $data);
            
            // Get or create jenis_surat
            $jenisSuratId = $this->getOrCreateJenisSurat($template);
            
            // Prepare surat data
            $suratData = [
                'nomor_surat' => $nomorSurat,
                'pegawai_nip' => $data['nippegawai'] ?? null,
                'jenis_surat_id' => $jenisSuratId,
                'pejabat_penandatangan_nip' => $data['nippejabat'] ?? null,
                'judul' => $this->generateJudul($template, $data),
                'tanggal_surat' => date('Y-m-d'),
                'bulan' => (int)($data['blnno'] ?? $data['blnsrt'] ?? date('n')),
                'tahun' => $data['thnno'] ?? $data['thnskrg'] ?? date('Y'),
                'status' => 'generated',
                'file_path' => $filepath,
                'data_surat' => json_encode($data)
            ];
            
            // Debug: Log data before insert
            log_info("Debug data before INSERT", [
                'suratData_file_path' => $suratData['file_path'],
                'suratData_file_path_is_null' => is_null($suratData['file_path']),
                'all_suratData' => $suratData
            ]);
            
            // Insert into database
            $sql = "INSERT INTO surat (nomor_surat, pegawai_nip, jenis_surat_id, pejabat_penandatangan_nip, judul, tanggal_surat, bulan, tahun, status, file_path, data_surat, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $db->query($sql, [
                $suratData['nomor_surat'],
                $suratData['pegawai_nip'],
                $suratData['jenis_surat_id'],
                $suratData['pejabat_penandatangan_nip'],
                $suratData['judul'],
                $suratData['tanggal_surat'],
                $suratData['bulan'],
                $suratData['tahun'],
                $suratData['status'],
                $suratData['file_path'],
                $suratData['data_surat']
            ]);
            
            log_info("Surat saved to database", [
                'template' => $template,
                'filename' => $filename,
                'nomor_surat' => $nomorSurat
            ]);
            
        } catch (\Exception $e) {
            log_error("Failed to save surat to database", [
                'template' => $template,
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Generate nomor surat based on template and data
     */
    private function generateNomorSurat($template, $data)
    {
        $bulan = $data['blnno'] ?? $data['blnsrt'] ?? date('n');
        $tahun = $data['thnno'] ?? $data['thnskrg'] ?? date('Y');
        $nomor = $data['nosrt'] ?? str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return 'B-' . $nomor . '/Kk.18.08/1/Kp.01.2/' . $bulan . '/' . $tahun;
    }
    
    /**
     * Get or create jenis_surat based on template
     */
    private function getOrCreateJenisSurat($template)
    {
        $db = Connection::getInstance();
        
        $templateMap = [
            'surat_keterangan_pengalaman_mengajar' => ['SKPM', 'Surat Keterangan Pengalaman Mengajar'],
            'surat_permohonan_skbt' => ['SKBT', 'Surat Permohonan SKBT'],
            'surat_pernyataan_disiplin_new' => ['PD', 'Surat Pernyataan Disiplin'],
            'surat_pernyataan_pidana' => ['PP', 'Surat Pernyataan Pidana'],
            'surat_persetujuan_pelepasan' => ['SPL', 'Surat Persetujuan Pelepasan'],
            'surat_persetujuan_penerimaan' => ['SPN', 'Surat Persetujuan Penerimaan'],
            'surat_sptjm' => ['SPTJM', 'Surat Pernyataan Tanggung Jawab Mutlak']
        ];
        
        $templateInfo = $templateMap[$template] ?? ['OTHER', ucwords(str_replace('_', ' ', $template))];
        $kode = $templateInfo[0];
        $nama = $templateInfo[1];
        
        // Check if exists
        $jenisSurat = $db->fetch("SELECT id FROM jenis_surat WHERE kode = ? OR template_file = ?", [$kode, $template]);
        
        if ($jenisSurat) {
            return $jenisSurat['id'];
        }
        
        // Create new
        $db->query("INSERT INTO jenis_surat (kode, nama, template_file, deskripsi, status) VALUES (?, ?, ?, ?, ?)", [
            $kode,
            $nama,
            $template,
            'Surat yang dibuat melalui sistem',
            'aktif'
        ]);
        
        return $db->lastInsertId();
    }
    
    /**
     * Generate judul surat based on template and data
     */
    private function generateJudul($template, $data)
    {
        $namaPegawai = $data['namapegawai'] ?? 'Unknown';
        
        $judulMap = [
            'surat_keterangan_pengalaman_mengajar' => 'Surat Keterangan Pengalaman Mengajar - ' . $namaPegawai,
            'surat_permohonan_skbt' => 'Surat Permohonan SKBT - ' . $namaPegawai,
            'surat_pernyataan_disiplin_new' => 'Surat Pernyataan Disiplin - ' . $namaPegawai,
            'surat_pernyataan_pidana' => 'Surat Pernyataan Pidana - ' . $namaPegawai,
            'surat_persetujuan_pelepasan' => 'Surat Persetujuan Pelepasan - ' . $namaPegawai,
            'surat_persetujuan_penerimaan' => 'Surat Persetujuan Penerimaan - ' . $namaPegawai,
            'surat_sptjm' => 'Surat Pernyataan Tanggung Jawab Mutlak - ' . $namaPegawai
        ];
        
        return $judulMap[$template] ?? ucwords(str_replace('_', ' ', $template)) . ' - ' . $namaPegawai;
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