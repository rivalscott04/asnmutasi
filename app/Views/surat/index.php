<?php
ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold">Jenis Surat Tersedia</h1>
                <p class="lead text-muted">Pilih jenis surat yang ingin Anda buat</p>
                
                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="/surat/history" class="btn btn-outline-primary">
                        <i class="fas fa-history me-2"></i>
                        Riwayat Surat
                    </a>
                    <a href="/dashboard-daerah" class="btn btn-outline-secondary">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <?php foreach ($surat_types as $index => $surat): ?>
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <div class="feature-icon mx-auto">
                            <i class="<?= $surat['icon'] ?>"></i>
                        </div>
                    </div>
                    
                    <h5 class="card-title mb-3"><?= htmlspecialchars($surat['title']) ?></h5>
                    <p class="card-text text-muted mb-4"><?= htmlspecialchars($surat['description']) ?></p>
                    
                    <div class="d-grid gap-2">
                        <a href="<?= $surat['url'] ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Buat Surat
                        </a>
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal<?= $index ?>">
                            <i class="fas fa-info-circle me-1"></i>
                            Info Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Info Modal -->
        <div class="modal fade" id="infoModal<?= $index ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="<?= $surat['icon'] ?> me-2"></i>
                            <?= htmlspecialchars($surat['title']) ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="<?= $surat['icon'] ?>"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="fw-bold">Deskripsi:</h6>
                                <p class="text-muted"><?= htmlspecialchars($surat['description']) ?></p>
                                
                                <h6 class="fw-bold mt-3">Kegunaan:</h6>
                                <ul class="text-muted">
                                    <?php 
                                    $kegunaan = [
                                        'pengalaman-mengajar' => [
                                            'Untuk keperluan mutasi jabatan',
                                            'Sebagai bukti pengalaman mengajar',
                                            'Persyaratan administrasi'
                                        ],
                                        'permohonan-skbt' => [
                                            'Permohonan Surat Keterangan Bebas Tugas',
                                            'Untuk keperluan mutasi',
                                            'Persyaratan administrasi'
                                        ],
                                        'pernyataan-disiplin' => [
                                            'Menyatakan tidak pernah dijatuhi hukuman disiplin',
                                            'Persyaratan mutasi',
                                            'Kelengkapan berkas'
                                        ],
                                        'pernyataan-pidana' => [
                                            'Menyatakan tidak pernah dipidana',
                                            'Persyaratan mutasi',
                                            'Kelengkapan berkas'
                                        ],
                                        'pernyataan-tugas-belajar' => [
                                            'Menyatakan tidak sedang menjalankan tugas belajar',
                                            'Persyaratan mutasi',
                                            'Kelengkapan berkas'
                                        ],
                                        'persetujuan-pelepasan' => [
                                            'Persetujuan pelepasan pegawai',
                                            'Untuk keperluan mutasi keluar',
                                            'Dokumen resmi'
                                        ],
                                        'persetujuan-penerimaan' => [
                                            'Persetujuan penerimaan pegawai',
                                            'Untuk keperluan mutasi masuk',
                                            'Dokumen resmi'
                                        ],
                                        'sptjm' => [
                                            'Surat Pernyataan Tanggung Jawab Mutlak',
                                            'Persyaratan mutasi',
                                            'Dokumen resmi'
                                        ],
                                        'anjab-abk' => [
                                            'Surat keterangan ANJAB ABK untuk PNS',
                                            'Analisis jabatan dan beban kerja',
                                            'Persyaratan mutasi'
                                        ]
                                    ];
                                    
                                    $currentKegunaan = $kegunaan[$surat['id']] ?? ['Dokumen resmi untuk keperluan mutasi'];
                                    foreach ($currentKegunaan as $item): 
                                    ?>
                                    <li><?= $item ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                
                                <h6 class="fw-bold mt-3">Data yang Diperlukan:</h6>
                                <div class="text-muted small">
                                    <?php 
                                    $dataDiperlukan = [
                                        'pengalaman-mengajar' => 'Data pegawai, data pejabat, nomor surat, tanggal surat',
                                        'permohonan-skbt' => 'Data pegawai, data pejabat, nomor surat, tanggal surat',
                                        'pernyataan-disiplin' => 'Data pegawai, tempat/tanggal lahir, pangkat/golongan, jabatan',
                                        'pernyataan-pidana' => 'Data pegawai, tempat/tanggal lahir, pangkat/golongan, jabatan',
                                        'pernyataan-tugas-belajar' => 'Data pegawai, tempat/tanggal lahir, pangkat/golongan, jabatan',
                                        'persetujuan-pelepasan' => 'Data pegawai, data pejabat, nomor surat, tanggal surat',
                                        'persetujuan-penerimaan' => 'Data pegawai, data pejabat, nomor surat, tanggal surat',
                                        'sptjm' => 'Data pegawai, data pejabat, nomor surat, tanggal surat',
                                        'anjab-abk' => 'Data pegawai, data pejabat, nomor surat, tanggal surat'
                                    ];
                                    echo $dataDiperlukan[$surat['id']] ?? 'Data pegawai dan data pendukung lainnya';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="<?= $surat['url'] ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Buat Surat
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Help Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body text-center p-5">
                    <h4 class="mb-3">
                        <i class="fas fa-question-circle text-primary me-2"></i>
                        Butuh Bantuan?
                    </h4>
                    <p class="text-muted mb-4">Jika Anda memerlukan bantuan dalam pembuatan surat atau memiliki pertanyaan, silakan hubungi administrator sistem.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="mailto:admin@asnmutasi.com" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>
                            Email Admin
                        </a>
                        <a href="tel:+6281234567890" class="btn btn-outline-success">
                            <i class="fas fa-phone me-2"></i>
                            Telepon
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$additionalCSS = '
<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin: 0 auto;
}

.modal .feature-icon {
    width: 100px;
    height: 100px;
    font-size: 2.5rem;
}
</style>
';

$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>