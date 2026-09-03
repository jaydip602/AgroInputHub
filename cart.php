<?php
// farmerside/cart.php - UI Fixed to match premium dark-eco theme
require_once __DIR__ . '/../config/db_config.php';
session_start();

if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}

$farmer_id = $_SESSION['farmer_id'];
$message = "";

// 1. Remove Item
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $remove_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
        header("Location: cart.php?msg=removed");
        exit;
    }
}

// 2. Update Quantity
if (isset($_GET['action']) && $_GET['action'] == 'update_qty' && isset($_GET['id']) && isset($_GET['qty'])) {
    $p_id = intval($_GET['id']);
    $new_qty = intval($_GET['qty']);
    if (isset($_SESSION['cart'][$p_id]) && $new_qty > 0) {
        $_SESSION['cart'][$p_id]['qty'] = $new_qty;
    }
    header("Location: cart.php");
    exit;
}

// 3. Clear Cart
if (isset($_POST['clear_cart_btn'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php?msg=cleared");
    exit;
}

// 4. Handle Direct Buy
if (isset($_POST['direct_place_order'])) {
    $p_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if ($p_id > 0) {
        $_SESSION['cart'] = []; 
        $_SESSION['cart'][$p_id] = [
            'name' => trim($_POST['direct_product_name']),
            'price' => floatval($_POST['direct_price']),
            'qty' => intval($_POST['direct_quantity'])
        ];
        header("Location: cart.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shopping Cart | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js"></script>
    
   <style>
        /* 🎯 Global Dark Theme Settings */
        body { background-color: #121416; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; }
        
        /* 🎯 Cards Styling (Same as Screenshot) */
        .cart-card { 
            background-color: #191c1f !important; 
            border: 1px solid #2d3238 !important; 
            border-radius: 12px; 
        }

        /* 🎯 Navbar Styling */
        .custom-nav { background-color: #191c1f; border-bottom: 1px solid #2d3238; }
        .logo-main-text { font-size: 1.5rem; font-weight: 800; color: #ffffff; line-height: 1; }
        .logo-main-text span { color: #28a745; }
        .logo-sub-text { font-size: 0.75rem; font-weight: 700; color: #ffc107; text-transform: uppercase; letter-spacing: 1px; }
        
        /* 🎯 Table Configuration */
        .table-dark { --bs-table-bg: transparent !important; }
        .cart-table-custom { border-collapse: separate; border-spacing: 0; }
        .cart-table-custom th { 
            background-color: #121416 !important; 
            color: #cbd5e1 !important; 
            border-bottom: 1px solid #2d3238 !important; 
            font-weight: 600;
            padding: 15px;
        }
        .cart-table-custom td { 
            border-bottom: 1px solid #2d3238 !important; 
            vertical-align: middle; 
            padding: 15px;
        }

        /* 🎯 Table Column Alignments */
        .cart-table-custom th:nth-child(1), .cart-table-custom td:nth-child(1) { text-align: left !important; padding-left: 20px !important; }
        .cart-table-custom th:nth-child(2), .cart-table-custom td:nth-child(2),
        .cart-table-custom th:nth-child(3), .cart-table-custom td:nth-child(3),
        .cart-table-custom th:nth-child(4), .cart-table-custom td:nth-child(4),
        .cart-table-custom th:nth-child(5), .cart-table-custom td:nth-child(5) { text-align: center !important; }

        /* 🎯 Buttons & Inputs */
        .qty-btn { 
            background-color: transparent; 
            border: 1px solid #28a745; 
            color: #28a745; 
            padding: 2px 10px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: bold;
        }
        .qty-btn:hover { background-color: #28a745; color: #fff; }
        
        .action-del-btn { color: #dc3545; background: rgba(220, 53, 69, 0.1); padding: 6px 8px; border-radius: 50%; display: inline-block; transition: 0.2s; }
        .action-del-btn:hover { background: #dc3545; color: white; }

        .btn-outline-danger-custom { border: 1px solid #dc3545; color: #dc3545; background: transparent; }
        .btn-outline-danger-custom:hover { background: rgba(220,53,69,0.1); color: #dc3545; }

        .btn-outline-success-custom { border: 1px solid #28a745; color: #28a745; background: transparent; }
        .btn-outline-success-custom:hover { background: rgba(40,167,69,0.1); color: #28a745; }

        .total-box { background-color: #000000; border: 1px solid #2d3238; border-radius: 8px; padding: 20px; }
    </style>
</head>
<body>

    <!-- 🌾 Premium Navbar -->
    <nav class="navbar navbar-expand-lg py-3 mb-4 custom-nav shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="dashboard.php" class="d-flex align-items-center text-decoration-none gap-2">
                <div style="width: 40px; height: 40px; border: 1px solid #28a745; border-radius: 10px; display:flex; justify-content:center; align-items:center; background:#121416;">
                    <i class="fa-solid fa-wheat-awn text-warning fs-5"></i>
                </div>
                <div>
                    <div class="logo-main-text">Agro <span>Input</span> Hub</div>
                    <div class="logo-sub-text">Digital Kisan Portal</div>
                </div>
            </a>   
            <a href="shop_shop.php" class="btn btn-sm btn-outline-light rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-store me-1"></i> Back to Market
            </a>
        </div>
    </nav>

    <div class="container my-4">
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'removed'): ?>
            <div class='alert alert-warning border-0 shadow-sm mb-4'><i class="fa-solid fa-circle-exclamation me-2"></i> Selected item was removed from your cart.</div>
        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'cleared'): ?>
            <div class='alert alert-danger border-0 shadow-sm mb-4'><i class="fa-solid fa-trash-can me-2"></i> Your cart has been cleared.</div>
        <?php endif; ?>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="card cart-card p-5 text-center my-4 shadow-sm border-0">
                <div class="text-muted mb-3" style="font-size: 50px;"><i class="fa-solid fa-basket-shopping"></i></div>
                <h5 class="fw-bold text-secondary">Your shopping cart is currently empty!</h5>
                <a href="shop_shop.php" class="btn btn-success fw-bold px-4 rounded-pill mt-3 shadow-sm">Browse Products</a>
            </div>
        <?php else: ?>
            
            <div class="row g-4">
                <!-- 🎯 Left Grid: Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-card p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-success m-0"><i class="fa-solid fa-cart-shopping me-2"></i> Selected Cart Materials</h5>
                            <form action="cart.php" method="POST" onsubmit="return confirm('Clear entire cart?');">
                                <button type="submit" name="clear_cart_btn" class="btn btn-sm btn-outline-danger-custom fw-bold px-3 rounded-pill">
                                    <i class="fa-solid fa-trash-can me-1"></i> Clear Cart Basket
                                </button>
                            </form>
                        </div>

                        <div class="table-responsive border border-secondary rounded" style="border-color: #2d3238 !important;">
                            <table class="table table-dark table-hover align-middle cart-table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>Material Description</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $grand_total = 0;
                                    foreach ($_SESSION['cart'] as $p_id => $item): 
                                        $sub_total = $item['price'] * $item['qty'];
                                        $grand_total += $sub_total;
                                    ?>
                                        <tr>
                                            <td class="fw-bold text-white"><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td class="fw-bold text-success">₹<?php echo number_format($item['price'], 2); ?></td>
                                            
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="cart.php?action=update_qty&id=<?php echo $p_id; ?>&qty=<?php echo ($item['qty'] - 1); ?>" class="qty-btn">-</a>
                                                    <span class="text-white px-2 fw-bold fs-6"><?php echo $item['qty']; ?></span>
                                                    <a href="cart.php?action=update_qty&id=<?php echo $p_id; ?>&qty=<?php echo ($item['qty'] + 1); ?>" class="qty-btn">+</a>
                                                </div>
                                            </td>
                                            
                                            <td class="fw-bold text-success">₹<?php echo number_format($sub_total, 2); ?></td>
                                            <td>
                                                <a href="cart.php?action=remove&id=<?php echo $p_id; ?>" class="action-del-btn text-decoration-none">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 text-start">
                            <a href="shop_shop.php" class="btn btn-sm btn-outline-success-custom fw-bold px-3 rounded-pill">
                                <i class="fa-solid fa-square-plus me-1"></i> Continue Shopping Market
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 🎯 Right Grid: Order Summary -->
                <div class="col-lg-4">
                    <div class="cart-card p-4 shadow-sm">
                        <h5 class="fw-bold text-success mb-4"><i class="fa-solid fa-receipt me-2"></i> Order Summary</h5>
                        <hr style="border-color: #2d3238;">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 total-box shadow-sm">
                            <span class="fs-6 fw-bold text-white">Total Payable:</span>
                            <span class="fs-3 fw-bold text-success">₹<?php echo number_format($grand_total, 2); ?></span>
                        </div>

                        <a href="address.php" class="btn btn-success btn-lg w-100 fw-bold py-3 shadow-sm rounded-pill text-white text-decoration-none text-center">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>