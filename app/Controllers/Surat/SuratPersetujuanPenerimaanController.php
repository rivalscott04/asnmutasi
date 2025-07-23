<?php

namespace App\Controllers\Surat;

use App\Controllers\BaseController;
use App\Models\Surat;

/**
 * Surat Persetujuan Penerimaan Controller
 * Menangani pembuatan surat persetujuan penerimaan pegawai
 */
class SuratPersetujuanPenerimaanController extends BaseController
{
    /**
     * Form Surat Persetujuan Penerimaan
     */
    public function show()
    {
        $data = [
            'title' => 'Surat Persetujuan Penerimaan Pegawai',
            'template' => 'surat_persetujuan_penerimaan'
        ];
        
        return $this->view('surat.persetujuan-penerimaan', $data);
    }
    
    /**
     * Generate Surat Persetujuan Penerimaan
     */
    public function generate()
    {
        try {
            $validated = $this->validate([
                'nosrt' => 'required|string|max:100',
                'blnno' => 'required|string',
                'thnno' => 'required|string|max:4',
                'tanggalsurat' => 'required|date',
                'namapegawai' => 'required|string',
                'nippegawai' => 'required|string',
                'pangkatgolpegawai' => 'required|string',
                'jabatanpegawai' => 'required|string',
                'unitkerja' => 'required|string',
                'namapejabat' => 'required|string',
                'nippejabat' => 'required|string',
                'pangkatgolpejabat' => 'required|string',
                'jabatanpejabat' => 'required|string',
                'ukerpejabat' => 'required|string'
            ]);
            
            return $this->generateSurat('surat_persetujuan_penerimaan', $validated);
            
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
    
    /**
     * Preview Surat Persetujuan Penerimaan
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
            $templatePath = ROOT_PATH . '/templates/surat_persetujuan_penerimaan.html';
            
            if (!file_exists($templatePath)) {
                throw new \Exception('Template tidak ditemukan');
            }
            
            $templateContent = file_get_contents($templatePath);
            
            // Replace placeholders
            foreach ($data as $key => $value) {
                $templateContent = str_replace('{{' . $key . '}}', htmlspecialchars($value), $templateContent);
            }
            
            // Add logo URL if not provided
            if (strpos($templateContent, '{{logo_url}}') !== false) {
                $logoUrl = $this->getLogoUrl();
                $templateContent = str_replace('{{logo_url}}', $logoUrl, $templateContent);
            }
            
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
        
        if ($this->expectsJson()) {
            return $this->success([
                'filename' => $filename,
                'download_url' => $this->url('surat/download/' . base64_encode($filename)),
                'preview_url' => $this->url('surat/preview/' . base64_encode($filename))
            ], 'Surat berhasil dibuat');
        } else {
            return $this->redirect('/surat/preview/' . base64_encode($filename));
        }
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