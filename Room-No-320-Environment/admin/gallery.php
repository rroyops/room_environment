<?php
/**
 * Room No. 320 Environment - Admin Section: Gallery Management
 * Moderates student photo submissions, approves pending items, or deletes images.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

// Authorization Guard
if (!is_admin()) {
    set_flash_message('danger', 'Admin credentials are required.');
    redirect(BASE_URL . 'login.php');
}

// Handle Status Approval Toggles
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $act = $_GET['action'];
    
    try {
        if ($act === 'approve') {
            $stmt = $db->prepare("UPDATE gallery SET is_approved = 1 WHERE id = ?");
            $stmt->execute([$id]);
            set_flash_message('success', 'Photograph approved and published.');
        } elseif ($act === 'reject') {
            $stmt = $db->prepare("UPDATE gallery SET is_approved = 0 WHERE id = ?");
            $stmt->execute([$id]);
            set_flash_message('warning', 'Photograph retracted from gallery.');
        } elseif ($act === 'delete') {
            // Delete photo record & physical file if it exists
            $stmt_img = $db->prepare("SELECT image_path FROM gallery WHERE id = ?");
            $stmt_img->execute([$id]);
            $img = $stmt_img->fetch();
            
            if ($img) {
                $filepath = __DIR__ . '/../uploads/' . $img['image_path'];
                if (file_exists($filepath) && $img['image_path'] !== 'default_image.png') {
                    unlink($filepath);
                }
                
                $stmt_del = $db->prepare("DELETE FROM gallery WHERE id = ?");
                $stmt_del->execute([$id]);
                set_flash_message('success', 'Gallery photo deleted permanently.');
            }
        }
    } catch (Exception $e) {
        set_flash_message('danger', 'Operation failed: ' . $e->getMessage());
    }
    redirect('gallery.php');
}

// Fetch all gallery items
$photos = [];
try {
    $photos = $db->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    // schema empty
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <div>
            <h2 class="font-display fw-bold text-success-emphasis mb-0">Moderate Gallery Submissions</h2>
            <small class="text-muted">Review, approve, or remove student uploads for the Room 320 Environmental Gallery.</small>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Console</a>
    </div>

    <!-- Gallery Management Table -->
    <div class="card border shadow-xs bento-card bg-white p-4">
        <h5 class="font-display fw-bold text-success-emphasis mb-4"><i class="bi bi-shield-check me-2"></i>Photographic Logs Moderator</h5>
        
        <?php if (empty($photos)): ?>
            <div class="text-center py-5">
                <i class="bi bi-images fs-2 text-muted mb-3 d-block"></i>
                <p class="text-muted small">No photos exist inside the system database ledger yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle border-light-subtle small">
                    <thead class="table-light font-display">
                        <tr>
                            <th>Preview</th>
                            <th>Title & Description</th>
                            <th>Uploader Details</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($photos as $photo): ?>
                            <tr id="gallery-row-<?php echo $photo['id']; ?>">
                                <td style="width: 80px;">
                                    <div class="border rounded bg-light overflow-hidden" style="width: 64px; height: 48px;">
                                        <?php 
                                        $filepath = __DIR__ . '/../uploads/' . $photo['image_path'];
                                        $src = file_exists($filepath) ? BASE_URL . 'uploads/' . $photo['image_path'] : 'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&q=80&w=150';
                                        ?>
                                        <img src="<?php echo $src; ?>" alt="" class="w-100 h-100 object-fit-cover">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($photo['title']); ?></div>
                                    <p class="text-muted small mb-0 text-truncate" style="max-width: 280px;"><?php echo htmlspecialchars($photo['description']); ?></p>
                                </td>
                                <td>
                                    <div class="text-dark fw-semibold"><?php echo htmlspecialchars($photo['uploaded_by']); ?></div>
                                    <span class="badge bg-secondary-subtle text-secondary font-mono" style="font-size: 8px;"><?php echo htmlspecialchars($photo['category']); ?></span>
                                </td>
                                <td>
                                    <?php if ($photo['is_approved']): ?>
                                        <span class="badge bg-success-subtle text-success d-inline-flex align-items-center gap-1"><i class="bi bi-check-circle-fill" style="font-size: 10px;"></i> Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning d-inline-flex align-items-center gap-1"><i class="bi bi-clock-history" style="font-size: 10px;"></i> Retracted</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <?php if ($photo['is_approved']): ?>
                                            <a href="gallery.php?action=reject&id=<?php echo $photo['id']; ?>" class="btn btn-outline-warning btn-sm py-1 px-2" title="Retract / Hide"><i class="bi bi-eye-slash"></i></a>
                                        <?php else: ?>
                                            <a href="gallery.php?action=approve&id=<?php echo $photo['id']; ?>" class="btn btn-outline-success btn-sm py-1 px-2" title="Approve & Publish"><i class="bi bi-check-lg"></i></a>
                                        <?php endif; ?>
                                        <a href="gallery.php?action=delete&id=<?php echo $photo['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Permanently delete this photo from server?');" title="Delete"><i class="bi bi-trash"></i></a>
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
