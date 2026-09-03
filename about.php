<?php
// farmerside/about.php - About Us Page with Matching Site Theme
require_once __DIR__ . '/../config/db_config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js"></script>
    
    <style>
        /* 🌟 Site Matching Soft-Dark Theme */
        body { 
            background-color: #0d0f11 !important; 
            color: #e2e8f0 !important; 
            font-family: 'Segoe UI', system-ui, sans-serif; 
            padding-bottom: 80px; 
            padding-top: 100px;
        }
        
        /* Navbar Match */
        .custom-nav {
            background-color: #191c1f !important;
            border-bottom: 1px solid #2d3238 !important;
        }

        /* Card Custom Match (જરાક લાઈટ અને સોફ્ટ બેકગ્રાઉન્ડ) */
        .about-card { 
            background: #191c1f !important; 
            border: 1px solid #2d3238 !important; 
            border-radius: 20px !important; 
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4) !important; 
        }

        /* Feature Boxes */
        .feature-box { 
            background: #14171a !important; 
            border: 1px solid #2d3238 !important; 
            border-radius: 14px; 
            padding: 25px; 
            height: 100%; 
            transition: all 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-4px);
            border-color: #28a745 !important;
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.15);
        }

        /* Mission Box */
        .mission-box {
            background: #14171a !important;
            border: 1px solid #2d3238 !important;
            border-left: 5px solid #28a745 !important;
            border-radius: 12px;
        }
    </style>
</head>
<body>

    <!-- 🌾 Fixed Top Navbar (Site Matching Logo Fix) -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="dashboard.php" class="premium-logo-container text-decoration-none">
                <div class="premium-logo-icon-wrapper">
                    <i class="fa-solid fa-wheat-awn"></i>
                </div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Input</span> Hub</div>
                    <div class="logo-sub-text">Digital Kisan Portal</div>
                </div>
            </a>   
            <a href="dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 fw-bold"><i class="fa-solid fa-house me-1"></i> Back to Dashboard</a>
        </div>
    </nav>

    <!-- મુખ્ય કન્ટેન્ટ -->
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card about-card p-4 p-md-5">
                    
                    <div class="text-center mb-5">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-bold mb-3">
                            <i class="fa-solid fa-seedling me-1"></i> Empowering Farmers
                        </span>
                        <h1 class="fw-bold text-success mb-3">About Agro Input Hub</h1>
                        <p class="text-muted lead mx-auto" style="max-width: 700px;">
                            Bridging the gap between modern agricultural retail and farmers by providing a seamless digital platform for genuine farming inputs, transparent billing, and expert guidance.
                        </p>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="feature-box text-center">
                                <div class="text-success fs-3 mb-3"><i class="fa-solid fa-store"></i></div>
                                <h5 class="fw-bold text-white">Genuine Inputs</h5>
                                <p class="text-muted small mb-0">Access high-quality fertilizers, seeds, pesticides, and technical farming tools directly from verified suppliers.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-box text-center">
                                <div class="text-success fs-3 mb-3"><i class="fa-solid fa-bolt"></i></div>
                                <h5 class="fw-bold text-white">Quick Digital Orders</h5>
                                <p class="text-muted small mb-0">Browse products with scientific details, check stock status in real-time, and place orders instantly.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-box text-center">
                                <div class="text-success fs-3 mb-3"><i class="fa-solid fa-star"></i></div>
                                <h5 class="fw-bold text-white">Farmer Reviews</h5>
                                <p class="text-muted small mb-0">Read and share genuine product ratings and experiences to help fellow farmers achieve better crop yields.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mission-box p-4 shadow-sm">
                        <h4 class="fw-bold text-success mb-3"><i class="fa-solid fa-bullseye me-2"></i> Our Mission</h4>
                        <p class="text-muted mb-0">
                            Our mission is to modernize traditional agricultural operations through digital transformation. By automating inventory tracking, secure digital billing, and transparent credit/ledger management, we aim to make farming input management effortless and reliable for every hardworking farmer.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>