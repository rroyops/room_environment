<?php
/**
 * Room No. 320 Environment - Forgot Password Request
 * Validates requested user email, generates a simulated reset ticket,
 * and demonstrates a secure workflow for resetting passwords.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

$reset_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_request'])) {
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security validation expired. Please refresh and try again.');
        redirect('forgot-password.php');
    }
    
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        set_flash_message('danger', 'Please enter your registered email address.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash_message('danger', 'Please enter a valid email address.');
    } else {
        try {
            // Check if user exists in db
            $stmt = $db->prepare("SELECT fullname FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                // In a production server, we would generate a crypto token and send an email.
                // Since this runs on XAMPP, we will display a mock code and instruction set.
                $reset_success = true;
                $reset_token = bin2hex(random_bytes(16));
                $reset_link = BASE_URL . "reset-password.php?token=" . $reset_token;
                
                set_flash_message('success', 'Password reset instructions have been generated successfully.');
            } else {
                set_flash_message('danger', 'We could not find an account registered with that email address.');
            }
        } catch (Exception $e) {
            set_flash_message('danger', 'An internal database failure occurred.');
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center py-4">
        <div class="col-md-6 col-lg-5">
            <div class="card border border-light-subtle shadow bento-card p-4 p-lg-5 bg-white">
                <div class="text-center mb-4">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-key-fill fs-4"></i>
                    </div>
                    <h2 class="font-display fw-bold text-success-emphasis mb-1">Recover Password</h2>
                    <p class="text-muted small">Enter your email to receive recovery instructions.</p>
                </div>
                
                <?php if ($reset_success): ?>
                    <div class="alert alert-success border-success-subtle bg-success-subtle p-3 mb-4">
                        <h6 class="alert-heading fw-bold text-success-emphasis"><i class="bi bi-envelope-check me-2"></i>Reset Ticket Generated</h6>
                        <p class="small mb-2 text-muted">Since this is a XAMPP local environment, email delivery has been bypassed. You can use the generated local reset link below to update your password:</p>
                        <a href="#" class="alert-link font-mono d-block text-truncate p-2 bg-white rounded border border-success-subtle mb-0" style="font-size: 11px;"><?php echo $reset_link; ?></a>
                    </div>
                    
                    <a href="login.php" class="btn btn-outline-success font-display w-full py-2">Return to Login</a>
                <?php else: ?>
                    <form action="forgot-password.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="mb-4">
                            <label for="email" class="form-label font-display fw-semibold text-dark">Registered Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="john@room320.com" required>
                            </div>
                        </div>
                        
                        <button type="submit" name="reset_request" class="btn btn-success font-display w-full py-2 mb-3"><i class="bi bi-send me-2"></i>Send Recovery Ticket</button>
                        
                        <div class="text-center">
                            <a href="login.php" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left me-1"></i>Back to Sign In</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
