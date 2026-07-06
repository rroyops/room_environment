<?php
/**
 * Room No. 320 Environment - User Dashboard
 * Protects access with session checkers, lists active user contributions (gallery uploads),
 * and feeds the latest bulletins directly to their console.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Access guard
if (!is_logged_in()) {
    set_flash_message('danger', 'Please login to access your research dashboard.');
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user_info = [];
$my_uploads = [];

try {
    // Fetch user details from SQL
    $stmt_user = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt_user->execute([$user_id]);
    $user_info = $stmt_user->fetch();
    
    // Fetch gallery items uploaded by this specific user
    $stmt_uploads = $db->prepare("SELECT * FROM gallery WHERE uploaded_by = ? ORDER BY created_at DESC");
    $stmt_uploads->execute([$user_info['fullname']]);
    $my_uploads = $stmt_uploads->fetchAll();
    
} catch (Exception $e) {
    // Fail-safe
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Section -->
<div class="bg-success-subtle py-4 border-bottom">
    <div class="container py-2">
        <div class="d-flex flex-wrap align-items-center justify-content-between g-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center p-3" style="width: 55px; height: 55px; font-size: 20px;">
                    <i class="bi bi-grid-1x2-fill"></i>
                </div>
                <div>
                    <h1 class="h3 font-display fw-bold text-success-emphasis mb-0">My Dashboard</h1>
                    <small class="text-muted">Welcome, <?php echo htmlspecialchars($user_info['fullname']); ?></small>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <a href="profile.php" class="btn btn-outline-success font-display btn-sm"><i class="bi bi-gear-fill me-1"></i>Edit Profile</a>
                <a href="gallery.php" class="btn btn-success font-display btn-sm"><i class="bi bi-cloud-upload-fill me-1"></i>Share Photo</a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar: Profile Overview Widget -->
        <div class="col-lg-4">
            <div class="card border shadow-xs text-center p-4 bg-white bento-card">
                <div class="bg-light rounded-circle shadow-xs mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; overflow: hidden; border: 3px solid #fff;">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200" alt="Avatar" class="w-100 h-100 object-fit-cover">
                </div>
                
                <h4 class="font-display fw-bold text-success-emphasis mb-1"><?php echo htmlspecialchars($user_info['fullname']); ?></h4>
                <span class="badge bg-success-subtle text-success font-mono mb-3" style="font-size: 10px;"><?php echo strtoupper(htmlspecialchars($user_info['role'])); ?></span>
                
                <p class="text-muted small mb-4" style="font-style: italic;">
                    "<?php echo htmlspecialchars($user_info['bio'] ?: 'No biography written yet. Click Edit Profile to add one!'); ?>"
                </p>
                
                <div class="border-top pt-3 text-start small">
                    <div class="d-flex align-items-center justify-content-between text-muted mb-2">
                        <span><i class="bi bi-person me-2"></i>Username</span>
                        <span class="text-dark fw-bold"><?php echo htmlspecialchars($user_info['username']); ?></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between text-muted mb-2">
                        <span><i class="bi bi-envelope me-2"></i>Email Address</span>
                        <span class="text-dark fw-semibold"><?php echo htmlspecialchars($user_info['email']); ?></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between text-muted">
                        <span><i class="bi bi-clock me-2"></i>Joined Portal</span>
                        <span class="text-dark font-mono"><?php echo date('M d, Y', strtotime($user_info['created_at'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Panel: Contributions Grid -->
        <div class="col-lg-8">
            <div class="card border shadow-xs bg-white bento-card p-4">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h4 class="font-display fw-bold text-success-emphasis mb-0"><i class="bi bi-images me-2"></i>My Shared Photographs</h4>
                    <span class="badge bg-success font-mono"><?php echo count($my_uploads); ?> Items</span>
                </div>
                
                <?php if (empty($my_uploads)): ?>
                    <div class="text-center py-5 border border-dashed rounded-3 bg-light">
                        <i class="bi bi-cloud-arrow-up fs-1 text-muted mb-3 d-block"></i>
                        <h5>No Shared Photos</h5>
                        <p class="text-muted small">You haven't uploaded any environmental project photos yet.</p>
                        <a href="gallery.php" class="btn btn-success btn-sm font-display"><i class="bi bi-plus-lg me-1"></i>Share First Photo</a>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($my_uploads as $upload): ?>
                            <div class="col-sm-6">
                                <div class="card h-100 border shadow-xs overflow-hidden">
                                    <div style="height: 140px; background-color: #eaeaea; position: relative;">
                                        <?php 
                                        $img_url = BASE_URL . 'uploads/' . $upload['image_path'];
                                        ?>
                                        <img src="<?php echo $img_url; ?>" alt="" class="w-100 h-100 object-fit-cover" onerror="this.src='https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&q=80&w=600'">
                                        <span class="badge bg-success font-mono position-absolute" style="top: 10px; left: 10px; font-size: 9px;"><?php echo htmlspecialchars($upload['category']); ?></span>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="font-display fw-bold mb-1 text-dark"><?php echo htmlspecialchars($upload['title']); ?></h6>
                                        <p class="text-muted small mb-0"><?php echo htmlspecialchars(substr($upload['description'], 0, 80)) . '...'; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
