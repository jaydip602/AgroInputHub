<?php
// admin/reports.php - Advanced Business Analytics Center (Ledger Completely Removed)
require_once '../config/db_config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$total_revenue = 0;
$total_orders = 0;
$estimated_profit = 0;

try {
    $stmt_rev = $conn->query("SELECT COUNT(order_id) as total_ord FROM orders WHERE order_status = 'Delivered'");
    $res_rev = $stmt_rev->fetch();
    $total_orders = $res_rev['total_ord'];

    $stmt_actual_sales = $conn->query("SELECT SUM(total_amount) AS total FROM bills");
    $actual_sales = $stmt_actual_sales->fetch();
    $total_revenue = $actual_sales['total'] ?? ($total_orders * 1250); 
    
    $estimated_profit = $total_revenue * 0.25; 
} catch (Exception $e) { }

$gst_rate = 18; 
$total_gst_collected = ($total_revenue * $gst_rate) / 118; 

// Top Selling Products Query (Fixed)
$top_products = [];
try {
    $query_top = "SELECT p.product_name, SUM(bi.quantity) as sales_count 
                  FROM bill_items bi 
                  JOIN products p ON bi.product_id = p.product_id 
                  GROUP BY bi.product_id, p.product_name 
                  ORDER BY sales_count DESC 
                  LIMIT 5";
    $top_products = $conn->query($query_top)->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Reports & Audit | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 110px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; color: white; transition: transform 0.2s; }
        .card-custom:hover { transform: translateY(-3px); }
        
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; background-color: #0d0f11 !important; border: 1px solid #2d3238; }
        .table-custom-fix th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; font-weight: 600; }
        .table-custom-fix td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
        
        .list-group-item { background: transparent !important; color: white !important; border-color: #2d3238 !important; }
        .card-custom label { color: #cbd5e1 !important; }
        
        @media print {
            .no-print { display: none !important; }
            body { padding-top: 20px; background: white !important; color: black !important; }
            .card-custom { background: white !important; color: black !important; border: 1px solid #ccc !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>

    <!-- 🌾 Fixed Top Navbar Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top no-print" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper"><i class="fa-solid fa-chart-pie"></i></div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Analytics</span> Desk</div>
                    <div class="logo-sub-text">Advanced Tax & Revenue Logs</div>
                </div>
            </a>   
            <div>
                <button onclick="window.print();" class="btn btn-sm btn-outline-warning rounded-pill px-4 text-white fw-bold me-2"><i class="fa-solid fa-print me-1"></i> Print Statement</button>
                <a href="admin_dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container mb-5 animate__animated animate__fadeIn">
        
        <!-- Live Counters Grid Wrapper (Ledger Card Removed, Adjusted to 3 Columns) -->
        <div class="row g-3 mb-4 text-center justify-content-center">
            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <div class="d-flex justify-content-between align-items-center text-start">
                        <div>
                            <span class="text-muted small text-uppercase" style="font-size: 11px; font-weight:700;">Total Store Revenue</span>
                            <h4 class="fw-bold text-success mt-1 font-monospace">₹<?php echo number_format($total_revenue, 2); ?></h4>
                            <small class="text-muted" style="font-size: 11px;"><i class="fa-solid fa-circle-check text-success"></i> <?php echo $total_orders; ?> Trans Ingested</small>
                        </div>
                        <div class="icon-box text-primary shadow-sm"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <div class="d-flex justify-content-between align-items-center text-start">
                        <div>
                            <span class="text-muted small text-uppercase" style="font-size: 11px; font-weight:700;">Net Profit Margin</span>
                            <h4 class="fw-bold text-primary mt-1 font-monospace">₹<?php echo number_format($estimated_profit, 2); ?></h4>
                            <span class="badge bg-dark border border-secondary text-success" style="font-size: 10px;">Avg 25% Ratio Margin</span>
                        </div>
                        <div class="icon-box text-success shadow-sm"><i class="fa-solid fa-chart-line"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <div class="d-flex justify-content-between align-items-center text-start">
                        <div>
                            <span class="text-muted small text-uppercase" style="font-size: 11px; font-weight:700;">Tax Collected (18% GST)</span>
                            <h4 class="fw-bold text-danger mt-1 font-monospace">₹<?php echo number_format($total_gst_collected, 2); ?></h4>
                            <small class="text-muted" style="font-size: 11px;">Central & State Sync</small>
                        </div>
                        <div class="icon-box text-danger shadow-sm"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Data Grid: Top Selling Materials -->
            <div class="col-lg-7">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold text-success mb-3 border-bottom border-secondary pb-2">
                        <i class="fa-solid fa-fire text-warning me-1"></i> Top Performing Crop Input Products (Top 5 Allocation)
                    </h5>
                    <div class="table-responsive border border-secondary shadow-sm">
                        <table class="table table-dark table-hover table-bordered align-middle text-center table-custom-fix mb-0" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th class="text-start">Agro Material Label Name</th>
                                    <th>Total Quantity Sold</th>
                                    <th>Market Popularity Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($top_products)): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-3">No sales records found.</td></tr>
                                <?php else: $i=1; foreach($top_products as $row): ?>
                                    <tr>
                                        <td class="font-monospace text-muted"><?php echo $i++; ?></td>
                                        <td class="text-start"><strong class="text-white"><?php echo htmlspecialchars($row['product_name']); ?></strong></td>
                                        <td class="fw-bold text-primary font-monospace"><?php echo $row['sales_count']; ?> Pcs Sold</td>
                                        <td>
                                            <?php 
                                                if($row['sales_count'] > 10) echo '<span class="badge bg-success text-dark fw-bold">🔥 High Demand</span>';
                                                else echo '<span class="badge bg-primary text-white fw-bold">Standard</span>';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Data Grid: Tax Slabs Breakdown Matrix -->
            <div class="col-lg-5">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold text-success mb-3 border-bottom border-secondary pb-2">
                        <i class="fa-solid fa-scale-balanced text-danger me-1"></i> Standard Inclusive GST BreakUp Ledger Matrix
                    </h5>
                    
                    <ul class="list-group list-group-flush mt-2" style="font-size: 14px;">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <strong class="d-block text-white">CGST (Central Tax Component)</strong>
                                <small class="text-muted small">Central Government Share Allocation (9%)</small>
                            </div>
                            <span class="fw-bold text-white font-monospace">₹<?php echo number_format($total_gst_collected / 2, 2); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <strong class="d-block text-white">SGST (State Tax Component)</strong>
                                <small class="text-muted small">State Government Share Allocation (9%)</small>
                            </div>
                            <span class="fw-bold text-white font-monospace">₹<?php echo number_format($total_gst_collected / 2, 2); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3" style="background-color: #0f1112 !important;">
                            <div>
                                <strong class="d-block text-danger">Total Tax Ingested (Combined GST)</strong>
                                <small class="text-muted small">18% Inclusive Agriculture Slab Standard</small>
                            </div>
                            <span class="badge bg-danger fs-6 font-monospace">₹<?php echo number_format($total_gst_collected, 2); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-3 fixed-bottom-footer no-print">
        &copy; 2026 Agro Input Hub | Control Panel Systems | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja</b>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>