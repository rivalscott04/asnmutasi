<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ASN Mutasi' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap Theme -->
    <style>
        .select2-container--default .select2-selection--single {
            height: calc(1.5em + 0.75rem + 4px);
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057;
            line-height: 1.5;
            padding-left: 0;
            padding-right: 20px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem + 2px);
            right: 0.75rem;
        }
        
        .select2-dropdown {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            border-top: none;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.375rem 0.75rem;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--secondary-color);
        }
        
        .select2-container {
            width: 100% !important;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--secondary-color);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(74, 124, 89, 0.25);
        }
        
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }
    </style>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c5530;
            --secondary-color: #4a7c59;
            --accent-color: #8fbc8f;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: color 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: white !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            transition: transform 0.2s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(44, 85, 48, 0.3);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }
        
        .footer {
            background: var(--primary-color);
            color: white;
            margin-top: 50px;
        }
        
        .alert {
            border: none;
            border-radius: 10px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 124, 89, 0.25);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0;
            margin-bottom: 50px;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
        }
        
        .loading {
            display: none !important;
        }
        
        .loading.show {
            display: block !important;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 50px 0;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
        }
    </style>
    
    <?php if (isset($additionalCSS)): ?>
        <?= $additionalCSS ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-file-alt me-2"></i>
                ASN Mutasi
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-home me-2"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/surat">
                            <i class="fas fa-file-text me-2"></i>Jenis Surat
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pegawai/">
                            <i class="fas fa-users me-2"></i>Data Pegawai
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/settings">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?= $_SESSION['user_name'] ?? 'User' ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/profile">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout" method="POST" class="d-inline logout-form">
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="container mt-3">
            <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>ASN Mutasi</h5>
                    <p class="mb-0">Sistem Pengelolaan Surat untuk Keperluan Mutasi ASN</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?= date('Y') ?> Kementerian Agama. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Loading Overlay -->
    <div class="loading position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="text-center text-white">
            <div class="spinner-border mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div>Memproses...</div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script>
        // Show loading overlay
        function showLoading() {
            $('.loading').addClass('show');
        }
        
        // Hide loading overlay
        function hideLoading() {
            $('.loading').removeClass('show');
        }
        
        // AJAX form submission
        $(document).on('submit', '.ajax-form', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const url = form.attr('action');
            const method = form.attr('method') || 'POST';
            const formData = new FormData(this);
            
            showLoading();
            
            $.ajax({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    
                    if (response.success) {
                        if (response.data && response.data.redirect) {
                            window.location.href = response.data.redirect;
                        } else {
                            showAlert('success', response.message || 'Berhasil!');
                        }
                    } else {
                        showAlert('danger', response.message || 'Terjadi kesalahan!');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    
                    // Check if it's an authentication error
                    if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.show_login_modal) {
                        showLoginModal(xhr.responseJSON.message, xhr.responseJSON.redirect);
                        return;
                    }
                    
                    let message = 'Terjadi kesalahan!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    
                    showAlert('danger', message);
                }
            });
        });
        
        // Show alert
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('.container').first().prepend(alertHtml);
            
            // Auto dismiss after 5 seconds
            setTimeout(function() {
                $('.alert').first().alert('close');
            }, 5000);
        }
        
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Ensure loading overlay is hidden on page load
        $(document).ready(function() {
            $('.loading').removeClass('show').hide();
            hideLoading();
        });
        
        // Force hide loading on window load as well
        $(window).on('load', function() {
            $('.loading').removeClass('show').hide();
            hideLoading();
        });
        
        // Show login modal for unauthenticated users
        function showLoginModal(message, redirectUrl) {
            Swal.fire({
                title: 'Akses Ditolak',
                text: message || 'Anda belum login. Silakan login terlebih dahulu.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2c5530',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-in-alt me-2"></i>Login',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = redirectUrl || '/login';
                }
            });
        }
        
        // Global AJAX setup to handle authentication errors
        $(document).ajaxError(function(event, xhr, settings) {
            if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.show_login_modal) {
                showLoginModal(xhr.responseJSON.message, xhr.responseJSON.redirect);
            }
        });
        
        // Handle clicks on protected links for non-AJAX requests
        $(document).on('click', 'a[href^="/dashboard-"], a[href^="/surat"], a[href^="/settings"]', function(e) {
            // Only handle if user is not logged in (check if login link exists in navbar)
            if ($('a[href="/login"]').length > 0) {
                e.preventDefault();
                showLoginModal('Anda belum login. Silakan login terlebih dahulu untuk mengakses halaman ini.', '/login');
            }
        });
        
        // Handle logout form submission with SweetAlert
        $(document).on('submit', '.logout-form', function(e) {
            e.preventDefault();
            
            const form = $(this);
            
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-out-alt me-2"></i>Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang logout dari sistem',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit logout request
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = response.data.redirect;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan saat logout'
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan sistem';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: message
                            });
                        }
                    });
                }
            });
        });
    </script>
    
    <?php if (isset($additionalJS)): ?>
        <?= $additionalJS ?>
    <?php endif; ?>
</body>
</html>