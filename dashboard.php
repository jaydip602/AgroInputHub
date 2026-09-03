<?php
// farmerside/dashboard.php - Premium Kisan Dashboard with Profile Photo & Multi-Language Support
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/flang.php'; // Multi-Language Support Included[cite: 7]

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}

$farmer_id = $_SESSION['farmer_id']; //[cite: 7]

try {
    // ૧. ખેડૂતની પ્રોફાઈલ વિગતો તથા પ્રોફાઇલ ફોટો મેળવો[cite: 7]
    $stmt_f = $conn->prepare("SELECT farmer_name, village, land_vigha, profile_image FROM farmers WHERE farmer_id = ?");
    $stmt_f->execute([$farmer_id]);
    $f_data = $stmt_f->fetch();

    $farmer_name = $f_data['farmer_name'] ?? 'Farmer'; //[cite: 7]
    $village = $f_data['village'] ?? 'N/A'; //[cite: 7]
    $land_size = $f_data['land_vigha'] ?? '0.00'; //[cite: 7]
    
    // 📸 Profile Image Path Setup
    $profile_img_name = $f_data['profile_image'] ?? '';
    $profile_img_path = '';
    if (!empty($profile_img_name) && file_exists(__DIR__ . '/../uploads/profile/' . $profile_img_name)) {
        $profile_img_path = '../uploads/profile/' . htmlspecialchars($profile_img_name);
    }

    // ૨. લેટેસ્ટ ઓર્ડરની વિગત[cite: 7]
    $stmt_latest = $conn->prepare("SELECT product_name, order_status FROM orders WHERE farmer_id = ? AND product_name IS NOT NULL AND product_name != '' ORDER BY order_id DESC LIMIT 1");
    $stmt_latest->execute([$farmer_id]);
    $latest_item = $stmt_latest->fetch();

    $last_order_name = $latest_item['product_name'] ?? "No Orders Placed"; //[cite: 7]
    $last_order_status = $latest_item['order_status'] ?? "-"; //[cite: 7]

} catch (Exception $e) {
    die("Dashboard Sync Fault: " . $e->getMessage()); //[cite: 7]
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Farmer Dashboard | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
    <script src="../assets/js/dynamic_bg.js"></script>
    
   <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 90px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; }
        .quick-actions-btn { text-decoration: none !important; display: block; transition: all 0.3s ease; height: 100%; }
        .quick-actions-btn:hover { transform: translateY(-5px); border-color: #28a745 !important; box-shadow: 0 12px 25px rgba(40,167,69,0.15) !important; }
        .quick-actions-btn span { color: #ffffff !important; font-weight: 700; }
        .card-custom .text-muted { color: #a0aec0 !important; font-weight: 600; }
        .card-custom h5, .card-custom h2 { color: #ffffff !important; }
        .profile-gear-btn { color: #a0aec0; font-size: 1.5rem; transition: all 0.3s ease; text-decoration: none; }
        .profile-gear-btn:hover { color: #28a745; transform: rotate(45deg); }

        /* 📸 Dashboard Welcome Profile Avatar Styles */
        .dashboard-avatar-box {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 2.5px solid #28a745;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            overflow: hidden;
            background: #14181b;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }
        .dashboard-avatar-box:hover {
            transform: scale(1.06);
            border-color: #20c997;
        }
        .dashboard-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .dashboard-avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #181d22;
        }
    </style>
</head>
<body>

    <!-- 🌾 Premium Navbar Wrapper -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="dashboard.php" class="premium-logo-container text-decoration-none">
                <div class="d-flex align-items-center gap-2">
                    <div class="premium-logo-icon-wrapper text-success fs-3"><i class="fa-solid fa-wheat-awn"></i></div>
                    <div class="premium-logo-text-wrapper">
                        <div class="logo-main-text text-white fw-bold">Agro <span>Input</span> Hub</div>
                        <div class="logo-sub-text text-muted small">Digital Kisan Portal</div>
                    </div>
                </div>
            </a>   

            <div class="d-flex align-items-center gap-3">
                <!-- 🌟 Multi-Language Switcher Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-language me-1"></i> 
                        <?php 
                            if($_SESSION['lang'] == 'gu') echo 'ગુજરાતી'; //[cite: 7]
                            elseif($_SESSION['lang'] == 'hi') echo 'हिन्दी'; //[cite: 7]
                            else echo 'English'; //[cite: 7]
                        ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item py-1 small" href="?changelang=gu">🇮🇳 ગુજરાતી</a></li>
                        <li><a class="dropdown-item py-1 small" href="?changelang=hi">🇮🇳 हिन्दी</a></li>
                        <li><a class="dropdown-item py-1 small" href="?changelang=en">🇬🇧 English</a></li>
                    </ul>
                </div>

                <a href="farmer_logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-4 fw-bold"><i class="fa-solid fa-right-from-bracket me-1"></i> <?php echo __('logout'); ?></a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <!-- 🌟 Welcome Card with Profile Avatar Front Layer -->
        <div class="card p-4 card-custom mb-4 border-start border-success border-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                
                <!-- Avatar & Welcome Info Container -->
                <div class="d-flex align-items-center gap-3">
                    <a href="profile.php" title="View & Edit Profile" class="text-decoration-none">
                        <div class="dashboard-avatar-box">
                            <?php if (!empty($profile_img_path)): ?>
                                <img src="<?php echo $profile_img_path; ?>" alt="Farmer Profile Photo" class="dashboard-avatar-img">
                            <?php else: ?>
                                <div class="dashboard-avatar-placeholder">
                                    <i class="fa-solid fa-user-shield text-success fs-3"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <div>
                        <h2 class="fw-bold text-success m-0 mb-1"><?php echo __('welcome'); ?>, <?php echo htmlspecialchars($farmer_name); ?>! 👋</h2>
                        <p class="text-muted mb-0"><?php echo __('village'); ?>: <span class="text-white font-monospace"><?php echo htmlspecialchars($village); ?></span> | <?php echo __('land'); ?>: <span class="text-white font-monospace"><?php echo htmlspecialchars($land_size); ?> Vigha</span></p>
                    </div>
                </div>

                <!-- Settings Gear Trigger -->
                <a href="profile.php" class="profile-gear-btn" title="Edit Profile & Security">
                    <i class="fa-solid fa-user-gear"></i>
                </a>
            </div>
        </div>

        <!-- Metric Summary Analytics Grid Counters -->
        <div class="row g-3 mb-5">
            <div class="col-md-6">
                <div class="card p-3 card-custom border-start border-primary border-4 h-100 d-flex flex-column justify-content-center">
                    <span class="small fw-bold text-muted text-uppercase tracking-wider"><?php echo __('latest_product'); ?></span>
                    <h5 class="fw-bold text-white mt-2 text-truncate" title="<?php echo htmlspecialchars($last_order_name); ?>"><?php echo htmlspecialchars($last_order_name); ?></h5>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3 card-custom border-start border-warning border-4 h-100 d-flex flex-column justify-content-center">
                    <span class="small fw-bold text-muted text-uppercase tracking-wider"><?php echo __('order_status'); ?></span>
                    <div class="mt-2">
                        <?php 
                        $lbl_status = $last_order_status; //[cite: 7]
                        $badge_class = "bg-secondary text-white";
                        if ($lbl_status == 'Pending') { $badge_class = "bg-warning text-dark"; }
                        elseif ($lbl_status == 'Approved') { $badge_class = "bg-info text-dark"; }
                        elseif ($lbl_status == 'Delivered' || $lbl_status == 'Success') { $badge_class = "bg-success text-white"; }
                        ?>
                        <span class="badge <?php echo $badge_class; ?> px-3 py-2 fs-6 fw-bold font-monospace border border-secondary"><?php echo $lbl_status; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operation Quick Grid Menu Links -->
        <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-bolt text-warning me-1"></i> <?php echo __('quick_menu'); ?></h5>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 text-center">
            <div class="col">
                <a href="shop_shop.php" class="quick-actions-btn card-custom py-4 border border-secondary">
                    <div class="fs-2 text-success mb-2"><i class="fa-solid fa-seedling"></i></div>
                    <span class="text-white fw-bold"><?php echo __('marketplace'); ?></span>
                </a>
            </div>
            <div class="col">
                <a href="order_history.php" class="quick-actions-btn card-custom py-4 border border-secondary">
                    <div class="fs-2 text-danger mb-2"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <span class="text-white fw-bold"><?php echo __('order_history'); ?></span>
                </a>
            </div>
            <div class="col">
                <a href="track_order.php" class="quick-actions-btn card-custom py-4 border border-secondary">
                    <div class="fs-2 text-warning mb-2"><i class="fa-solid fa-truck-fast"></i></div>
                    <span class="text-white fw-bold"><?php echo __('track_order'); ?></span>
                </a>
            </div>
            <div class="col">
                <a href="staff_experts.php" class="quick-actions-btn card-custom py-4 border border-secondary">
                    <div class="fs-2 text-info mb-2"><i class="fa-solid fa-user-tie"></i></div>
                    <span class="text-white fw-bold"><?php echo __('agri_experts'); ?></span>
                </a>
            </div>
            <div class="col">
                <a href="about.php" class="quick-actions-btn card-custom py-4 border border-secondary">
                    <div class="fs-2 text-primary mb-2"><i class="fa-solid fa-circle-info"></i></div>
                    <span class="text-white fw-bold"><?php echo __('about'); ?></span>
                </a>
            </div>
        </div>
        
    </div>

    <!-- Bottom Footer Layer -->
    <footer class="fixed-bottom text-center py-3 fixed-bottom-footer bg-dark border-top border-secondary text-muted" style="font-size: 13px;">
        &copy; 2026 Agro Input Hub | Dynamic Statement Engine | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja</b>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>