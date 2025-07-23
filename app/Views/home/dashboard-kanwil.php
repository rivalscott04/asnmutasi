<?php
ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6 fw-bold">Dashboard Kanwil/SuperAdmin</h1>
                    <p class="text-muted mb-0">Selamat datang, <?= htmlspecialchars($user) ?>!</p>
                    <small class="badge bg-warning">Role: <?= ucfirst($role) ?></small>
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
            <div class="card bg-primary text-white">
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
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Surat Dibuat</h6>
                            <h2 class="mb-0"><?= $stats['surat_dibuat'] ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
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
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Users</h6>
                            <h2 class="mb-0"><?= $stats['total_users'] ?? 0 ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admin Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Aksi Admin - Manajemen Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="#" class="btn btn-outline-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" onclick="alert('Fitur manajemen user akan segera tersedia')">
                                <i class="fas fa-users-cog fa-2x mb-2"></i>
                                <span class="fw-bold text-center">Kelola User</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="#" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" onclick="alert('Fitur laporan akan segera tersedia')">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <span class="fw-bold text-center">Laporan</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="#" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" onclick="alert('Fitur backup akan segera tersedia')">
                                <i class="fas fa-database fa-2x mb-2"></i>
                                <span class="fw-bold text-center">Backup Data</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="/settings" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-cog fa-2x mb-2"></i>
                                <span class="fw-bold text-center">Pengaturan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions for Letters -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Aksi Cepat - Surat Kanwil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach (array_slice($surat_types, 0, 4) as $surat): ?>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?= $surat['url'] ?>" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="<?= $surat['icon'] ?> fa-2x mb-2"></i>
                                <span class="fw-bold text-center"><?= htmlspecialchars($surat['title']) ?></span>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Available Letter Types -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-contract me-2"></i>
                        Jenis Surat Tersedia untuk Kanwil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <?php foreach ($surat_types as $surat): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="<?= $surat['icon'] ?> fa-3x text-primary"></i>
                                    </div>
                                    <h6 class="card-title"><?= htmlspecialchars($surat['title']) ?></h6>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($surat['description']) ?></p>
                                    <a href="<?= $surat['url'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i>
                                        Buat Surat
                                    </a>
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
                        Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['recent_letters'])): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada aktivitas</p>
                        <small>Mulai buat surat untuk melihat aktivitas</small>
                    </div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['recent_letters'] as $letter): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold"><?= htmlspecialchars($letter['jenis_nama'] ?? 'Surat') ?></div>
                                <small class="text-muted">
                                    <?= htmlspecialchars($letter['pegawai_nama'] ?? 'N/A') ?> • 
                                    <?= date('d/m/Y H:i', strtotime($letter['created_at'])) ?>
                                </small>
                            </div>
                            <span class="badge bg-<?= $letter['status'] === 'generated' ? 'success' : ($letter['status'] === 'draft' ? 'warning' : 'secondary') ?> rounded-pill">
                                <?= ucfirst($letter['status']) ?>
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
                        <i class="fas fa-shield-alt me-2"></i>
                        Informasi Admin
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-warning">Hak Akses SuperAdmin</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Akses semua jenis surat
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Kelola user dan role
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Lihat laporan sistem
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Backup dan restore data
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-warning">Statistik Sistem</h6>
                        <p class="small text-muted mb-2">Total template: <?= count($surat_types) ?></p>
                        <p class="small text-muted mb-2">Total users: <?= $stats['total_users'] ?? 0 ?></p>
                        <p class="small text-muted mb-2">Surat dibuat: <?= $stats['surat_dibuat'] ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-warning">Kontak Support</h6>
                        <p class="small text-muted mb-2">Sebagai admin, Anda dapat menghubungi support teknis.</p>
                        <a href="mailto:support@asnmutasi.com" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-headset me-1"></i>
                            Support Teknis
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