<?php

namespace App\Controllers\Surat;

use App\Controllers\BaseController;
use App\Models\Surat;

/**
 * Surat Persetujuan Pelepasan Controller
 * Menangani pembuatan surat persetujuan pelepasan pegawai
 */
class SuratPersetujuanPelepasanController extends BaseController
{
    /**
     * Form Surat Persetujuan Pelepasan
     */
    public function show()
    {
        $data = [
            'title' => 'Surat Persetujuan Pelepasan Pegawai',
            'template' => 'surat_persetujuan_pelepasan'
        ];
        
        return $this->view('surat.persetujuan-pelepasan', $data);
    }
    
    /**
     * Generate Surat Persetujuan Pelepasan
     */
    public function generate()
    {
        try {
            $validated = $this->validate([
                'nosrt' => 'required|string|max:100',
                'blnsrt' => 'required|string',
                'thnsrt' => 'required|string|max:4',
                'tanggalsrt' => 'required|date',
                'namapegawai' => 'required|string',
                'nippegawai' => 'required|string',
                'pangkatgolpegawai' => 'required|string',
                'jabatanpegawai' => 'required|string',
                'unitkerjapegawai' => 'required|string',
                'tempattugas' => 'required|string',
                'jabatnpegawai2' => 'required|string',
                'tempattugas2' => 'required|string',
                'kabataukotatujuan' => 'required|string',
                'namapejabat' => 'required|string',
                'nippejabat' => 'required|string',
                'pangkatgolpejabat' => 'required|string',
                'jabatanpejabat' => 'required|string',
                'unitkerjapejabat' => 'required|string'
            ]);
            
            // Get office data from database
            $kantor = $this->getKantorData();
            
            // Add office data to validated data
            $validated['kabkota'] = $kantor['kabupaten_kota'] ?? 'LOMBOK TIMUR';
            $validated['kabkota2'] = $kantor['kabkolower'] ?? 'Kabupaten Lombok Timur';
            $validated['ibukota'] = $kantor['ibukota'] ?? 'SELONG';
            $validated['jln'] = $kantor['alamat'] ?? 'Jl. TGH. Lopan No. 12 Selong';
            $validated['telfon'] = $kantor['telepon'] ?? 'Telp. (0370) 654321';
            $validated['fax'] = $kantor['fax'] ?? 'Fax. (0370) 654322';
            $validated['email'] = $kantor['email'] ?? 'kankemenag.lotim@kemenag.go.id';
            $validated['website'] = $kantor['website'] ?? 'www.kankemenag.lotim.go.id';
            
            return $this->generateSurat('surat_persetujuan_pelepasan', $validated);
            
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
    
    /**
     * Preview Surat Persetujuan Pelepasan
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
            $data['kabkota2'] = $kantor['kabkolower'] ?? 'Kabupaten Lombok Timur';
            $data['ibukota'] = $kantor['ibukota'] ?? 'SELONG';
            $data['jln'] = $kantor['alamat'] ?? 'Jl. TGH. Lopan No. 12 Selong';
            $data['telfon'] = $kantor['telepon'] ?? 'Telp. (0370) 654321';
            $data['fax'] = $kantor['fax'] ?? 'Fax. (0370) 654322';
            $data['email'] = $kantor['email'] ?? 'kankemenag.lotim@kemenag.go.id';
            $data['website'] = $kantor['website'] ?? 'www.kankemenag.lotim.go.id';
            
            // Format tanggal untuk signature dalam format Indonesia
            $data['dd-mm-yyyy'] = $this->formatDateToIndonesian(date('d-m-Y'));
            
            // Load template
            $templatePath = ROOT_PATH . '/templates/surat_persetujuan_pelepasan.html';
            
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
        
        // Return array lengkap
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'download_url' => $this->url('surat/download/' . base64_encode($filename)),
            'preview_url' => $this->url('surat/preview/' . base64_encode($filename))
        ];
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