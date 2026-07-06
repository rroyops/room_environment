<?php
/**
 * Room No. 320 Environment - Admin Section: Announcements CRUD
 * Manages official community bulletins and environmental news updates.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

// Authorization Guard
if (!is_admin()) {
    set_flash_message('danger', 'Admin credentials are required.');
    redirect(BASE_URL . 'login.php');
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$edit_announcement = null;

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security validation expired.');
        redirect('announcements.php');
    }
    
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        set_flash_message('danger', 'All fields are strictly required.');
    } else {
        try {
            if (isset($_POST['add_announcement'])) {
                $stmt = $db->prepare("INSERT INTO announcements (title, content) VALUES (?, ?)");
                $stmt->execute([$title, $content]);
                set_flash_message('success', 'Bulletin announcement published.');
                redirect('announcements.php');
            } elseif (isset($_POST['edit_announcement_submit'])) {
                $id = (int)$_POST['announcement_id'];
                $stmt = $db->prepare("UPDATE announcements SET title = ?, content = ? WHERE id = ?");
                $stmt->execute([$title, $content, $id]);
                set_flash_message('success', 'Bulletin announcement updated.');
                redirect('announcements.php');
            }
        } catch (Exception $e) {
            set_flash_message('danger', 'Operation failed: ' . $e->getMessage());
        }
    }
}

// Handle GET Actions
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->execute([$id]);
        set_flash_message('success', 'Announcement deleted.');
    } catch (Exception $e) {
        set_flash_message('danger', 'Delete failed.');
    }
    redirect('announcements.php');
} elseif ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("SELECT * FROM announcements WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $edit_announcement = $stmt->fetch();
        if (!$edit_announcement) {
            set_flash_message('danger', 'Announcement not found.');
            redirect('announcements.php');
        }
    } catch (Exception $e) {
        set_flash_message('danger', 'Database error.');
        redirect('announcements.php');
    }
}

// Fetch announcements
$announcements = [];
try {
    $announcements = $db->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    // schema empty
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <div>
            <h2 class="font-display fw-bold text-success-emphasis mb-0">Manage Community Bulletins</h2>
            <small class="text-muted">Broadcast official announcements to all registered user screens.</small>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Console</a>
    </div>

    <!-- Edit Announcement Screen -->
    <?php if ($action === 'edit' && $edit_announcement): ?>
        <div class="card border shadow-xs bento-card p-4 p-lg-5 bg-white mb-5">
            <h4 class="font-display fw-bold text-success-emphasis mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Bulletin Post</h4>
            <form action="announcements.php?action=edit&id=<?php echo $edit_announcement['id']; ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="announcement_id" value="<?php echo $edit_announcement['id']; ?>">
                
                <div class="mb-3">
                    <label class="form-label font-display fw-semibold">Bulletin Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_announcement['title']); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label font-display fw-semibold">Content</label>
                    <textarea name="content" class="form-control" rows="6" required><?php echo htmlspecialchars($edit_announcement['content']); ?></textarea>
                </div>
                
                <div class="border-top pt-4 mt-4 d-flex gap-2">
                    <button type="submit" name="edit_announcement_submit" class="btn btn-success font-display px-4"><i class="bi bi-check-circle me-1"></i>Save Updates</button>
                    <a href="announcements.php" class="btn btn-outline-secondary font-display px-4">Cancel</a>
                </div>
            </form>
        </div>
        
    <!-- List & Add Screen -->
    <?php else: ?>
        <div class="row g-4">
            <!-- Add Announcement form -->
            <div class="col-lg-4">
                <div class="card border shadow-xs bento-card p-4 bg-white">
                    <h4 class="font-display fw-bold text-success-emphasis mb-3"><i class="bi bi-plus-circle me-2"></i>Add Bulletin</h4>
                    <form action="announcements.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Bulletin Title</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Weekly sensor maintenance schedule" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Bulletin Content</label>
                            <textarea name="content" class="form-control form-control-sm" rows="6" placeholder="Write announcement text here..." required></textarea>
                        </div>
                        
                        <button type="submit" name="add_announcement" class="btn btn-success btn-sm font-display w-full py-2"><i class="bi bi-check-circle me-1"></i>Publish Bulletin</button>
                    </form>
                </div>
            </div>
            
            <!-- List Panel -->
            <div class="col-lg-8">
                <div class="card border shadow-xs bento-card bg-white p-4">
                    <h5 class="font-display fw-bold text-success-emphasis mb-4"><i class="bi bi-megaphone me-2"></i>Bulletins Registry</h5>
                    
                    <?php if (empty($announcements)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-megaphone-fill fs-2 text-muted mb-3 d-block"></i>
                            <p class="text-muted small">No announcements posted. Add one using the form.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-light-subtle small font-sans">
                                <thead class="table-light font-display">
                                    <tr>
                                        <th>Title</th>
                                        <th>Content Preview</th>
                                        <th>Date Published</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($announcements as $announce): ?>
                                        <tr id="announcement-row-<?php echo $announce['id']; ?>">
                                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($announce['title']); ?></td>
                                            <td class="text-muted small"><?php echo htmlspecialchars(substr($announce['content'], 0, 80)) . '...'; ?></td>
                                            <td class="font-mono text-muted"><?php echo date('Y-m-d H:i', strtotime($announce['created_at'])); ?></td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="announcements.php?action=edit&id=<?php echo $announce['id']; ?>" class="btn btn-outline-success btn-sm py-1 px-2" title="Edit"><i class="bi bi-pencil"></i></a>
                                                    <a href="announcements.php?action=delete&id=<?php echo $announce['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Remove this announcement?');" title="Delete"><i class="bi bi-trash"></i></a>
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
