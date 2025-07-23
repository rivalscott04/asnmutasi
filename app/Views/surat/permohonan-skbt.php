<?php
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-file-contract me-2"></i>
                        Surat Permohonan SKBT
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Isi data berikut untuk membuat surat permohonan Surat Keterangan Bebas Tugas</p>
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
                    
                    <form id="suratForm" method="POST">

                        
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
                                <label for="blnsrt" class="form-label">Bulan <span class="text-danger">*</span></label>
                                <select class="form-select" id="blnsrt" name="blnsrt" required>
                                    <option value="">Pilih bulan...</option>
                                    <option value="01" <?= ($old['blnsrt'] ?? '') === '01' ? 'selected' : '' ?>>01 (Januari)</option>
                                    <option value="02" <?= ($old['blnsrt'] ?? '') === '02' ? 'selected' : '' ?>>02 (Februari)</option>
                                    <option value="03" <?= ($old['blnsrt'] ?? '') === '03' ? 'selected' : '' ?>>03 (Maret)</option>
                                    <option value="04" <?= ($old['blnsrt'] ?? '') === '04' ? 'selected' : '' ?>>04 (April)</option>
                                    <option value="05" <?= ($old['blnsrt'] ?? '') === '05' ? 'selected' : '' ?>>05 (Mei)</option>
                                    <option value="06" <?= ($old['blnsrt'] ?? '') === '06' ? 'selected' : '' ?>>06 (Juni)</option>
                                    <option value="07" <?= ($old['blnsrt'] ?? '') === '07' ? 'selected' : '' ?>>07 (Juli)</option>
                                    <option value="08" <?= ($old['blnsrt'] ?? '') === '08' ? 'selected' : '' ?>>08 (Agustus)</option>
                                    <option value="09" <?= ($old['blnsrt'] ?? '') === '09' ? 'selected' : '' ?>>09 (September)</option>
                                    <option value="10" <?= ($old['blnsrt'] ?? '') === '10' ? 'selected' : '' ?>>10 (Oktober)</option>
                                    <option value="11" <?= ($old['blnsrt'] ?? '') === '11' ? 'selected' : '' ?>>11 (November)</option>
                                    <option value="12" <?= ($old['blnsrt'] ?? '') === '12' ? 'selected' : '' ?>>12 (Desember)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="thnskrg" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="thnskrg" name="thnskrg" 
                                       value="<?= htmlspecialchars($old['thnskrg'] ?? date('Y')) ?>" 
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
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tgl" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="tgl" name="tgl" 
                                       value="<?= htmlspecialchars($old['tgl'] ?? date('d')) ?>" 
                                       min="1" max="31" maxlength="2" required>
                            </div>
                            <div class="col-md-4">
                                <label for="bln" class="form-label">Bulan <span class="text-danger">*</span></label>
                                <select class="form-select" id="bln" name="bln" required>
                                    <option value="">Pilih bulan...</option>
                                    <option value="Januari" <?= ($old['bln'] ?? '') === 'Januari' ? 'selected' : '' ?>>Januari</option>
                                    <option value="Februari" <?= ($old['bln'] ?? '') === 'Februari' ? 'selected' : '' ?>>Februari</option>
                                    <option value="Maret" <?= ($old['bln'] ?? '') === 'Maret' ? 'selected' : '' ?>>Maret</option>
                                    <option value="April" <?= ($old['bln'] ?? '') === 'April' ? 'selected' : '' ?>>April</option>
                                    <option value="Mei" <?= ($old['bln'] ?? '') === 'Mei' ? 'selected' : '' ?>>Mei</option>
                                    <option value="Juni" <?= ($old['bln'] ?? '') === 'Juni' ? 'selected' : '' ?>>Juni</option>
                                    <option value="Juli" <?= ($old['bln'] ?? '') === 'Juli' ? 'selected' : '' ?>>Juli</option>
                                    <option value="Agustus" <?= ($old['bln'] ?? '') === 'Agustus' ? 'selected' : '' ?>>Agustus</option>
                                    <option value="September" <?= ($old['bln'] ?? '') === 'September' ? 'selected' : '' ?>>September</option>
                                    <option value="Oktober" <?= ($old['bln'] ?? '') === 'Oktober' ? 'selected' : '' ?>>Oktober</option>
                                    <option value="November" <?= ($old['bln'] ?? '') === 'November' ? 'selected' : '' ?>>November</option>
                                    <option value="Desember" <?= ($old['bln'] ?? '') === 'Desember' ? 'selected' : '' ?>>Desember</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="thn" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="thn" name="thn" 
                                       value="<?= htmlspecialchars($old['thn'] ?? date('Y')) ?>" 
                                       min="2020" max="2030" maxlength="4" required>
                            </div>
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
                            <label for="unitkerja" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="unitkerja" name="unitkerja" 
                                   value="<?= htmlspecialchars($old['unitkerja'] ?? '') ?>" 
                                   placeholder="Nama unit kerja" required>
                        </div>
                        
                        <!-- Keperluan -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-success">
                                <i class="fas fa-clipboard-list me-2"></i>
                                Keperluan
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="mb-3">
                            <label for="keperluan" class="form-label">Keperluan SKBT <span class="text-danger">*</span></label>
                            <select class="form-select" id="keperluan" name="keperluan" required>
                                <option value="">Pilih keperluan...</option>
                                <option value="mutasi" <?= ($old['keperluan'] ?? '') === 'mutasi' ? 'selected' : '' ?>>Keperluan Mutasi</option>
                                <option value="promosi" <?= ($old['keperluan'] ?? '') === 'promosi' ? 'selected' : '' ?>>Keperluan Promosi</option>
                                <option value="pensiun" <?= ($old['keperluan'] ?? '') === 'pensiun' ? 'selected' : '' ?>>Keperluan Pensiun</option>
                                <option value="tugas_belajar" <?= ($old['keperluan'] ?? '') === 'tugas_belajar' ? 'selected' : '' ?>>Keperluan Tugas Belajar</option>
                                <option value="lainnya" <?= ($old['keperluan'] ?? '') === 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="keperluanLainnya" style="display: none;">
                            <label for="keperluan_lainnya" class="form-label">Sebutkan Keperluan Lainnya</label>
                            <input type="text" class="form-control" id="keperluan_lainnya" name="keperluan_lainnya" 
                                   value="<?= htmlspecialchars($old['keperluan_lainnya'] ?? '') ?>" 
                                   placeholder="Jelaskan keperluan lainnya">
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
                        
                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between mt-5">
                            <a href="/surat" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                            <div>
                                <button type="button" class="btn btn-success" onclick="previewSurat()">
                                    <i class="fas fa-eye me-2"></i>
                                    Preview & Generate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="text-success mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi SKBT
                    </h6>
                    <ul class="text-muted small mb-0">
                        <li>SKBT (Surat Keterangan Bebas Tugas) diperlukan untuk berbagai keperluan administrasi</li>
                        <li>Pastikan keperluan yang dipilih sesuai dengan kebutuhan Anda</li>
                        <li>Surat ini menyatakan bahwa pegawai tidak memiliki tugas atau tanggung jawab yang mengikat</li>
                        <li>Dokumen ini biasanya diperlukan untuk proses mutasi atau promosi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Preview Surat Permohonan SKBT
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent" class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat preview...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-info me-2" onclick="saveToDatabase()">
                    <i class="fas fa-save me-2"></i>
                    Simpan ke Database
                </button>
                <button type="button" class="btn btn-success" onclick="generateSurat()">
                    <i class="fas fa-download me-2"></i>
                    Generate Surat
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$additionalJS = <<<'EOD'
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for employee and official fields
    function initializePegawaiSelect() {
        // Employee field
        $("#namapegawai").select2({
            placeholder: "Pilih atau cari pegawai...",
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: "/api/pegawai/search",
                dataType: "json",
                delay: 300,
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
            },
            templateResult: function(item) {
                if (item.loading) {
                    return item.text;
                }
                return $("<span>" + item.text + "</span>");
            },
            templateSelection: function(item) {
                return item.text || item.id;
            }
        });
        
        // Official field
        $("#namapejabat").select2({
            placeholder: "Pilih atau cari pejabat...",
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: "/api/pegawai/search",
                dataType: "json",
                delay: 300,
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
            },
            templateResult: function(item) {
                if (item.loading) {
                    return item.text;
                }
                return $("<span>" + item.text + "</span>");
            },
            templateSelection: function(item) {
                return item.text || item.id;
            }
        });
        
        // Handle employee selection
        $("#namapegawai").on("select2:select", function (e) {
            var data = e.params.data;
            if (data.data) {
                // Auto-fill employee fields
                $("#nippegawai").val(data.data.nip);
                if (data.data.golongan) {
                    $("#pangkatgolpegawai").val(data.data.golongan);
                }
                if (data.data.jabatan) {
                    $("#jabatanpegawai").val(data.data.jabatan);
                }
                if (data.data.unit_kerja) {
                    $("#unitkerja").val(data.data.unit_kerja);
                }
            }
        });
        
        // Handle official selection
        $("#namapejabat").on("select2:select", function (e) {
            var data = e.params.data;
            if (data.data) {
                // Auto-fill official fields
                $("#nippejabat").val(data.data.nip);
                if (data.data.golongan) {
                    $("#pangkatgolpejabat").val(data.data.golongan);
                }
                if (data.data.jabatan) {
                    $("#jabatanpejabat").val(data.data.jabatan);
                }
            }
        });
        
        // Handle clear selection
        $("#namapegawai").on("select2:clear", function (e) {
            $("#nippegawai, #pangkatgolpegawai, #jabatanpegawai, #unitkerja").val("");
        });
        
        $("#namapejabat").on("select2:clear", function (e) {
            $("#nippejabat, #pangkatgolpejabat, #jabatanpejabat").val("");
        });
    }
    
    // Initialize Select2
    initializePegawaiSelect();
});

// NIP validation
document.getElementById("nippegawai").addEventListener("input", function(e) {
    this.value = this.value.replace(/[^0-9]/g, "");
});

document.getElementById("nippejabat").addEventListener("input", function(e) {
    this.value = this.value.replace(/[^0-9]/g, "");
});

// Keperluan change handler
document.getElementById("keperluan").addEventListener("change", function() {
    const keperluanLainnya = document.getElementById("keperluanLainnya");
    const keperluanLainnyaInput = document.getElementById("keperluan_lainnya");
    
    if (this.value === "lainnya") {
        keperluanLainnya.style.display = "block";
        keperluanLainnyaInput.required = true;
    } else {
        keperluanLainnya.style.display = "none";
        keperluanLainnyaInput.required = false;
        keperluanLainnyaInput.value = "";
    }
});

// Initialize keperluan visibility
document.addEventListener("DOMContentLoaded", function() {
    const keperluanSelect = document.getElementById("keperluan");
    if (keperluanSelect.value === "lainnya") {
        document.getElementById("keperluanLainnya").style.display = "block";
        document.getElementById("keperluan_lainnya").required = true;
    }
});

// Preview function
function previewSurat() {
    const form = document.getElementById("suratForm");
    const formData = new FormData(form);
    
    // Validate required fields
    const requiredFields = form.querySelectorAll("[required]");
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add("is-invalid");
            isValid = false;
        } else {
            field.classList.remove("is-invalid");
        }
    });
    
    if (!isValid) {
        showAlert("Mohon lengkapi semua field yang wajib diisi", "danger");
        return;
    }
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById("previewModal"));
    modal.show();
    
    // Make AJAX request for preview
    fetch("/surat/permohonan-skbt/preview", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById("previewContent").innerHTML = html;
    })
    .catch(error => {
        document.getElementById("previewContent").innerHTML = 
            '<div class="alert alert-danger">Gagal memuat preview: ' + error.message + '</div>';
    });
}

// Save to database
function saveToDatabase() {
    const form = document.getElementById("suratForm");
    const formData = new FormData(form);
    
    // Validate required fields
    const requiredFields = form.querySelectorAll("[required]");
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add("is-invalid");
            isValid = false;
        } else {
            field.classList.remove("is-invalid");
        }
    });
    
    if (!isValid) {
        showAlert("Mohon lengkapi semua field yang wajib diisi", "danger");
        return;
    }
    
    // Show loading state
    const saveBtn = document.querySelector('button[onclick="saveToDatabase()"]');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    
    // Make AJAX request to save
    fetch("/surat/permohonan-skbt/save", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Data surat berhasil disimpan ke database",
                confirmButtonText: "OK",
                confirmButtonColor: "#28a745"
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: data.message || "Terjadi kesalahan saat menyimpan data",
                confirmButtonText: "OK",
                confirmButtonColor: "#dc3545"
            });
        }
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Terjadi kesalahan sistem",
            confirmButtonText: "OK",
            confirmButtonColor: "#dc3545"
        });
    })
    .finally(() => {
        // Restore button state
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

// Generate surat function for modal
function generateSurat() {
    const form = document.getElementById("suratForm");
    const formData = new FormData(form);
    
    // Validate required fields
    const requiredFields = form.querySelectorAll("[required]");
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add("is-invalid");
            isValid = false;
        } else {
            field.classList.remove("is-invalid");
        }
    });
    
    if (!isValid) {
        showAlert("Mohon lengkapi semua field yang wajib diisi", "danger");
        return;
    }
    
    // Show loading state
    const generateBtn = document.querySelector('button[onclick="generateSurat()"]');
    const originalText = generateBtn.innerHTML;
    generateBtn.disabled = true;
    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
    
    fetch("/surat/permohonan-skbt/generate", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Surat Permohonan SKBT berhasil dibuat",
                confirmButtonText: "Download",
                confirmButtonColor: "#28a745"
            }).then(() => {
                // Download or redirect to preview
                if (data.data.download_url) {
                    window.open(data.data.download_url, "_blank");
                } else if (data.data.preview_url) {
                    window.open(data.data.preview_url, "_blank");
                }
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('previewModal'));
                modal.hide();
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: data.message || "Terjadi kesalahan saat membuat surat",
                confirmButtonText: "OK",
                confirmButtonColor: "#dc3545"
            });
        }
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Terjadi kesalahan sistem",
            confirmButtonText: "OK",
            confirmButtonColor: "#dc3545"
        });
    })
    .finally(() => {
        // Restore button state
        generateBtn.disabled = false;
        generateBtn.innerHTML = originalText;
    });
}
</script>
EOD;

$additionalCSS = '
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
.section-header h5 {
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.is-invalid {
    border-color: #dc3545;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.text-danger {
    font-size: 0.875em;
}

#previewContent {
    max-height: 500px;
    overflow-y: auto;
}

.form-select {
    border-radius: 8px;
}

.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
</style>
';

$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>