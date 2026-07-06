<?php
/**
 * Room No. 320 Environment - Contact Support & Collaboration
 * Integrates secure feedback sheets, XSS clean headers, CSRF validation tokens,
 * and records contact messages straight into the MySQL database.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    // Validate CSRF
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($csrf_token)) {
        set_flash_message('danger', 'Security validation expired. Please refresh the page and try again.');
        redirect('contact.php');
    }
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Server-side validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        set_flash_message('danger', 'All fields are strictly required.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash_message('danger', 'Please supply a structurally valid email address.');
    } else {
        try {
            // Write to database
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, subject, message, is_read) VALUES (?, ?, ?, ?, 0)");
            $stmt->execute([$name, $email, $subject, $message]);
            
            set_flash_message('success', 'Your correspondence was logged successfully! The Chief Eco Coordinator will contact you shortly.');
            redirect('contact.php');
        } catch (Exception $e) {
            set_flash_message('danger', 'Failed to store your request. Database error.');
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Section -->
<div class="bg-success-subtle py-5 border-bottom">
    <div class="container text-center py-3">
        <span class="text-success text-uppercase font-mono fw-bold" style="font-size: 11px;">Collaboration Gate</span>
        <h1 class="display-5 font-display fw-bold text-success-emphasis mt-2">Connect with Room 320</h1>
        <p class="lead text-muted mx-auto mb-0" style="max-width: 650px; font-size: 16px;">
            Request physical sensor tour slots, submit custom air filter code structures, or join as a research fellow.
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Contact Details Columns -->
        <div class="col-lg-5">
            <h3 class="font-display fw-bold text-success-emphasis mb-4">Contact Information</h3>
            <p class="text-muted mb-4">Our systems are managed by volunteer academic leads. Feel free to contact our technical team for sensor deployment specifications or direct questions.</p>
            
            <div class="d-flex flex-column gap-3 mb-5">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center p-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-geo-alt fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Research Annex Room 320</h6>
                        <small class="text-muted">Science Campus, 3rd Floor, West Wing</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center p-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-envelope-at fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Support Email</h6>
                        <small class="text-muted">support@room320.com</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center p-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-telephone-plus fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Hotline</h6>
                        <small class="text-muted">Monday - Friday (10:00 AM to 4:00 PM)</small>
                    </div>
                </div>
            </div>
            
            <!-- Map Placeholder / Embed -->
            <div class="border rounded-3 overflow-hidden shadow-xs" style="height: 200px; position: relative;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.0526955034676!2d90.4125181150041!3d23.750865484587693!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8582736b00b%3A0xe74e6f46cc7c1b50!2sDaffodil+International+University!5e0!3m2!1sen!2sbd!4v1553531317540!5m2!1sen!2sbd" class="w-100 h-100 border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        
        <!-- Secure Contact Form Column -->
        <div class="col-lg-7">
            <div class="card border border-light-subtle shadow-sm bento-card p-4 p-lg-5 bg-white">
                <h3 class="font-display fw-bold text-success-emphasis mb-3">Send Secure Message</h3>
                <p class="text-muted small mb-4">Please submit your details. Our systems utilize cryptographic security tokens (CSRF) to protect communication channels.</p>
                
                <form action="contact.php" method="POST" id="contact-form">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label font-display fw-semibold text-dark">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label font-display fw-semibold text-dark">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required>
                        </div>
                        <div class="col-12">
                            <label for="subject" class="form-label font-display fw-semibold text-dark">Subject Header</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="e.g. Drip Feed Sensor Calibration" required>
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label font-display fw-semibold text-dark">Message / Proposal Content</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Detailed question or collaboration ideas..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="send_message" class="btn btn-success font-display px-4 py-2 w-full"><i class="bi bi-send-fill me-2"></i>Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
