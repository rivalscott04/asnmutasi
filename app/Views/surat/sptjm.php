<?php
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-info text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-file-contract me-2"></i>
                        SPTJM (Surat Pernyataan Tanggung Jawab Mutlak)
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Isi data berikut untuk membuat Surat Pernyataan Tanggung Jawab Mutlak</p>
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
                    
                    <form id="suratForm" method="POST" action="/surat/sptjm">

                        
                        <!-- Data Surat -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-info">
                                <i class="fas fa-file-alt me-2"></i>
                                Data Surat
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nosrt" class="form-label">Nomor Urut Surat <span class="text-danger">*</span></label>
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
                        
                        <!-- Tanggal Surat -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-info">
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
                        
                        <!-- Data Pejabat Penandatangan -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-info">
                                <i class="fas fa-user-tie me-2"></i>
                                Data Pejabat Penandatangan
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="namapejabat" class="form-label">Nama Pejabat <span class="text-danger">*</span></label>
                                <select class="form-select" id="namapejabat" name="namapejabat" required>
                                    <option value="">Pilih pejabat...</option>
                                    <?php if (!empty($old['namapejabat'])): ?>
                                        <option value="<?= htmlspecialchars($old['namapejabat']) ?>" selected><?= htmlspecialchars($old['namapejabat']) ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nippejabat" class="form-label">NIP Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nippejabat" name="nippejabat" 
                                       value="<?= htmlspecialchars($old['nippejabat'] ?? '') ?>" 
                                       placeholder="NIP pejabat" readonly required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pangkatgolpejabat" class="form-label">Pangkat/Golongan Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pangkatgolpejabat" name="pangkatgolpejabat" 
                                       value="<?= htmlspecialchars($old['pangkatgolpejabat'] ?? '') ?>" 
                                       placeholder="Contoh: Pembina, IV/a" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatanpejabat" class="form-label">Jabatan Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatanpejabat" name="jabatanpejabat" 
                                       value="<?= htmlspecialchars($old['jabatanpejabat'] ?? '') ?>" 
                                       placeholder="Jabatan pejabat" readonly required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="ukerpejabat" class="form-label">Unit/Satuan Kerja Pejabat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ukerpejabat" name="ukerpejabat" 
                                   value="<?= htmlspecialchars($old['ukerpejabat'] ?? '') ?>" 
                                   placeholder="Unit/Satuan Kerja Pejabat" readonly required>
                        </div>
                        
                        <!-- Persetujuan -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-info">
                                <i class="fas fa-check-circle me-2"></i>
                                Persetujuan
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pernyataan_benar" name="pernyataan_benar" 
                                       value="1" <?= ($old['pernyataan_benar'] ?? '') ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="pernyataan_benar">
                                    Saya menyatakan bahwa semua data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sanksi_setuju" name="sanksi_setuju" 
                                       value="1" <?= ($old['sanksi_setuju'] ?? '') ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="sanksi_setuju">
                                    Saya bersedia menerima sanksi sesuai ketentuan peraturan perundang-undangan apabila pernyataan ini tidak benar. <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="tanggung_jawab_setuju" name="tanggung_jawab_setuju" 
                                       value="1" <?= ($old['tanggung_jawab_setuju'] ?? '') ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="tanggung_jawab_setuju">
                                    Saya bertanggung jawab mutlak atas segala konsekuensi dari mutasi ini. <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between mt-5">
                            <a href="/surat" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-info me-2" onclick="previewSurat()">
                                    <i class="fas fa-eye me-2"></i>
                                    Preview
                                </button>
                                <button type="submit" class="btn btn-info text-white">
                                    <i class="fas fa-download me-2"></i>
                                    Generate Surat
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="text-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi SPTJM
                    </h6>
                    <ul class="text-muted small mb-0">
                        <li>SPTJM adalah dokumen penting yang menyatakan tanggung jawab mutlak pegawai</li>
                        <li>Dokumen ini diperlukan untuk proses mutasi PNS</li>
                        <li>Pastikan semua data yang diisi sudah benar dan sesuai dengan dokumen resmi</li>
                        <li>Pernyataan yang tidak benar dapat berakibat pada sanksi administratif</li>
                        <li>Surat ini mengikat secara hukum dan harus ditandatangani di atas materai</li>
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
                    Preview SPTJM
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent" class="text-center">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat preview...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-info text-white" onclick="generateFromPreview()">
                    <i class="fas fa-download me-2"></i>
                    Generate Surat
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$additionalJS = '
<script>
// Ensure jQuery is loaded before executing
jQuery(document).ready(function($) {
    // Initialize Select2 for namapejabat
    $("#namapejabat").select2({
    placeholder: "Ketik untuk mencari pejabat...",
    allowClear: true,
    minimumInputLength: 2,
    ajax: {
        url: "/api/pegawai/search",
        dataType: "json",
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
        }
    }
});

// Auto-fill pejabat fields when selection is made
$("#namapejabat").on("select2:select", function (e) {
    const data = e.params.data.data;
    if (data && data.nip) $("#nippejabat").val(data.nip);
    if (data && data.golongan) $("#pangkatgolpejabat").val(data.golongan);
    if (data && data.jabatan) $("#jabatanpejabat").val(data.jabatan);
    if (data && data.unit_kerja) $("#ukerpejabat").val(data.unit_kerja);
});

// Clear pejabat fields when selection is cleared
$("#namapejabat").on("select2:clear", function (e) {
    $("#nippejabat").val("");
    $("#pangkatgolpejabat").val("");
    $("#jabatanpejabat").val("");
    $("#ukerpejabat").val("");
});

// NIP input formatting removed - allow any input

// Preview function
function previewSurat() {
    const form = document.getElementById("suratForm");
    const formData = new FormData(form);
    
    // Validate required fields
    const requiredFields = form.querySelectorAll("[required]");
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (field.type === "checkbox") {
            if (!field.checked) {
                field.classList.add("is-invalid");
                isValid = false;
            } else {
                field.classList.remove("is-invalid");
            }
        } else {
            if (!field.value.trim()) {
                field.classList.add("is-invalid");
                isValid = false;
            } else {
                field.classList.remove("is-invalid");
            }
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
    fetch("/surat/sptjm/preview", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById("previewContent").innerHTML = html;
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

    // Form submission with loading
    document.getElementById("suratForm").addEventListener("submit", function(e) {
        showLoading();
    });
});
</script>
';

$additionalCSS = '
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

.form-check-input.is-invalid {
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

.form-check-label {
    font-size: 0.95em;
    line-height: 1.4;
}

.alert-info {
    border-left: 4px solid #0dcaf0;
}

.form-select {
    border-radius: 8px;
}
</style>
';

$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>