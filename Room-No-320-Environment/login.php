<?php
/**
 * Room No. 320 Environment - Secure User Login
 * Implements prepared queries, secure session injection, XSS filtering,
 * and handles CSRF validation to protect against malicious request hijacking.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Redirect to dashboard if already authenticated
if (is_logged_in()) {
    redirect('dashboard.php');
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security validation expired. Please refresh the page and try again.');
        redirect('login.php');
    }
    
    $identity = trim($_POST['identity']); // Can be username or email
    $password = $_POST['password'];
    
    if (empty($identity) || empty($password)) {
        set_flash_message('danger', 'Please supply both your username/email and password.');
    } else {
        try {
            // Find user in SQL db
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$identity, $identity]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Store authentication fields in active session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_fullname'] = $user['fullname'];
                
                set_flash_message('success', 'Welcome back, ' . htmlspecialchars($user['fullname']) . '! Access granted.');
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    redirect('admin/dashboard.php');
                } else {
                    redirect('dashboard.php');
                }
            } else {
                set_flash_message('danger', 'Invalid username/email or password credentials.');
            }
        } catch (Exception $e) {
            set_flash_message('danger', 'An internal error occurred during processing.');
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
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>
                    <h2 class="font-display fw-bold text-success-emphasis mb-1">Account Login</h2>
                    <p class="text-muted small">Access your custom Room 320 environmental panels.</p>
                </div>
                
                <form action="login.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    
                    <div class="mb-3">
                        <label for="identity" class="form-label font-display fw-semibold text-dark">Username or Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" id="identity" name="identity" placeholder="e.g. admin or admin@room320.com" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <label for="password" class="form-label font-display fw-semibold text-dark mb-0">Password</label>
                            <a href="forgot-password.php" class="text-success small text-decoration-none">Forgot Password?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                            <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-success font-display w-full py-2 mb-3"><i class="bi bi-box-arrow-in-right me-2"></i>Sign In</button>
                    
                    <div class="text-center">
                        <small class="text-muted">Don't have an account? <a href="register.php" class="text-success text-decoration-none fw-semibold">Register here</a></small>
                    </div>
                </form>
            </div>
            
            <!-- Quick Login Hints / Credentials Box -->
            <div class="card border border-success-subtle bg-success-subtle mt-4">
                <div class="card-body p-3 text-center">
                    <p class="mb-1 text-success-emphasis small fw-bold"><i class="bi bi-info-circle me-1"></i> Quick Test Accounts (XAMPP Setup)</p>
                    <div class="d-flex justify-content-center gap-3 small font-mono" style="font-size: 11px;">
                        <div><strong class="text-success">Admin:</strong> admin / admin123</div>
                        <div><strong class="text-success">User:</strong> john / password123</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
