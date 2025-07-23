<?php

namespace App\Controllers\Surat;

use App\Controllers\BaseController;
use App\Models\Surat;

/**
 * Surat Permohonan SKBT Controller
 * Menangani pembuatan surat permohonan SKBT
 */
class SuratPermohonanSkbtController extends BaseController
{
    /**
     * Form Surat Permohonan SKBT
     */
    public function show()
    {
        // Get office data from database
        $kantor = $this->getKantorData();
        
        $data = [
            'title' => 'Surat Permohonan SKBT',
            'template' => 'surat_permohonan_skbt',
            'kantor' => $kantor
        ];
        
        return $this->view('surat.permohonan-skbt', $data);
    }
    
    /**
     * Save Surat Permohonan SKBT to database
     */
    public function save()
    {
        try {
            // Log save attempt
            log_info("Attempting to save Surat Permohonan SKBT to database", [
                "user_id" => $_SESSION['user_id'] ?? null,
                "timestamp" => date('Y-m-d H:i:s'),
                "form_data_keys" => array_keys($_POST)
            ]);
            
            // Validate form data
            $validated = $this->validate([
                'nosrt' => 'required|string|max:100',
                'blnsrt' => 'required|string|max:10',
                'thnskrg' => 'required|string|max:4',
                'tgl' => 'required|string|max:2',
                'bln' => 'required|string|max:20',
                'thn' => 'required|string|max:4',
                'namapegawai' => 'required|string|max:100',
                'nippegawai' => 'required|string|max:50',
                'pangkatgolpegawai' => 'required|string|max:100',
                'jabatanpegawai' => 'required|string|max:200',
                'unitkerja' => 'required|string|max:200',
                'keperluan' => 'required|string|max:200',
                'namapejabat' => 'required|string|max:100',
                 'nippejabat' => 'required|string|max:50',
                 'pangkatgolpejabat' => 'required|string|max:100',
                 'jabatanpejabat' => 'required|string|max:200'
             ]);
            
            // Generate nomor surat dengan format lengkap sesuai template
            $nomorSurat = 'B-' . $validated['nosrt'] . '/Kk.18.08/1/Kp.01.2/' . $validated['blnsrt'] . '/' . $validated['thnskrg'];
            
            // Get database connection
            $db = \Core\Database\Connection::getInstance();
            
            // Check if jenis_surat exists for SKBT
            $jenisSurat = $db->fetch("SELECT id FROM jenis_surat WHERE kode = 'SKBT' OR nama LIKE '%SKBT%' LIMIT 1");
            if (!$jenisSurat) {
                // Create jenis_surat if not exists
                $db->query("INSERT INTO jenis_surat (kode, nama, deskripsi, status) VALUES (?, ?, ?, ?)", [
                    'SKBT',
                    'Surat Keterangan Bebas Tugas',
                    'Surat permohonan keterangan bebas tugas untuk keperluan administrasi',
                    'aktif'
                ]);
                $jenisSuratId = $db->lastInsertId();
                
                log_info("Created new jenis_surat for SKBT", ['id' => $jenisSuratId]);
            } else {
                $jenisSuratId = $jenisSurat['id'];
            }
            
            // Prepare data for database
            $suratData = [
                'nomor_surat' => $nomorSurat,
                'pegawai_nip' => $validated['nippegawai'],
                'jenis_surat_id' => $jenisSuratId,
                'pejabat_penandatangan_nip' => $validated['nippejabat'],
                'judul' => 'Surat Permohonan SKBT - ' . $validated['namapegawai'],
                'tanggal_surat' => date('Y-m-d'),
                'bulan' => (int)$validated['blnsrt'],
                'tahun' => $validated['thnskrg'],
                'status' => 'draft',
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
            log_info("Surat Permohonan SKBT successfully saved to database", [
                "surat_id" => $suratId,
                "nomor_surat" => $nomorSurat,
                "pegawai_nip" => $validated['nippegawai'],
                "pegawai_nama" => $validated['namapegawai'],
                "pejabat_nip" => $validated['nippejabat'],
                "pejabat_nama" => $validated['namapejabat'],
                "keperluan" => $validated['keperluan'],
                "user_id" => $_SESSION['user_id'] ?? null,
                "timestamp" => date('Y-m-d H:i:s')
            ]);
            
            return $this->success([
                'surat_id' => $suratId,
                'nomor_surat' => $nomorSurat
            ], 'Data surat berhasil disimpan ke database');
            
        } catch (\Exception $e) {
            // Log error
            log_error("Failed to save Surat Permohonan SKBT to database", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
                "form_data" => $this->filterSensitiveData($_POST),
                "user_id" => $_SESSION['user_id'] ?? null,
                "timestamp" => date('Y-m-d H:i:s')
            ]);
            
            return $this->error('Gagal menyimpan data: ' . $e->getMessage(), 422);
        }
    }
    
    /**
     * Generate Surat Permohonan SKBT
     */
    public function generate()
    {
        try {
            // Set JSON content type header
            header('Content-Type: application/json');
            
            // Validate only form fields, not office data (taken from database)
            $validated = $this->validate([
                'nosrt' => 'required|string|max:100',
                'blnsrt' => 'required|string',
                'thnskrg' => 'required|string|max:4',
                'tgl' => 'required|string|max:2',
                'bln' => 'required|string',
                'thn' => 'required|string|max:4',
                'namapegawai' => 'required|string',
                'nippegawai' => 'required|string',
                'pangkatgolpegawai' => 'required|string',
                'jabatanpegawai' => 'required|string',
                'unitkerja' => 'required|string',
                'keperluan' => 'required|string',
                'keperluan_lainnya' => 'string',
                'namapejabat' => 'required|string',
                'nippejabat' => 'required|string',
                'pangkatgolpejabat' => 'string',
                'jabatanpejabat' => 'string'
            ]);
            
            // STEP 1: Save to database first
            $this->saveToDatabase($validated);
            
            // STEP 2: Get office data from database
            $kantor = $this->getKantorData();
            
            // Add office data to validated data
            $validated['kabkota'] = $kantor['kabupaten_kota'] ?? 'LOMBOK TIMUR';
            $validated['ibukota'] = $kantor['ibukota'] ?? 'SELONG';
            $validated['jln'] = $kantor['alamat'] ?? 'Jl. TGH. Lopan No. 12 Selong';
            $validated['telfon'] = $kantor['telepon'] ?? 'Telp. (0370) 654321';
            $validated['fax'] = $kantor['fax'] ?? 'Fax. (0370) 654322';
            $validated['email'] = $kantor['email'] ?? 'kankemenag.lotim@kemenag.go.id';
            $validated['website'] = $kantor['website'] ?? 'www.kankemenag.lotim.go.id';
            
            // Format tanggal untuk signature dalam format Indonesia
            $validated['dd-mm-yyyy'] = $this->formatDateToIndonesian(date('d-m-Y'));
            
            // STEP 3: Generate surat
            $result = $this->generateSurat('surat_permohonan_skbt', $validated);
            
            // Send response and exit
            $result->send();
            exit;
            
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            $errorResponse = $this->error($e->getMessage(), 422);
            $errorResponse->send();
            exit;
        }
    }
    
    /**
     * Preview Surat Permohonan SKBT
     */
    public function preview()
    {
        try {
            // Get all form data from POST request
            $data = $_POST;
            
            // Get office data from database
            $kantor = $this->getKantorData();
            
            // Add office data to preview data
            $data['kabkota'] = $kantor['kabupaten_kota'] ?? 'LOMBOK TIMUR';
            $data['ibukota'] = $kantor['ibukota'] ?? 'SELONG';
            $data['jln'] = $kantor['alamat'] ?? 'Jl. TGH. Lopan No. 12 Selong';
            $data['telfon'] = $kantor['telepon'] ?? 'Telp. (0370) 654321';
            $data['fax'] = $kantor['fax'] ?? 'Fax. (0370) 654322';
            $data['email'] = $kantor['email'] ?? 'kankemenag.lotim@kemenag.go.id';
            $data['website'] = $kantor['website'] ?? 'www.kankemenag.lotim.go.id';
            
            // Format tanggal untuk signature dalam format Indonesia
            $data['dd-mm-yyyy'] = $this->formatDateToIndonesian(date('d-m-Y'));
            
            // Load template
            $templatePath = ROOT_PATH . '/templates/surat_permohonan_skbt.html';
            
            if (!file_exists($templatePath)) {
                throw new \Exception('Template tidak ditemukan');
            }
            
            $templateContent = file_get_contents($templatePath);
            
            // Replace placeholders
            foreach ($data as $key => $value) {
                $templateContent = str_replace('{{' . $key . '}}', htmlspecialchars($value), $templateContent);
            }
            
            // Always add logo URL
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
        
        // Always add logo URL
        $logoUrl = $this->getLogoUrl();
        $templateContent = str_replace('{{logo_url}}', $logoUrl, $templateContent);
        
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
        
        // Return array lengkap
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'download_url' => $this->url('surat/download/' . base64_encode($filename)),
            'preview_url' => $this->url('surat/preview/' . base64_encode($filename))
        ];
    }
    
    /**
     * Save data to database (extracted from save method)
     */
    private function saveToDatabase($validated)
    {
        // Log save attempt
        log_info("Saving Surat Permohonan SKBT to database during generate", [
            "user_id" => $_SESSION['user_id'] ?? null,
            "timestamp" => date('Y-m-d H:i:s'),
            "pegawai_nip" => $validated['nippegawai']
        ]);
        
        // Generate nomor surat dengan format lengkap sesuai template
        $nomorSurat = 'B-' . $validated['nosrt'] . '/Kk.18.08/1/Kp.01.2/' . $validated['blnsrt'] . '/' . $validated['thnskrg'];
        
        // Get database connection
        $db = \Core\Database\Connection::getInstance();
        
        // Check if jenis_surat exists for SKBT
        $jenisSurat = $db->fetch("SELECT id FROM jenis_surat WHERE kode = 'SKBT' OR nama LIKE '%SKBT%' LIMIT 1");
        if (!$jenisSurat) {
            // Create jenis_surat if not exists
            $db->query("INSERT INTO jenis_surat (kode, nama, deskripsi, status) VALUES (?, ?, ?, ?)", [
                'SKBT',
                'Surat Keterangan Bebas Tugas',
                'Surat permohonan keterangan bebas tugas untuk keperluan administrasi',
                'aktif'
            ]);
            $jenisSuratId = $db->lastInsertId();
            
            log_info("Created new jenis_surat for SKBT", ['id' => $jenisSuratId]);
        } else {
            $jenisSuratId = $jenisSurat['id'];
        }
        
        // Prepare data for database
        $suratData = [
            'nomor_surat' => $nomorSurat,
            'pegawai_nip' => $validated['nippegawai'],
            'jenis_surat_id' => $jenisSuratId,
            'pejabat_penandatangan_nip' => $validated['nippejabat'],
            'judul' => 'Surat Permohonan SKBT - ' . $validated['namapegawai'],
            'tanggal_surat' => date('Y-m-d'),
            'bulan' => (int)$validated['blnsrt'],
            'tahun' => $validated['thnskrg'],
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
        log_info("Surat Permohonan SKBT successfully saved to database during generate", [
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
}