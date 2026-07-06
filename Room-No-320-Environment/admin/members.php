<?php
/**
 * Room No. 320 Environment - Admin Section: Members CRUD
 * Implements create, read, update, and delete actions for team profiles.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

// Authorization Guard
if (!is_admin()) {
    set_flash_message('danger', 'Admin credentials are required.');
    redirect(BASE_URL . 'login.php');
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$edit_member = null;

// Handle CRUD Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security token expired.');
        redirect('members.php');
    }
    
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $bio = trim($_POST['bio']);
    $joined_date = $_POST['joined_date'];
    
    if (empty($name) || empty($role) || empty($email) || empty($bio) || empty($joined_date)) {
        set_flash_message('danger', 'All fields except phone are required.');
    } else {
        try {
            if (isset($_POST['add_member'])) {
                // ADD Member
                $stmt = $db->prepare("INSERT INTO members (name, role, email, phone, bio, joined_date, photo) VALUES (?, ?, ?, ?, ?, ?, 'default_member.png')");
                $stmt->execute([$name, $role, $email, $phone, $bio, $joined_date]);
                set_flash_message('success', 'Member profile added successfully.');
                redirect('members.php');
            } elseif (isset($_POST['edit_member_submit'])) {
                // EDIT Member
                $id = (int)$_POST['member_id'];
                $stmt = $db->prepare("UPDATE members SET name = ?, role = ?, email = ?, phone = ?, bio = ?, joined_date = ? WHERE id = ?");
                $stmt->execute([$name, $role, $email, $phone, $bio, $joined_date, $id]);
                set_flash_message('success', 'Member profile updated successfully.');
                redirect('members.php');
            }
        } catch (Exception $e) {
            set_flash_message('danger', 'Operation failed: ' . $e->getMessage());
        }
    }
}

// Handle GET Actions (Delete or Fetch for Edit)
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("DELETE FROM members WHERE id = ?");
        $stmt->execute([$id]);
        set_flash_message('success', 'Member successfully removed.');
    } catch (Exception $e) {
        set_flash_message('danger', 'Delete failed.');
    }
    redirect('members.php');
} elseif ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("SELECT * FROM members WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $edit_member = $stmt->fetch();
        if (!$edit_member) {
            set_flash_message('danger', 'Member not found.');
            redirect('members.php');
        }
    } catch (Exception $e) {
        set_flash_message('danger', 'Database query failed.');
        redirect('members.php');
    }
}

// Fetch all members for list view
$all_members = [];
try {
    $all_members = $db->query("SELECT * FROM members ORDER BY joined_date DESC")->fetchAll();
} catch (Exception $e) {
    // schema missing
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <div>
            <h2 class="font-display fw-bold text-success-emphasis mb-0">Manage Research Members</h2>
            <small class="text-muted">Configure profiles displayed inside the active community ledger.</small>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Console</a>
    </div>

    <!-- Edit Screen -->
    <?php if ($action === 'edit' && $edit_member): ?>
        <div class="card border shadow-xs bento-card p-4 p-lg-5 bg-white mb-5">
            <h4 class="font-display fw-bold text-success-emphasis mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Member profile</h4>
            <form action="members.php?action=edit&id=<?php echo $edit_member['id']; ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="member_id" value="<?php echo $edit_member['id']; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-display fw-semibold">Member Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_member['name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-display fw-semibold">Role / Title</label>
                        <input type="text" name="role" class="form-control" value="<?php echo htmlspecialchars($edit_member['role']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-display fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($edit_member['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-display fw-semibold">Phone Contact</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($edit_member['phone']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-display fw-semibold">Joined Date</label>
                        <input type="date" name="joined_date" class="form-control" value="<?php echo htmlspecialchars($edit_member['joined_date']); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label font-display fw-semibold">Researcher Bio</label>
                        <textarea name="bio" class="form-control" rows="4" required><?php echo htmlspecialchars($edit_member['bio']); ?></textarea>
                    </div>
                </div>
                
                <div class="border-top pt-4 mt-4 d-flex gap-2">
                    <button type="submit" name="edit_member_submit" class="btn btn-success font-display px-4"><i class="bi bi-check-circle me-1"></i>Update Profile</button>
                    <a href="members.php" class="btn btn-outline-secondary font-display px-4">Cancel</a>
                </div>
            </form>
        </div>
        
    <!-- List & Add Screen -->
    <?php else: ?>
        <div class="row g-4">
            <!-- Add Member Form Widget -->
            <div class="col-lg-4">
                <div class="card border shadow-xs bento-card p-4 bg-white">
                    <h4 class="font-display fw-bold text-success-emphasis mb-3"><i class="bi bi-person-plus me-2"></i>Add Member</h4>
                    <form action="members.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. Dr. Jane Fox">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Title / Role</label>
                            <input type="text" name="role" class="form-control form-control-sm" required placeholder="e.g. Solar Tech Architect">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" required placeholder="e.g. jane@room320.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Phone (Optional)</label>
                            <input type="text" name="phone" class="form-control form-control-sm" placeholder="e.g. +1234567">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Joined Date</label>
                            <input type="date" name="joined_date" class="form-control form-control-sm" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Short Bio</label>
                            <textarea name="bio" class="form-control form-control-sm" rows="3" required placeholder="Specializes in smart water drip feeds..."></textarea>
                        </div>
                        
                        <button type="submit" name="add_member" class="btn btn-success btn-sm font-display w-full py-2"><i class="bi bi-check-circle me-1"></i>Publish Member</button>
                    </form>
                </div>
            </div>
            
            <!-- Members Data Ledger -->
            <div class="col-lg-8">
                <div class="card border shadow-xs bento-card bg-white overflow-hidden p-4">
                    <h5 class="font-display fw-bold text-success-emphasis mb-4"><i class="bi bi-table me-2"></i>Active Members Registry</h5>
                    
                    <?php if (empty($all_members)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-person-exclamation fs-2 text-muted mb-3 d-block"></i>
                            <p class="text-muted small">No member entries exist. Add one using the form.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-light-subtle small">
                                <thead class="table-light font-display">
                                    <tr>
                                        <th>Name</th>
                                        <th>Role / Contact</th>
                                        <th>Joined</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_members as $mem): ?>
                                        <tr id="member-row-<?php echo $mem['id']; ?>">
                                            <td>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($mem['name']); ?></div>
                                                <span class="text-muted font-mono" style="font-size: 10px;"><?php echo htmlspecialchars($mem['email']); ?></span>
                                            </td>
                                            <td>
                                                <div class="badge bg-success-subtle text-success mb-1" style="font-size: 9px;"><?php echo htmlspecialchars($mem['role']); ?></div>
                                                <div class="text-muted font-mono" style="font-size: 10px;"><?php echo htmlspecialchars($mem['phone'] ?: 'No Phone'); ?></div>
                                            </td>
                                            <td class="font-mono text-muted"><?php echo date('Y-m-d', strtotime($mem['joined_date'])); ?></td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="members.php?action=edit&id=<?php echo $mem['id']; ?>" class="btn btn-outline-success btn-sm px-2 py-1" title="Edit"><i class="bi bi-pencil"></i></a>
                                                    <a href="members.php?action=delete&id=<?php echo $mem['id']; ?>" class="btn btn-outline-danger btn-sm px-2 py-1" onclick="return confirm('Remove this member?');" title="Delete"><i class="bi bi-trash"></i></a>
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
