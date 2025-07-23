<?php
$additionalCSS = '
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<style>
    .dataTables_wrapper {
        padding: 0;
    }
    
    .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    .dataTables_filter input {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.5rem 1rem;
        margin-left: 0.5rem;
    }
    
    .dataTables_filter input:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 0.2rem rgba(74, 124, 89, 0.25);
    }
    
    .dataTables_length select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        margin: 0 0.5rem;
    }
    
    .dataTables_info {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        margin: 0 2px;
        border: none !important;
    }
    
    .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
        color: white !important;
        border: none !important;
    }
    
    .dataTables_paginate .paginate_button:hover {
        background: var(--accent-color) !important;
        color: white !important;
        border: none !important;
    }
    
    .table thead th {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(74, 124, 89, 0.1);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .table tbody td {
        vertical-align: middle;
        border-color: #e9ecef;
        padding: 1rem 0.75rem;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
    }
    
    .dropdown-toggle::after {
        margin-left: 0.5rem;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border-radius: 10px;
        padding: 0.5rem 0;
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    
    .dropdown-item:hover {
        background-color: var(--accent-color);
        color: white;
    }
    
    .dropdown-item i {
        width: 20px;
        margin-right: 0.5rem;
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        display: none;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .stats-card {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stats-label {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            border-radius: 10px;
        }
        
        .dataTables_filter,
        .dataTables_length {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .stats-number {
            font-size: 2rem;
        }
    }
</style>
';

include __DIR__ . '/../layouts/app.php';
?>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-users me-2"></i>Data Pegawai
                    </h1>
                    <p class="text-muted mb-0">Kelola dan lihat informasi pegawai</p>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="refreshTable()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-number" id="totalPegawai">-</div>
                <div class="stats-label">Total Pegawai</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-number" id="totalDokumen">-</div>
                <div class="stats-label">Total Dokumen</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-number" id="pegawaiAktif">-</div>
                <div class="stats-label">Pegawai Aktif</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-number" id="dokumenBulanIni">-</div>
                <div class="stats-label">Dokumen Bulan Ini</div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>Daftar Pegawai
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="pegawaiTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Count Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Dokumen Pegawai
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="documentModalBody">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Pegawai Detail Modal -->
<div class="modal fade" id="pegawaiModal" tabindex="-1" aria-labelledby="pegawaiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pegawaiModalLabel">
                    <i class="fas fa-user me-2"></i>Detail Pegawai
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="pegawaiModalBody">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
let pegawaiTable;

$(document).ready(function() {
    // Initialize DataTable
    initializeDataTable();
    
    // Load statistics
    loadStatistics();
});

function initializeDataTable() {
    pegawaiTable = $('#pegawaiTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '/pegawai/datatable',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTable AJAX error:', error);
                showAlert('Terjadi kesalahan saat memuat data', 'danger');
            }
        },
        columns: [
            { data: 'nip', name: 'nip' },
            { data: 'nama', name: 'nama' },
            { data: 'jabatan', name: 'jabatan' },
            { data: 'unit_kerja', name: 'unit_kerja' },
            { 
                data: 'aksi', 
                name: 'aksi', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            }
        ],
        order: [[1, 'asc']], // Order by nama
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data per halaman',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
            infoFiltered: '(disaring dari _MAX_ total data)',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Selanjutnya',
                previous: 'Sebelumnya'
            },
            emptyTable: 'Tidak ada data yang tersedia',
            zeroRecords: 'Tidak ditemukan data yang sesuai'
        },
        drawCallback: function(settings) {
            // Re-initialize tooltips after table redraw
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
}

function refreshTable() {
    if (pegawaiTable) {
        pegawaiTable.ajax.reload(null, false);
    }
    loadStatistics();
}

function showDocumentCount(nip) {
    showLoading();
    
    fetch(`/pegawai/document-count/${nip}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                displayDocumentModal(data.data);
            } else {
                showAlert(data.message || 'Terjadi kesalahan', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data dokumen', 'danger');
        });
}

function displayDocumentModal(data) {
    const { pegawai, total_documents, documents_by_type, recent_documents } = data;
    
    let html = `
        <div class="row mb-3">
            <div class="col-md-6">
                <h6><strong>Nama:</strong> ${pegawai.nama}</h6>
                <p class="text-muted mb-1"><strong>NIP:</strong> ${pegawai.nip}</p>
                <p class="text-muted mb-1"><strong>Jabatan:</strong> ${pegawai.jabatan || '-'}</p>
                <p class="text-muted"><strong>Unit Kerja:</strong> ${pegawai.unit_kerja || '-'}</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="bg-primary text-white p-3 rounded">
                    <h3 class="mb-0">${total_documents}</h3>
                    <small>Total Dokumen</small>
                </div>
            </div>
        </div>
        
        <hr>
        
        <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Dokumen per Jenis</h6>
    `;
    
    if (documents_by_type.length > 0) {
        html += '<div class="row">';
        documents_by_type.forEach(doc => {
            html += `
                <div class="col-md-6 mb-2">
                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                        <span>${doc.jenis_surat || 'Tidak Diketahui'}</span>
                        <span class="badge bg-primary">${doc.jumlah}</span>
                    </div>
                </div>
            `;
        });
        html += '</div>';
    } else {
        html += '<p class="text-muted">Belum ada dokumen yang dibuat.</p>';
    }
    
    if (recent_documents.length > 0) {
        html += `
            <hr>
            <h6 class="mb-3"><i class="fas fa-clock me-2"></i>Dokumen Terbaru</h6>
            <div class="list-group list-group-flush">
        `;
        
        recent_documents.forEach(doc => {
            const date = new Date(doc.created_at).toLocaleDateString('id-ID');
            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${doc.jenis_nama || 'Tidak Diketahui'}</h6>
                        <small class="text-muted">${date}</small>
                    </div>
                    <span class="badge bg-secondary">${doc.nomor_surat || 'No. Surat'}</span>
                </div>
            `;
        });
        
        html += '</div>';
    }
    
    document.getElementById('documentModalBody').innerHTML = html;
    new bootstrap.Modal(document.getElementById('documentModal')).show();
}

function viewPegawai(nip) {
    showLoading();
    
    fetch(`/pegawai/detail/${nip}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                displayPegawaiModal(data.data);
            } else {
                showAlert(data.message || 'Terjadi kesalahan', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat detail pegawai', 'danger');
        });
}

function displayPegawaiModal(pegawai) {
    const html = `
        <div class="row">
            <div class="col-12">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>NIP</strong></td>
                        <td>: ${pegawai.nip}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>: ${pegawai.nama}</td>
                    </tr>
                    <tr>
                        <td><strong>Golongan</strong></td>
                        <td>: ${pegawai.golongan || '-'}</td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan</strong></td>
                        <td>: ${pegawai.jabatan || '-'}</td>
                    </tr>
                    <tr>
                        <td><strong>Unit Kerja</strong></td>
                        <td>: ${pegawai.unit_kerja || '-'}</td>
                    </tr>
                    <tr>
                        <td><strong>Induk Unit</strong></td>
                        <td>: ${pegawai.induk_unit || '-'}</td>
                    </tr>
                    <tr>
                        <td><strong>TMT Pensiun</strong></td>
                        <td>: ${pegawai.tmt_pensiun || '-'}</td>
                    </tr>
                </table>
            </div>
        </div>
    `;
    
    document.getElementById('pegawaiModalBody').innerHTML = html;
    new bootstrap.Modal(document.getElementById('pegawaiModal')).show();
}

function loadStatistics() {
    // This would typically load from an API endpoint
    // For now, we'll use placeholder values
    document.getElementById('totalPegawai').textContent = '-';
    document.getElementById('totalDokumen').textContent = '-';
    document.getElementById('pegawaiAktif').textContent = '-';
    document.getElementById('dokumenBulanIni').textContent = '-';
    
    // You can implement actual API calls here
    fetch('/pegawai/statistics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalPegawai').textContent = data.total_pegawai || '0';
                document.getElementById('totalDokumen').textContent = data.total_dokumen || '0';
                document.getElementById('pegawaiAktif').textContent = data.pegawai_aktif || '0';
                document.getElementById('dokumenBulanIni').textContent = data.dokumen_bulan_ini || '0';
            }
        })
        .catch(error => {
            console.log('Statistics not available:', error);
        });
}

function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

function showAlert(message, type = 'info') {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the top of the container
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>