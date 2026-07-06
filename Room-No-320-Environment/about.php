<?php
/**
 * Room No. 320 Environment - About Us
 * Outlines the history, spatial definition, core ecological mission,
 * academic credentials, and the conceptual blueprint of the space.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Section -->
<div class="bg-success-subtle py-5 border-bottom">
    <div class="container text-center py-3">
        <span class="text-success text-uppercase font-mono fw-bold" style="font-size: 11px;">Our History & Mission</span>
        <h1 class="display-5 font-display fw-bold text-success-emphasis mt-2">About Room No. 320</h1>
        <p class="lead text-muted mx-auto mb-0" style="max-width: 650px; font-size: 16px;">
            A living ecosystem and interactive green incubator testing the intersection of IoT sensor technology and natural vegetation design within academic rooms.
        </p>
    </div>
</div>

<!-- History and Vision Block -->
<div class="container py-5">
    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            <span class="text-success text-uppercase font-mono fw-semibold" style="font-size: 11px;">A Spatial Revolution</span>
            <h2 class="font-display fw-bold text-success-emphasis mt-1 mb-4">Origin of the Room 320 Environmental Hub</h2>
            <p class="text-muted">
                Established in 2024 by a coalition of environmental scholars and systems engineering students, Room No. 320 began as a humble study lounge. The core vision was simple: can we optimize human productivity, cognitive performance, and mood by dynamically managing a room's microclimate?
            </p>
            <p class="text-muted">
                Today, the room features smart responsive irrigation systems, bio-filtration panels (oxygenating wall beds), and continuous gas-threshold monitors. It acts as an active research laboratory where student-built devices and green plants form a perfect symbiotic cycle.
            </p>
            
            <div class="row g-3 mt-2">
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-patch-check-fill text-success fs-4"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">100% Certified Eco</h6>
                            <small class="text-muted">Academic space tests</small>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-cpu text-success fs-4"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">Live API Streams</h6>
                            <small class="text-muted">IoT gas sensors</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&q=80&w=1000" alt="Ecosystem" class="img-fluid rounded-3 shadow border">
        </div>
    </div>
</div>

<!-- Core Values Section -->
<div class="bg-light py-5 border-top border-bottom">
    <div class="container py-3">
        <div class="text-center mb-5" style="max-width: 650px; margin: 0 auto;">
            <span class="text-success text-uppercase font-mono fw-bold" style="font-size: 11px;">Our Pillars</span>
            <h2 class="font-display fw-bold text-success-emphasis mt-2">The Principles Guiding Room 320</h2>
            <p class="text-muted">Our decisions, research pathways, and collaborative community drives are anchored firmly upon three essential core values.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 p-4 shadow-sm bento-card text-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 55px; height: 55px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <h4 class="font-display fw-bold text-success-emphasis mb-3">Academic Rigor</h4>
                    <p class="text-muted small mb-0">Every bio-filtration and sensor experiment is peer-reviewed and cross-validated with scientific equipment to ensure data integrity and reliability.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 p-4 shadow-sm bento-card text-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 55px; height: 55px;">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <h4 class="font-display fw-bold text-success-emphasis mb-3">Inclusion</h4>
                    <p class="text-muted small mb-0">We believe sustainable research thrives on diverse minds. We actively welcome programmers, biological scientists, artists, and community organizers.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 p-4 shadow-sm bento-card text-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 55px; height: 55px;">
                        <i class="bi bi-lightning-charge fs-4"></i>
                    </div>
                    <h4 class="font-display fw-bold text-success-emphasis mb-3">Open-Source Innovation</h4>
                    <p class="text-muted small mb-0">All our sensor schematics, plant feed codebases, and climate telemetry streams are licensed openly to inspire other campuses globally.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Conceptual Space Map -->
<div class="container py-5">
    <div class="bg-success-subtle rounded-4 p-4 p-lg-5 shadow-xs border">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h3 class="font-display fw-bold text-success-emphasis mb-3">Interactive Spatial Concept</h3>
                <p class="text-muted">
                    Room No. 320 features multiple specialized environmental pods:
                </p>
                <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <div>
                            <strong class="font-display text-dark">Zone Alpha (The Hydro-Grid):</strong>
                            <span class="text-muted d-block small">Automated drip feeds supporting clean moss beds, ferns, and local low-light foliage crops.</span>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <div>
                            <strong class="font-display text-dark">Zone Beta (The IoT Core):</strong>
                            <span class="text-muted d-block small">Local gateway relay processing multi-sensor packets, mapping active gas spikes.</span>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <div>
                            <strong class="font-display text-dark">Zone Gamma (The Air-Well):</strong>
                            <span class="text-muted d-block small">Variable-speed forced ventilation channels pulling raw air through dynamic soil beds.</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-5 text-center">
                <div class="bg-white border rounded-3 p-4 shadow-sm text-center">
                    <i class="bi bi-map fs-1 text-success mb-3 d-block"></i>
                    <h5 class="fw-bold text-success-emphasis">Request a Space Tour</h5>
                    <p class="text-muted small mb-4">Want to examine our physical air-filtration stacks or smart solar feeds in-person?</p>
                    <a href="contact.php" class="btn btn-success font-display w-full">Request Room Booking</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
