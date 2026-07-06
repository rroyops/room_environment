<?php
/**
 * Room No. 320 Environment - Secure User Registration
 * Implements prepared check queries, unique user constraints, robust validators,
 * and cryptographic hashing (bcrypt) to safely build fresh user credentials.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Redirect if already authenticated
if (is_logged_in()) {
    redirect('dashboard.php');
}

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security validation expired. Please refresh the page and try again.');
        redirect('register.php');
    }
    
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Server-side validation
    if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        set_flash_message('danger', 'All registration fields are strictly required.');
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        set_flash_message('danger', 'Username must be between 3 to 20 characters and contain only letters, numbers, or underscores.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash_message('danger', 'Please enter a structurally valid email address.');
    } elseif (strlen($password) < 6) {
        set_flash_message('danger', 'Password must be at least 6 characters in length.');
    } elseif ($password !== $confirm_password) {
        set_flash_message('danger', 'The passwords entered do not match each other.');
    } else {
        try {
            // Check if username or email is already taken
            $stmt_check = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt_check->execute([$username, $email]);
            
            if ($stmt_check->rowCount() > 0) {
                set_flash_message('danger', 'Username or email address is already registered on our servers.');
            } else {
                // Secure password hashing
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // Write into SQL database
                $stmt_insert = $db->prepare("INSERT INTO users (username, email, password, role, fullname) VALUES (?, ?, ?, 'user', ?)");
                $stmt_insert->execute([$username, $email, $hashed_password, $fullname]);
                
                set_flash_message('success', 'Registration completed successfully! Please sign in using your new credentials.');
                redirect('login.php');
            }
        } catch (Exception $e) {
            set_flash_message('danger', 'An internal database registration error occurred.');
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center py-3">
        <div class="col-md-7 col-lg-6">
            <div class="card border border-light-subtle shadow bento-card p-4 p-lg-5 bg-white">
                <div class="text-center mb-4">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-plus-fill fs-4"></i>
                    </div>
                    <h2 class="font-display fw-bold text-success-emphasis mb-1">Create Account</h2>
                    <p class="text-muted small">Join the Room 320 Environmental Research ecosystem.</p>
                </div>
                
                <form action="register.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="fullname" class="form-label font-display fw-semibold text-dark">Full Name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="e.g. Johnathan Doe" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="username" class="form-label font-display fw-semibold text-dark">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="e.g. john_doe" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label font-display fw-semibold text-dark">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label font-display fw-semibold text-dark">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Min. 6 characters" required>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label font-display fw-semibold text-dark">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                        </div>
                    </div>
                    
                    <div class="form-check mt-4 mb-4">
                        <input class="form-check-input" type="checkbox" value="" id="agreeTerms" required>
                        <label class="form-check-label text-muted small" for="agreeTerms">
                            I verify that my sensor logs and upload uploads conform to university sustainable research standards.
                        </label>
                    </div>
                    
                    <button type="submit" name="register" class="btn btn-success font-display w-full py-2 mb-3"><i class="bi bi-person-check-fill me-2"></i>Create Account</button>
                    
                    <div class="text-center">
                        <small class="text-muted">Already have an account? <a href="login.php" class="text-success text-decoration-none fw-semibold">Sign in here</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
