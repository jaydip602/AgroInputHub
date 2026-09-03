<?php
require_once __DIR__ . '/../config/db_config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* CSS ફાઈલના બદલે અહીં ડાયરેક્ટ સ્ટાઇલ મૂકી છે જેથી લોચા ન પડે */
        body { background-color: #0d0f11 !important; color: #ffffff !important; font-family: sans-serif; }
        .navbar { background-color: #1a1d20 !important; border-bottom: 2px solid #28a745 !important; padding: 15px 0; }
        .hero-section { background: #1a1d20; border: 1px solid #2d3238; border-radius: 15px; padding: 60px 40px; text-align: center; margin: 30px 0; }
        .feature-card { background: #1a1d20 !important; border: 1px solid #2d3238 !important; padding: 30px; border-radius: 15px; height: 100%; transition: 0.3s; }
        .feature-card:hover { border-color: #28a745 !important; transform: translateY(-5px); }
        .text-white-bright { color: #ffffff !important; font-weight: 700 !important; }
        .text-muted-bright { color: #cbd5e1 !important; font-weight: 500 !important; }
        .btn-admin { background: #198754 !important; color: white !important; padding: 8px 25px; border-radius: 50px; font-weight: bold; }
        footer { margin-top: 50px; padding: 20px; text-align: center; font-size: 14px; color: #94a3b8; border-top: 1px solid #2d3238; }
    </style>
</head>
<body>

<!-- 🌾 Fixed Top Premium Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 2px solid #28a745;">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- પ્રીમિયમ લોગો -->
        <a href="index.php" class="premium-logo-container">
            <div class="premium-logo-icon-wrapper">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div class="premium-logo-text-wrapper">
                <div class="logo-main-text">Agro <span>Admin</span> Hub</div>
                <div class="logo-sub-text">Control Panel Center</div>
            </div>
        </a>   

        <!-- નેવિગેશન લિંક્સ -->
        <div class="d-flex align-items-center gap-3">
            <a href="contact.php" class="text-white text-decoration-none fw-bold small">Contact</a>
            <a href="register.php" class="text-white text-decoration-none fw-bold small">Register</a>
            <a href="admin_login.php" class="btn btn-success fw-bold shadow-sm px-4 py-2 rounded-pill small">
                Login
            </a>
        </div>       
    </div>
</nav>

<!-- પેજનું કન્ટેન્ટ -->
<div style="margin-top: 100px;">
    <!-- તમારું બાકીનું કન્ટેન્ટ અહીં આવશે -->
</div>
<div class="container">
    <div class="hero-section">
        <h1 class="display-4 fw-bold text-white-bright">Central Admin Control</h1>
        <p class="lead text-muted-bright">Manage Farmers, Inventory Stocks, and Lab Analytics Data Access.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card">
                <i class="fa-solid fa-users fa-2x text-success mb-3"></i>
                <h5 class="text-white-bright">Farmer Management</h5>
                <p class="text-muted-bright small">Register, update, and manage farmer accounts and their land data.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="fa-solid fa-box-open fa-2x text-primary mb-3"></i>
                <h5 class="text-white-bright">Inventory & Ledger</h5>
                <p class="text-muted-bright small">Manage society stock and oversee all transaction credit entries.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="fa-solid fa-flask fa-2x text-info mb-3"></i>
                <h5 class="text-white-bright">Lab Report Engine</h5>
                <p class="text-muted-bright small">Ingest and publish automated soil health data and analytics reports.</p>
            </div>
        </div>
    </div>
</div>

<footer>
    &copy; 2026 Agro Input Hub | Admin Control Gateway | Developed by: Jaydip Parmar & Pruthviraj Jadeja
</footer>

</body>
</html>