<?php
/**
 * Room No. 320 Environment - Homepage
 * Features an interactive carousel, community summaries, latest announcements,
 * featured environmental activities, and search integrations.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Handle global search if submitted
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_results = [];

if (!empty($search_query)) {
    try {
        // Search across members, activities, and announcements
        $stmt_members = $db->prepare("SELECT 'member' as type, id, name as title, role as subtitle, bio as description FROM members WHERE name LIKE :q OR bio LIKE :q OR role LIKE :q");
        $stmt_members->execute(['q' => "%$search_query%"]);
        
        $stmt_activities = $db->prepare("SELECT 'activity' as type, id, title, activity_date as subtitle, description FROM activities WHERE title LIKE :q OR description LIKE :q");
        $stmt_activities->execute(['q' => "%$search_query%"]);
        
        $stmt_announcements = $db->prepare("SELECT 'announcement' as type, id, title, created_at as subtitle, content as description FROM announcements WHERE title LIKE :q OR content LIKE :q");
        $stmt_announcements->execute(['q' => "%$search_query%"]);
        
        $search_results = array_merge(
            $stmt_members->fetchAll(),
            $stmt_activities->fetchAll(),
            $stmt_announcements->fetchAll()
        );
    } catch (Exception $e) {
        set_flash_message('danger', 'Search query failed to process.');
    }
}

// Fetch announcements & activities for normal rendering
$announcements = [];
$activities = [];
try {
    $announcements = $db->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3")->fetchAll();
    $activities = $db->query("SELECT * FROM activities ORDER BY activity_date DESC LIMIT 3")->fetchAll();
} catch (Exception $e) {
    // Graceful fallback if schema not loaded yet
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Search Results Display Panel -->
<?php if (!empty($search_query)): ?>
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="font-display fw-bold mb-0">Search Results for "<span class="text-success"><?php echo htmlspecialchars($search_query); ?></span>"</h2>
        <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i> Clear Search</a>
    </div>
    
    <?php if (empty($search_results)): ?>
        <div class="text-center py-5 border rounded-3 bg-white">
            <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
            <h5>No Matches Found</h5>
            <p class="text-muted small">Try refining your terms or searching for general keywords like "carbon", "sensor", "energy", or member names.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($search_results as $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border shadow-xs">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-success-subtle text-success text-uppercase font-mono" style="font-size: 10px;"><?php echo $item['type']; ?></span>
                                    <small class="text-muted font-mono" style="font-size: 11px;"><?php echo htmlspecialchars($item['subtitle']); ?></small>
                                </div>
                                <h5 class="card-title font-display fw-semibold mb-2"><?php echo htmlspecialchars($item['title']); ?></h5>
                                <p class="card-text text-muted small mb-3"><?php echo htmlspecialchars(substr($item['description'], 0, 140)) . '...'; ?></p>
                            </div>
                            
                            <div>
                                <?php if ($item['type'] === 'member'): ?>
                                    <a href="members.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-success w-full">View Member profile</a>
                                <?php elseif ($item['type'] === 'activity'): ?>
                                    <a href="activities.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-success w-full">Learn More</a>
                                <?php else: ?>
                                    <a href="index.php#announcements-section" class="btn btn-sm btn-outline-success w-full">View Announcements</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php else: ?>

<!-- Interactive Image Slider (Carousel) -->
<div id="heroCarousel" class="carousel slide carousel-fade shadow-sm" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active hero-carousel-item" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1600');">
            <div class="hero-overlay"></div>
            <div class="carousel-caption d-md-block text-start" style="bottom: 12%; max-width: 650px; left: 10%;">
                <span class="badge bg-success mb-3 px-3 py-2 font-mono text-uppercase">Active IoT Monitoring</span>
                <h1 class="display-4 fw-bold font-display text-white mb-3" style="line-height: 1.15;">Sensing Campus Climates in Real-Time</h1>
                <p class="lead text-white-50 mb-4 fs-6">Discover how Room No. 320 designs automated grids to manage humidity, carbon thresholds, and micro-sensory elements across workspaces.</p>
                <div class="d-flex gap-3">
                    <a href="activities.php" class="btn btn-success font-display px-4 py-2">Explore Activities</a>
                    <a href="about.php" class="btn btn-outline-light font-display px-4 py-2">Learn Our Mission</a>
                </div>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="carousel-item hero-carousel-item" style="background-image: url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=1600');">
            <div class="hero-overlay"></div>
            <div class="carousel-caption d-md-block text-start" style="bottom: 12%; max-width: 650px; left: 10%;">
                <span class="badge bg-success mb-3 px-3 py-2 font-mono text-uppercase">Indoor Bio-Walls</span>
                <h1 class="display-4 fw-bold font-display text-white mb-3" style="line-height: 1.15;">Breathing Innovation Into Interior Space</h1>
                <p class="lead text-white-50 mb-4 fs-6">Experience our smart vertical forest and responsive micro-climate designs engineered to boost focus, productivity, and oxygen quality.</p>
                <div class="d-flex gap-3">
                    <a href="gallery.php" class="btn btn-success font-display px-4 py-2">Browse Gallery</a>
                    <a href="contact.php" class="btn btn-outline-light font-display px-4 py-2">Get In Touch</a>
                </div>
            </div>
        </div>
        <!-- Slide 3 -->
        <div class="carousel-item hero-carousel-item" style="background-image: url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=1600');">
            <div class="hero-overlay"></div>
            <div class="carousel-caption d-md-block text-start" style="bottom: 12%; max-width: 650px; left: 10%;">
                <span class="badge bg-success mb-3 px-3 py-2 font-mono text-uppercase">Join The Cause</span>
                <h1 class="display-4 fw-bold font-display text-white mb-3" style="line-height: 1.15;">Collaborative Student Research Platform</h1>
                <p class="lead text-white-50 mb-4 fs-6">Whether you are an engineer, biologist, or student advocate, Room 320 provides the framework to test your eco-hardware concepts.</p>
                <div class="d-flex gap-3">
                    <a href="register.php" class="btn btn-success font-display px-4 py-2">Join Us Today</a>
                    <a href="members.php" class="btn btn-outline-light font-display px-4 py-2">Meet Our Team</a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Integrated Utility / Global Search bar -->
<div class="bg-success-subtle py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-md-6 col-lg-5">
                <h5 class="mb-0 font-display fw-bold text-success-emphasis"><i class="bi bi-search me-2"></i>What are you looking for?</h5>
                <p class="mb-0 text-muted small">Search announcements, member roles, eco activities, or bios.</p>
            </div>
            <div class="col-md-6 col-lg-7">
                <form action="index.php" method="GET" class="d-flex gap-2">
                    <input type="text" name="q" class="form-control" placeholder="Search eco projects, names, roles..." aria-label="Search" value="<?php echo htmlspecialchars($search_query); ?>" required>
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-search me-1"></i>Search</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Grid Information Blocks / Core Values -->
<div class="container py-5">
    <div class="text-center mb-5" style="max-width: 650px; margin: 0 auto;">
        <span class="text-success text-uppercase font-mono fw-bold" style="font-size: 11px;">Sustainable Ecosystems</span>
        <h2 class="font-display fw-bold text-success-emphasis mt-2">Connecting Research with Living Habitats</h2>
        <p class="text-muted">Room No. 320 Environment represents a breakthrough concept: transforming a standard university space into an active research bed for air filters, micro-climates, and responsive IoT tech.</p>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 hover-card bento-card">
                <div class="bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center mb-4" style="width: 50px; height: 50px;">
                    <i class="bi bi-cpu fs-4"></i>
                </div>
                <h4 class="font-display fw-bold mb-3 text-success-emphasis">IoT Telemetry</h4>
                <p class="text-muted small">Continuous logging of CO2 density, localized thermal thresholds, and atmospheric micro-variations. Data is pushed to dynamic dashboards in real-time.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 hover-card bento-card">
                <div class="bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center mb-4" style="width: 50px; height: 50px;">
                    <i class="bi bi-flower1 fs-4"></i>
                </div>
                <h4 class="font-display fw-bold mb-3 text-success-emphasis">Bio-Filtration</h4>
                <p class="text-muted small">Using integrated vegetation matrices (bio-walls) and smart nutrient injectors to evaluate plant-based carbon conversion inside closed academic rooms.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 hover-card bento-card">
                <div class="bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center mb-4" style="width: 50px; height: 50px;">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <h4 class="font-display fw-bold mb-3 text-success-emphasis">Student Advocacy</h4>
                <p class="text-muted small">Providing a space where computer scientists, ecologists, and design students combine their forces in localized, high-impact hackathons.</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Split Content: Announcements & Activities -->
<div class="bg-light py-5 border-top border-bottom">
    <div class="container">
        <div class="row g-5">
            <!-- Latest Announcements -->
            <div class="col-lg-5" id="announcements-section">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="font-display fw-bold text-success-emphasis mb-0"><i class="bi bi-megaphone me-2"></i>Bulletins</h3>
                    <span class="badge bg-success-subtle text-success">Latest News</span>
                </div>
                
                <?php if (empty($announcements)): ?>
                    <div class="alert alert-secondary text-center py-4">No recent announcements are posted.</div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($announcements as $announce): ?>
                            <div class="card border-0 shadow-sm p-3 hover-card">
                                <span class="text-muted font-mono" style="font-size: 11px;"><?php echo date('M d, Y', strtotime($announce['created_at'])); ?></span>
                                <h5 class="font-display fw-bold text-dark mt-1 mb-2"><?php echo htmlspecialchars($announce['title']); ?></h5>
                                <p class="text-muted small mb-0"><?php echo htmlspecialchars($announce['content']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Featured Activities -->
            <div class="col-lg-7">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="font-display fw-bold text-success-emphasis mb-0"><i class="bi bi-calendar-event me-2"></i>Recent Initiatives</h3>
                    <a href="activities.php" class="text-success text-decoration-none small">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                
                <?php if (empty($activities)): ?>
                    <div class="alert alert-secondary text-center py-4">No active initiatives found.</div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($activities as $act): ?>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden hover-card">
                                    <div style="height: 150px; background-image: url('https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&q=80&w=600'); background-size: cover; background-position: center;"></div>
                                    <div class="card-body">
                                        <span class="text-success font-mono" style="font-size: 11px;"><?php echo date('M d, Y', strtotime($act['activity_date'])); ?></span>
                                        <h5 class="card-title font-display fw-bold text-dark mt-1"><?php echo htmlspecialchars($act['title']); ?></h5>
                                        <p class="card-text text-muted small"><?php echo htmlspecialchars(substr($act['description'], 0, 95)) . '...'; ?></p>
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

<!-- Highlight CTA Bar -->
<div class="bg-success text-white py-5">
    <div class="container text-center">
        <h2 class="font-display fw-bold mb-3">Want to Contribute with Air Quality Sensors?</h2>
        <p class="lead text-white-50 mx-auto mb-4" style="max-width: 650px; font-size: 16px;">We accept custom-engineered sensor scripts and vegetation modules from verified accounts. Get your account and start posting research logs.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="register.php" class="btn btn-light text-success fw-bold font-display px-4 py-2">Create Account</a>
            <a href="contact.php" class="btn btn-outline-light font-display px-4 py-2">Contact Admin</a>
        </div>
    </div>
</div>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
