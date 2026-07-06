<?php
/**
 * Room No. 320 Environment - Activities Log
 * Connects to the SQL database, lists past and ongoing eco-initiatives,
 * air filtering sprints, and climate hackathons.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Retrieve activities
$activities = [];
try {
    $stmt = $db->query("SELECT * FROM activities ORDER BY activity_date DESC");
    $activities = $stmt->fetchAll();
} catch (Exception $e) {
    // Schema not yet loaded
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Section -->
<div class="bg-success-subtle py-5 border-bottom">
    <div class="container text-center py-3">
        <span class="text-success text-uppercase font-mono fw-bold" style="font-size: 11px;">Active Chronicles</span>
        <h1 class="display-5 font-display fw-bold text-success-emphasis mt-2">Activities & Eco Initiatives</h1>
        <p class="lead text-muted mx-auto mb-0" style="max-width: 650px; font-size: 16px;">
            A historical ledger of our sensory environmental diagnostic audits, tree plantations, and interactive campus hackathons.
        </p>
    </div>
</div>

<div class="container py-5">
    <?php if (empty($activities)): ?>
        <div class="text-center py-5 border rounded-3 bg-white">
            <i class="bi bi-calendar2-x fs-1 text-muted mb-3 d-block"></i>
            <h5>No Activities Logged</h5>
            <p class="text-muted small">No eco activities or research projects are currently recorded in the database ledger.</p>
        </div>
    <?php else: ?>
        <div class="row g-5">
            <!-- Timeline Layout on Desktop -->
            <div class="col-lg-10 mx-auto">
                <div class="d-flex flex-column gap-5" id="activities-timeline">
                    <?php 
                    $count = 0;
                    foreach ($activities as $act): 
                        $count++;
                        // Alternate grid placement for rhythm
                        $is_even = ($count % 2 === 0);
                        $row_class = $is_even ? 'flex-lg-row-reverse' : '';
                    ?>
                        <div class="row align-items-center g-4 <?php echo $row_class; ?>">
                            <!-- Image Block -->
                            <div class="col-lg-5">
                                <div class="overflow-hidden rounded-3 shadow-sm border" style="height: 280px; position: relative;">
                                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=800" alt="<?php echo htmlspecialchars($act['title']); ?>" class="w-100 h-100 object-fit-cover hover-card" style="transition: transform 0.3s ease;">
                                    <span class="badge bg-success font-mono position-absolute" style="top: 15px; left: 15px;"><i class="bi bi-calendar-event me-1"></i><?php echo date('M d, Y', strtotime($act['activity_date'])); ?></span>
                                </div>
                            </div>
                            
                            <!-- Information Block -->
                            <div class="col-lg-7">
                                <span class="text-success text-uppercase font-mono fw-bold" style="font-size: 11px;">Eco Project Log #0<?php echo $act['id']; ?></span>
                                <h3 class="font-display fw-bold text-success-emphasis mt-1 mb-3"><?php echo htmlspecialchars($act['title']); ?></h3>
                                <p class="text-muted mb-4" style="line-height: 1.7;">
                                    <?php echo htmlspecialchars($act['description']); ?>
                                </p>
                                
                                <div class="d-flex align-items-center gap-4">
                                    <div class="small text-muted font-mono"><i class="bi bi-clock me-1"></i> Logged On: <?php echo date('Y-m-d', strtotime($act['created_at'])); ?></div>
                                    <div class="small text-success fw-bold"><i class="bi bi-shield-check me-1"></i> Verified Audit</div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($count < count($activities)): ?>
                            <hr class="border-light-subtle" style="opacity: 0.6;">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
