<?php
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-danger text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-gavel me-2"></i>
                        Surat Pernyataan Pidana
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Isi data berikut untuk membuat surat pernyataan tidak pernah dipidana</p>
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
                    
                    <form id="suratForm" method="POST" action="/surat/pernyataan-pidana">

                        
                        <!-- Data Surat -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-danger">
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
                            <h5 class="text-danger">
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
                            <h5 class="text-danger">
                                <i class="fas fa-user me-2"></i>
                                Data Pegawai
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="namapegawai" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <select class="form-control" id="namapegawai" name="namapegawai" required>
                                    <?php if (isset($old['namapegawai']) && !empty($old['namapegawai'])): ?>
                                        <option value="<?= htmlspecialchars($old['namapegawai']) ?>" selected>
                                            <?= htmlspecialchars($old['namapegawai']) ?>
                                        </option>
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
                                       placeholder="Contoh: Penata Muda, III/a" required>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatanpegawai" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatanpegawai" name="jabatanpegawai" 
                                       value="<?= htmlspecialchars($old['jabatanpegawai'] ?? '') ?>" 
                                       placeholder="Jabatan saat ini" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tempattugas" class="form-label">Satuan Kerja <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tempattugas" name="tempattugas" 
                                   value="<?= htmlspecialchars($old['tempattugas'] ?? '') ?>" 
                                   placeholder="Nama satuan kerja" required>
                        </div>
                        
                        <!-- Data Pejabat Penandatangan -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-danger">
                                <i class="fas fa-user-tie me-2"></i>
                                Data Pejabat Penandatangan
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="namapejabat" class="form-label">Nama Pejabat <span class="text-danger">*</span></label>
                                <select class="form-control" id="namapejabat" name="namapejabat" required>
                                    <?php if (isset($old['namapejabat']) && !empty($old['namapejabat'])): ?>
                                        <option value="<?= htmlspecialchars($old['namapejabat']) ?>" selected>
                                            <?= htmlspecialchars($old['namapejabat']) ?>
                                        </option>
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
                                       placeholder="Contoh: Pembina, IV/a" required>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatanpejabat" class="form-label">Jabatan Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatanpejabat" name="jabatanpejabat" 
                                       value="<?= htmlspecialchars($old['jabatanpejabat'] ?? '') ?>" 
                                       placeholder="Jabatan pejabat" required>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-file-download me-2"></i>
                                Generate Surat Pernyataan Pidana
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
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.alert {
    border-radius: 10px;
    border: none;
}

.text-danger {
    color: #dc3545 !important;
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



<?php
$additionalJS = '
<script>
$(document).ready(function() {
    // Initialize Select2 for employee fields
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
                    $("#unitkerja, #tempattugas").val(data.data.unit_kerja);
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
                if (data.data.unit_kerja) {
                    $("#ukerpejabat").val(data.data.unit_kerja);
                }
            }
        });
        
        // Handle clear selection
        $("#namapegawai").on("select2:clear", function (e) {
            $("#nippegawai, #pangkatgolpegawai, #jabatanpegawai, #unitkerja, #tempattugas").val("");
        });
        
        $("#namapejabat").on("select2:clear", function (e) {
            $("#nippejabat, #pangkatgolpejabat, #jabatanpejabat, #ukerpejabat").val("");
        });
    }
    
    // Initialize Select2
    initializePegawaiSelect();
    
    // Form validation
    $("#suratForm").on("submit", function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find("[required]").each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
            }
        });
        
        // Validate NIP format (18 digits)
        const nipPegawai = $("#nippegawai").val().trim();
        const nipPejabat = $("#nippejabat").val().trim();
        
        if (nipPegawai && !/^\\d{18}$/.test(nipPegawai)) {
            isValid = false;
            $("#nippegawai").addClass("is-invalid");
            showAlert("NIP Pegawai harus 18 digit angka", "danger");
        }
        
        if (nipPejabat && !/^\\d{18}$/.test(nipPejabat)) {
            isValid = false;
            $("#nippejabat").addClass("is-invalid");
            showAlert("NIP Pejabat harus 18 digit angka", "danger");
        }
        
        // Validate email format
        const email = $("#email").val().trim();
        if (email && !/^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/.test(email)) {
            isValid = false;
            $("#email").addClass("is-invalid");
            showAlert("Format email tidak valid", "danger");
        }
        
        if (!isValid) {
            e.preventDefault();
            showAlert("Mohon lengkapi semua field yang diperlukan dengan benar", "danger");
            return false;
        }
        
        // Show loading
        showLoading();
    });
    
    // Remove invalid class on input
    $(".form-control, .form-select").on("input change", function() {
        $(this).removeClass("is-invalid");
    });
    
    // NIP input formatting removed - allow any input
    
    // Auto format phone and fax
    $("#telfon, #fax").on("input", function() {
        let value = $(this).val().replace(/[^0-9\\-\\+\\(\\)\\s]/g, "");
        $(this).val(value);
    });
});

function showAlert(message, type = "info") {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === "danger" ? "exclamation-triangle" : "info-circle"} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $(".card-body").prepend(alertHtml);
    
    // Auto dismiss after 5 seconds
    setTimeout(function() {
        $(".alert").fadeOut();
    }, 5000);
}

function showLoading() {
    const button = $("button[type=\"submit\"]");
    const originalText = button.html();
    button.prop("disabled", true).html("<i class=\"fas fa-spinner fa-spin me-2\"></i>Generating...");
}
</script>
';

$additionalCSS = '
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.alert {
    border-radius: 10px;
    border: none;
}

.text-danger {
    color: #dc3545 !important;
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
';

$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>