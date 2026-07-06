<?php
/**
 * Room No. 320 Environment - Admin Section: Main Dashboard
 * Displays core metrics, fast count widgets, and latest messages.
 * Restricts access purely to accounts containing 'admin' privileges.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

// Authorization Guard
if (!is_admin()) {
    set_flash_message('danger', 'Unauthorized operation. Admin access credentials are required.');
    redirect(BASE_URL . 'login.php');
}

// Variables for count calculations
$counts = [
    'users' => 0,
    'members' => 0,
    'gallery' => 0,
    'activities' => 0,
    'messages' => 0,
    'announcements' => 0
];

try {
    $counts['users'] = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $counts['members'] = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
    $counts['gallery'] = $db->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
    $counts['activities'] = $db->query("SELECT COUNT(*) FROM activities")->fetchColumn();
    $counts['announcements'] = $db->query("SELECT COUNT(*) FROM announcements")->fetchColumn();
    $counts['messages'] = $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
    
    // Fetch latest 3 unread contact messages
    $latest_messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 3")->fetchAll();
    
} catch (Exception $e) {
    $latest_messages = [];
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Header Banner -->
<div class="bg-success-subtle py-4 border-bottom">
    <div class="container py-2">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 p-3" style="width: 55px; height: 55px;">
                <i class="bi bi-speedometer2 fs-3"></i>
            </div>
            <div>
                <h1 class="h3 font-display fw-bold text-success-emphasis mb-0">Admin Management Console</h1>
                <small class="text-muted">Global control panel for Room No. 320 Community and Eco assets.</small>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Bento Grid Counts Row -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card h-100 border text-center p-3 hover-card bg-white bento-card">
                <i class="bi bi-people text-success fs-3 mb-2"></i>
                <h6 class="text-muted font-display small mb-1">Users</h6>
                <h3 class="fw-bold text-success-emphasis mb-0 font-mono"><?php echo $counts['users']; ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card h-100 border text-center p-3 hover-card bg-white bento-card">
                <i class="bi bi-person-workspace text-success fs-3 mb-2"></i>
                <h6 class="text-muted font-display small mb-1">Members</h6>
                <h3 class="fw-bold text-success-emphasis mb-0 font-mono"><?php echo $counts['members']; ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card h-100 border text-center p-3 hover-card bg-white bento-card">
                <i class="bi bi-images text-success fs-3 mb-2"></i>
                <h6 class="text-muted font-display small mb-1">Gallery</h6>
                <h3 class="fw-bold text-success-emphasis mb-0 font-mono"><?php echo $counts['gallery']; ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card h-100 border text-center p-3 hover-card bg-white bento-card">
                <i class="bi bi-calendar-event text-success fs-3 mb-2"></i>
                <h6 class="text-muted font-display small mb-1">Activities</h6>
                <h3 class="fw-bold text-success-emphasis mb-0 font-mono"><?php echo $counts['activities']; ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card h-100 border text-center p-3 hover-card bg-white bento-card">
                <i class="bi bi-megaphone text-success fs-3 mb-2"></i>
                <h6 class="text-muted font-display small mb-1">Bulletins</h6>
                <h3 class="fw-bold text-success-emphasis mb-0 font-mono"><?php echo $counts['announcements']; ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card h-100 border border-success-subtle bg-success-subtle text-center p-3 hover-card bento-card">
                <i class="bi bi-chat-left-dots text-success fs-3 mb-2"></i>
                <h6 class="text-success-emphasis font-display small mb-1">Unread Mails</h6>
                <h3 class="fw-bold text-success mb-0 font-mono"><?php echo $counts['messages']; ?></h3>
            </div>
        </div>
    </div>

    <!-- Admin CRUD Quick links -->
    <div class="row g-4">
        <!-- Sidebar Navigation Shortcuts -->
        <div class="col-md-4">
            <div class="card border shadow-xs bg-white p-3 bento-card mb-4">
                <h5 class="font-display fw-bold text-success-emphasis mb-3 px-2">CRUD Sectors</h5>
                <div class="list-group list-group-flush small" id="admin-crud-links">
                    <a href="<?php echo BASE_URL; ?>admin/members.php" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 border-0 rounded-2"><span class="fw-medium text-dark"><i class="bi bi-person-workspace me-2 text-success"></i>Manage Members</span><i class="bi bi-chevron-right text-muted"></i></a>
                    <a href="<?php echo BASE_URL; ?>admin/gallery.php" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 border-0 rounded-2"><span class="fw-medium text-dark"><i class="bi bi-images me-2 text-success"></i>Manage Gallery</span><i class="bi bi-chevron-right text-muted"></i></a>
                    <a href="<?php echo BASE_URL; ?>admin/activities.php" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 border-0 rounded-2"><span class="fw-medium text-dark"><i class="bi bi-calendar-event me-2 text-success"></i>Manage Activities</span><i class="bi bi-chevron-right text-muted"></i></a>
                    <a href="<?php echo BASE_URL; ?>admin/announcements.php" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 border-0 rounded-2"><span class="fw-medium text-dark"><i class="bi bi-megaphone me-2 text-success"></i>Manage Announcements</span><i class="bi bi-chevron-right text-muted"></i></a>
                    <a href="<?php echo BASE_URL; ?>admin/messages.php" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 border-0 rounded-2"><span class="fw-medium text-dark"><i class="bi bi-chat-left-dots me-2 text-success"></i>Manage Messages</span><i class="bi bi-chevron-right text-muted"></i></a>
                </div>
            </div>
            
            <div class="card border shadow-xs bg-success-subtle text-success-emphasis p-4 bento-card">
                <h6 class="font-display fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Secure Administration</h6>
                <p class="small mb-0 opacity-80">This panel is protected by Session Token checks. All CRUD submissions are parsed utilizing strictly parameterized SQL prepared statements to protect against malicious injections.</p>
            </div>
        </div>
        
        <!-- Main: Latest Feed Messages -->
        <div class="col-md-8">
            <div class="card border shadow-xs bg-white p-4 bento-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="font-display fw-bold text-success-emphasis mb-0"><i class="bi bi-chat-square-text me-2"></i>Latest Unread Correspondence</h5>
                    <a href="<?php echo BASE_URL; ?>admin/messages.php" class="small text-success text-decoration-none">Review Mailbox</a>
                </div>
                
                <?php if (empty($latest_messages)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted small">No contact forms or research requests are pending review.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($latest_messages as $msg): ?>
                            <div class="border rounded-3 p-3 bg-light hover-card" id="msg-card-<?php echo $msg['id']; ?>">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($msg['name']); ?> <span class="text-muted font-mono small" style="font-weight: normal; font-size: 11px;">(<?php echo htmlspecialchars($msg['email']); ?>)</span></h6>
                                    <small class="text-muted font-mono" style="font-size: 10px;"><?php echo date('M d, Y H:i', strtotime($msg['created_at'])); ?></small>
                                </div>
                                <div class="fw-semibold text-success-emphasis small mb-1"><?php echo htmlspecialchars($msg['subject']); ?></div>
                                <p class="small text-muted mb-0">
                                    <?php echo htmlspecialchars(substr($msg['message'], 0, 160)) . '...'; ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
