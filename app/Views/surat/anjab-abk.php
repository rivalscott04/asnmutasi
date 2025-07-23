<?php
ob_start();
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-alt fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">Surat Keterangan Analisis Jabatan dan Analisis Beban Kerja</h4>
                            <small class="opacity-75">Formulir pembuatan surat keterangan ANJAB ABK PNS</small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form id="suratForm" method="POST" action="/surat/anjab-abk" class="needs-validation" novalidate>

                        
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
                                <label for="nosurat" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nosurat" name="nosurat" 
                                       value="<?= htmlspecialchars($old['nosurat'] ?? '') ?>" 
                                       placeholder="Contoh: 001" required>
                            </div>
                            <div class="col-md-4">
                                <label for="blnnomor" class="form-label">Bulan (Angka) <span class="text-danger">*</span></label>
                                <select class="form-select" id="blnnomor" name="blnnomor" required>
                                    <option value="">Pilih bulan...</option>
                                    <option value="01" <?= ($old['blnnomor'] ?? '') === '01' ? 'selected' : '' ?>>01 (Januari)</option>
                                    <option value="02" <?= ($old['blnnomor'] ?? '') === '02' ? 'selected' : '' ?>>02 (Februari)</option>
                                    <option value="03" <?= ($old['blnnomor'] ?? '') === '03' ? 'selected' : '' ?>>03 (Maret)</option>
                                    <option value="04" <?= ($old['blnnomor'] ?? '') === '04' ? 'selected' : '' ?>>04 (April)</option>
                                    <option value="05" <?= ($old['blnnomor'] ?? '') === '05' ? 'selected' : '' ?>>05 (Mei)</option>
                                    <option value="06" <?= ($old['blnnomor'] ?? '') === '06' ? 'selected' : '' ?>>06 (Juni)</option>
                                    <option value="07" <?= ($old['blnnomor'] ?? '') === '07' ? 'selected' : '' ?>>07 (Juli)</option>
                                    <option value="08" <?= ($old['blnnomor'] ?? '') === '08' ? 'selected' : '' ?>>08 (Agustus)</option>
                                    <option value="09" <?= ($old['blnnomor'] ?? '') === '09' ? 'selected' : '' ?>>09 (September)</option>
                                    <option value="10" <?= ($old['blnnomor'] ?? '') === '10' ? 'selected' : '' ?>>10 (Oktober)</option>
                                    <option value="11" <?= ($old['blnnomor'] ?? '') === '11' ? 'selected' : '' ?>>11 (November)</option>
                                    <option value="12" <?= ($old['blnnomor'] ?? '') === '12' ? 'selected' : '' ?>>12 (Desember)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="tahunskrg" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tahunskrg" name="tahunskrg" 
                                       value="<?= htmlspecialchars($old['tahunskrg'] ?? date('Y')) ?>" 
                                       placeholder="<?= date('Y') ?>" required>
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
                                <select class="form-select" id="namapejabat" name="namapejabat" required>
                                    <option value="">Pilih pejabat...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nippejabat" class="form-label">NIP Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nippejabat" name="nippejabat" 
                                       value="<?= htmlspecialchars($old['nippejabat'] ?? '') ?>" 
                                       placeholder="NIP akan terisi otomatis" readonly required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pangkatgolpejabat" class="form-label">Pangkat/Golongan Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pangkatgolpejabat" name="pangkatgolpejabat" 
                                       value="<?= htmlspecialchars($old['pangkatgolpejabat'] ?? '') ?>" 
                                       placeholder="Akan terisi otomatis" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatanpejabat" class="form-label">Jabatan Pejabat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatanpejabat" name="jabatanpejabat" 
                                       value="<?= htmlspecialchars($old['jabatanpejabat'] ?? '') ?>" 
                                       placeholder="Akan terisi otomatis" readonly required>
                            </div>
                        </div>
                        
                        <!-- Data Unit Kerja dan Analisis -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-primary">
                                <i class="fas fa-chart-bar me-2"></i>
                                Data Analisis Jabatan dan Beban Kerja
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="unitkerja" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="unitkerja" name="unitkerja" 
                                       value="<?= htmlspecialchars($old['unitkerja'] ?? '') ?>" 
                                       placeholder="Contoh: Kantor Kementerian Agama Kota Jakarta Pusat" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="namajabatan" class="form-label">Nama Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namajabatan" name="namajabatan" 
                                       value="<?= htmlspecialchars($old['namajabatan'] ?? '') ?>" 
                                       placeholder="Contoh: Kepala Seksi Pendidikan Madrasah" required>
                            </div>
                            <div class="col-md-6">
                                <label for="bbnkerja" class="form-label">Jumlah Beban Kerja <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="bbnkerja" name="bbnkerja" 
                                       value="<?= htmlspecialchars($old['bbnkerja'] ?? '') ?>" 
                                       placeholder="Contoh: 1" min="1" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="eksisting" class="form-label">Jumlah Pegawai Eksisting <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="eksisting" name="eksisting" 
                                       value="<?= htmlspecialchars($old['eksisting'] ?? '') ?>" 
                                       placeholder="Contoh: 0" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="kelebihan" class="form-label">Kelebihan Pegawai <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="kelebihan" name="kelebihan" 
                                       value="<?= htmlspecialchars($old['kelebihan'] ?? '') ?>" 
                                       placeholder="Contoh: 0" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="kekurangan" class="form-label">Kekurangan Pegawai <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="kekurangan" name="kekurangan" 
                                       value="<?= htmlspecialchars($old['kekurangan'] ?? '') ?>" 
                                       placeholder="Contoh: 1" min="0" required>
                            </div>
                        </div>
                        
                        <!-- Tanggal Surat -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="text-primary">
                                <i class="fas fa-calendar me-2"></i>
                                Tanggal Surat
                            </h5>
                            <hr>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="dd-mm-yyyy" class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dd-mm-yyyy" name="dd-mm-yyyy" 
                                       value="<?= htmlspecialchars($old['dd-mm-yyyy'] ?? date('Y-m-d')) ?>" required>
                                <div class="form-text">Tanggal akan ditampilkan dalam format: Kota, DD Bulan YYYY</div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="/surat" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                            
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="previewSurat()">
                                    <i class="fas fa-eye me-2"></i>
                                    Preview
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-download me-2"></i>
                                    Generate Surat
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Preview Surat Keterangan ANJAB ABK
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent" class="text-center">
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

<?php
$additionalJS = '
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for official fields
    function initializePejabatSelect() {
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
        $("#namapejabat").on("select2:clear", function (e) {
            $("#nippejabat, #pangkatgolpejabat, #jabatanpejabat").val("");
        });
    }
    
    // Initialize all Select2 components
    initializePejabatSelect();
    
    // Form validation
    $("#suratForm").on("submit", function(e) {
        e.preventDefault();
        
        // Show loading
        const submitBtn = $(this).find("button[type=submit]");
        const originalText = submitBtn.html();
        submitBtn.html("<i class=\"fas fa-spinner fa-spin me-2\"></i>Generating...").prop("disabled", true);
        
        // Submit form
        $.ajax({
            url: $(this).attr("action"),
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // Show success message
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        // Download file
                        window.open("/surat/download/" + response.data.download_id, "_blank");
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let message = "Terjadi kesalahan sistem";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: message
                });
            },
            complete: function() {
                // Reset button
                submitBtn.html(originalText).prop("disabled", false);
            }
        });
    });
});

// Preview function
function previewSurat() {
    const form = $("#suratForm");
    
    // Validate required fields
    if (!form[0].checkValidity()) {
        form[0].reportValidity();
        return;
    }
    
    // Show modal
    $("#previewModal").modal("show");
    
    // Convert form data to object
    const formData = {};
    form.serializeArray().forEach(function(item) {
        formData[item.name] = item.value;
    });
    
    // Load preview
    $.ajax({
        url: "/surat/anjab-abk/preview",
        method: "POST",
        data: JSON.stringify({ data: formData }),
        contentType: "application/json",
        dataType: "json",
        success: function(response) {
            if (response.success) {
                // Generate preview HTML
                const data = response.data;
                const previewHtml = generatePreviewHTML(data);
                $("#previewContent").html(previewHtml);
            } else {
                $("#previewContent").html("<div class=\"alert alert-danger\">" + response.message + "</div>");
            }
        },
        error: function() {
            $("#previewContent").html("<div class=\"alert alert-danger\">Gagal memuat preview</div>");
        }
    });
}

// Generate from preview
function generateFromPreview() {
    $("#previewModal").modal("hide");
    $("#suratForm").submit();
}

// Generate preview HTML
function generatePreviewHTML(data) {
    return `
        <div class="preview-document" style="max-width: 800px; margin: 0 auto; font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.4;">
            <div style="text-align: center; margin-bottom: 25px; border-bottom: 3px solid #000; padding-bottom: 12px;">
                <div style="font-weight: bold; font-size: 13pt; line-height: 1.2; margin-bottom: 5px;">
                    KEMENTERIAN AGAMA REPUBLIK INDONESIA<br>
                    KANTOR KEMENTERIAN AGAMA ${data.kabkota}
                </div>
                <div style="font-size: 11pt; line-height: 1.1; margin-top: 8px;">
                    ${data.jln}<br>
                    ${data.telfon}<br>
                    ${data.fax} ${data.email}
                </div>
            </div>
            
            <div style="text-align: center; font-weight: bold; font-size: 11pt; margin: 25px 0 15px 0;">
                <strong>SURAT KETERANGAN ANALISIS JABATAN DAN ANALISIS BEBAN KERJA</strong><br>
                <strong>PEGAWAI NEGERI SIPIL</strong>
            </div>
            
            <div style="text-align: center; margin-bottom: 20px; font-size: 11pt;">
                Nomor: B-${data.nosurat}/Kk.18.08/1/KP.07.6/${data.blnnomor}/${data.tahunskrg}
            </div>
            
            <div style="text-align: justify; margin-bottom: 15px;">
                <div style="margin-bottom: 15px;">Yang bertanda tangan dibawah ini :</div>
                
                <div style="margin: 15px 0;">
                    <div style="display: flex; margin-bottom: 6px;">
                        <div style="width: 150px;">Nama</div>
                        <div style="width: 20px;">:</div>
                        <div>${data.namapejabat}</div>
                    </div>
                    <div style="display: flex; margin-bottom: 6px;">
                        <div style="width: 150px;">NIP</div>
                        <div style="width: 20px;">:</div>
                        <div>${data.nippejabat}</div>
                    </div>
                    <div style="display: flex; margin-bottom: 6px;">
                        <div style="width: 150px;">Pangkat/Gol/Ruang</div>
                        <div style="width: 20px;">:</div>
                        <div>${data.pangkatgolpejabat}</div>
                    </div>
                    <div style="display: flex; margin-bottom: 6px;">
                        <div style="width: 150px;">Jabatan</div>
                        <div style="width: 20px;">:</div>
                        <div>${data.jabatanpejabat}</div>
                    </div>
                </div>
                
                <p>Dengan ini menerangkan jumlah jabatan Pegawai Negeri Sipil pada Unit Kerja ${data.unitkerja} sebagai berikut :</p>
                
                <div style="margin: 20px 0;">
                    <table style="width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 10pt;" border="1">
                        <thead>
                            <tr>
                                <th rowspan="2" style="padding: 8px; text-align: center; font-weight: bold; background-color: #f0f0f0;">No.</th>
                                <th rowspan="2" style="padding: 8px; text-align: center; font-weight: bold; background-color: #f0f0f0;">Nama Jabatan</th>
                                <th rowspan="2" style="padding: 8px; text-align: center; font-weight: bold; background-color: #f0f0f0;">Jumlah Beban Kerja</th>
                                <th colspan="3" style="padding: 8px; text-align: center; font-weight: bold; background-color: #f0f0f0;">Jumlah Pegawai</th>
                                <th rowspan="2" style="padding: 8px; text-align: center; font-weight: bold; background-color: #f0f0f0;">Keterangan</th>
                            </tr>
                            <tr>
                                <th style="padding: 8px; text-align: center; font-weight: bold; background-color: #f0f0f0;">Eksisting</th>
                                <th style="padding: 8px; text-align: center; font-weight: bold; background-color: #f0f0f0;">Kelebihan</th>
                                <th style="padding: 8px; text-align: center; font-weight: bold; background-color: #f0f0f0;">Kekurangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 8px; text-align: center;">1</td>
                                <td style="padding: 8px; text-align: center;">${data.namajabatan}</td>
                                <td style="padding: 8px; text-align: center;">${data.bbnkerja}</td>
                                <td style="padding: 8px; text-align: center;">${data.eksisting}</td>
                                <td style="padding: 8px; text-align: center;">${data.kelebihan}</td>
                                <td style="padding: 8px; text-align: center;">${data.kekurangan}</td>
                                <td style="padding: 8px; text-align: center;">Perlu diisi melalui mutasi PNS</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <p>Demikian surat keterangan ini di buat untuk dapat dipergunakan sebagaimana mestinya.</p>
            </div>
            
            <div style="margin-top: 30px; margin-left: auto; width: 200px; text-align: left;">
                <div style="margin-bottom: 5px;">${data.kabkota}, ${formatDate(data["dd-mm-yyyy"])}</div>
                <div>Kepala,</div>
                <div style="height: 60px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">${data.namapejabat}</div>
                <div style="margin-top: 5px;">NIP. ${data.nippejabat}</div>
            </div>
        </div>
    `;
}

// Format date helper
function formatDate(dateString) {
    const months = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    
    const date = new Date(dateString);
    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    
    return `${day} ${month} ${year}`;
}
</script>
';

$additionalCSS = '
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.section-header h5 {
    color: #0d6efd;
    font-weight: 600;
}

.section-header hr {
    border-top: 2px solid #0d6efd;
    opacity: 0.3;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-1px);
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #0d6efd, #0056b3) !important;
    border: none;
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

.preview-document {
    border: 1px solid #dee2e6;
    padding: 20px;
    background: white;
    border-radius: 8px;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
';

$content = ob_get_clean();
require_once VIEWS_PATH . '/layouts/app.php';
?>