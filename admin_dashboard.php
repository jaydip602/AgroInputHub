<?php
// admin/admin_dashboard.php - Shop Side Admin & Staff Dashboard with Role-Based Navigation
require_once '../config/db_config.php';
require_once 'auth.php';
require_once '../includes/lang.php'; // 🌟 Multi-Language Support Included[cite: 2]

// 👤 ૧. રોલ ચકાસણી (Admin vs Staff)
// Admin: role_id == 1 | Staff: role_id != 1 અથવા session માં staff_id સેટ હોય[cite: 2]
$is_admin = (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1);
$is_staff = (isset($_SESSION['role_id']) && $_SESSION['role_id'] != 1) || isset($_SESSION['staff_id']) || (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'staff');
$staff_id = $_SESSION['staff_id'] ?? ($_SESSION['admin_id'] ?? 1);

// 🔍 ૨. જો માત્ર Staff લોગિન હોય તો જ તેની પ્રોફાઇલનું એપ્રુવલ સ્ટેટસ મેળવવું
$staff_status = "";
if ($is_staff) {
    try {
        $st_stmt = $conn->prepare("SELECT approval_status FROM agro_staff WHERE staff_id = ? LIMIT 1");
        $st_stmt->execute([$staff_id]);
        $staff_status = $st_stmt->fetchColumn() ?: "Pending";
    } catch (Exception $e) {
        $staff_status = "Pending";
    }
}

try {
    // 📊 ૩. લાઈવ એનાલિટિક્સ કાઉન્ટર્સ (Live Database Counts)[cite: 2]
    $stmt_sales = $conn->query("SELECT SUM(total_amount) AS total FROM bills");
    $sales_data = $stmt_sales->fetch();
    $total_sales = $sales_data['total'] ?? 0;

    $stmt_farmers = $conn->query("SELECT COUNT(*) AS total FROM farmers");
    $total_farmers = $stmt_farmers->fetch()['total'] ?? 0;

    $stmt_orders_count = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE order_status = 'Pending'");
    $pending_orders = $stmt_orders_count->fetch()['total'] ?? 0;

    $stmt_stock_alert = $conn->query("SELECT COUNT(*) AS total FROM products WHERE stock_qty < 10 AND status = 'Active'");
    $low_stock = $stmt_stock_alert->fetch()['total'] ?? 0;

    // 📋 ૪. લાઈવ પેન્ડિંગ ઓર્ડર્સનું લિસ્ટ[cite: 2]
    $stmt_recent = $conn->query("SELECT o.*, f.farmer_name, f.village, p.product_name, bi.quantity
                                 FROM orders o 
                                 JOIN farmers f ON o.farmer_id = f.farmer_id 
                                 LEFT JOIN bill_items bi ON o.order_id = bi.bill_id OR o.farmer_id = (SELECT farmer_id FROM bills WHERE bill_id = bi.bill_id LIMIT 1)
                                 LEFT JOIN products p ON bi.product_id = p.product_id
                                 WHERE o.order_status = 'Pending' 
                                 GROUP BY o.order_id
                                 ORDER BY o.order_id DESC LIMIT 5");
    $recent_orders = $stmt_recent->fetchAll();

} catch (Exception $e) {
    die("ડેટાબેઝ એરર: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?? 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_staff ? 'Staff Dashboard' : 'Admin Dashboard'; ?> | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 110px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); }
        .border-sales { border-left: 5px solid #28a745 !important; }
        .border-farmers { border-left: 5px solid #007bff !important; }
        .border-orders { border-left: 5px solid #ffc107 !important; }
        .border-stock { border-left: 5px solid #dc3545 !important; }
        
        .menu-btn { padding: 22px; font-weight: 700; border-radius: 15px; transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #191c1f; border: 1px solid #2d3238; color: #ffffff; text-decoration: none; }
        .menu-btn:hover { background-color: #0f1112; border-color: #28a745; color: #28a745; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(40,167,69,0.15); }
        .menu-icon { font-size: 32px; margin-bottom: 12px; }
        .card-custom .text-muted { color: #a0aec0 !important; font-weight: 600; }
        
        .table-custom-fix th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; font-weight: 600; }
        .table-custom-fix td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
        
        /* 👨‍🌾 Staff Profile Alert Banner */
        .staff-profile-banner {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.15), rgba(25, 28, 31, 0.95));
            border: 1px dashed #28a745;
            border-radius: 15px;
            padding: 16px 20px;
        }
    </style>
</head>
<body>

    <!-- 🌾 Fixed Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper">
                    <i class="fa-solid <?php echo $is_staff ? 'fa-user-tie' : 'fa-user-gear'; ?>"></i>
                </div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span><?php echo $is_staff ? 'Staff' : 'Admin'; ?></span> Hub</div>
                    <div class="logo-sub-text"><?php echo __('welcome'); ?></div>
                </div>
            </a>   
            <div class="d-flex align-items-center gap-2">
                
                <!-- ✨ ૧. માત્ર STAFF લોગિન હોય ત્યારે જ દેખાશે -->
                <?php if ($is_staff): ?>
                    <a href="staff_profile.php" class="btn btn-sm btn-success text-dark fw-bold rounded-pill px-3 shadow-sm">
                        <i class="fa-solid fa-user-pen me-1"></i> My Profile (મારી પ્રોફાઇલ)
                    </a>
                <?php endif; ?>

                <!-- 🛡️ ૨. માત્ર ADMIN લોગિન હોય ત્યારે Verify Staff દેખાશે -->
                <?php if ($is_admin): ?>
                    <a href="verify_staff.php" class="btn btn-sm btn-outline-success text-light rounded-pill px-3">
                        <i class="fa-solid fa-user-check me-1"></i> Verify Staff
                    </a>
                <?php endif; ?>

                <!-- 🌟 Multi-Language Switcher Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-language me-1"></i> 
                        <?php 
                            if($_SESSION['lang'] == 'gu') echo 'ગુજરાતી';
                            elseif($_SESSION['lang'] == 'hi') echo 'हिन्दी';
                            else echo 'English';
                        ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item py-1 small" href="?changelang=gu">🇮🇳 ગુજરાતી</a></li>
                        <li><a class="dropdown-item py-1 small" href="?changelang=hi">🇮🇳 हिन्दी</a></li>
                        <li><a class="dropdown-item py-1 small" href="?changelang=en">🇬🇧 English</a></li>
                    </ul>
                </div>

                <a href="logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> <?php echo __('logout'); ?>
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-4 animate__animated animate__fadeIn">
        
        <!-- 👨‍🌾 માત્ર STAFF માટે પ્રોફાઇલ સ્ટેટસ બેનર -->
        <?php if ($is_staff): ?>
            <div class="staff-profile-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2 shadow-sm">
                <div>
                    <h6 class="fw-bold text-success mb-1">
                        <i class="fa-solid fa-id-card-clip me-1"></i> સ્ટાફ એગ્રોનોમિસ્ટ પ્રોફાઇલ સ્ટેટસ
                    </h6>
                    <small class="text-light opacity-75">ખેડૂતો માટે તમારી ડિગ્રી, સંસ્થા અને કયા પાકમાં નિષ્ણાત છો (Best In) તે વિગત અપડેટ કરો.</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge <?php echo ($staff_status === 'Approved') ? 'bg-success' : (($staff_status === 'Pending') ? 'bg-warning text-dark' : 'bg-danger'); ?> px-3 py-2">
                        Approval: <?php echo $staff_status; ?>
                    </span>
                    <a href="staff_profile.php" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Live Counters Stats -->
        <h5 class="text-success fw-bold mb-3"><i class="fa-solid fa-chart-line me-1"></i> <?php echo __('metrics'); ?></h5>
        <div class="row g-3 mb-5 text-center">
            <div class="col-6 col-md-3">
                <div class="card card-custom stat-card border-sales p-3">
                    <span class="small fw-bold text-muted text-uppercase"><?php echo __('total_sales'); ?></span>
                    <h3 class="fw-bold text-success mt-2 font-monospace">₹ <?php echo number_format($total_sales, 2); ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom stat-card border-farmers p-3">
                    <span class="small fw-bold text-muted text-uppercase"><?php echo __('active_farmers'); ?></span>
                    <h3 class="fw-bold text-primary mt-2 font-monospace"><?php echo $total_farmers; ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom stat-card border-orders p-3">
                    <span class="small fw-bold text-muted text-uppercase"><?php echo __('pending_orders'); ?></span>
                    <h3 class="fw-bold text-warning mt-2 font-monospace"><?php echo $pending_orders; ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom stat-card border-stock p-3">
                    <span class="small fw-bold text-muted text-uppercase"><?php echo __('low_stock'); ?></span>
                    <h3 class="fw-bold text-danger mt-2 font-monospace"><?php echo $low_stock; ?> Pcs</h3>
                </div>
            </div>
        </div>

        <!-- Operations Quick Actions Grid Menu -->
        <h5 class="text-success fw-bold mb-3"><i class="fa-solid fa-sliders text-success me-1"></i> <?php echo __('core_modules'); ?></h5>
        <div class="row g-3 mb-5 text-center">
            
            <!-- ✨ માત્ર STAFF માટે My Profile કાર્ડ -->
            <?php if ($is_staff): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="staff_profile.php" class="menu-btn shadow-sm" style="border-bottom: 3px solid #28a745;">
                        <i class="fa-solid fa-user-pen menu-icon text-success"></i>
                        <span class="small">My Profile</span>
                    </a>
                </div>
            <?php endif; ?>

            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="customers.php" class="menu-btn shadow-sm"><i class="fa-solid fa-users menu-icon text-primary"></i><span class="small">1. <?php echo __('farmers'); ?></span></a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="inventory.php" class="menu-btn shadow-sm"><i class="fa-solid fa-boxes-stacked menu-icon text-success"></i><span class="small">2. <?php echo __('products'); ?></span></a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="billing.php" class="menu-btn shadow-sm"><i class="fa-solid fa-file-invoice-dollar menu-icon text-white"></i><span class="small">3. <?php echo __('billing'); ?></span></a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="orders_manage.php" class="menu-btn shadow-sm"><i class="fa-solid fa-cart-flatbed-suitcase menu-icon text-warning"></i><span class="small">4. <?php echo __('orders'); ?></span></a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="product_reviews.php" class="menu-btn shadow-sm"><i class="fa-solid fa-star menu-icon text-warning"></i><span class="small">5. <?php echo __('reviews'); ?></span></a>
            </div>

            <!-- 🛡️ માત્ર ADMIN માટે મોડ્યુલ્સ -->
            <?php if ($is_admin): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="verify_staff.php" class="menu-btn shadow-sm" style="border-bottom: 3px solid #20c997;"><i class="fa-solid fa-user-check menu-icon text-info"></i><span class="small">6. <?php echo __('verify_staff'); ?></span></a>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="reports.php" class="menu-btn shadow-sm" style="border-bottom: 3px solid #fd7e14;"><i class="fa-solid fa-chart-pie menu-icon" style="color: #fd7e14;"></i><span class="small">7. <?php echo __('reports'); ?></span></a>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="audit_logs.php" class="menu-btn shadow-sm"><i class="fa-solid fa-file-shield menu-icon text-danger"></i><span class="small">8. <?php echo __('audit_logs'); ?></span></a>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="db_backup.php" class="menu-btn shadow-sm"><i class="fa-solid fa-database menu-icon text-warning"></i><span class="small">9. <?php echo __('db_backup'); ?></span></a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Live Pending Orders Alerts Table -->
        <div class="card p-4 card-custom shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                <h5 class="fw-bold mb-0 text-white"><i class="fa-solid fa-bell text-warning animate__animated animate__pulse animate__infinite d-inline-block me-1"></i> <?php echo __('pending_queue'); ?></h5>
                <a href="orders_manage.php" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold text-white"><?php echo __('view_queue'); ?></a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover table-bordered align-middle text-center table-custom-fix mb-0">
                    <thead>
                        <tr>
                            <th><?php echo __('order_id'); ?></th>
                            <th><?php echo __('farmer_profile'); ?></th>
                            <th><?php echo __('village'); ?></th>
                            <th><?php echo __('allocation'); ?></th>
                            <th><?php echo __('timestamp'); ?></th>
                            <th><?php echo __('live_status'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($recent_orders)): ?>
                            <tr><td colspan="6" class="text-muted p-4"><?php echo __('no_orders'); ?></td></tr>
                        <?php else: foreach($recent_orders as $ro): ?>
                            <tr>
                                <td class="fw-bold font-monospace text-success">#ORD-0<?php echo $ro['order_id']; ?></td>
                                <td class="fw-bold text-white"><?php echo htmlspecialchars($ro['farmer_name']); ?></td>
                                <td class="font-monospace text-muted"><?php echo htmlspecialchars($ro['village']); ?></td>
                                <td class="text-start">
                                    <span class="badge bg-dark border border-secondary px-3 py-1.5 fw-bold text-white"><?php echo htmlspecialchars($ro['product_name'] ?? 'Agri Material Package'); ?></span> 
                                    <span class="text-danger fw-bold font-monospace ms-1">[<?php echo $ro['quantity'] ?? '1'; ?> Pcs]</span>
                                </td>
                                <td class="small text-muted font-monospace"><?php echo date('d-m-Y', strtotime($ro['order_date'])); ?></td>
                                <td><span class="badge bg-warning text-dark fw-bold px-3 py-1.5 shadow-sm"><i class="fa-solid fa-spinner fa-spin me-1"></i> <?php echo __('pending'); ?></span></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="text-center py-3 fixed-bottom-footer" style="background-color: #191c1f; border-top: 1px solid #2d3238; color: #a0aec0; font-size: 13px;">
        &copy; 2026 Agro Input Hub | Control Panel Center | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja</b>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>