<?php
// farmerside/product_details.php - Complete Page with Large Image View, Direct Buy Logic & Customer Ratings Integration
require_once __DIR__ . '/../config/db_config.php';
session_start();

if(!isset($_SESSION['farmer_id'])) { 
    header("Location: farmer_login.php"); 
    exit; 
}

// 🌟 રેટિંગ સબમિટ થાય ત્યારે પ્રોસેસ કરવા માટે
if (isset($_POST['submit_review'])) {
    $p_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $f_id = $_SESSION['farmer_id'];
    $stars = intval($_POST['rating_stars']);
    $comment = trim($_POST['review_text']);

    if ($p_id > 0 && $stars > 0 && !empty($comment)) {
        try {
            $stmt_rev = $conn->prepare("INSERT INTO product_reviews (product_id, farmer_id, rating, review_text) VALUES (?, ?, ?, ?)");
            $stmt_rev->execute([$p_id, $f_id, $stars, $comment]);
            header("Location: product_details.php?id=" . $p_id . "&review=success");
            exit;
        } catch (Exception $e) {
            // Error handling if table missing or other exception
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['direct_place_order'])) {
    $p_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if ($p_id > 0) {
        $_SESSION['cart'] = []; 
        $_SESSION['cart'][$p_id] = [
            'name' => trim($_POST['direct_product_name']),
            'price' => floatval($_POST['direct_price']),
            'qty' => intval($_POST['direct_quantity'])
        ];
        
        $_SESSION['last_product_id'] = $p_id;

        header("Location: address.php"); 
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $p_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if ($p_id > 0) {
        $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if (isset($_SESSION['cart'][$p_id])) {
            $_SESSION['cart'][$p_id]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$p_id] = [
                'name' => trim($_POST['product_name']),
                'price' => floatval($_POST['price']),
                'qty' => $qty
            ];
        }
        header("Location: cart.php");
        exit;
    }
}

// 🎯 સેફ્ટી ચેક સાથે product_id ફેચ કરો
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND status = 'Active'");
$stmt->execute([$product_id]);
$p = $stmt->fetch();

if(!$p) {
    die("<div class='container text-center my-5 alert alert-danger border-0 shadow'>❌ This product is not available! <a href='shop_shop.php'>Back to Shop</a></div>");
}

$img_name = !empty($p['product_image']) ? $p['product_image'] : 'default.png';

$discount_pct = isset($p['discount_pct']) ? intval($p['discount_pct']) : 0;
$discount_money = ($p['price'] * $discount_pct) / 100;
$final_price = $p['price'] - $discount_money;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($p['product_name']); ?> - Details | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
    <script src="../assets/js/dynamic_bg.js"></script>
    
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; }
        .details-card { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.4) !important; overflow: hidden; }
        
        /* 🌟 ઓટોમેટિક ડાર્ક થીમ બ્લર બેકગ્રાઉન્ડ ઇફેક્ટ */
        .img-display-box { 
            background: rgba(25, 28, 31, 0.7) !important; 
            backdrop-filter: blur(10px);
            border: 1px solid #2d3238 !important; 
            border-radius: 15px; 
            padding: 15px; 
            text-align: center; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            position: relative; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            height: 420px; 
        }
        .product-main-img { 
            max-width: 100%; 
            max-height: 100%; 
            object-fit: contain; 
            mix-blend-mode: luminosity; 
        }

        .scientific-box { background-color: #0f1112 !important; border-left: 5px solid #28a745 !important; border: 1px solid #2d3238; border-radius: 8px; padding: 18px; }
        .discount-badge-detail { font-size: 14px; font-weight: bold; position: absolute; top: 15px; right: 15px; z-index: 5; }
        .details-card .meta-label, .details-card label, .details-card .text-muted, .details-card .text-secondary { color: #a0aec0 !important; opacity: 1 !important; font-weight: 600 !important; }
        .details-card h2, .details-card strong, .details-card .text-dark { color: #ffffff !important; }
        .qty-input-box-fix { background-color: #0f1112 !important; border: 1px solid #2d3238 !important; color: #ffffff !important; font-weight: 700; }
        
        .custom-nav {
            padding-top: 5px !important;
            padding-bottom: 5px !important;
            margin-top: 0px !important;
            margin-bottom: 15px !important;
        }
        body {
            padding-top: 0px !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark custom-nav py-3 shadow-sm mb-4">
        <div class="container">
            <a href="dashboard.php" class="premium-logo-container text-decoration-none">
                <div class="d-flex align-items-center gap-2">
                    <div class="premium-logo-icon-wrapper text-success fs-3"><i class="fa-solid fa-wheat-awn"></i></div>
                    <div class="premium-logo-text-wrapper">
                        <div class="logo-main-text text-white fw-bold">Agro <span>Input</span> Hub</div>
                        <div class="logo-sub-text text-muted small">Digital Kisan Portal</div>
                    </div>
                </div>
            </a>
            <a href="shop_shop.php" class="btn btn-sm btn-outline-success rounded-pill px-4 fw-bold"><i class="fa-solid fa-store me-1"></i> Back to Shop</a>
        </div>
    </nav>

    <div class="container my-5">
        <?php if(isset($_GET['review']) && $_GET['review'] == 'success'): ?>
            <div class='alert alert-success fw-bold text-center small shadow-sm mb-4'>🎉 Your product review has been successfully published![cite: 8]</div>
        <?php endif; ?>

        <div class="card details-card p-4 p-md-5 mb-4">
            <div class="row g-5">
                
                <div class="col-md-5">
                    <div class="img-display-box">
                        <?php if($discount_pct > 0): ?>
                            <span class="badge bg-danger discount-badge-detail shadow-sm animate__animated animate__flash animate__infinite animate__slower">-<?php echo $discount_pct; ?>% OFF</span>
                        <?php endif; ?>
                        <img src="../uploads/<?php echo $img_name; ?>" class="product-main-img" alt="Product Image" onerror="this.src='../uploads/default.png';">
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="d-flex gap-2 mb-2">
                        <span class="badge bg-success text-uppercase px-3 py-2 fs-6 shadow-sm"><?php echo htmlspecialchars($p['category']); ?></span>
                        <?php if(!empty($p['sub_category'])): ?>
                            <span class="badge bg-secondary text-uppercase px-3 py-2 fs-6 shadow-sm"><?php echo htmlspecialchars($p['sub_category']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <h2 class="fw-bold text-success mb-1"><?php echo htmlspecialchars($p['product_name']); ?></h2>
                    <p class="text-muted fs-5 mb-3">Company: <strong class="text-white"><?php echo htmlspecialchars($p['company_name']); ?></strong></p>

                    <?php if(!empty($p['technical_name'])): ?>
                        <div class="mb-3">
                            <span class="small fw-bold text-muted d-block mb-1">Technical Composition:</span>
                            <span class="badge bg-dark text-success border border-secondary fw-bold fs-6 px-3 py-1.5"><i class="fa-solid fa-flask text-success me-1"></i> <?php echo htmlspecialchars($p['technical_name']); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <h3 class="fw-bold text-white d-flex align-items-baseline gap-3 m-0">
                            Price: <span class="text-success">₹<?php echo number_format($final_price, 2); ?></span>
                            <?php if($discount_pct > 0): ?>
                                <del class="text-muted fs-5 fw-normal">₹<?php echo number_format($p['price'], 2); ?></del>
                            <?php endif; ?>
                            <span class="fs-6 text-muted fw-normal">/ per unit</span>
                        </h3>
                    </div>

                    <div class="mb-4 text-muted fw-bold">
                        Stock Status: <span class="text-white font-monospace"><?php echo $p['stock_qty']; ?></span> units available 
                    </div>

                    <div class="scientific-box mb-4 shadow-sm">
                        <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-microscope text-success"></i> 🌿 Scientific Details & Usage Guide</h6>
                        <div class="row g-2">
                            <div class="col-12 border-bottom border-secondary pb-2 mb-2">
                                <span class="text-secondary small d-block"><i class="fa-solid fa-seedling text-success me-1"></i> Recommended Crops:</span>
                                <strong class="text-white fs-6"><?php echo htmlspecialchars($p['target_crop'] ?? 'Suitable for all crops'); ?></strong>
                            </div>
                            <?php if(!empty($p['target_pest'])): ?>
                                <div class="col-6">
                                    <span class="text-secondary small d-block"><i class="fa-solid fa-bug text-danger me-1"></i> Target Pest / Disease:</span>
                                    <strong class="text-white"><?php echo htmlspecialchars($p['target_pest']); ?></strong>
                                </div>
                            <?php endif; ?>
                            <div class="col-6">
                                <span class="text-secondary small d-block"><i class="fa-solid fa-scale-balanced text-warning me-1"></i> Dosage Rate:</span>
                                <strong class="text-white"><?php echo htmlspecialchars($p['dosage_per_ha'] ?? 'As per requirement'); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top border-secondary">
                        <?php if($p['stock_qty'] > 0): ?>
                            <form action="" method="POST" style="max-width: 450px;">
                                <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($p['product_name']); ?>">
                                <input type="hidden" name="price" value="<?php echo $final_price; ?>">
                                
                                <input type="hidden" name="direct_product_name" value="<?php echo htmlspecialchars($p['product_name']); ?>">
                                <input type="hidden" name="direct_price" value="<?php echo $final_price; ?>">
                                <input type="hidden" name="direct_quantity" id="direct_quantity_hidden" value="1">

                                <div class="input-group mb-3 shadow-sm" style="max-width: 280px;">
                                    <span class="input-group-text bg-black text-muted border-secondary fw-bold">Quantity (Pcs)</span>
                                    <input type="number" name="quantity" id="qty_input" class="form-control text-center qty-input-box-fix fs-5" value="1" min="1" max="<?php echo $p['stock_qty']; ?>" required>
                                </div>
                                
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="submit" name="add_to_cart" class="btn btn-outline-success btn-lg w-100 fw-bold shadow-sm text-white" style="font-size: 15px;">
                                            <i class="fa-solid fa-cart-plus me-1"></i> Add to Cart
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" name="direct_place_order" onclick="document.getElementById('direct_quantity_hidden').value = document.getElementById('qty_input').value;" class="btn btn-success btn-lg w-100 fw-bold text-white shadow" style="font-size: 15px;">
                                            <i class="fa-solid fa-bolt me-1 text-warning"></i> Buy Now
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg w-100 fw-bold rounded-pill" disabled>🚫 Out of Stock</button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- 🌟 Customer Ratings & Reviews Section -->
        <div class="card details-card p-4 p-md-5">
            <h4 class="fw-bold text-success mb-4 border-bottom border-secondary pb-3"><i class="fa-solid fa-star me-2 text-warning"></i> Farmer Ratings & Reviews</h4>

            <?php
            try {
                $rev_stmt = $conn->prepare("SELECT r.*, f.farmer_name, f.village FROM product_reviews r JOIN farmers f ON r.farmer_id = f.farmer_id WHERE r.product_id = ? ORDER BY r.review_id DESC");
                $rev_stmt->execute([$product_id]);
                $all_reviews = $rev_stmt->fetchAll();

                $avg_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM product_reviews WHERE product_id = ?");
                $avg_stmt->execute([$product_id]);
                $avg_data = $avg_stmt->fetch();
                $avg_rating = round($avg_data['avg_rating'], 1);
                $total_reviews = $avg_data['total_reviews'];
            } catch (Exception $e) {
                $all_reviews = [];
                $avg_rating = 0;
                $total_reviews = 0;
            }
            ?>

            <div class="row align-items-center mb-4 pb-3 border-bottom border-secondary">
                <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                    <div class="display-4 fw-bold text-warning"><?php echo $avg_rating > 0 ? $avg_rating : '0.0'; ?> <small class="fs-5 text-muted">/5</small></div>
                    <div class="text-warning fs-5 my-1">
                        <?php 
                        $full_stars = floor($avg_rating);
                        for($i=1; $i<=5; $i++) {
                            echo ($i <= $full_stars) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                        }
                        ?>
                    </div>
                    <small class="text-muted fw-bold">Based on <?php echo $total_reviews; ?> verified farmer reviews</small>
                </div>

                <div class="col-md-8">
                    <form action="" method="POST" class="p-3 rounded" style="background: #0d0f11; border: 1px solid #2d3238;">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <h6 class="fw-bold text-white mb-2"><i class="fa-solid fa-pen-nib me-1 text-success"></i> Write Your Product Review:</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-sm-5">
                                <select name="rating_stars" class="form-select form-select-sm bg-black text-white border-secondary fw-bold" required>
                                    <option value="">Select Rating Stars</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                                    <option value="4">⭐⭐⭐⭐ (4 - Very Good)</option>
                                    <option value="3">⭐⭐⭐ (3 - Good)</option>
                                    <option value="2">⭐⭐ (2 - Fair)</option>
                                    <option value="1">⭐ (1 - Poor)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-2">
                            <textarea name="review_text" class="form-control form-control-sm bg-black text-white border-secondary" rows="2" placeholder="Share your experience regarding crop result, quality, etc..." required></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="btn btn-sm btn-success fw-bold rounded-pill px-4">
                            <i class="fa-solid fa-paper-plane me-1"></i> Submit Review
                        </button>
                    </form>
                </div>
            </div>

            <div class="review-list">
                <h6 class="fw-bold text-muted mb-3">All Reviews (<?php echo $total_reviews; ?>)</h6>
                <?php if(empty($all_reviews)): ?>
                    <p class="text-muted small fst-italic">No reviews registered yet for this item. Be the first farmer to share feedback!</p>
                <?php else: foreach($all_reviews as $rev): ?>
                    <div class="p-3 mb-3 rounded shadow-sm" style="background: #0f1112; border: 1px solid #2d3238;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <span class="fw-bold text-white fs-6"><?php echo htmlspecialchars($rev['farmer_name']); ?></span>
                                <small class="text-muted ms-2 font-monospace" style="color: #6ee7b7 !important;">Loc: <?php echo htmlspecialchars($rev['village'] ?? 'Kondh'); ?></small>
                            </div>
                            <span class="text-warning small">
                                <?php for($s=1; $s<=5; $s++) {
                                    echo ($s <= $rev['rating']) ? '<i class="fa-solid fa-star fa-xs"></i>' : '<i class="fa-regular fa-star fa-xs"></i>';
                                } ?>
                            </span>
                        </div>
                        <p class="text-muted small mb-1"><?php echo htmlspecialchars($rev['review_text']); ?></p>
                        <div class="text-end">
                            <small class="text-secondary font-monospace" style="font-size: 10px;"><i class="fa-regular fa-clock me-1"></i><?php echo date('d-m-Y H:i', strtotime($rev['created_at'])); ?></small>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
