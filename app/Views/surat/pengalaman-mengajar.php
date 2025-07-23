<?php
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Surat Pengalaman Mengajar
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Isi data berikut untuk membuat surat pengalaman mengajar</p>
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
                    
                    <form id="suratForm" method="POST" action="/surat/pengalaman-mengajar">

                        
                        <!-- Data Surat -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-primary">
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
                                    <option value="">Pilih bulan...</option>
                                    <option value="01" <?= ($old['blnno'] ?? '') === '01' ? 'selected' : '' ?>>01 (Januari)</option>
                                    <option value="02" <?= ($old['blnno'] ?? '') === '02' ? 'selected' : '' ?>>02 (Februari)</option>
                                    <option value="03" <?= ($old['blnno'] ?? '') === '03' ? 'selected' : '' ?>>03 (Maret)</option>
                                    <option value="04" <?= ($old['blnno'] ?? '') === '04' ? 'selected' : '' ?>>04 (April)</option>
                                    <option value="05" <?= ($old['blnno'] ?? '') === '05' ? 'selected' : '' ?>>05 (Mei)</option>
                                    <option value="06" <?= ($old['blnno'] ?? '') === '06' ? 'selected' : '' ?>>06 (Juni)</option>
                                    <option value="07" <?= ($old['blnno'] ?? '') === '07' ? 'selected' : '' ?>>07 (Juli)</option>
                                    <option value="08" <?= ($old['blnno'] ?? '') === '08' ? 'selected' : '' ?>>08 (Agustus)</option>
                                    <option value="09" <?= ($old['blnno'] ?? '') === '09' ? 'selected' : '' ?>>09 (September)</option>
                                    <option value="10" <?= ($old['blnno'] ?? '') === '10' ? 'selected' : '' ?>>10 (Oktober)</option>
                                    <option value="11" <?= ($old['blnno'] ?? '') === '11' ? 'selected' : '' ?>>11 (November)</option>
                                    <option value="12" <?= ($old['blnno'] ?? '') === '12' ? 'selected' : '' ?>>12 (Desember)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="thnno" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="thnno" name="thnno" 
                                       value="<?= htmlspecialchars($old['thnno'] ?? date('Y')) ?>" 
                                       min="2020" max="2030" maxlength="4" required>
                            </div>
                        </div>
                        
                        <!-- Data Pegawai -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-primary">
                                <i class="fas fa-user me-2"></i>
                                Data Pegawai
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="mb-3">
                            <label for="namapegawai" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <select class="form-control" id="namapegawai" name="namapegawai" required>
                                <?php if (isset($old['namapegawai']) && !empty($old['namapegawai'])): ?>
                                    <option value="<?= htmlspecialchars($old['namapegawai']) ?>" selected>
                                        <?= htmlspecialchars($old['namapegawai']) ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nippegawai" class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nippegawai" name="nippegawai" 
                                       value="<?= htmlspecialchars($old['nippegawai'] ?? '') ?>" 
                                       placeholder="NIP pegawai" required readonly>
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
                                   placeholder="Nama satuan kerja" required readonly>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="sekolah" class="form-label">Jenis Sekolah <span class="text-danger">*</span></label>
                                <select class="form-select" id="sekolah" name="sekolah" required>
                                    <option value="">Pilih jenis sekolah...</option>
                                    <option value="Madrasah Ibtidaiyah" <?= ($old['sekolah'] ?? '') === 'Madrasah Ibtidaiyah' ? 'selected' : '' ?>>Madrasah Ibtidaiyah</option>
                                    <option value="Madrasah Tsanawiyah" <?= ($old['sekolah'] ?? '') === 'Madrasah Tsanawiyah' ? 'selected' : '' ?>>Madrasah Tsanawiyah</option>
                                    <option value="Madrasah Aliyah" <?= ($old['sekolah'] ?? '') === 'Madrasah Aliyah' ? 'selected' : '' ?>>Madrasah Aliyah</option>
                                    <option value="Sekolah Dasar" <?= ($old['sekolah'] ?? '') === 'Sekolah Dasar' ? 'selected' : '' ?>>Sekolah Dasar</option>
                                    <option value="Sekolah Menengah Pertama" <?= ($old['sekolah'] ?? '') === 'Sekolah Menengah Pertama' ? 'selected' : '' ?>>Sekolah Menengah Pertama</option>
                                    <option value="Sekolah Menengah Atas" <?= ($old['sekolah'] ?? '') === 'Sekolah Menengah Atas' ? 'selected' : '' ?>>Sekolah Menengah Atas</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tglmulai" class="form-label">Tanggal Mulai Mengajar <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tglmulai" name="tglmulai" 
                                       value="<?= htmlspecialchars($old['tglmulai'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <!-- Data Pejabat Penandatangan -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-primary">
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
                                <button type="button" class="btn btn-primary" onclick="previewSurat()">
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
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Penting
                    </h6>
                    <ul class="text-muted small mb-0">
                        <li>Pastikan semua data yang diisi sudah benar dan sesuai</li>
                        <li>NIP harus terdiri dari 18 digit angka</li>
                        <li>Surat yang telah dibuat dapat diunduh dalam format PDF</li>
                        <li>Gunakan fitur preview untuk melihat hasil sebelum generate</li>
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
                    Preview Surat Pengalaman Mengajar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="previewFrame" style="width: 100%; height: 600px; border: none; display: none;"></iframe>
                <div id="previewContent" class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat preview...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="generateFromPreview()">
                    <i class="fas fa-download me-2"></i>
                    Generate Surat
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Select2 CSS and JS are loaded in the layout file -->
<!-- Pegawai Select JS -->
<script src="/js/pegawai-select.js"></script>

<?php
$additionalJS = '
<script>
// Ensure jQuery is loaded before executing
(function() {
    function initializeForm() {
        if (typeof jQuery === "undefined") {
            setTimeout(initializeForm, 100);
            return;
        }
        
        jQuery(document).ready(function($) {
            // Initialize pegawai select using the external JS file
            if (typeof initializePegawaiSelect === "function") {
                initializePegawaiSelect();
            }

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
                
                // NIP validation removed - handled by server-side validation
                
                if (!isValid) {
                    e.preventDefault();
                    showAlert("danger", "Mohon lengkapi semua field yang diperlukan dengan benar");
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
        });
    }
    initializeForm();
})();

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
    fetch("/surat/pengalaman-mengajar/preview", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        // Hide loading content
        document.getElementById("previewContent").style.display = "none";
        
        // Show iframe and load HTML content
        const iframe = document.getElementById("previewFrame");
        iframe.style.display = "block";
        
        // Write HTML content to iframe
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(html);
        iframeDoc.close();
    })
    .catch(error => {
        document.getElementById("previewContent").innerHTML = 
            `<div class="alert alert-danger">Gagal memuat preview: ${error.message}</div>`;
    });
}

// Generate from preview
function generateFromPreview() {
    document.getElementById("suratForm").submit();
}

// Form submission with SweetAlert
document.getElementById("suratForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector("button[type=submit]");
    const originalText = submitBtn.innerHTML;
    
    // Log payload data for debugging
    console.log("=== PAYLOAD DATA GENERATE SURAT ===");
    const payloadData = {};
    for (let [key, value] of formData.entries()) {
        payloadData[key] = value;
        console.log(`${key}: ${value}`);
    }
    console.log("Complete payload object:", payloadData);
    console.log("======================================");
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>Generating...`;
    
    fetch("/surat/pengalaman-mengajar/generate", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Surat Keterangan Pengalaman Mengajar berhasil dibuat",
                confirmButtonText: "Download",
                confirmButtonColor: "#28a745"
            }).then(() => {
                // Download or redirect to preview
                if (data.data.download_url) {
                    window.open(data.data.download_url, "_blank");
                } else if (data.data.preview_url) {
                    window.open(data.data.preview_url, "_blank");
                }
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
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Reset preview modal when closed
document.getElementById("previewModal").addEventListener("hidden.bs.modal", function() {
    // Reset to loading state
    document.getElementById("previewContent").style.display = "block";
    document.getElementById("previewFrame").style.display = "none";
    
    // Reset loading content
    document.getElementById("previewContent").innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Memuat preview...</p>
    `;
});
</script>
';

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