<?php
ob_start();
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Riwayat Surat</h1>
                    <p class="text-muted mb-0">Daftar semua surat yang telah digenerate</p>
                </div>
                <div>
                    <a href="/surat" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>
                        Buat Surat Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (empty($surat_list)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Surat</h5>
                    <p class="text-muted mb-4">Anda belum membuat surat apapun. Mulai buat surat pertama Anda!</p>
                    <a href="/surat" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Buat Surat Pertama
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Surat</h6>
                            <h3 class="mb-0"><?= $total_records ?></h3>
                        </div>
                        <i class="fas fa-file-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Surat Generated</h6>
                            <h3 class="mb-0"><?= count(array_filter($surat_list, function($s) { return $s['status'] === 'generated'; })) ?></h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Halaman</h6>
                            <h3 class="mb-0"><?= $current_page ?> / <?= $total_pages ?></h3>
                        </div>
                        <i class="fas fa-list fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Surat List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Daftar Surat
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Surat</th>
                                    <th>Jenis Surat</th>
                                    <th>Pegawai</th>
                                    <th>Pejabat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($surat_list as $index => $surat): ?>
                                <tr>
                                    <td><?= (($current_page - 1) * 10) + $index + 1 ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($surat['nomor_surat']) ?></strong>
                                        <?php if ($surat['judul']): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($surat['judul']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($surat['jenis_nama'] ?? 'Unknown') ?></span>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($surat['pegawai_nama'] ?? 'Unknown') ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($surat['pegawai_nip']) ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($surat['pejabat_nama'] ?? 'Unknown') ?>
                                        <?php if ($surat['pejabat_penandatangan_nip']): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($surat['pejabat_penandatangan_nip']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($surat['tanggal_surat'])) ?>
                                        <br><small class="text-muted"><?= date('H:i', strtotime($surat['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                        $statusClass = [
                                            'draft' => 'bg-warning',
                                            'generated' => 'bg-success',
                                            'signed' => 'bg-primary'
                                        ];
                                        $statusText = [
                                            'draft' => 'Draft',
                                            'generated' => 'Generated',
                                            'signed' => 'Signed'
                                        ];
                                        ?>
                                        <span class="badge <?= $statusClass[$surat['status']] ?? 'bg-secondary' ?>">
                                            <?= $statusText[$surat['status']] ?? ucfirst($surat['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <?php if (isset($surat['preview_url'])): ?>
                                            <a href="<?= $surat['preview_url'] ?>" class="btn btn-outline-primary" title="Lihat Surat" target="_blank">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <?php endif; ?>
                                            <?php if (isset($surat['download_url'])): ?>
                                            <a href="<?= $surat['download_url'] ?>" class="btn btn-outline-success" title="Download PDF">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <?php endif; ?>
                                            <button class="btn btn-outline-info" onclick="showDetails(<?= htmlspecialchars(json_encode($surat)) ?>)" title="Detail">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($current_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $current_page - 1 ?>">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                    <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $current_page + 1 ?>">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php endif; ?>
    
    <?php endif; ?>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detail Surat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetails(surat) {
    const content = document.getElementById('detailContent');
    
    let dataHtml = '';
    if (surat.data_surat) {
        try {
            const data = typeof surat.data_surat === 'string' ? JSON.parse(surat.data_surat) : surat.data_surat;
            dataHtml = '<h6>Data Form:</h6><div class="row">';
            
            Object.keys(data).forEach(key => {
                if (data[key] && key !== 'csrf_token') {
                    dataHtml += `
                        <div class="col-md-6 mb-2">
                            <strong>${key}:</strong><br>
                            <span class="text-muted">${data[key]}</span>
                        </div>
                    `;
                }
            });
            
            dataHtml += '</div>';
        } catch (e) {
            dataHtml = '<p class="text-muted">Data tidak dapat ditampilkan</p>';
        }
    }
    
    content.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Informasi Surat</h6>
                <table class="table table-sm">
                    <tr><td><strong>Nomor Surat:</strong></td><td>${surat.nomor_surat}</td></tr>
                    <tr><td><strong>Jenis:</strong></td><td>${surat.jenis_nama || 'Unknown'}</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge bg-success">${surat.status}</span></td></tr>
                    <tr><td><strong>Tanggal Surat:</strong></td><td>${new Date(surat.tanggal_surat).toLocaleDateString('id-ID')}</td></tr>
                    <tr><td><strong>Dibuat:</strong></td><td>${new Date(surat.created_at).toLocaleString('id-ID')}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Informasi Pegawai</h6>
                <table class="table table-sm">
                    <tr><td><strong>Nama Pegawai:</strong></td><td>${surat.pegawai_nama || 'Unknown'}</td></tr>
                    <tr><td><strong>NIP Pegawai:</strong></td><td>${surat.pegawai_nip}</td></tr>
                    <tr><td><strong>Pejabat:</strong></td><td>${surat.pejabat_nama || 'Unknown'}</td></tr>
                    <tr><td><strong>NIP Pejabat:</strong></td><td>${surat.pejabat_penandatangan_nip || '-'}</td></tr>
                </table>
            </div>
        </div>
        <hr>
        ${dataHtml}
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
}
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>