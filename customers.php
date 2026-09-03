<?php
// admin/customers.php - Farmer Registry Viewer (Read-Only)
require_once '../config/db_config.php';
session_start();

// 🔐 Security Verification Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// 📋 Fetch Registered Farmer Profiles Matrix (Read-Only)
try {
    $all_farmers = $conn->query("SELECT * FROM farmers ORDER BY farmer_id DESC")->fetchAll();
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registry | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 110px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; color: white; }
        
        .table-custom-fix th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; font-weight: 600; }
        .table-custom-fix td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
    </style>
</head>
<body>

    <!-- 🌾 Fixed Top Premium Admin Navbar Menu Container -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper"><i class="fa-solid fa-users"></i></div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Kisan <span>Registry</span> Hub</div>
                    <div class="logo-sub-text">Registered Farmers Directory</div>
                </div>
            </a>   
            <a href="admin_dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container mb-5 animate__animated animate__fadeIn">
        <div class="row">
            <!-- 📊 Full Width Kisan Registry Data Table -->
            <div class="col-12">
                <div class="card card-custom p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                        <h5 class="fw-bold text-success m-0"><i class="fa-solid fa-address-book me-1"></i> Synchronized Farmer Accounts Registry</h5>
                        <span class="badge bg-success px-3 py-2 rounded-pill font-monospace">Total Registered: <?php echo count($all_farmers); ?></span>
                    </div>

                    <div class="table-responsive border border-secondary shadow-sm">
                        <table class="table table-dark table-hover table-bordered align-middle text-center table-custom-fix mb-0">
                            <thead>
                                <tr>
                                    <th>Kisan ID</th>
                                    <th>Farmer Profile Name</th>
                                    <th>Contact Info</th>
                                    <th>Village Location</th>
                                    <th>Cultivation Area</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($all_farmers)): ?>
                                    <tr>
                                        <td colspan="5" class="text-muted p-4">⚠️ Identity Log Registry Empty! No farmer records cached.</td>
                                    </tr>
                                <?php else: foreach($all_farmers as $farmer): ?>
                                    <tr>
                                        <td class="font-monospace text-muted">#F-0<?php echo $farmer['farmer_id']; ?></td>
                                        <td class="text-start fw-bold text-white"><?php echo htmlspecialchars($farmer['farmer_name']); ?></td>
                                        <td class="text-start font-monospace">
                                            <span class="text-success fw-bold"><i class="fa-solid fa-phone me-1"></i> <?php echo $farmer['phone_number']; ?></span><br>
                                            <small class="text-info" style="font-size: 11px;"><i class="fa-solid fa-envelope me-1"></i> <?php echo !empty($farmer['email']) ? htmlspecialchars($farmer['email']) : '<span class="text-muted">No Email</span>'; ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark border border-secondary text-white px-3 py-1.5 small font-monospace">
                                                Loc: <?php echo htmlspecialchars($farmer['village']); ?>
                                            </span>
                                        </td>
                                        <td class="font-monospace text-warning fw-bold"><?php echo $farmer['land_vigha']; ?> Vigha</td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Dashboard Fixed Status Bar Layer -->
    <footer class="text-center py-3 fixed-bottom-footer">
        &copy; 2026 Agro Input Hub | Control Panel Systems | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja</b>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>