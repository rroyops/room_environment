<?php
/**
 * Room No. 320 Environment - Header Component
 * Contains HTML wrapper, meta definitions, CDNs, custom theme controllers, and navigation bar.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// Retrieve active filename for navbar highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Eco Research & Innovation Hub</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts: Space Grotesk (display), Inter (sans), and JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome/Bootstrap Icons for sleek visual cues -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS Core -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body class="bg-light text-dark">

    <!-- Responsive Navigation Bar -->
    <nav class="navbar navbar-expand-lg border-bottom sticky-top bg-white py-3 shadow-xs">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo BASE_URL; ?>index.php" id="nav-brand-main">
                <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3 p-2" style="width: 38px; height: 38px;">
                    <i class="bi bi-tree-fill fs-5"></i>
                </div>
                <span class="font-display fw-bold text-success-emphasis tracking-tight" style="font-family: 'Space Grotesk', sans-serif;">Room 320 Env</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active fw-semibold text-success' : ''; ?>" href="<?php echo BASE_URL; ?>index.php" id="nav-home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active fw-semibold text-success' : ''; ?>" href="<?php echo BASE_URL; ?>about.php" id="nav-about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'gallery.php') ? 'active fw-semibold text-success' : ''; ?>" href="<?php echo BASE_URL; ?>gallery.php" id="nav-gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'members.php') ? 'active fw-semibold text-success' : ''; ?>" href="<?php echo BASE_URL; ?>members.php" id="nav-members">Members</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'activities.php') ? 'active fw-semibold text-success' : ''; ?>" href="<?php echo BASE_URL; ?>activities.php" id="nav-activities">Activities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active fw-semibold text-success' : ''; ?>" href="<?php echo BASE_URL; ?>contact.php" id="nav-contact">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Light / Dark Mode Toggle Button -->
                    <button class="btn btn-outline-secondary border-0 p-2 d-flex align-items-center justify-content-center" id="themeToggler" type="button" aria-label="Toggle Theme" style="width: 38px; height: 38px; border-radius: 50%;">
                        <i class="bi bi-moon-stars" id="themeIcon"></i>
                    </button>

                    <!-- Authentication Navigation Buttons -->
                    <?php if (is_logged_in()): ?>
                        <div class="dropdown" id="userMenuDropdown">
                            <button class="btn btn-success d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="userMenuBtn">
                                <i class="bi bi-person-circle"></i>
                                <span><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></span>
                                <?php if (is_admin()): ?>
                                    <span class="badge bg-white text-success font-mono" style="font-size: 10px;">Admin</span>
                                <?php endif; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" id="userMenuOptions">
                                <?php if (is_admin()): ?>
                                    <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?php echo BASE_URL; ?>admin/dashboard.php"><i class="bi bi-speedometer2 text-success"></i> Admin Dashboard</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?php echo BASE_URL; ?>dashboard.php"><i class="bi bi-grid-1x2 text-success"></i> User Dashboard</a></li>
                                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?php echo BASE_URL; ?>profile.php"><i class="bi bi-gear text-success"></i> Edit Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" href="<?php echo BASE_URL; ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-outline-success font-display fw-medium px-4" id="nav-login-btn">Login</a>
                        <a href="<?php echo BASE_URL; ?>register.php" class="btn btn-success font-display fw-medium px-4" id="nav-register-btn">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Global alert flash messages -->
    <div class="container mt-4">
        <?php display_flash_message(); ?>
    </div>
