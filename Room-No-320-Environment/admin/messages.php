<?php
/**
 * Room No. 320 Environment - Admin Section: Contact Messages
 * Displays, updates (is_read), or removes public requests/messages.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

// Authorization Guard
if (!is_admin()) {
    set_flash_message('danger', 'Admin credentials are required.');
    redirect(BASE_URL . 'login.php');
}

// Handle mark read / delete GET commands
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $act = $_GET['action'];
    
    try {
        if ($act === 'read') {
            $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
            $stmt->execute([$id]);
            set_flash_message('success', 'Message marked as read.');
        } elseif ($act === 'unread') {
            $stmt = $db->prepare("UPDATE contact_messages SET is_read = 0 WHERE id = ?");
            $stmt->execute([$id]);
            set_flash_message('success', 'Message marked as unread.');
        } elseif ($act === 'delete') {
            $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
            $stmt->execute([$id]);
            set_flash_message('success', 'Message deleted from mailbox.');
        }
    } catch (Exception $e) {
        set_flash_message('danger', 'Operation failed: ' . $e->getMessage());
    }
    redirect('messages.php');
}

// Fetch messages
$messages = [];
try {
    $messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    // schema empty
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <div>
            <h2 class="font-display fw-bold text-success-emphasis mb-0">Support & Collaboration Mailbox</h2>
            <small class="text-muted">Review, read, and delete public correspondence submitted through contact forms.</small>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Console</a>
    </div>

    <!-- Messages List Panel -->
    <div class="card border shadow-xs bento-card bg-white p-4">
        <h5 class="font-display fw-bold text-success-emphasis mb-4"><i class="bi bi-envelope me-2"></i>Inbox Folder</h5>
        
        <?php if (empty($messages)): ?>
            <div class="text-center py-5">
                <i class="bi bi-mailbox fs-2 text-muted mb-3 d-block"></i>
                <p class="text-muted small">No message submissions exist in the mailbox ledger.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle border-light-subtle small font-sans">
                    <thead class="table-light font-display">
                        <tr>
                            <th>Sender Details</th>
                            <th>Subject & Body Preview</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr class="<?php echo !$msg['is_read'] ? 'fw-bold bg-light-subtle' : ''; ?>" id="message-row-<?php echo $msg['id']; ?>">
                                <td>
                                    <div class="text-dark"><?php echo htmlspecialchars($msg['name']); ?></div>
                                    <span class="text-muted font-mono" style="font-size: 10px;"><?php echo htmlspecialchars($msg['email']); ?></span>
                                </td>
                                <td>
                                    <div class="text-success-emphasis fw-bold mb-1"><?php echo htmlspecialchars($msg['subject']); ?></div>
                                    <p class="text-muted mb-0 small text-truncate" style="max-width: 320px;" title="<?php echo htmlspecialchars($msg['message']); ?>">
                                        <?php echo htmlspecialchars($msg['message']); ?>
                                    </p>
                                </td>
                                <td>
                                    <?php if ($msg['is_read']): ?>
                                        <span class="badge bg-secondary-subtle text-secondary">Read</span>
                                    <?php else: ?>
                                        <span class="badge bg-success text-white">Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td class="font-mono text-muted"><?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?></td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <?php if ($msg['is_read']): ?>
                                            <a href="messages.php?action=unread&id=<?php echo $msg['id']; ?>" class="btn btn-outline-secondary btn-sm py-1 px-2" title="Mark as Unread"><i class="bi bi-envelope-open"></i></a>
                                        <?php else: ?>
                                            <a href="messages.php?action=read&id=<?php echo $msg['id']; ?>" class="btn btn-outline-success btn-sm py-1 px-2" title="Mark as Read"><i class="bi bi-envelope-check"></i></a>
                                        <?php endif; ?>
                                        <a href="messages.php?action=delete&id=<?php echo $msg['id']; ?>" class="btn btn-outline-danger btn-sm py-1 px-2" onclick="return confirm('Permanently delete this message?');" title="Delete"><i class="bi bi-trash"></i></a>
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
