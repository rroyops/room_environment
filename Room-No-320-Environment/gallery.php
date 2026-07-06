<?php
/**
 * Room No. 320 Environment - Photo Gallery
 * Displays approved environmental project photographs, category filters, and enables secure image uploads
 * for authenticated platform members.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Create uploads directory if it does not exist
$uploads_dir = __DIR__ . '/uploads';
if (!file_exists($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
}

// Handle image upload submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image'])) {
    if (!is_logged_in()) {
        set_flash_message('danger', 'You must be logged in to upload research photographs.');
        redirect('login.php');
    }
    
    // Validate CSRF
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security validation expired. Please resubmit.');
        redirect('gallery.php');
    }
    
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = sanitize($_POST['category']);
    $uploaded_by = $_SESSION['user_fullname'];
    
    // Basic validations
    if (empty($title) || empty($description)) {
        set_flash_message('danger', 'Title and description are required.');
    } elseif (!isset($_FILES['gallery_file']) || $_FILES['gallery_file']['error'] !== UPLOAD_ERR_OK) {
        set_flash_message('danger', 'Please select a valid image file to upload.');
    } else {
        $file = $_FILES['gallery_file'];
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowed_types)) {
            set_flash_message('danger', 'Invalid file type. Only JPG, JPEG, PNG, and GIF images are permitted.');
        } else {
            // Generate safe, unique name
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = 'gallery_' . uniqid() . '_' . time() . '.' . $ext;
            $destination = $uploads_dir . '/' . $new_filename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                try {
                    // Store image details in SQL database
                    // If uploaded by an admin, auto-approve; otherwise, approve as well or keep for admin moderating.
                    // Let's auto-approve for all registered members to make it interactive, but can be managed by admin.
                    $is_approved = 1; 
                    
                    $stmt = $db->prepare("INSERT INTO gallery (title, description, image_path, uploaded_by, category, is_approved) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $description, $new_filename, $uploaded_by, $category, $is_approved]);
                    
                    set_flash_message('success', 'Photograph successfully uploaded and shared with the Room 320 Gallery!');
                    redirect('gallery.php');
                } catch (Exception $e) {
                    set_flash_message('danger', 'Failed to record the upload. Please try again.');
                }
            } else {
                set_flash_message('danger', 'Failed to move the uploaded file. Check directory permissions.');
            }
        }
    }
}

// Handle image deletion (for Admins only)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (!is_admin()) {
        set_flash_message('danger', 'Unauthorized operation.');
        redirect('gallery.php');
    }
    
    $delete_id = (int)$_GET['id'];
    try {
        // Fetch filename to delete from disk
        $stmt_img = $db->prepare("SELECT image_path FROM gallery WHERE id = ?");
        $stmt_img->execute([$delete_id]);
        $img = $stmt_img->fetch();
        
        if ($img) {
            $filepath = $uploads_dir . '/' . $img['image_path'];
            if (file_exists($filepath) && $img['image_path'] !== 'default_image.png') {
                unlink($filepath);
            }
            
            $stmt_del = $db->prepare("DELETE FROM gallery WHERE id = ?");
            $stmt_del->execute([$delete_id]);
            set_flash_message('success', 'Gallery item successfully deleted.');
        } else {
            set_flash_message('danger', 'Item not found.');
        }
    } catch (Exception $e) {
        set_flash_message('danger', 'Database query failure.');
    }
    redirect('gallery.php');
}

// Fetch category filter
$active_category = isset($_GET['category']) ? trim($_GET['category']) : 'All';

// Fetch gallery items
$gallery_items = [];
try {
    if ($active_category === 'All') {
        $stmt_fetch = $db->prepare("SELECT * FROM gallery WHERE is_approved = 1 ORDER BY created_at DESC");
        $stmt_fetch->execute();
    } else {
        $stmt_fetch = $db->prepare("SELECT * FROM gallery WHERE is_approved = 1 AND category = ? ORDER BY created_at DESC");
        $stmt_fetch->execute([$active_category]);
    }
    $gallery_items = $stmt_fetch->fetchAll();
} catch (Exception $e) {
    // Schema not yet imported
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Section -->
<div class="bg-success-subtle py-5 border-bottom">
    <div class="container text-center py-3">
        <span class="text-success text-uppercase font-mono fw-bold" style="font-size: 11px;">Exhibition Corner</span>
        <h1 class="display-5 font-display fw-bold text-success-emphasis mt-2">Room 320 Photo Gallery</h1>
        <p class="lead text-muted mx-auto mb-0" style="max-width: 650px; font-size: 16px;">
            A visual documentation of vertical forests, active drip modules, IoT sensor installations, and climate workshops.
        </p>
    </div>
</div>

<div class="container py-5">
    <!-- Category Filtering Row -->
    <div class="d-flex flex-wrap align-items-center justify-content-between g-3 mb-5 border-bottom pb-4">
        <div class="d-flex flex-wrap gap-2">
            <?php
            $categories = ['All', 'Research', 'Initiatives', 'Events', 'General'];
            foreach ($categories as $cat):
                $active_class = ($active_category === $cat) ? 'btn-success text-white' : 'btn-outline-secondary';
                $link = ($cat === 'All') ? 'gallery.php' : 'gallery.php?category=' . urlencode($cat);
            ?>
                <a href="<?php echo $link; ?>" class="btn rounded-pill px-3 font-display small <?php echo $active_class; ?>"><?php echo $cat; ?></a>
            <?php endforeach; ?>
        </div>
        
        <!-- Trigger Modal button for Logged In users -->
        <?php if (is_logged_in()): ?>
            <button type="button" class="btn btn-success d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                <i class="bi bi-cloud-upload"></i> Share Photograph
            </button>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline-success btn-sm d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-in-right"></i> Login to Share Photos
            </a>
        <?php endif; ?>
    </div>

    <!-- Gallery Grid -->
    <?php if (empty($gallery_items)): ?>
        <div class="text-center py-5 border rounded-3 bg-white">
            <i class="bi bi-images fs-1 text-muted mb-3 d-block"></i>
            <h5>No Photographs in Category</h5>
            <p class="text-muted small">Be the first to upload a photograph documenting activities in this research sector.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($gallery_items as $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border overflow-hidden shadow-xs hover-card">
                        <!-- Card Image -->
                        <div style="height: 240px; position: relative; overflow: hidden; background-color: #eaeaea;">
                            <?php 
                            // Determine image path (check if custom uploaded or placeholder)
                            $img_src = '';
                            if (file_exists($uploads_dir . '/' . $item['image_path'])) {
                                $img_src = BASE_URL . 'uploads/' . $item['image_path'];
                            } else {
                                // Fallback placeholder based on title/category
                                $img_src = 'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&q=80&w=600';
                            }
                            ?>
                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-100 h-100 object-fit-cover">
                            
                            <span class="badge bg-success font-mono position-absolute" style="top: 15px; left: 15px; font-size: 10px;"><?php echo htmlspecialchars($item['category']); ?></span>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title font-display fw-bold text-dark"><?php echo htmlspecialchars($item['title']); ?></h5>
                                <p class="card-text text-muted small mb-3"><?php echo htmlspecialchars($item['description']); ?></p>
                            </div>
                            
                            <div class="border-top pt-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <small class="text-dark d-block fw-semibold" style="font-size: 11px;"><?php echo htmlspecialchars($item['uploaded_by']); ?></small>
                                        <small class="text-muted font-mono" style="font-size: 10px;"><?php echo date('M d, Y', strtotime($item['created_at'])); ?></small>
                                    </div>
                                </div>
                                
                                <?php if (is_admin()): ?>
                                    <a href="gallery.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" onclick="return confirm('Delete this gallery photograph?');" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Upload Image Modal (Bootstrap 5) -->
<?php if (is_logged_in()): ?>
<div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="gallery.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                
                <div class="modal-header border-bottom bg-light">
                    <h5 class="modal-title font-display fw-bold text-success-emphasis" id="uploadImageModalLabel"><i class="bi bi-cloud-upload me-2"></i>Share Eco Photograph</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="title" class="form-label font-display fw-medium">Photograph Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Air Filtration Stack #3" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label font-display fw-medium">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="Research">Research & Experiments</option>
                            <option value="Initiatives">Initiatives & Bio-Walls</option>
                            <option value="Events">Workshops & Events</option>
                            <option value="General">General / Study Space</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label font-display fw-medium">Short Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Briefly explain what environmental aspect is documented in this photo..." required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="gallery_file" class="form-label font-display fw-medium">Select Image File</label>
                        <div class="upload-dropzone p-4 text-center" onclick="document.getElementById('gallery_file').click();">
                            <i class="bi bi-image fs-1 text-success mb-2 d-block"></i>
                            <span class="small text-muted d-block" id="file-label">Click to browse JPG, PNG, GIF files</span>
                            <span class="text-muted font-mono" style="font-size: 10px;">Max file size: 5MB</span>
                            <input type="file" id="gallery_file" name="gallery_file" accept="image/*" class="d-none" required onchange="document.getElementById('file-label').innerText = this.files[0].name;">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="upload_image" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Publish Image</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
