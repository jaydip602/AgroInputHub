<?php
// admin/contact.php - Central Admin Support & Technical Escalation Desk (Premium Luxury Dark)
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Technical Support | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { 
            background-color: #0d0f11; 
            color: #e2e8f0; 
            font-family: 'Segoe UI', system-ui, sans-serif; 
            padding-bottom: 80px; 
            padding-top: 110px; /* fixed-top navbar માટે પર્ફેક્ટ પેડિંગ */
        }
        
        /* 🎯 ટોપ પ્રીમિયમ કમાન્ડ સેન્ટર પટ્ટી */
        .contact-header { 
            background: linear-gradient(135deg, #191c1f, #0f1112); 
            color: #28a745; 
            padding: 35px 0; 
            border-radius: 15px;
            border: 1px solid #2d3238;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
        }
        
        /* 🎯 લક્ઝરી ડાર્ક થીમ કાર્ડ્સ */
        .card-custom { 
            background: #191c1f !important; 
            border: 1px solid #2d3238 !important; 
            border-radius: 15px !important; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
            transition: all 0.3s ease;
            height: 100%;
        }
        .card-custom:hover { 
            transform: translateY(-5px); 
            border-color: #28a745 !important; 
            box-shadow: 0 12px 25px rgba(40,167,69,0.15) !important; 
        }

        .icon-circle { 
            width: 55px; 
            height: 55px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 22px; 
            background-color: #0d0f11 !important;
            border: 1px solid #2d3238;
        }

        /* 🎯 ડિસ્પ્લે બોક્સ કંટ્રોલ */
        .info-box-display {
            background-color: #0d0f11;
            border: 1px solid #2d3238;
            border-radius: 8px;
            padding: 10px;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            display: inline-block;
            width: 100%;
            margin-top: 10px;
        }

        .card-custom .text-muted { color: #a0aec0 !important; font-weight: 500; }
        .card-custom h5 { color: #ffffff !important; font-weight: 700; }
    </style>
</head>
<body>

    <!-- 🌾 Fixed Top Premium Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Admin</span> Hub</div>
                    <div class="logo-sub-text">Control Panel Center</div>
                </div>
            </a>   
<!-- ❌ આ જૂની લાઇન શોધો -->
<a href="index.php" class="btn btn-success fw-bold shadow-sm px-5 py-2.5 rounded-pill">
    <i class="fa-solid fa-arrow-left me-1"></i> Return 
</a>        </div>
    </nav>

    <div class="container my-4 animate__animated animate__fadeIn">
        <!-- Help Header Section -->
        <div class="contact-header text-center mb-5">
            <div class="container">
                <h2 class="fw-bold m-0"><i class="fa-solid fa-headset text-warning me-2"></i> Administrator Technical Help Desk</h2>
                <p class="mb-0 mt-2 text-muted font-monospace small">Server Control, Database Recovery & System Developer Center</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            
            <!-- 💻 Box 1: Developer Support -->
            <div class="col-md-4">
                <div class="card card-custom p-4 text-center">
                    <div class="icon-circle text-warning mx-auto mb-3">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <h5>BCA Developer Team</h5>
                    <p class="text-muted small">Contact for database crashes, PHP session anomalies, or core architectural source modifications.</p>
                    <div class="info-box-display text-white">
                        <i class="fa-solid fa-laptop-code me-1 text-success"></i> Jaydip Parmar & Pruthviraj Jadeja
                    </div>
                </div>
            </div>

            <!-- 🛠️ Box 2: Server Admin -->
            <div class="col-md-4">
                <div class="card card-custom p-4 text-center">
                    <div class="icon-circle text-info mx-auto mb-3">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <h5>Server Hosting Support</h5>
                    <p class="text-muted small">For XAMPP localhost connectivity, live hosting cookie configurations, or server encryption bugs.</p>
                    <div class="info-box-display">
                        <a href="mailto:admin-jaydipparmar995@gmail.com" class="text-info text-decoration-none" style="font-size: 13px;"><i class="fa-solid fa-envelope-open me-1"></i> admin-jaydipparmar995@gmail.com</a>
                    </div>
                </div>
            </div>

            <!-- 🚨 Box 3: Emergency Outage -->
            <div class="col-md-4">
                <div class="card card-custom p-4 text-center">
                    <div class="icon-circle text-danger mx-auto mb-3">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h5>Security & Integrity Audit</h5>
                    <p class="text-muted small">Immediate escalation route if admin credential database fields get locked out or compromised.</p>
                    <div class="info-box-display">
                        <span class="text-warning"><i class="fa-solid fa-shield-halved text-danger me-1"></i> +91 78629 14314</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- 🔙 Return Button -->
        <div class="text-center mt-5">
            <a href="admin_dashboard.php" class="btn btn-success fw-bold shadow-sm px-5 py-2.5 rounded-pill">
                <i class="fa-solid fa-arrow-left me-1"></i> Return to Control Panel
            </a>
        </div>
    </div>

    <!-- Bottom Footer Layer -->
    <footer class="fixed-bottom text-center py-3 fixed-bottom-footer" style="font-size: 13px;">
        &copy; 2026 Agro Input Hub | Secure Central Portal Control Desk | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja</b>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>