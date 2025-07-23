<?php
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login
                    </h4>
                </div>
                <div class="card-body p-5">
                    <form action="/login" method="POST" class="ajax-form">
                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold">
                                <i class="fas fa-user me-2"></i>
                                Username
                            </label>
                            <input type="text" class="form-control form-control-lg" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-lock me-2"></i>
                                Password
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
                            </button>
                        </div>
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$additionalJS = '
<script>
$(document).ready(function() {
    // Toggle password visibility
    $(\"#togglePassword\").click(function() {
        const passwordField = $(\"#password\");
        const icon = $(this).find(\"i\");
        
        if (passwordField.attr(\"type\") === \"password\") {
            passwordField.attr(\"type\", \"text\");
            icon.removeClass(\"fa-eye\").addClass(\"fa-eye-slash\");
        } else {
            passwordField.attr(\"type\", \"password\");
            icon.removeClass(\"fa-eye-slash\").addClass(\"fa-eye\");
        }
    });
    

    
    // Handle login form submission with AJAX
    $(\".ajax-form\").on(\"submit\", function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find(\"button[type=\\\"submit\\\"]\");
        const originalText = submitBtn.html();
        
        const username = $(\"#username\").val().trim();
        const password = $(\"#password\").val().trim();
        
        if (!username || !password) {
            Swal.fire({
                icon: \"warning\",
                title: \"Peringatan!\",
                text: \"Mohon lengkapi semua field!\"
            });
            return false;
        }
        
        if (password.length < 6) {
            Swal.fire({
                icon: \"warning\",
                title: \"Peringatan!\",
                text: \"Password minimal 6 karakter!\"
            });
            return false;
        }
        
        // Show loading state
        submitBtn.html(\"<i class=\\\"fas fa-spinner fa-spin me-2\\\"></i>Memproses...\").prop(\"disabled\", true);
        
        // Submit form via AJAX
        $.ajax({
            url: form.attr(\"action\"),
            method: \"POST\",
            data: form.serialize(),
            dataType: \"json\",
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: \"success\",
                        title: \"Berhasil!\",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = response.data.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: \"error\",
                        title: \"Gagal!\",
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let message = \"Terjadi kesalahan sistem\";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: \"error\",
                    title: \"Error!\",
                    text: message
                });
            },
            complete: function() {
                // Reset button
                submitBtn.html(originalText).prop(\"disabled\", false);
            }
        });
    });
});
</script>
';

$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/app.php';
?>