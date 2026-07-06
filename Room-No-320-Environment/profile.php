<?php
/**
 * Room No. 320 Environment - Profile Management
 * Session-guarded file enabling users to update full names, bio notes,
 * and change passwords securely using validation checks and prepared queries.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Access guard
if (!is_logged_in()) {
    set_flash_message('danger', 'Please login to manage your account details.');
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user_info = [];

try {
    // Fetch user details
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();
} catch (Exception $e) {
    // fail-safe
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security validation expired. Please refresh the page and try again.');
        redirect('profile.php');
    }
    
    $fullname = trim($_POST['fullname']);
    $bio = trim($_POST['bio']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($fullname)) {
        set_flash_message('danger', 'Full name cannot be blank.');
    } else {
        try {
            // Base SQL
            $sql = "UPDATE users SET fullname = ?, bio = ?";
            $params = [$fullname, $bio];
            
            // If updating password
            if (!empty($new_password)) {
                if (strlen($new_password) < 6) {
                    throw new Exception('New password must be at least 6 characters.');
                }
                if ($new_password !== $confirm_password) {
                    throw new Exception('New password confirmation does not match.');
                }
                
                $sql .= ", password = ?";
                $params[] = password_hash($new_password, PASSWORD_BCRYPT);
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $user_id;
            
            $stmt_update = $db->prepare($sql);
            $stmt_update->execute($params);
            
            // Sync session variables
            $_SESSION['user_fullname'] = $fullname;
            
            set_flash_message('success', 'Your environmental researcher profile was updated successfully!');
            redirect('profile.php');
        } catch (Exception $e) {
            set_flash_message('danger', 'Profile update failed: ' . $e->getMessage());
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Section -->
<div class="bg-success-subtle py-4 border-bottom">
    <div class="container py-2">
        <h1 class="h3 font-display fw-bold text-success-emphasis mb-0"><i class="bi bi-gear-wide-connected me-2"></i>Edit Profile Details</h1>
        <small class="text-muted">Modify your display parameters and security details.</small>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border shadow-xs bento-card p-4 p-lg-5 bg-white">
                <form action="profile.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    
                    <h5 class="font-display fw-bold text-success-emphasis border-bottom pb-2 mb-4"><i class="bi bi-person-badge me-2"></i>Biographical Information</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="username" class="form-label font-display fw-semibold text-dark">Platform Username</label>
                            <input type="text" class="form-control bg-light font-mono" id="username" value="<?php echo htmlspecialchars($user_info['username']); ?>" readonly>
                            <span class="text-muted" style="font-size: 10px;">Username strings are structurally immutable on XAMPP locks.</span>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label font-display fw-semibold text-dark">Platform Email</label>
                            <input type="text" class="form-control bg-light font-mono" id="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label for="fullname" class="form-label font-display fw-semibold text-dark">Full Display Name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user_info['fullname']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="bio" class="form-label font-display fw-semibold text-dark">Short Researcher Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Briefly describe your focus areas (e.g., moss walls, air sensory systems, solar hydration feeds)..."><?php echo htmlspecialchars($user_info['bio']); ?></textarea>
                        </div>
                    </div>
                    
                    <h5 class="font-display fw-bold text-success-emphasis border-bottom pb-2 mb-4"><i class="bi bi-key me-2"></i>Credentials / Change Password</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="new_password" class="form-label font-display fw-semibold text-dark">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Leave blank to retain current password">
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label font-display fw-semibold text-dark">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repeat new password">
                        </div>
                    </div>
                    
                    <div class="border-top pt-4 d-flex gap-3">
                        <button type="submit" name="update_profile" class="btn btn-success font-display px-4"><i class="bi bi-check-circle me-1"></i>Save Parameters</button>
                        <a href="dashboard.php" class="btn btn-outline-secondary font-display px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
