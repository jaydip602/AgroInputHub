<?php
// order_history.php - Farmer Side Online Order History with Return Status Tracking
require_once '../config/db_config.php';
session_start();

if(!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}
$farmer_id = $_SESSION['farmer_id'];

$orders = [];
try {
    // 🎯 ઓર્ડર સાથે product_returns ટેબલને JOIN કરીને રિટર્ન સ્ટેટસ મેળવો
    $query = "SELECT o.*, r.return_status, r.reason 
              FROM orders o 
              LEFT JOIN product_returns r ON o.order_id = r.order_id 
              WHERE o.farmer_id = ? 
              ORDER BY o.order_id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$farmer_id]);
    $orders = $stmt->fetchAll();
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Order History | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js"></script>
    
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', sans-serif; padding-top: 100px; padding-bottom: 80px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 20px !important; }
        .table-custom th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; }
        .table-custom td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
    </style>
</head>
<body>
 <nav class="navbar navbar-dark market-nav mb-4 fixed-top">
        <div class="container">
            <a href="dashboard.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper">
                    <i class="fa-solid fa-wheat-awn"></i>
                </div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Input</span> Hub</div>
                    <div class="logo-sub-text">Digital Kisan Portal</div>
                </div>
            </a>   
            
            <div class="d-flex align-items-center gap-2">
             
            <a href="dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-3 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Home</a>
            <a href="farmer_logout.php" class="btn btn-sm btn-outline-success rounded-pill px-3 text-white fw-bold"><i class="fa-solid fa-right-from-bracket me-1"></i> logout</a>
                
        </div>
        </div>
    </nav>

    
    <div class="container my-4">
        <?php if(isset($_GET['return']) && $_GET['return'] == 'success'): ?>
            <div class="alert alert-success fw-bold text-center shadow-sm mb-4">📦 Your product return request has been successfully submitted!</div>
        <?php endif; ?>

        <div class="card card-custom p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                <h5 class="fw-bold text-success m-0"><i class="fa-solid fa-clock-rotate-left me-2"></i> Track Your Placed Orders & Returns</h5>
                <span class="badge bg-secondary p-2 font-monospace"><?php echo count($orders); ?> Orders Registered</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered table-custom align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Items Ordered</th>
                            <th>Amount</th>
                            <th>Order Status</th>
                          </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="6" class="text-muted p-5">🛒 No orders placed yet!</td></tr>
                        <?php else: foreach ($orders as $row): ?>
                            <tr>
                                <td class="fw-bold font-monospace text-success">#ORD-0<?php echo $row['order_id']; ?></td>
                                <td class="text-start fw-bold" style="max-width: 250px;"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td class="fw-bold font-monospace text-success">₹<?php echo number_format($row['total_amount'], 2); ?></td>
                                <td>
                                    <span class="badge bg-success fw-bold px-3 py-1.5"><?php echo $row['order_status']; ?></span>
                                </td>
                              
                               
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>