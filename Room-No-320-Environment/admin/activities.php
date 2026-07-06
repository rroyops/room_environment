<?php
/**
 * Room No. 320 Environment - Admin Section: Activities CRUD
 * Handles adding, editing, and deleting community ecological initiatives.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

// Authorization Guard
if (!is_admin()) {
    set_flash_message('danger', 'Admin credentials are required.');
    redirect(BASE_URL . 'login.php');
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$edit_activity = null;

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security validation expired.');
        redirect('activities.php');
    }
    
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $activity_date = $_POST['activity_date'];
    
    if (empty($title) || empty($description) || empty($activity_date)) {
        set_flash_message('danger', 'All parameters are required.');
    } else {
        try {
            if (isset($_POST['add_activity'])) {
                // Add Activity
                $stmt = $db->prepare("INSERT INTO activities (title, description, activity_date, image_path) VALUES (?, ?, ?, 'default_activity.png')");
                $stmt->execute([$title, $description, $activity_date]);
                set_flash_message('success', 'Initiative activity logged successfully.');
                redirect('activities.php');
            } elseif (isset($_POST['edit_activity_submit'])) {
                // Edit Activity
                $id = (int)$_POST['activity_id'];
                $stmt = $db->prepare("UPDATE activities SET title = ?, description = ?, activity_date = ? WHERE id = ?");
                $stmt->execute([$title, $description, $activity_date, $id]);
                set_flash_message('success', 'Initiative activity updated successfully.');
                redirect('activities.php');
            }
        } catch (Exception $e) {
            set_flash_message('danger', 'Operation failed: ' . $e->getMessage());
        }
    }
}

// Handle GET requests
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("DELETE FROM activities WHERE id = ?");
        $stmt->execute([$id]);
        set_flash_message('success', 'Activity log removed.');
    } catch (Exception $e) {
        set_flash_message('danger', 'Delete failed.');
    }
    redirect('activities.php');
} elseif ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("SELECT * FROM activities WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $edit_activity = $stmt->fetch();
        if (!$edit_activity) {
            set_flash_message('danger', 'Activity not found.');
            redirect('activities.php');
        }
    } catch (Exception $e) {
        set_flash_message('danger', 'Database error.');
        redirect('activities.php');
    }
}

// Fetch all activities
$all_activities = [];
try {
    $all_activities = $db->query("SELECT * FROM activities ORDER BY activity_date DESC")->fetchAll();
} catch (Exception $e) {
    // schema empty
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <div>
            <h2 class="font-display fw-bold text-success-emphasis mb-0">Manage Community Activities</h2>
            <small class="text-muted">Register, edit, and audit public environmental initiative logs.</small>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Console</a>
    </div>

    <!-- Edit Activity Screen -->
    <?php if ($action === 'edit' && $edit_activity): ?>
        <div class="card border shadow-xs bento-card p-4 p-lg-5 bg-white mb-5">
            <h4 class="font-display fw-bold text-success-emphasis mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Activity Log</h4>
            <form action="activities.php?action=edit&id=<?php echo $edit_activity['id']; ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="activity_id" value="<?php echo $edit_activity['id']; ?>">
                
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label font-display fw-semibold">Activity Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_activity['title']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label font-display fw-semibold">Event Date</label>
                        <input type="date" name="activity_date" class="form-control" value="<?php echo htmlspecialchars($edit_activity['activity_date']); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label font-display fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($edit_activity['description']); ?></textarea>
                    </div>
                </div>
                
                <div class="border-top pt-4 mt-4 d-flex gap-2">
                    <button type="submit" name="edit_activity_submit" class="btn btn-success font-display px-4"><i class="bi bi-check-circle me-1"></i>Save Updates</button>
                    <a href="activities.php" class="btn btn-outline-secondary font-display px-4">Cancel</a>
                </div>
            </form>
        </div>
        
    <!-- List & Add Screen -->
    <?php else: ?>
        <div class="row g-4">
            <!-- Add Activity Widget -->
            <div class="col-lg-4">
                <div class="card border shadow-xs bento-card p-4 bg-white">
                    <h4 class="font-display fw-bold text-success-emphasis mb-3"><i class="bi bi-calendar-plus me-2"></i>Log Initiative</h4>
                    <form action="activities.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Activity Title</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Tree Plantation Drive" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Event Date</label>
                            <input type="date" name="activity_date" class="form-control form-control-sm" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Activity Details</label>
                            <textarea name="description" class="form-control form-control-sm" rows="5" placeholder="Explain the initiative details, participants, metrics reached..." required></textarea>
                        </div>
                        
                        <button type="submit" name="add_activity" class="btn btn-success btn-sm font-display w-full py-2"><i class="bi bi-check-circle me-1"></i>Publish Activity</button>
                    </form>
                </div>
            </div>
            
            <!-- Activities Data table -->
            <div class="col-lg-8">
                <div class="card border shadow-xs bento-card bg-white p-4">
                    <h5 class="font-display fw-bold text-success-emphasis mb-4"><i class="bi bi-journal-check me-2"></i>Initiatives Registry</h5>
                    
                    <?php if (empty($all_activities)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x fs-2 text-muted mb-3 d-block"></i>
                            <p class="text-muted small">No logged activities exist. Record one using the side panel form.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-light-subtle small font-sans">
                                <thead class="table-light font-display">
                                    <tr>
                                        <th>Title</th>
                                        <th>Brief Details</th>
                                        <th>Event Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_activities as $act): ?>
                                        <tr id="activity-row-<?php echo $act['id']; ?>">
                                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($act['title']); ?></td>
                                            <td class="text-muted small"><?php echo htmlspecialchars(substr($act['description'], 0, 75)) . '...'; ?></td>
                                            <td class="font-mono text-muted"><?php echo date('Y-m-d', strtotime($act['activity_date'])); ?></td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="activities.php?action=edit&id=<?php echo $act['id']; ?>" class="btn btn-outline-success btn-sm py-1 px-2" title="Edit"><i class="bi bi-pencil"></i></a>
                                                    <a href="activities.php?action=delete&id=<?php echo $act['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Remove this activity log?');" title="Delete"><i class="bi bi-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
