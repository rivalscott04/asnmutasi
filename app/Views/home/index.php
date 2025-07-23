<?php
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-normal mb-4">Sistem ASN Mutasi</h1>
                <p class="lead mb-4" style="font-weight: 400;"><?= $description ?></p>
                <div class="d-flex gap-3">
                    <a href="/surat" class="btn btn-light btn-lg">
                        <i class="fas fa-file-alt me-2"></i>
                        Buat Surat
                    </a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="/login" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login
                    </a>
                    <?php else: ?>
                    <?php 
                        $dashboardUrl = '/dashboard-daerah'; // default
                        if (isset($_SESSION['user_role'])) {
                            switch ($_SESSION['user_role']) {
                                case 'kanwil':
                                    $dashboardUrl = '/dashboard-kanwil';
                                    break;
                                case 'pusat':
                                    $dashboardUrl = '/dashboard-pusat';
                                    break;
                                case 'daerah':
                                default:
                                    $dashboardUrl = '/dashboard-daerah';
                                    break;
                            }
                        }
                    ?>
                    <a href="<?= $dashboardUrl ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="feature-icon mx-auto" style="width: 200px; height: 200px; font-size: 4rem;">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-normal mb-3">Fitur Unggulan</h2>
                <p class="lead text-muted" style="font-weight: 400;">Sistem yang memudahkan pembuatan berbagai jenis surat untuk keperluan mutasi ASN</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h5 class="card-title">Otomatis</h5>
                        <p class="card-text">Pembuatan surat secara otomatis dengan template yang sudah tersedia</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="card-title">Aman</h5>
                        <p class="card-text">Data tersimpan dengan aman dan sesuai standar keamanan</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="card-title">Cepat</h5>
                        <p class="card-text">Proses pembuatan surat yang cepat dan efisien</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Available Letters Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-normal mb-3">Jenis Surat Tersedia</h2>
                <p class="lead text-muted" style="font-weight: 400;">Berbagai jenis surat yang dapat dibuat melalui sistem ini</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chalkboard-teacher fa-3x text-primary mb-3"></i>
                        <h6 class="card-title">Surat Keterangan Pengalaman Mengajar</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                        <h6 class="card-title">Surat Permohonan SKBT</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h6 class="card-title">Surat Pernyataan Disiplin</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-gavel fa-3x text-primary mb-3"></i>
                        <h6 class="card-title">Surat Pernyataan Pidana</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                        <h6 class="card-title">Surat Pernyataan Tugas Belajar</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-sign-out-alt fa-3x text-primary mb-3"></i>
                        <h6 class="card-title">Surat Persetujuan Pelepasan</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h6 class="card-title">Surat Persetujuan Penerimaan</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-file-signature fa-3x text-primary mb-3"></i>
                        <h6 class="card-title">Surat Pertanggung Jawaban Mutlak</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <a href="/surat" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-right me-2"></i>
                Lihat Semua Jenis Surat
            </a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-normal mb-3">Cara Kerja</h2>
                <p class="lead text-muted" style="font-weight: 400;">Langkah mudah untuk membuat surat</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon">
                        <span class="fw-bold">1</span>
                    </div>
                    <h5>Pilih Jenis Surat</h5>
                    <p class="text-muted">Pilih jenis surat yang ingin dibuat dari daftar yang tersedia</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon">
                        <span class="fw-bold">2</span>
                    </div>
                    <h5>Isi Data</h5>
                    <p class="text-muted">Lengkapi formulir dengan data yang diperlukan</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon">
                        <span class="fw-bold">3</span>
                    </div>
                    <h5>Download Surat</h5>
                    <p class="text-muted">Surat siap diunduh dan digunakan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>