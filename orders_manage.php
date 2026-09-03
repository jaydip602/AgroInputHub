<?php
// admin/orders_manage.php - Admin Online Orders Management Dashboard (Duplicate Fix Version)
require_once __DIR__ . '/../config/db_config.php';
session_start();

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id']) && !isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

$message = "";

if (isset($_POST['update_status_btn'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['order_status'];
    
    $operator_name = "admin_main";
    if (isset($_SESSION['staff_username'])) {
        $operator_name = $_SESSION['staff_username'];
    } elseif (isset($_SESSION['admin_username'])) {
        $operator_name = $_SESSION['admin_username'];
    } elseif (isset($_SESSION['username'])) {
        $operator_name = $_SESSION['username'];
    }
    
    try {
        $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
        $stmt->execute([$new_status, $order_id]);
        
        $log = $conn->prepare("INSERT INTO audit_logs (username, action_performed, table_affected) VALUES (?, ?, 'orders')");
        $log->execute([$operator_name, "ઓર્ડર #ORD-$order_id નું સ્ટેટસ બદલીને '$new_status' કર્યું."]);
        
        $message = "<div class='alert alert-success py-2 small fw-bold alert-dismissible fade show text-center mb-3'>🎉 Order status successfully updated! <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger py-2 small fw-bold text-center mb-3'>❌ Fault Ingestion: " . $e->getMessage() . "</div>";
    }
}

$all_orders = [];
try {
    $all_orders = $conn->query("SELECT o.*, f.farmer_name, f.village,
                                     (SELECT COUNT(*) FROM bills b WHERE b.order_id = o.order_id) AS bill_exists
                                FROM orders o 
                                JOIN farmers f ON o.farmer_id = f.farmer_id 
                                ORDER BY o.order_id DESC")->fetchAll();
} catch (Exception $e) {
    $message = "<div class='alert alert-danger py-2 small fw-bold text-center mb-3'>❌ Order Loading Exception: " . $e->getMessage() . "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Orders Control | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 110px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; color: white; }
        .table-custom-fix th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; font-weight: 600; }
        .table-custom-fix td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
        .status-select { font-size: 13px; font-weight: 700; padding: 4px 8px; border-radius: 6px; border: 1px solid #2d3238 !important; }
        .form-select { background-color: #0d0f11 !important; color: white !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper"><i class="fa-solid fa-cart-flatbed"></i></div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Order <span>Control</span> Hub</div>
                    <div class="logo-sub-text">Online Request Queue</div>
                </div>
            </a>   
            <a href="admin_dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container mb-5 animate__animated animate__fadeIn">
        <?php if(!empty($message)) echo $message; ?>

        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4 text-success border-bottom border-secondary pb-2"><i class="fa-solid fa-list-check me-1"></i> Live Online Orders Operations Dashboard</h5>
            <div class="table-responsive border border-secondary shadow-sm">
                <table class="table table-dark table-hover table-bordered align-middle text-center table-custom-fix mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Farmer Profile Identity</th>
                            <th>Requested Material Description</th>
                            <th>Total Amount</th>
                            <th>Payment Gateway</th>
                            <th>Order Timestamp</th>
                            <th>Dispatch Status Operations</th>
                            <th>Billing Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($all_orders)): ?>
                            <tr><td colspan="8" class="text-muted p-4">🎉 Excellent! No online orders logged in the repository.</td></tr>
                        <?php else: foreach($all_orders as $row): ?>
                            <tr>
                                <td class="fw-bold font-monospace text-success">#ORD-0<?php echo $row['order_id']; ?></td>
                                <td class="text-start">
                                    <div class="fw-bold text-white"><?php echo htmlspecialchars($row['farmer_name']); ?></div>
                                    <small class="text-muted-custom small font-monospace" style="color: #6ee7b7 !important;">Loc: <?php echo htmlspecialchars($row['village']); ?></small>
                                </td>
                                <td class="text-start fw-bold text-white" style="max-width: 250px;"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td class="fw-bold text-success font-monospace">₹ <?php echo number_format($row['total_amount'], 2); ?></td>
                                <td>
                                    <?php 
                                    $pm = $row['payment_mode'];
                                    if(stripos($pm, 'baki') !== false || stripos($pm, 'credit') !== false) {
                                        echo "<span class='badge bg-danger text-white px-3 py-1.5 fw-bold shadow-sm'>$pm</span>";
                                    } else {
                                        echo "<span class='badge bg-primary text-white px-3 py-1.5 shadow-sm'>$pm</span>";
                                    }
                                    ?>
                                </td>
                                <td class="text-muted small font-monospace fw-bold"><?php echo date('d-m-Y H:i', strtotime($row['order_date'])); ?></td>
                                <td>
                                    <form action="" method="POST" class="d-flex align-items-center justify-content-center gap-1">
                                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                        <?php $st = $row['order_status']; ?>
                                        <select name="order_status" class="form-select form-select-sm status-select fw-bold 
                                            <?php 
                                            if($st == 'Pending') echo 'bg-warning text-dark'; 
                                            elseif($st == 'Approved') echo 'bg-info text-dark'; 
                                            elseif($st == 'Delivered') echo 'bg-success text-dark'; 
                                            else echo 'bg-secondary text-white'; 
                                            ?>">
                                            <option value="Pending" <?php if($st == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Approved" <?php if($st == 'Approved') echo 'selected'; ?>>Approved</option>
                                            <option value="Delivered" <?php if($st == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                            <option value="Cancelled" <?php if($st == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status_btn" class="btn btn-sm btn-dark py-1 px-2.5 shadow-sm border border-secondary" title="Save Status">
                                            <i class="fa-solid fa-floppy-disk"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <?php if (intval($row['bill_exists']) == 0 && $st != 'Delivered'): ?>
                                        <a href="billing.php?auto_create=1&farmer_id=<?php echo $row['farmer_id']; ?>&amount=<?php echo $row['total_amount']; ?>&payment=<?php echo urlencode($row['payment_mode']); ?>&from_order=<?php echo $row['order_id']; ?>" class="btn btn-sm btn-success fw-bold shadow-sm rounded-pill px-3">
                                            <i class="fa-solid fa-file-invoice-dollar me-1"></i> Generate POS Bill
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle border border-success text-success fw-bold px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i> Invoice Synced</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="text-center py-3 fixed-bottom-footer">
        &copy; 2026 Agro Input Hub | Control Panel Systems | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja</b>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```[cite: 6]