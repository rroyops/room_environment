<?php
/**
 * Room No. 320 Environment - Footer Component
 * Contains corporate site footer layout, script attachments, and dark/light mode Javascript controller.
 */
?>
    <!-- Corporate Site Footer -->
    <footer class="bg-dark text-light-emphasis border-top mt-5 py-5" style="border-color: rgba(255,255,255,0.08) !important;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 p-2" style="width: 38px; height: 38px;">
                            <i class="bi bi-tree-fill fs-5"></i>
                        </div>
                        <h5 class="mb-0 text-white font-display fw-bold" style="font-family: 'Space Grotesk', sans-serif;">Room 320 Env</h5>
                    </div>
                    <p class="small text-muted mb-0">
                        Empowering students, researchers, and community members with the state-of-the-art tools and monitoring grids required to design the campus microclimates and indoor eco-labs of tomorrow.
                    </p>
                </div>
                
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="text-white font-display fw-semibold mb-3">Quick Navigation</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="<?php echo BASE_URL; ?>index.php" class="text-muted text-decoration-none hover-white">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about.php" class="text-muted text-decoration-none hover-white">About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>gallery.php" class="text-muted text-decoration-none hover-white">Photo Gallery</a></li>
                        <li><a href="<?php echo BASE_URL; ?>members.php" class="text-muted text-decoration-none hover-white">Members Directory</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="text-white font-display fw-semibold mb-3">Resources</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="<?php echo BASE_URL; ?>activities.php" class="text-muted text-decoration-none hover-white">Activities Log</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact.php" class="text-muted text-decoration-none hover-white">Help & Support</a></li>
                        <li><a href="<?php echo BASE_URL; ?>login.php" class="text-muted text-decoration-none hover-white">User Accounts</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <h6 class="text-white font-display fw-semibold mb-3">Eco Lab Access</h6>
                    <p class="small text-muted mb-3">Subscribe to receive emergency climate bulletins or notification updates from our multi-sensors stream.</p>
                    <div class="input-group input-group-sm">
                        <input type="email" class="form-control bg-transparent border-secondary text-white" placeholder="Enter your email" aria-label="Recipient's email">
                        <button class="btn btn-success" type="button"><i class="bi bi-send-fill"></i></button>
                    </div>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: rgba(255,255,255,0.08);">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-muted mb-0">&copy; 2026 Room No. 320 Environment. All rights reserved. Devised for modern academic sustainable design.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3 small">
                        <a href="#" class="text-muted text-decoration-none hover-white"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-muted text-decoration-none hover-white"><i class="bi bi-github fs-5"></i></a>
                        <a href="#" class="text-muted text-decoration-none hover-white"><i class="bi bi-twitter-x fs-5"></i></a>
                        <a href="#" class="text-muted text-decoration-none hover-white"><i class="bi bi-linkedin fs-5"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle with Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Light/Dark Mode JavaScript Controller -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const htmlEl = document.documentElement;
            const themeToggler = document.getElementById('themeToggler');
            const themeIcon = document.getElementById('themeIcon');
            
            // Function to set theme
            function setTheme(theme) {
                if (theme === 'dark') {
                    htmlEl.setAttribute('data-bs-theme', 'dark');
                    document.body.classList.remove('bg-light', 'text-dark');
                    document.body.classList.add('bg-dark-subtle', 'text-light');
                    themeIcon.className = 'bi bi-sun';
                    localStorage.setItem('theme', 'dark');
                } else {
                    htmlEl.setAttribute('data-bs-theme', 'light');
                    document.body.classList.remove('bg-dark-subtle', 'text-light');
                    document.body.classList.add('bg-light', 'text-dark');
                    themeIcon.className = 'bi bi-moon-stars';
                    localStorage.setItem('theme', 'light');
                }
            }
            
            // Load theme choice on boot
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);
            
            // Theme toggle click handler
            if (themeToggler) {
                themeToggler.addEventListener('click', function() {
                    const currentTheme = htmlEl.getAttribute('data-bs-theme');
                    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    setTheme(nextTheme);
                });
            }
            
            // Auto fade out flash alerts after 4 seconds
            const flashAlert = document.getElementById('flash-alert');
            if (flashAlert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(flashAlert);
                    bsAlert.close();
                }, 4000);
            }
        });
    </script>
</body>
</html>
