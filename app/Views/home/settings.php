<?php ob_start(); ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-cog me-3"></i>
                    Settings
                </h1>
                <p class="lead mb-0">Kelola pengaturan sistem dan preferensi akun Anda</p>
            </div>
        </div>
    </div>
</div>

<!-- Settings Content -->
<div class="container my-5">
    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <div class="row">
        <!-- Profile Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Pengaturan Profil
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="userName" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="userName" value="<?= htmlspecialchars($user) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" placeholder="user@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="userPhone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="userPhone" placeholder="+62 812 3456 7890">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- System Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Pengaturan Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="defaultTemplate" class="form-label">Template Default</label>
                        <select class="form-select" id="defaultTemplate">
                            <option value="">Pilih Template Default</option>
                            <option value="pengalaman-mengajar">Surat Keterangan Pengalaman Mengajar</option>
                            <option value="permohonan-skbt">Surat Permohonan SKBT</option>
                            <option value="pernyataan-disiplin">Surat Pernyataan Disiplin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="autoSave" class="form-label">Auto Save</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoSave" checked>
                            <label class="form-check-label" for="autoSave">
                                Simpan otomatis saat mengisi form
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notifications" class="form-label">Notifikasi</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notifications" checked>
                            <label class="form-check-label" for="notifications">
                                Terima notifikasi sistem
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>
                        Terapkan Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Office Settings -->
        <?php if (isset($role) && $role === 'daerah'): ?>
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        Pengaturan Data Kantor
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/settings/kantor" method="POST" enctype="multipart/form-data" id="kantorForm">
                        <div class="mb-3">
                            <label for="kabkota" class="form-label">Kabupaten/Kota</label>
                            <input type="text" class="form-control" id="kabkota" name="kabkota" 
                                   value="<?= htmlspecialchars($kantor['kabupaten_kota'] ?? '') ?>"
                                   placeholder="Contoh: KABUPATEN LOMBOK BARAT" required>
                        </div>
                        <div class="mb-3">
                            <label for="ibukota" class="form-label">Ibu Kota Kabupaten/Kota</label>
                            <input type="text" class="form-control" id="ibukota" name="ibukota" 
                                   value="<?= htmlspecialchars($kantor['ibukota'] ?? '') ?>"
                                   placeholder="Contoh: Mataram" required>
                            <small class="form-text text-muted">Nama ibu kota yang akan digunakan dalam bagian ttd</small>
                        </div>
                        <div class="mb-3">
                            <label for="jln" class="form-label">Alamat Kantor</label>
                            <input type="text" class="form-control" id="jln" name="jln" 
                                   value="<?= htmlspecialchars($kantor['alamat'] ?? '') ?>"
                                   placeholder="Alamat lengkap kantor" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telfon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telfon" name="telfon" 
                                       value="<?= htmlspecialchars($kantor['telepon'] ?? '') ?>"
                                       placeholder="Telp. (0370) 681234" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fax" class="form-label">Fax</label>
                                <input type="text" class="form-control" id="fax" name="fax" 
                                       value="<?= htmlspecialchars($kantor['fax'] ?? '') ?>"
                                       placeholder="Fax. (0370) 681235" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($kantor['email'] ?? '') ?>"
                                   placeholder="kankemenag@kemenag.go.id" required>
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control" id="website" name="website" 
                                   value="<?= htmlspecialchars($kantor['website'] ?? '') ?>"
                                   placeholder="https://kankemenag.kemenag.go.id">
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo Kantor</label>
                            <?php if (!empty($kantor['logo_path'])): ?>
                                <div class="mb-2">
                                    <img src="<?= htmlspecialchars($kantor['logo_path']) ?>" alt="Logo Kantor" class="img-thumbnail" style="max-height: 100px;">
                                    <small class="text-muted d-block">Logo saat ini</small>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <small class="form-text text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>
                            Simpan Data Kantor
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Security Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Keamanan
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="currentPassword">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="newPassword">
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirmPassword">
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>
                            Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Data Management -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-database me-2"></i>
                        Manajemen Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-info" type="button">
                            <i class="fas fa-download me-2"></i>
                            Export Data Surat
                        </button>
                        <button class="btn btn-secondary" type="button">
                            <i class="fas fa-upload me-2"></i>
                            Import Template
                        </button>
                        <button class="btn btn-outline-primary" type="button">
                            <i class="fas fa-sync-alt me-2"></i>
                            Sinkronisasi Data
                        </button>
                        <hr>
                        <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#clearDataModal">
                            <i class="fas fa-trash me-2"></i>
                            Hapus Semua Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clear Data Modal -->
<div class="modal fade" id="clearDataModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Apakah Anda yakin ingin menghapus semua data surat yang tersimpan?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>
                    Ya, Hapus Semua
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$additionalCSS = '
<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.form-check-input:checked {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    border-radius: 15px 15px 0 0;
}
</style>
';

$additionalJS = '
<script>
// Handle Kantor Form Submission with SweetAlert
document.addEventListener("DOMContentLoaded", function() {
    const kantorForm = document.getElementById("kantorForm");
    
    if (kantorForm) {
        kantorForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector("button[type=submit]");
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...`;
            
            fetch("/settings/kantor", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    throw new Error("Server returned non-JSON response");
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: "Data kantor berhasil diperbarui",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#28a745"
                    }).then(() => {
                        window.location.reload();
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
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});
</script>
';

$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>