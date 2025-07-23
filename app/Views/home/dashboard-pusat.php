<?php
ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6 fw-bold">Dashboard Pusat</h1>
                    <p class="text-muted mb-0">Selamat datang, <?= htmlspecialchars($user) ?>!</p>
                    <small class="badge bg-secondary">Role: <?= ucfirst($role) ?> (Read Only)</small>
                </div>
                <div>
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-circle me-1"></i>
                        Online
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Surat</h6>
                            <h2 class="mb-0"><?= $stats['total_surat'] ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-file-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Surat Dibuat</h6>
                            <h2 class="mb-0"><?= $stats['surat_dibuat'] ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-eye fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Template Tersedia</h6>
                            <h2 class="mb-0"><?= count($surat_types) ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-file-contract fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-muted text-white" style="background-color: #6c757d;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Mode Akses</h6>
                            <h2 class="mb-0">View</h2>
                        </div>
                        <div>
                            <i class="fas fa-lock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Read Only Notice -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-3 fa-2x"></i>
                <div>
                    <h5 class="alert-heading mb-1">Mode Read-Only</h5>
                    <p class="mb-0">Sebagai user pusat, Anda hanya dapat melihat data surat yang telah dibuat. Untuk membuat surat baru atau mengelola data, silakan hubungi administrator kanwil.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Only Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Aksi Monitoring - Pusat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" style="cursor: not-allowed;" title="Hanya dapat melihat data">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <span class="fw-bold text-center">Lihat Laporan</span>
                                <small class="text-muted">(View Only)</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" style="cursor: not-allowed;" title="Hanya dapat melihat data">
                                <i class="fas fa-file-alt fa-2x mb-2"></i>
                                <span class="fw-bold text-center">Monitor Surat</span>
                                <small class="text-muted">(View Only)</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" style="cursor: not-allowed;" title="Hanya dapat melihat data">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <span class="fw-bold text-center">Lihat User</span>
                                <small class="text-muted">(View Only)</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" style="cursor: not-allowed;" title="Hanya dapat melihat data">
                                <i class="fas fa-database fa-2x mb-2"></i>
                                <span class="fw-bold text-center">Monitor Sistem</span>
                                <small class="text-muted">(View Only)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Available Letter Types (View Only) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-contract me-2"></i>
                        Template Surat (Monitoring)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <?php foreach ($surat_types as $surat): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm bg-light">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="<?= $surat['icon'] ?> fa-3x text-secondary"></i>
                                    </div>
                                    <h6 class="card-title text-muted"><?= htmlspecialchars($surat['title']) ?></h6>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($surat['description']) ?></p>
                                    <button class="btn btn-secondary btn-sm" disabled title="Mode read-only">
                                        <i class="fas fa-eye me-1"></i>
                                        View Only
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity & System Info -->
    <div class="row mt-5">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Aktivitas Terbaru (Monitoring)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['recent_letters'])): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada aktivitas</p>
                        <small>Data aktivitas akan muncul ketika ada surat yang dibuat</small>
                    </div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['recent_letters'] as $letter): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold text-muted"><?= htmlspecialchars($letter['jenis_nama'] ?? 'Surat') ?></div>
                                <small class="text-muted">
                                    <?= htmlspecialchars($letter['pegawai_nama'] ?? 'N/A') ?> • 
                                    <?= date('d/m/Y H:i', strtotime($letter['created_at'])) ?>
                                </small>
                            </div>
                            <span class="badge bg-secondary rounded-pill">
                                Viewed
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Pusat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-secondary">Hak Akses Read-Only</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2">
                                <i class="fas fa-eye text-info me-2"></i>
                                Melihat semua data surat
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-chart-bar text-info me-2"></i>
                                Monitoring aktivitas sistem
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-file-alt text-info me-2"></i>
                                Akses laporan dan statistik
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-times text-danger me-2"></i>
                                Tidak dapat membuat/edit data
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-secondary">Statistik Monitoring</h6>
                        <p class="small text-muted mb-2">Total template: <?= count($surat_types) ?></p>
                        <p class="small text-muted mb-2">Surat terpantau: <?= $stats['surat_dibuat'] ?></p>
                        <p class="small text-muted mb-2">Mode: Read-Only</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-secondary">Kontak Admin</h6>
                        <p class="small text-muted mb-2">Untuk membuat surat atau mengelola data, hubungi administrator kanwil.</p>
                        <a href="mailto:admin@asnmutasi.com" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-envelope me-1"></i>
                            Hubungi Admin Kanwil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>