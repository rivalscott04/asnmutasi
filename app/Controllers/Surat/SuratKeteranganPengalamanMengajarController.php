<?php

namespace App\Controllers\Surat;

use App\Controllers\BaseController;
use App\Models\Surat;

/**
 * Surat Keterangan Pengalaman Mengajar Controller
 * Menangani pembuatan surat keterangan pengalaman mengajar
 */
class SuratKeteranganPengalamanMengajarController extends BaseController
{
    /**
     * Form Surat Keterangan Pengalaman Mengajar
     */
    public function show()
    {
        // Get office data from database
        $kantor = $this->getKantorData();
        
        $data = [
            'title' => 'Surat Keterangan Pengalaman Mengajar',
            'template' => 'surat_keterangan_pengalaman_mengajar',
            'kantor' => $kantor
        ];
        
        return $this->view('surat.pengalaman-mengajar', $data);
    }
    
    /**
     * Generate Surat Keterangan Pengalaman Mengajar
     */
    public function generate()
    {
        log_debug('Masuk ke generate surat pengalaman mengajar', [
            'request' => $_POST,
            'rules' => [
                'nosrt' => 'required|string|max:100',
                'blnno' => 'required|string|max:10',
                'thnno' => 'required|string|max:4',
                'namapegawai' => 'required|string|max:100',
                'nippegawai' => 'required|string|max:50',
                'pangkatgolpegawai' => 'required|string|max:100',
                'jabatanpegawai' => 'required|string|max:200',
                'tempattugas' => 'required|string|max:200',
                'sekolah' => 'required|string|max:100',
                'tglmulai' => 'required|date',
                'namapejabat' => 'required|string|max:100',
                'nippejabat' => 'required|string|max:50',
                'pangkatgolpejabat' => 'required|string|max:100',
                'jabatanpejabat' => 'required|string|max:200'
            ]
        ]);
        try {
            // Validate only form fields, not office data (taken from database)
            $validated = $this->validate([
                'nosrt' => 'required|string|max:100',
                'blnno' => 'required|string|max:10',
                'thnno' => 'required|string|max:4',
                'namapegawai' => 'required|string|max:100',
                'nippegawai' => 'required|string|max:50',
                'pangkatgolpegawai' => 'required|string|max:100',
                'jabatanpegawai' => 'required|string|max:200',
                'tempattugas' => 'required|string|max:200',
                'sekolah' => 'required|string|max:100',
                'tglmulai' => 'required|date',
                'namapejabat' => 'required|string|max:100',
                'nippejabat' => 'required|string|max:50',
                'pangkatgolpejabat' => 'required|string|max:100',
                'jabatanpejabat' => 'required|string|max:200'
            ]);
            
            // STEP 1: Save to database first
            $this->saveToDatabase($validated);
            
            // STEP 2: Get office data from database
            $kantor = $this->getKantorData();
            
            // Add office data to validated data
            $validated['kabkota'] = $kantor['kabupaten_kota'] ?? 'LOMBOK TIMUR';
            $validated['kabkota2'] = $kantor['kabkolower'] ?? 'Kabupaten Lombok Timur';
            $validated['jln'] = $kantor['alamat'] ?? 'Jl. TGH. Lopan No. 12 Selong';
            $validated['telfon'] = $kantor['telepon'] ?? 'Telp. (0370) 654321';
            $validated['fax'] = $kantor['fax'] ?? 'Fax. (0370) 654322';
            $validated['email'] = $kantor['email'] ?? 'kankemenag.lotim@kemenag.go.id';
            $validated['website'] = $kantor['website'] ?? 'www.kankemenag.lotim.go.id';
            $validated['ibukota'] = $kantor['ibukota'] ?? 'SELONG';
            
            // Format tanggal untuk signature dalam format Indonesia
            $validated['dd-mm-yyyy'] = $this->formatDateToIndonesian(date('d-m-Y'));
            
            // Format tanggal mulai mengajar dalam format Indonesia
            if (!empty($validated['tglmulai'])) {
                $tglMulai = $this->formatDateToIndonesian(date('d-m-Y', strtotime($validated['tglmulai'])));
                $validated['tglmulai'] = $tglMulai;
            }
            
            // STEP 3: Generate surat
            $result = $this->generateSurat('surat_keterangan_pengalaman_mengajar', $validated);
            log_info('Surat pengalaman mengajar berhasil dibuat dan disimpan ke database', ['nosrt' => $validated['nosrt']]);
            return $result;
            
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
    
    /**
     * Preview Surat Keterangan Pengalaman Mengajar
     */
    public function preview()
    {
        try {
            // Get office data from database
            $kantor = $this->getKantorData();
             
            // Get form data from POST request
            $data = [];
            $fields = [
                'nosrt', 'blnno', 'thnno',
                'namapegawai', 'nippegawai', 'pangkatgolpegawai', 'jabatanpegawai', 'tempattugas',
                'sekolah', 'tglmulai',
                'namapejabat', 'nippejabat', 'pangkatgolpejabat', 'jabatanpejabat'
            ];
             
            foreach ($fields as $field) {
                $data[$field] = $this->input($field, '');
            }
             
            // Add office data
            $data['kabkota'] = $kantor['kabupaten_kota'] ?? 'LOMBOK TIMUR';
            $data['kabkota2'] = $kantor['kabkolower'] ?? 'Kabupaten Lombok Timur';
            $data['ibukota'] = $kantor['ibukota'] ?? 'SELONG';
            $data['jln'] = $kantor['alamat'] ?? 'Jl. TGH. Lopan No. 12 Selong';
            $data['telfon'] = $kantor['telepon'] ?? 'Telp. (0370) 654321';
            $data['fax'] = $kantor['fax'] ?? 'Fax. (0370) 654322';
            $data['email'] = $kantor['email'] ?? 'kankemenag.lotim@kemenag.go.id';
            $data['website'] = $kantor['website'] ?? 'www.kankemenag.lotim.go.id';
            
            // Format tanggal untuk signature dalam format Indonesia
            $data['dd-mm-yyyy'] = $this->formatDateToIndonesian(date('d-m-Y'));
            
            // Format tanggal mulai mengajar dalam format Indonesia
            if (!empty($data['tglmulai'])) {
                $tglMulai = date('d-m-Y', strtotime($data['tglmulai']));
                $data['tglmulai'] = $this->formatDateToIndonesian($tglMulai);
            }
            
            // Load template
            $templatePath = ROOT_PATH . '/templates/surat_keterangan_pengalaman_mengajar.html';
            
            if (!file_exists($templatePath)) {
                throw new \Exception('Template tidak ditemukan');
            }
            
            $templateContent = file_get_contents($templatePath);
            
            // Replace placeholders
            foreach ($data as $key => $value) {
                $templateContent = str_replace('{{' . $key . '}}', htmlspecialchars($value), $templateContent);
            }
            
            // Add logo URL
            $logoUrl = $this->getLogoUrl();
            $templateContent = str_replace('{{logo_url}}', $logoUrl, $templateContent);
            
            // Set proper content type header
            header('Content-Type: text/html; charset=utf-8');
            
            // Return HTML directly for modal display
            echo $templateContent;
            exit;
            
        } catch (\Exception $e) {
            header('Content-Type: text/html; charset=utf-8');
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            exit;
        }
    }
    
    /**
     * Generate surat dari template
     */
    private function generateSurat($template, $data)
    {
        // Load template
        $templatePath = ROOT_PATH . '/templates/' . $template . '.html';
        
        if (!file_exists($templatePath)) {
            throw new \Exception('Template tidak ditemukan: ' . $template);
        }
        
        $templateContent = file_get_contents($templatePath);
        
        // Replace placeholders
        foreach ($data as $key => $value) {
            $templateContent = str_replace('{{' . $key . '}}', $value, $templateContent);
        }
        
        // Add logo URL if not provided
        if (strpos($templateContent, '{{logo_url}}') !== false) {
            $logoUrl = $this->getLogoUrl();
            $templateContent = str_replace('{{logo_url}}', $logoUrl, $templateContent);
        }
        
        // Generate unique filename
        $filename = $template . '_' . date('YmdHis') . '_' . uniqid() . '.html';
        $filepath = STORAGE_PATH . '/generated/' . $filename;
        
        // Create directory if not exists
        $dir = dirname($filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Save generated file
        file_put_contents($filepath, $templateContent);
        
        // Log debug untuk filepath dan filename
        log_debug('GenerateSurat: Filepath dan filename hasil generate', [
            'filepath' => $filepath,
            'filename' => $filename
        ]);
        
        if ($this->expectsJson()) {
            return $this->success([
                'filepath' => $filepath,
                'filename' => $filename,
                'download_url' => $this->url('surat/download/' . base64_encode($filename)),
                'preview_url' => $this->url('surat/preview/' . base64_encode($filename))
            ], 'Surat berhasil dibuat');
        } else {
            return [
                'filepath' => $filepath,
                'filename' => $filename,
                'preview_url' => $this->url('surat/preview/' . base64_encode($filename))
            ];
        }
    }
    
    /**
     * Save data to database (extracted from save method)
     */
    private function saveToDatabase($validated)
    {
        // Log save attempt
        log_info("Saving Surat Keterangan Pengalaman Mengajar to database during generate", [
            "user_id" => $_SESSION['user_id'] ?? null,
            "timestamp" => date('Y-m-d H:i:s'),
            "pegawai_nip" => $validated['nippegawai']
        ]);
        
        // Generate nomor surat dengan format lengkap sesuai template
        $nomorSurat = 'B-' . $validated['nosrt'] . '/Kk.18.08/1/Kp.01.2/' . $validated['blnno'] . '/' . $validated['thnno'];
        
        // Get database connection
        $db = \Core\Database\Connection::getInstance();
        
        // Check if jenis_surat exists for Pengalaman Mengajar
        $jenisSurat = $db->fetch("SELECT id FROM jenis_surat WHERE kode = 'PENGALAMAN_MENGAJAR' OR nama LIKE '%Pengalaman Mengajar%' LIMIT 1");
        if (!$jenisSurat) {
            // Create jenis_surat if not exists
            $db->query("INSERT INTO jenis_surat (kode, nama, deskripsi, status) VALUES (?, ?, ?, ?)", [
                'PENGALAMAN_MENGAJAR',
                'Surat Keterangan Pengalaman Mengajar',
                'Surat keterangan pengalaman mengajar untuk keperluan administrasi',
                'aktif'
            ]);
            $jenisSuratId = $db->lastInsertId();
            
            log_info("Created new jenis_surat for Pengalaman Mengajar", ['id' => $jenisSuratId]);
        } else {
            $jenisSuratId = $jenisSurat['id'];
        }
        
        // Prepare data for database
        $suratData = [
            'nomor_surat' => $nomorSurat,
            'pegawai_nip' => $validated['nippegawai'],
            'jenis_surat_id' => $jenisSuratId,
            'pejabat_penandatangan_nip' => $validated['nippejabat'],
            'judul' => 'Surat Keterangan Pengalaman Mengajar - ' . $validated['namapegawai'],
            'tanggal_surat' => date('Y-m-d'),
            'bulan' => (int)$validated['blnno'],
            'tahun' => $validated['thnno'],
            'status' => 'generated',
            'data_surat' => json_encode($validated)
        ];
        
        // Insert into database
        $sql = "INSERT INTO surat (nomor_surat, pegawai_nip, jenis_surat_id, pejabat_penandatangan_nip, judul, tanggal_surat, bulan, tahun, status, data_surat, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $result = $db->query($sql, [
            $suratData['nomor_surat'],
            $suratData['pegawai_nip'],
            $suratData['jenis_surat_id'],
            $suratData['pejabat_penandatangan_nip'],
            $suratData['judul'],
            $suratData['tanggal_surat'],
            $suratData['bulan'],
            $suratData['tahun'],
            $suratData['status'],
            $suratData['data_surat']
        ]);
        
        $suratId = $db->lastInsertId();
        
        // Log successful save
        log_info("Surat Keterangan Pengalaman Mengajar successfully saved to database during generate", [
            "surat_id" => $suratId,
            "nomor_surat" => $nomorSurat,
            "pegawai_nip" => $validated['nippegawai'],
            "pegawai_nama" => $validated['namapegawai'],
            "status" => 'generated'
        ]);
        
        return $suratId;
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
     * Download PDF dari file HTML hasil generate menggunakan Browsershot
     */
    public function downloadPdf($filename)
    {
        // Pastikan Browsershot sudah di-install via Composer
        if (!class_exists('Spatie\\Browsershot\\Browsershot')) {
            http_response_code(500);
            echo 'Browsershot belum terinstall. Jalankan composer require spatie/browsershot';
            exit;
        }
        
        $htmlPath = STORAGE_PATH . '/generated/' . basename($filename);
        if (!file_exists($htmlPath)) {
            http_response_code(404);
            echo 'File HTML tidak ditemukan.';
            exit;
        }
        
        $pdfPath = STORAGE_PATH . '/generated/' . pathinfo($filename, PATHINFO_FILENAME) . '.pdf';
        
        try {
            // Generate PDF dari HTML menggunakan Browsershot
            \Spatie\Browsershot\Browsershot::html(file_get_contents($htmlPath))
                ->setOption('enable-local-file-access', true)
                ->format('A4')
                ->showBackground()
                ->margins(0, 0, 0, 0)
                ->deviceScaleFactor(2)
                ->waitUntilNetworkIdle()
                ->save($pdfPath);
        } catch (\Exception $e) {
            http_response_code(500);
            echo 'Gagal generate PDF: ' . htmlspecialchars($e->getMessage());
            exit;
        }
        
        // Kirim PDF ke user (download)
        if (file_exists($pdfPath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($pdfPath) . '"');
            header('Content-Length: ' . filesize($pdfPath));
            readfile($pdfPath);
            exit;
        } else {
            http_response_code(500);
            echo 'File PDF gagal dibuat.';
            exit;
        }
    }
}