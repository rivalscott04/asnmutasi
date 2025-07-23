<?php
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Surat Persetujuan Pelepasan
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Isi data berikut untuk membuat surat persetujuan pelepasan pegawai</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Terdapat kesalahan:</h6>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <form id="suratForm" method="POST" action="/surat/persetujuan-pelepasan">

                        
                        <!-- Data Surat -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-success">
                                <i class="fas fa-file-alt me-2"></i>
                                Data Surat
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nosrt" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nosrt" name="nosrt" 
                                       value="<?= htmlspecialchars($old['nosrt'] ?? '') ?>" 
                                       placeholder="Contoh: 001" required>
                            </div>
                            <div class="col-md-4">
                                <label for="blnno" class="form-label">Bulan <span class="text-danger">*</span></label>
                                <select class="form-select" id="blnno" name="blnno" required>
                                    <option value="">Pilih Bulan</option>
                                    <option value="01" <?= ($old['blnno'] ?? '') == '01' ? 'selected' : '' ?>>01 (Januari)</option>
                                    <option value="02" <?= ($old['blnno'] ?? '') == '02' ? 'selected' : '' ?>>02 (Februari)</option>
                                    <option value="03" <?= ($old['blnno'] ?? '') == '03' ? 'selected' : '' ?>>03 (Maret)</option>
                                    <option value="04" <?= ($old['blnno'] ?? '') == '04' ? 'selected' : '' ?>>04 (April)</option>
                                    <option value="05" <?= ($old['blnno'] ?? '') == '05' ? 'selected' : '' ?>>05 (Mei)</option>
                                    <option value="06" <?= ($old['blnno'] ?? '') == '06' ? 'selected' : '' ?>>06 (Juni)</option>
                                    <option value="07" <?= ($old['blnno'] ?? '') == '07' ? 'selected' : '' ?>>07 (Juli)</option>
                                    <option value="08" <?= ($old['blnno'] ?? '') == '08' ? 'selected' : '' ?>>08 (Agustus)</option>
                                    <option value="09" <?= ($old['blnno'] ?? '') == '09' ? 'selected' : '' ?>>09 (September)</option>
                                    <option value="10" <?= ($old['blnno'] ?? '') == '10' ? 'selected' : '' ?>>10 (Oktober)</option>
                                    <option value="11" <?= ($old['blnno'] ?? '') == '11' ? 'selected' : '' ?>>11 (November)</option>
                                    <option value="12" <?= ($old['blnno'] ?? '') == '12' ? 'selected' : '' ?>>12 (Desember)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="thnno" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="thnno" name="thnno" 
                                       value="<?= htmlspecialchars($old['thnno'] ?? date('Y')) ?>" 
                                       min="2020" max="2030" maxlength="4" required>
                            </div>
                        </div>
                        
                        <!-- Tanggal Surat -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-success">
                                <i class="fas fa-calendar me-2"></i>
                                Tanggal Surat
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dd-mm-yyyy" class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="dd-mm-yyyy" name="dd-mm-yyyy" 
                                   value="<?= htmlspecialchars($old['dd-mm-yyyy'] ?? date('Y-m-d')) ?>" required>
                        </div>
                        
                        <!-- Data Pegawai -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-success">
                                <i class="fas fa-user me-2"></i>
                                Data Pegawai
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="namapegawai" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <select class="form-select" id="namapegawai" name="namapegawai" required>
                                    <option value="">Pilih atau cari pegawai...</option>
                                    <?php if (!empty($old['namapegawai'])): ?>
                                    <option value="<?= htmlspecialchars($old['namapegawai']) ?>" selected><?= htmlspecialchars($old['namapegawai']) ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nippegawai" class="form-label">NIP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nippegawai" name="nippegawai" 
                                       value="<?= htmlspecialchars($old['nippegawai'] ?? '') ?>" 
                                       placeholder="NIP pegawai" required readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pangkatgolpegawai" class="form-label">Pangkat/Golongan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pangkatgolpegawai" name="pangkatgolpegawai" 
                                       value="<?= htmlspecialchars($old['pangkatgolpegawai'] ?? '') ?>" 
                                       placeholder="Contoh: Penata Muda, III/a" required readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatanpegawai" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatanpegawai" name="jabatanpegawai" 
                                       value="<?= htmlspecialchars($old['jabatanpegawai'] ?? '') ?>" 
                                       placeholder="Jabatan saat ini" required readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tempattugas" class="form-label">Satuan Kerja <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tempattugas" name="tempattugas" 
                                   value="<?= htmlspecialchars($old['tempattugas'] ?? '') ?>" 
                                   placeholder="Nama satuan kerja" required>
                        </div>
                        
                        <!-- Data Tujuan Mutasi -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-success">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Data Tujuan Mutasi
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jabatnpegawai2" class="form-label">Jabatan Tujuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatnpegawai2" name="jabatnpegawai2" 
                                       value="<?= htmlspecialchars($old['jabatnpegawai2'] ?? '') ?>" 
                                       placeholder="Jabatan di tempat tujuan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tempattugas2" class="form-label">Satuan Kerja Tujuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tempattugas2" name="tempattugas2" 
                                       value="<?= htmlspecialchars($old['tempattugas2'] ?? '') ?>" 
                                       placeholder="Nama satuan kerja tujuan" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="kabataukotatujuan" class="form-label">Kabupaten/Kota Tujuan <span class="text-danger">*</span></label>
                            <select class="form-select" id="kabataukotatujuan" name="kabataukotatujuan" required>
                                <option value="">Pilih Kabupaten/Kota Tujuan</option>
                                <option value="Kabupaten Bima" <?= ($old['kabataukotatujuan'] ?? '') == 'Kabupaten Bima' ? 'selected' : '' ?>>Kabupaten Bima</option>
                                <option value="Kabupaten Dompu" <?= ($old['kabataukotatujuan'] ?? '') == 'Kabupaten Dompu' ? 'selected' : '' ?>>Kabupaten Dompu</option>
                                <option value="Kabupaten Lombok Barat" <?= ($old['kabataukotatujuan'] ?? '') == 'Kabupaten Lombok Barat' ? 'selected' : '' ?>>Kabupaten Lombok Barat</option>
                                <option value="Kabupaten Lombok Tengah" <?= ($old['kabataukotatujuan'] ?? '') == 'Kabupaten Lombok Tengah' ? 'selected' : '' ?>>Kabupaten Lombok Tengah</option>
                                <option value="Kabupaten Lombok Timur" <?= ($old['kabataukotatujuan'] ?? '') == 'Kabupaten Lombok Timur' ? 'selected' : '' ?>>Kabupaten Lombok Timur</option>
                                <option value="Kabupaten Lombok Utara" <?= ($old['kabataukotatujuan'] ?? '') == 'Kabupaten Lombok Utara' ? 'selected' : '' ?>>Kabupaten Lombok Utara</option>
                                <option value="Kabupaten Sumbawa" <?= ($old['kabataukotatujuan'] ?? '') == 'Kabupaten Sumbawa' ? 'selected' : '' ?>>Kabupaten Sumbawa</option>
                                <option value="Kabupaten Sumbawa Barat" <?= ($old['kabataukotatujuan'] ?? '') == 'Kabupaten Sumbawa Barat' ? 'selected' : '' ?>>Kabupaten Sumbawa Barat</option>
                                <option value="Kota Bima" <?= ($old['kabataukotatujuan'] ?? '') == 'Kota Bima' ? 'selected' : '' ?>>Kota Bima</option>
                                <option value="Kota Mataram" <?= ($old['kabataukotatujuan'] ?? '') == 'Kota Mataram' ? 'selected' : '' ?>>Kota Mataram</option>
                            </select>
                        </div>
                        
                        <!-- Data Pejabat Penandatangan -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-success">
                                <i class="fas fa-user-tie me-2"></i>
                                Data Pejabat Penandatangan
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="namapejabat" class="form-label">Nama Pejabat <span class="text-danger">*</span></label>
                                <select class="form-select" id="namapejabat" name="namapejabat" required>
                                    <option value="">Pilih atau cari pejabat...</option>
                                    <?php if (!empty($old['namapejabat'])): ?>
                                    <option value="<?= htmlspecialchars($old['namapejabat']) ?>" selected><?= htmlspecialchars($old['namapejabat']) ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nippejabat" class="form-label">NIP Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nippejabat" name="nippejabat" 
                                       value="<?= htmlspecialchars($old['nippejabat'] ?? '') ?>" 
                                       placeholder="NIP pejabat" required readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pangkatgolpejabat" class="form-label">Pangkat/Golongan Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pangkatgolpejabat" name="pangkatgolpejabat" 
                                       value="<?= htmlspecialchars($old['pangkatgolpejabat'] ?? '') ?>" 
                                       placeholder="Contoh: Pembina, IV/a" required readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatanpejabat" class="form-label">Jabatan Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatanpejabat" name="jabatanpejabat" 
                                       value="<?= htmlspecialchars($old['jabatanpejabat'] ?? '') ?>" 
                                       placeholder="Jabatan pejabat" required readonly>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-file-download me-2"></i>
                                Generate Surat Persetujuan Pelepasan
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Pastikan semua data yang diisi sudah benar sebelum generate surat
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.section-header h5 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.section-header hr {
    margin-top: 0.5rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.alert {
    border-radius: 10px;
    border: none;
}

.text-success {
    color: #28a745 !important;
}

@media (max-width: 768px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
}
</style>

<!-- Select2 CSS and JS are loaded in the layout file -->

<script>
// Ensure jQuery is loaded before executing
jQuery(document).ready(function($) {
    // Initialize Select2 for pegawai
    $('#namapegawai').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik nama atau NIP pegawai...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '/api/pegawai/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                
                if (data.success) {
                    return {
                        results: data.data.items,
                        pagination: {
                            more: data.data.incomplete_results
                        }
                    };
                } else {
                    return {
                        results: []
                    };
                }
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        const data = e.params.data.data;
        if (data && data.nip) $('#nippegawai').val(data.nip);
        if (data && data.golongan) $('#pangkatgolpegawai').val(data.golongan);
        if (data && data.jabatan) $('#jabatanpegawai').val(data.jabatan);
        if (data && data.unit_kerja) $('#tempattugas').val(data.unit_kerja);
    }).on('select2:clear', function (e) {
        $('#nippegawai').val('');
        $('#pangkatgolpegawai').val('');
        $('#jabatanpegawai').val('');
        $('#tempattugas').val('');
    });

    // Initialize Select2 for pejabat
    $('#namapejabat').select2({
        theme: 'bootstrap-5',
        placeholder: 'Ketik nama atau NIP pejabat...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '/api/pegawai/search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                
                if (data.success) {
                    return {
                        results: data.data.items,
                        pagination: {
                            more: data.data.incomplete_results
                        }
                    };
                } else {
                    return {
                        results: []
                    };
                }
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        const data = e.params.data.data;
        if (data && data.nip) $('#nippejabat').val(data.nip);
        if (data && data.golongan) $('#pangkatgolpejabat').val(data.golongan);
        if (data && data.jabatan) $('#jabatanpejabat').val(data.jabatan);
    }).on('select2:clear', function (e) {
        $('#nippejabat').val('');
        $('#pangkatgolpejabat').val('');
        $('#jabatanpejabat').val('');
    });

    // Form validation
    $('#suratForm').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // NIP validation removed - handled by server-side validation
        
        // Validate email format
        const email = $('#email').val().trim();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            isValid = false;
            $('#email').addClass('is-invalid');
            showAlert('Format email tidak valid', 'danger');
        }
        
        if (!isValid) {
            e.preventDefault();
            showAlert('Mohon lengkapi semua field yang diperlukan dengan benar', 'danger');
            return false;
        }
        
        // Show loading
        showLoading();
    });
    
    // Remove invalid class on input
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // NIP input formatting removed - allow any input
    
    // Auto format phone and fax
    $('#telfon, #fax').on('input', function() {
        let value = $(this).val().replace(/[^0-9\-\+\(\)\s]/g, '');
        $(this).val(value);
    });
});

function showAlert(message, type = 'info') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    jQuery('.card-body').prepend(alertHtml);
    
    // Auto dismiss after 5 seconds
    setTimeout(function() {
        jQuery('.alert').fadeOut();
    }, 5000);
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>