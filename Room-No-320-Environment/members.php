<?php
/**
 * Room No. 320 Environment - Members Directory
 * Connects to the SQL database, lists verified project coordinators/advocates,
 * and incorporates search, role filters, and dynamic pagination.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Configure Pagination variables
$limit = 6; // members per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Filter terms
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$role_filter = isset($_GET['role']) ? trim($_GET['role']) : '';

// Retrieve members
$members = [];
$total_members = 0;

try {
    // Construct SQL Query dynamically based on search and filters
    $sql = "SELECT * FROM members WHERE 1=1";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND (name LIKE :search OR bio LIKE :search OR role LIKE :search)";
        $params['search'] = "%$search%";
    }
    
    if (!empty($role_filter)) {
        $sql .= " AND role = :role";
        $params['role'] = $role_filter;
    }
    
    // Get total count for pagination
    $count_sql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
    $stmt_count = $db->prepare($count_sql);
    $stmt_count->execute($params);
    $total_members = $stmt_count->fetchColumn();
    
    // Add pagination limiters
    $sql .= " ORDER BY joined_date DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($sql);
    // Bind pagination params manually since they are integers
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => $val) {
        $stmt->bindValue(':' . $key, $val);
    }
    
    $stmt->execute();
    $members = $stmt->fetchAll();
    
    // Calculate total pages
    $total_pages = ceil($total_members / $limit);
    if ($total_pages < 1) $total_pages = 1;
    
    // Fetch unique roles for side filter dropdown
    $unique_roles = $db->query("SELECT DISTINCT role FROM members ORDER BY role ASC")->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    // Schema not loaded
    $total_pages = 1;
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Section -->
<div class="bg-success-subtle py-5 border-bottom">
    <div class="container text-center py-3">
        <span class="text-success text-uppercase font-mono fw-bold" style="font-size: 11px;">Ecosystem Directory</span>
        <h1 class="display-5 font-display fw-bold text-success-emphasis mt-2">Active Research Members</h1>
        <p class="lead text-muted mx-auto mb-0" style="max-width: 650px; font-size: 16px;">
            Meet the multi-disciplinary team of advisors, research leads, and student builders driving environmental change in Room 320.
        </p>
    </div>
</div>

<div class="container py-5">
    <!-- Filter Control Board -->
    <div class="card border border-light-subtle shadow-xs mb-5 bento-card bg-white">
        <div class="card-body p-4">
            <form action="members.php" method="GET" class="row g-3">
                <div class="col-md-6 col-lg-5">
                    <label class="form-label font-display fw-medium text-dark">Search Directory</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, research bio..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                
                <div class="col-md-4 col-lg-4">
                    <label class="form-label font-display fw-medium text-dark">Filter by Role</label>
                    <select name="role" class="form-select">
                        <option value="">All Academic Roles</option>
                        <?php if (!empty($unique_roles)): ?>
                            <?php foreach ($unique_roles as $role): ?>
                                <option value="<?php echo htmlspecialchars($role); ?>" <?php echo ($role_filter === $role) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($role); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-2 col-lg-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-success font-display w-full"><i class="bi bi-funnel-fill me-1"></i>Apply</button>
                    <?php if (!empty($search) || !empty($role_filter)): ?>
                        <a href="members.php" class="btn btn-outline-secondary font-display"><i class="bi bi-arrow-counterclockwise"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Members Grid -->
    <?php if (empty($members)): ?>
        <div class="text-center py-5 border rounded-3 bg-white">
            <i class="bi bi-people fs-1 text-muted mb-3 d-block"></i>
            <h5>No Members Found</h5>
            <p class="text-muted small">No profiles matched your active query. Reset the search filter board and try again.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($members as $mem): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border shadow-xs hover-card overflow-hidden">
                        <!-- Card Header Decoration -->
                        <div class="bg-success-subtle py-4 px-3 text-center border-bottom" style="position: relative;">
                            <div class="status-indicator status-active position-absolute" style="top: 15px; right: 15px;" title="Online Contributor"></div>
                            
                            <!-- Profile picture fallback -->
                            <div class="bg-white rounded-circle shadow-sm mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 85px; height: 85px; overflow: hidden; border: 3px solid #fff;">
                                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=200" alt="Avatar" class="w-100 h-100 object-fit-cover">
                            </div>
                            
                            <h5 class="font-display fw-bold text-success-emphasis mb-0"><?php echo htmlspecialchars($mem['name']); ?></h5>
                            <span class="badge bg-success-subtle text-success font-mono mt-2" style="font-size: 10px;"><?php echo htmlspecialchars($mem['role']); ?></span>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <p class="card-text text-muted small text-center mb-4" style="font-style: italic;">
                                "<?php echo htmlspecialchars($mem['bio']); ?>"
                            </p>
                            
                            <div class="border-top pt-3 small">
                                <div class="d-flex align-items-center justify-content-between text-muted mb-1">
                                    <span><i class="bi bi-envelope me-2"></i>Email</span>
                                    <span class="text-dark fw-medium"><?php echo htmlspecialchars($mem['email']); ?></span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between text-muted mb-1">
                                    <span><i class="bi bi-calendar-check me-2"></i>Joined</span>
                                    <span class="text-dark font-mono"><?php echo date('M Y', strtotime($mem['joined_date'])); ?></span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between text-muted">
                                    <span><i class="bi bi-telephone me-2"></i>Contact</span>
                                    <span class="text-dark font-mono"><?php echo htmlspecialchars($mem['phone'] ?: 'N/A'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination Controls -->
        <?php if ($total_pages > 1): ?>
            <nav class="mt-5" aria-label="Page navigation">
                <ul class="pagination justify-content-center gap-1">
                    <!-- Previous button -->
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link border-0 rounded" href="members.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role_filter); ?>"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($page === $i) ? 'active' : ''; ?>">
                            <a class="page-link border-0 rounded <?php echo ($page === $i) ? 'bg-success text-white' : 'text-success'; ?>" href="members.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role_filter); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <!-- Next button -->
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link border-0 rounded" href="members.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role_filter); ?>"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
