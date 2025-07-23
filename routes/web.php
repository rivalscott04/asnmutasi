<?php

/**
 * Web Routes
 * Define all web routes for the application
 */

use Core\Router;
use App\Controllers\HomeController;
use App\Controllers\SuratController;
use App\Controllers\AuthController;
use App\Controllers\PegawaiController;

// Get router instance from application
$router = $app->getRouter();

// Home routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);

// Protected routes (require authentication)
$router->group(['middleware' => ['App\Middleware\AuthMiddleware']], function($router) {
    // Role-specific dashboards
    $router->get('/dashboard-daerah', [HomeController::class, 'dashboard']);
    $router->get('/dashboard-kanwil', [HomeController::class, 'dashboard']);
    $router->get('/dashboard-pusat', [HomeController::class, 'dashboard']);
    
    $router->get('/settings', [HomeController::class, 'settings']);
    $router->post('/settings/kantor', [HomeController::class, 'saveKantorSettings']);
});

// Authentication routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Surat (Letter) routes - Protected
$router->group(['prefix' => '/surat', 'middleware' => ['App\Middleware\AuthMiddleware']], function($router) {
    // List all letter types
    $router->get('/', [SuratController::class, 'index']);
    
    // Surat Keterangan Pengalaman Mengajar
    $router->get('/pengalaman-mengajar', [App\Controllers\Surat\SuratKeteranganPengalamanMengajarController::class, 'show']);
    $router->post('/pengalaman-mengajar', [App\Controllers\Surat\SuratKeteranganPengalamanMengajarController::class, 'generate']);
    
    // Surat Permohonan SKBT
    $router->get('/permohonan-skbt', [SuratController::class, 'showPermohonanSkbt']);
    $router->post('/permohonan-skbt/save', [App\Controllers\Surat\SuratPermohonanSkbtController::class, 'save']);
    $router->post('/permohonan-skbt/generate', [App\Controllers\Surat\SuratPermohonanSkbtController::class, 'generate']);
    
    // Surat Pernyataan Disiplin
    $router->get('/pernyataan-disiplin', [SuratController::class, 'showPernyataanDisiplin']);
    $router->post('/pernyataan-disiplin', [SuratController::class, 'generatePernyataanDisiplin']);
    
    // Surat Pernyataan Pidana
    $router->get('/pernyataan-pidana', [SuratController::class, 'showPernyataanPidana']);
    $router->post('/pernyataan-pidana', [SuratController::class, 'generatePernyataanPidana']);
    
    // Surat Pernyataan Tugas Belajar
    $router->get('/pernyataan-tugas-belajar', [SuratController::class, 'showPernyataanTugasBelajar']);
    $router->post('/pernyataan-tugas-belajar', [SuratController::class, 'generatePernyataanTugasBelajar']);
    
    // Surat Persetujuan Pelepasan
    $router->get('/persetujuan-pelepasan', [SuratController::class, 'showPersetujuanPelepasan']);
    $router->post('/persetujuan-pelepasan', [SuratController::class, 'generatePersetujuanPelepasan']);
    
    // Surat Persetujuan Penerimaan
    $router->get('/persetujuan-penerimaan', [SuratController::class, 'showPersetujuanPenerimaan']);
    $router->post('/persetujuan-penerimaan', [SuratController::class, 'generatePersetujuanPenerimaan']);
    
    // Surat SPTJM
    $router->get('/sptjm', [SuratController::class, 'showSptjm']);
    $router->post('/sptjm', [SuratController::class, 'generateSptjm']);
    
    // Surat Keterangan ANJAB ABK
    $router->get('/anjab-abk', [App\Controllers\Surat\SuratAnjabAbkController::class, 'show']);
    $router->post('/anjab-abk', [App\Controllers\Surat\SuratAnjabAbkController::class, 'generate']);
    
    // Preview surat pengalaman mengajar (AJAX)
    $router->post('/pengalaman-mengajar/preview', [App\Controllers\Surat\SuratKeteranganPengalamanMengajarController::class, 'preview']);
    // Preview surat permohonan skbt (AJAX)
    $router->post('/permohonan-skbt/preview', [App\Controllers\Surat\SuratPermohonanSkbtController::class, 'preview']);
    // Preview surat pernyataan disiplin (AJAX)
    $router->post('/pernyataan-disiplin/preview', [App\Controllers\Surat\SuratPernyataanDisiplinController::class, 'preview']);
    // Preview surat pernyataan pidana (AJAX)
    $router->post('/pernyataan-pidana/preview', [App\Controllers\Surat\SuratPernyataanPidanaController::class, 'preview']);
    // Preview surat pernyataan tugas belajar (AJAX)
    $router->post('/pernyataan-tugas-belajar/preview', [App\Controllers\Surat\SuratPernyataanTugasBelajarController::class, 'preview']);
    // Preview surat persetujuan pelepasan (AJAX)
    $router->post('/persetujuan-pelepasan/preview', [App\Controllers\Surat\SuratPersetujuanPelepasanController::class, 'preview']);
    // Preview surat persetujuan penerimaan (AJAX)
    $router->post('/persetujuan-penerimaan/preview', [App\Controllers\Surat\SuratPersetujuanPenerimaanController::class, 'preview']);
    // Preview surat SPTJM (AJAX)
    $router->post('/sptjm/preview', [App\Controllers\Surat\SuratSptjmController::class, 'preview']);
    // Preview surat ANJAB ABK (AJAX)
    $router->post('/anjab-abk/preview', [App\Controllers\Surat\SuratAnjabAbkController::class, 'preview']);
    
    // Download generated letter
    $router->get('/download/{id}', [SuratController::class, 'download']);
    
    // Preview generated letter
    $router->get('/preview/{id}', [SuratController::class, 'preview']);
});

// Route alias agar /surat tanpa slash juga valid
$router->get('/surat', function() {
    return (new \App\Controllers\SuratController())->index();
});

// Pegawai routes - Protected
$router->group(['prefix' => '/pegawai', 'middleware' => ['App\Middleware\AuthMiddleware']], function($router) {
    // Main pegawai page
    $router->get('/', [PegawaiController::class, 'index']);
    
    // DataTable API endpoint
    $router->get('/datatable', [PegawaiController::class, 'datatable']);
    
    // Document count API
    $router->get('/document-count/{nip}', [PegawaiController::class, 'getDocumentCount']);
    
    // Pegawai detail API
    $router->get('/detail/{nip}', [PegawaiController::class, 'getDetail']);
    
    // Statistics API
    $router->get('/statistics', [PegawaiController::class, 'getStatistics']);
});

// Route alias agar /pegawai tanpa slash juga valid
$router->get('/pegawai', function() {
    return (new \App\Controllers\PegawaiController())->index();
});

// API routes - Protected
$router->group(['prefix' => '/api', 'middleware' => ['App\Middleware\AuthMiddleware']], function($router) {
    // API for AJAX requests
    $router->get('/surat-types', [SuratController::class, 'getSuratTypes']);
    $router->post('/validate-form', [SuratController::class, 'validateForm']);
    // Pegawai search API
    $router->get('/pegawai/search', [SuratController::class, 'searchPegawai']);
    $router->get('/pegawai/{nip}', [SuratController::class, 'getPegawai']);
});

// Static file serving (for development)
if (Core\Support\Env::get('APP_ENV') === 'local') {
    $router->get('/css/{file}', function($file) {
        $path = PUBLIC_PATH . '/css/' . $file;
        if (file_exists($path)) {
            header('Content-Type: text/css');
            readfile($path);
        } else {
            http_response_code(404);
        }
    });
    
    $router->get('/js/{file}', function($file) {
        $path = PUBLIC_PATH . '/js/' . $file;
        if (file_exists($path)) {
            header('Content-Type: application/javascript');
            readfile($path);
        } else {
            http_response_code(404);
        }
    });
    
    $router->get('/images/{file}', function($file) {
        $path = PUBLIC_PATH . '/images/' . $file;
        if (file_exists($path)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $path);
            finfo_close($finfo);
            
            header('Content-Type: ' . $mimeType);
            readfile($path);
        } else {
            http_response_code(404);
        }
    });
}

// 404 handler is automatically handled by Router::dispatch()