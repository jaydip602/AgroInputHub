<?php
// farmerside/shop_shop.php - Premium Balanced Marketplace Grid Layer with Live Search & Category Filter
require_once __DIR__ . '/../config/db_config.php';
session_start();

if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}
$farmer_id = $_SESSION['farmer_id'];
$message = "";

if (isset($_POST['add_to_cart'])) {
    $p_id = intval($_POST['product_id']);
    $p_name = $_POST['product_name'];
    $p_price = floatval($_POST['price']); 
    $p_qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($p_qty > 0) {
        $_SESSION['cart'][$p_id] = ['name' => $p_name, 'price' => $p_price, 'qty' => $p_qty];
        $message = "<div class='alert alert-success alert-dismissible fade show mb-4' role='alert'>
                        🛒 <strong>Success!</strong> '" . htmlspecialchars($p_name) . "' added to cart.
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    </div>";
    }
}

// 🎯 કેટેગરી ફિલ્ટર લોજિક
$selected_category = isset($_GET['category']) ? trim($_GET['category']) : 'All';

$products = [];
try {
    if ($selected_category === 'All' || empty($selected_category)) {
        $stmt = $conn->query("SELECT * FROM products WHERE status = 'Active' ORDER BY discount_pct DESC, product_id DESC");
        $products = $stmt->fetchAll();
    } else {
        $stmt = $conn->prepare("SELECT * FROM products WHERE status = 'Active' AND category = ? ORDER BY discount_pct DESC, product_id DESC");
        $stmt->execute([$selected_category]);
        $products = $stmt->fetchAll();
    }
} catch (Exception $e) {
    die("Catalog Error: " . $e->getMessage());
}

$cart_count = !empty($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agro Market Shop | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js"></script>
    
    

    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top:30px; }
        
        .market-nav {
            background-color: #191c1f !important;
            border-bottom: 1px solid #2d3238 !important;
            padding: 12px 0;
        }
        .market-nav .container {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        .card-custom .text-muted, 
        .card-custom p.small,
        .input-group-text {
            color: #a0aec0 !important;
            opacity: 1 !important;
            font-weight: 600 !important;
        }

        .qty-box-fix {
            background-color: #0f1112 !important;
            border: 1px solid #2d3238 !important;
            color: #28a745 !important;
            font-weight: 700;
        }
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
                <a href="cart.php" class="btn btn-success btn-sm position-relative px-4 rounded-pill fw-bold shadow-sm">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Cart 
                    <?php if($cart_count > 0): ?><span class="badge bg-danger rounded-pill ms-1"><?php echo $cart_count; ?></span><?php endif; ?>
                </a>
                <a href="dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-3 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Home</a>
                <a href="farmer_logout.php" class="btn btn-sm btn-outline-success rounded-pill px-3 text-white fw-bold"><i class="fa-solid fa-right-from-bracket me-1"></i> logout</a>
            
            </div>
        </div>
    </nav>

    <div class="container my-5" style="margin-top: 80px !important;">
        <?php echo $message; ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-2">
            <h4 class="fw-bold text-success m-0"><i class="fa-solid fa-store me-2"></i> Certified Inventory Depot</h4>
            <span class="badge bg-secondary p-2 font-monospace fw-bold"><?php echo count($products); ?> Products Available</span>
        </div>

        <!-- 🔍 Live Search & Category Filter Section -->
        <div class="row mb-4 g-3 align-items-center">
            <!-- Live Search Input Box -->
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-success"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="liveSearchInput" class="form-control text-white bg-dark border-secondary" placeholder="Search products by name or company..." onkeyup="filterProductsLive()">
                </div>
            </div>
            
            <!-- Category Filter Tabs -->
            <div class="col-md-5 text-md-end">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    <a href="shop_shop.php?category=All" class="btn btn-sm <?php echo ($selected_category === 'All') ? 'btn-success' : 'btn-outline-secondary text-white'; ?> rounded-pill px-3 fw-bold">All</a>
                    <a href="shop_shop.php?category=Pesticides" class="btn btn-sm <?php echo ($selected_category === 'Pesticides') ? 'btn-success' : 'btn-outline-secondary text-white'; ?> rounded-pill px-3 fw-bold">Pesticides</a>
                    <a href="shop_shop.php?category=Fertilizers" class="btn btn-sm <?php echo ($selected_category === 'Fertilizers') ? 'btn-success' : 'btn-outline-secondary text-white'; ?> rounded-pill px-3 fw-bold">Fertilizers</a>
                    <a href="shop_shop.php?category=Seeds" class="btn btn-sm <?php echo ($selected_category === 'Seeds') ? 'btn-success' : 'btn-outline-secondary text-white'; ?> rounded-pill px-3 fw-bold">Seeds</a>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="productContainer">
            <?php if(empty($products)): ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-dark border border-secondary text-muted">No products found in this category!</div>
                </div>
            <?php else: foreach ($products as $row): 
                $max_stock = intval($row['stock_qty']);
                $discount_pct = intval($row['discount_pct'] ?? 0);
                $final_price = $row['price'] - (($row['price'] * $discount_pct) / 100);
            ?>
                <div class="col product-card-item" data-name="<?php echo strtolower(htmlspecialchars($row['product_name'] . ' ' . $row['company_name'])); ?>">
                    <div class="card card-custom h-100 p-2 position-relative shadow-sm" style="background:#191c1f; border:1px solid #2d3238; border-radius:15px;">
                        <span class="badge bg-success position-absolute top-0 start-0 m-2 font-monospace" style="z-index:10;"><?php echo $row['category']; ?></span>
                        <?php if($discount_pct > 0): ?><span class="badge bg-danger position-absolute top-0 end-0 m-2 animate__animated animate__flash animate__infinite animate__slower">-<?php echo $discount_pct; ?>% OFF</span><?php endif; ?>
                        
                        <div class="text-center p-3 rounded" style="height:170px; display:flex; align-items:center; justify-content:center; background:#0f1112; border:1px solid #2d3238;">
                            <a href="product_details.php?id=<?php echo $row['product_id']; ?>" class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <img src="../uploads/<?php echo $row['product_image'] ?: 'default.png'; ?>" style="max-height:100%; max-width:100%; object-fit:contain;" onerror="this.src='../uploads/default.png';">
                            </a>
                        </div>
                        
                        <div class="card-body p-2 d-flex flex-column justify-content-between">
                            <div class="mt-2">
                                <?php if(!empty($row['sub_category'])): ?>
                                    <span class="badge bg-dark border border-secondary text-warning mb-1" style="font-size: 10px;"><?php echo htmlspecialchars($row['sub_category']); ?></span>
                                <?php endif; ?>

                                <h6 class="fw-bold text-white text-truncate mb-1"><?php echo htmlspecialchars($row['product_name']); ?></h6>
                                <p class="small text-muted mb-2">Mfr: <strong class="text-white"><?php echo htmlspecialchars($row['company_name']); ?></strong></p>
                                <div class="d-flex align-items-baseline gap-2 mb-2">
                                    <span class="fw-bold text-success fs-5">₹<?php echo number_format($final_price, 2); ?></span>
                                    <?php if($discount_pct > 0): ?><del class="text-muted small">₹<?php echo number_format($row['price'], 2); ?></del><?php endif; ?>
                                </div>
                            </div>
                            
                            <div>
                                <div class="text-end mb-2"><span class="badge bg-black text-muted border border-secondary small fw-bold">Stock: <?php echo $max_stock; ?> Pcs</span></div>
                                <?php if($max_stock > 0): ?>
                                    <form action="" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>">
                                        <input type="hidden" name="price" value="<?php echo $final_price; ?>">
                                        
                                        <div class="input-group input-group-sm mb-2 shadow-sm">
                                            <span class="input-group-text small font-monospace bg-black border-secondary">Qty:</span>
                                            <input type="number" name="quantity" class="form-control text-center qty-box-fix" value="1" min="1" max="<?php echo $max_stock; ?>" required>
                                        </div>
                                        <button type="submit" name="add_to_cart" class="btn btn-sm btn-success w-100 fw-bold rounded-pill shadow-sm"><i class="fa-solid fa-cart-plus me-1"></i> Add to Cart</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary w-100 fw-bold rounded-pill" disabled>🚫 Sold Out</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
    
    <footer class="fixed-bottom text-center py-3 fixed-bottom-footer">&copy; 2026 Agro Input Hub | Catalog Portal</footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // 🔍 Live Search Script Logic
    function filterProductsLive() {
        let inputQuery = document.getElementById('liveSearchInput').value.toLowerCase();
        let productCards = document.getElementsByClassName('product-card-item');

        for (let i = 0; i < productCards.length; i++) {
            let searchableText = productCards[i].getAttribute('data-name');
            if (searchableText.includes(inputQuery)) {
                productCards[i].style.display = "";
            } else {
                productCards[i].style.display = "none";
            }
        }
    }
    </script>
</body>
</html>