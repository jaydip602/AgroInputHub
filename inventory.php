<?php
// admin/inventory.php - Soft Delete, Live Stock/Price, Discount & Package Size Ingestion Configured Inventory
require_once '../config/db_config.php';
session_start();

// 🔐 Security Assertion Clearance Route
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$message = "";

// 🗑️ Soft Delete Operation Engine
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    try {
        $del_stmt = $conn->prepare("UPDATE products SET status = 'Deleted' WHERE product_id = ?");
        if ($del_stmt->execute([$product_id])) {
            $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <i class='fa-solid fa-eye-slash me-2'></i> <strong>Success!</strong> Product record has been successfully hidden from the marketplace.
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>";
        }
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger text-center small py-2'>❌ Operational Fault: " . $e->getMessage() . "</div>";
    }
}

// ⚡ Dynamic Batch Update Logic: Stock, Base Price & Offer Discount Percentage Ingestion
if (isset($_POST['update_stock_btn'])) {
    $up_product_id = intval($_POST['up_product_id']);
    $up_stock      = intval($_POST['up_stock']);
    $up_price      = floatval($_POST['up_price']);
    $up_discount   = intval($_POST['up_discount']);

    if ($up_product_id > 0 && $up_stock >= 0 && $up_price >= 0 && $up_discount >= 0 && $up_discount <= 100) {
        try {
            $up_stmt = $conn->prepare("UPDATE products SET stock_qty = ?, price = ?, discount_pct = ? WHERE product_id = ?");
            if ($up_stmt->execute([$up_stock, $up_price, $up_discount, $up_product_id])) {
                $message = "<div class='alert alert-success alert-dismissible fade show'>✨ <strong>Inventory & Offers Dispatched!</strong> Updated price and live metrics are now active.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger text-center small py-2'>❌ Update Exception: " . $e->getMessage() . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning text-center small py-2'>⚠️ Parameter Mismatch: Ensure pricing is valid and discount stays between 0% and 100%!</div>";
    }
}

// 📥 Fresh Material Ingestion Gateway (📦 Package Size Added)
if (isset($_POST['add_product_btn'])) {
    $product_name   = trim($_POST['product_name']);
    $company_name   = trim($_POST['company_name']);
    $technical_name = trim($_POST['technical_name']);
    $category       = $_POST['category'];
    $package_size   = trim($_POST['package_size']); // 📦 પેકેજ સાઈઝ ઇનપુટ
    
    $pest_type      = trim($_POST['sub_category']); // Insecticide / Fungicide / Herbicide
    $formulation    = isset($_POST['formulation']) ? trim($_POST['formulation']) : ''; // Liquid / Powder / Granules
    $liquid_type    = isset($_POST['liquid_type']) ? trim($_POST['liquid_type']) : ''; // Systemic / Contact

    // સબ-કેટેગરીનું પ્રોફેશનલ ફોર્મેટ તૈયાર કરો
    if ($category === 'Pesticides') {
        if ($formulation === 'Liquid' && !empty($liquid_type)) {
            $sub_category = "$pest_type ($formulation - $liquid_type)";
        } else {
            $sub_category = "$pest_type ($formulation)";
        }
    } else {
        $sub_category = $pest_type;
    }

    $price          = floatval($_POST['price']);
    $discount_pct   = intval($_POST['discount_pct']);
    $stock_qty      = intval($_POST['stock_qty']);
    $target_crop    = trim($_POST['target_crop']);    
    $target_pest    = trim($_POST['target_pest']);    
    $dosage_per_ha  = trim($_POST['dosage_per_ha']);  

    $product_image = "default.png";
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $target_dir = "../uploads/";
        $file_name = time() . "_" . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $product_image = $file_name;
        }
    }

    try {
        $query = "INSERT INTO products (product_name, company_name, technical_name, category, sub_category, package_size, price, discount_pct, stock_qty, product_image, target_crop, target_pest, dosage_per_ha, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";
        $stmt = $conn->prepare($query);
        
        if ($stmt->execute([$product_name, $company_name, $technical_name, $category, $sub_category, $package_size, $price, $discount_pct, $stock_qty, $product_image, $target_crop, $target_pest, $dosage_per_ha])) {
            $message = "<div class='alert alert-success alert-dismissible fade show'>✨ <strong>Material Logged!</strong> Product along with the package size and offer is live.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger text-center small py-2'>❌ Ingestion Error: " . $e->getMessage() . "</div>";
    }
}

// 📋 Fetch Active Inventory Schema Records
$all_products = [];
try {
    $all_products = $conn->query("SELECT * FROM products WHERE status = 'Active' ORDER BY product_id DESC")->fetchAll();
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Register | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 110px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; color: white; }
        
        .nav-tabs { border-bottom: 1px solid #2d3238 !important; }
        .nav-tabs .nav-link { font-weight: 600; color: #a0aec0; border: none; background: none; }
        .nav-tabs .nav-link.active { color: #28a745 !important; border-bottom: 3px solid #28a745 !important; }
        
        .form-control, .form-select { background-color: #0d0f11 !important; border: 1px solid #2d3238 !important; color: white !important; }
        .form-control:focus, .form-select:focus { border-color: #28a745 !important; box-shadow: 0 0 8px rgba(40,167,69,0.2) !important; }
        .card-custom label { color: #e2e8f0 !important; font-weight: 600 !important; }
        
        .table-custom-fix th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; font-weight: 600; }
        .table-custom-fix td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
    </style>
</head>
<body>

    <!-- 🌾 Fixed Top Premium Admin Navbar Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper"><i class="fa-solid fa-boxes-stacked"></i></div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Stock</span> Portal</div>
                    <div class="logo-sub-text">Inventory Command Center</div>
                </div>
            </a>   
            <a href="admin_dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container mb-5">
        <?php if(!empty($message)) echo $message; ?>

        <div class="row g-4">
            <!-- Left Side Operations Interface Panel -->
            <div class="col-lg-5">
                <div class="card card-custom p-4 mb-4">
                    
                    <ul class="nav nav-tabs mb-3" id="inventoryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add-pane" type="button" role="tab"><i class="fa-solid fa-plus me-1"></i> Ingest New Stock</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="update-tab" data-bs-toggle="tab" data-bs-target="#update-pane" type="button" role="tab"><i class="fa-solid fa-badge-percent me-1"></i> Dynamic Matrix Sync</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="inventoryTabsContent">
                        
                        <!-- 📥 Tab 1: Product Ingestion Form Interface -->
                        <div class="tab-pane fade show active" id="add-pane" role="tabpanel" aria-labelledby="add-tab">
                            <form action="inventory.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Material / Brand Label Identifier</label>
                                    <input type="text" name="product_name" class="form-control form-control-sm text-white fw-bold" placeholder="e.g. Urea, Coragen, NPK" required>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Manufacturer / Company</label>
                                        <input type="text" name="company_name" class="form-control form-control-sm text-white" placeholder="e.g. IFFCO, Bayer" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Technical Formulation</label>
                                        <input type="text" name="technical_name" class="form-control form-control-sm text-info font-monospace" placeholder="e.g. Nitrogen 46%">
                                    </div>
                                </div>

                                <!-- 🎯 Category & Pesticide Classification Selection -->
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Primary Category</label>
                                        <select name="category" id="category_select" class="form-select form-select-sm fw-bold text-success" required onchange="updateSubCategories()">
                                            <option value="Pesticides" selected>Pesticides (દવાઓ)</option>
                                            <option value="Fertilizers">Fertilizers (ખાતર)</option>
                                            <option value="Seeds">Seeds (બિયારણ)</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Pesticide Type</label>
                                        <select name="sub_category" id="sub_category_select" class="form-select form-select-sm fw-bold text-white" required>
                                            <option value="Insecticide">Insecticide (કીટનાશક)</option>
                                            <option value="Fungicide">Fungicide (ફૂગનાશક)</option>
                                            <option value="Herbicide">Herbicide (નીંદણનાશક)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 📦 Package Size Input Field -->
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-warning">Package Size / Unit (પેકેજ સાઈઝ)</label>
                                    <input type="text" name="package_size" class="form-control form-control-sm text-white fw-bold" placeholder="e.g. 500ml / 1 KG / 50 KG Bag" required>
                                </div>

                                <!-- 🎯 Formulation & Liquid Sub-Type Selection -->
                                <div class="row p-2 g-2 mb-2" id="formulation_box">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold text-info" style="font-size: 11px;">🧪 Formulation (સ્વરૂપ)</label>
                                        <select name="formulation" id="formulation_select" class="form-select form-select-sm fw-bold text-info" onchange="checkLiquidSubCategory()">
                                            <option value="Liquid" selected>Liquid (પ્રવાહી)</option>
                                            <option value="Powder">Powder (પાઉડર)</option>
                                            <option value="Granules">Granules (દાણાદાર)</option>
                                        </select>
                                    </div>
                                    <div class="col-6" id="liquid_type_box">
                                        <label class="form-label small fw-bold text-warning" style="font-size: 11px;">💧 Liquid Type (પ્રવાહી પ્રકાર)</label>
                                        <select name="liquid_type" id="liquid_type_select" class="form-select form-select-sm fw-bold text-warning">
                                            <option value="Systemic">Systemic (સિસ્ટમિક)</option>
                                            <option value="Contact">Contact (કોન્ટેક્ટ)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">Base Price (₹)</label>
                                        <input type="number" step="0.01" name="price" class="form-control form-control-sm text-success fw-bold font-monospace text-center" placeholder="0.00" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">Discount (%)</label>
                                        <input type="number" name="discount_pct" class="form-control form-control-sm text-danger fw-bold font-monospace text-center" min="0" max="100" value="0">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">Initial Stock</label>
                                        <input type="number" name="stock_qty" class="form-control form-control-sm text-white fw-bold font-monospace text-center" placeholder="Units" required>
                                    </div>
                                </div>

                                <div class="p-3 bg-dark rounded mb-3 border-start border-success border-3 shadow-inner">
                                    <h6 class="fw-bold text-success small mb-2"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> Scientific & Agronomy Target Ingestion</h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold" style="font-size: 11px;">🎯 Recommended Target Crops</label>
                                        <input type="text" name="target_crop" class="form-control form-control-sm text-white" placeholder="e.g. Cotton, Groundnut">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold" style="font-size: 11px;">🐛 Target Pests / Pathogens</label>
                                            <input type="text" name="target_pest" class="form-control form-control-sm text-white" placeholder="e.g. Pink Bollworm">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold" style="font-size: 11px;">⚖️ Prescribed Dosage Metrics</label>
                                            <input type="text" name="dosage_per_ha" class="form-control form-control-sm text-warning font-monospace" placeholder="e.g. 150 ml / Ha">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Product Image Blueprint File</label>
                                    <input type="file" name="product_image" class="form-control form-control-sm" accept="image/*">
                                </div>

                                <button type="submit" name="add_product_btn" class="btn btn-success btn-sm w-100 fw-bold shadow-sm py-2 rounded-pill"><i class="fa-solid fa-circle-check"></i> Commit Records to Warehouse</button>
                            </form>
                        </div>

                        <!-- ⚡ Tab 2: Live Stock / Price / Offer Matrix Synchronizer -->
                        <div class="tab-pane fade" id="update-pane" role="tabpanel" aria-labelledby="update-tab">
                            <form action="inventory.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-success">Select Target Inventory Profile:</label>
                                    <select name="up_product_id" id="up_product_id" class="form-select form-select-sm fw-bold border-success" searchable="true" required onchange="populateUpdateFields()">
                                        <option value="">-- Choose Stock Record Profile --</option>
                                        <?php foreach ($all_products as $p): ?>
                                            <option value="<?php echo $p['product_id']; ?>" data-stock="<?php echo $p['stock_qty']; ?>" data-price="<?php echo $p['price']; ?>" data-discount="<?php echo $p['discount_pct']; ?>">
                                                <?php echo htmlspecialchars($p['product_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Live Synchronized Stock Quantity</label>
                                    <input type="number" name="up_stock" id="up_stock" class="form-control form-control-sm fw-bold text-center text-white font-monospace fs-5" min="0" required>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Base Price (₹)</label>
                                        <input type="number" step="0.01" name="up_price" id="up_price" class="form-control form-control-sm fw-bold text-center text-success font-monospace fs-5" min="0" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Offer Discount (%)</label>
                                        <input type="number" name="up_discount" id="up_discount" class="form-control form-control-sm fw-bold text-center text-danger font-monospace fs-5" min="0" max="100" required>
                                    </div>
                                </div>

                                <button type="submit" name="update_stock_btn" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm mt-2 py-2 rounded-pill"><i class="fa-solid fa-arrows-rotate"></i> Sync Live Changes Instantly</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <!-- 📊 Right Side: Real-time WareHouse Inventory Logs Register Table (📦 Package Size Column Added) -->
            <div class="col-lg-7">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold text-success mb-3 border-bottom border-secondary pb-2"><i class="fa-solid fa-list me-1"></i> Live Warehouse Inventory Status Register</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-bordered text-center align-middle table-custom-fix mb-0" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>Product Details</th>
                                    <th>Category</th>
                                    <th>Package</th>
                                    <th>Base Rate</th>
                                    <th>Market Offer Price</th>
                                    <th>Stock Level</th>
                                    <th>Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($all_products)): ?>
                                    <tr><td colspan="7" class="text-muted p-4">🎉 Warehouse Registry Empty! Log fresh profiles.</td></tr>
                                <?php else: foreach($all_products as $row): ?>
                                    <tr>
                                        <td class="text-start">
                                            <strong class="text-white"><?php echo htmlspecialchars($row['product_name']); ?></strong><br>
                                            <small class="text-muted-custom small font-monospace"><?php echo htmlspecialchars($row['company_name']); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark border border-secondary px-2 py-1 fw-bold text-white"><?php echo $row['category']; ?></span>
                                            <?php if(!empty($row['sub_category'])): ?>
                                                <br><span class="text-success small" style="font-size: 11px;"><?php echo htmlspecialchars($row['sub_category']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark text-info border border-secondary"><?php echo htmlspecialchars($row['package_size'] ?? 'Standard'); ?></span>
                                        </td>
                                        <td class="text-muted text-decoration-line-through font-monospace">₹<?php echo number_format($row['price'], 2); ?></td>
                                        <td class="fw-bold text-success font-monospace fs-6">
                                            <?php 
                                                $discount_money = ($row['price'] * $row['discount_pct']) / 100;
                                                $final_price = $row['price'] - $discount_money;
                                                echo "₹" . number_format($final_price, 2);
                                                
                                                if($row['discount_pct'] > 0) {
                                                    echo "<br><span class='badge bg-danger small shadow-sm' style='font-size:10px;'>-".$row['discount_pct']."% OFF</span>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $stock = $row['stock_qty'];
                                                $badge = $stock < 10 ? 'bg-danger text-white' : 'bg-success text-dark';
                                                echo "<span class='badge $badge px-3 py-1.5 fw-bold shadow-sm font-monospace'>$stock</span>";
                                            ?>
                                        </td>
                                        <td>
                                            <a href="inventory.php?action=delete&id=<?php echo $row['product_id']; ?>" 
                                               class="btn btn-sm btn-outline-danger py-1 px-3 fw-bold rounded-pill" style="font-size: 11px;"
                                               onclick="return confirmDelete('<?php echo htmlspecialchars($row['product_name']); ?>');">
                                                <i class="fa-solid fa-trash me-1"></i> Drop
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="text-center py-3 fixed-bottom-footer">
        &copy; 2026 Agro Input Hub | Control Panel Center | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja </b>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(pName) {
            return confirm("Are you absolutely certain you want to remove '" + pName + "' from the live marketplace repository?");
        }

        // 🎯 Dynamic Sub-Categories Updater based on Primary Category selection
        function updateSubCategories() {
            const category = document.getElementById('category_select').value;
            const subCategorySelect = document.getElementById('sub_category_select');
            const formulationBox = document.getElementById('formulation_box');
            
            subCategorySelect.innerHTML = "";
            let options = [];
            
            if (category === 'Pesticides') {
                formulationBox.style.display = 'flex';
                options = [
                    {text: "Insecticide (કીટનાશક)", value: "Insecticide"},
                    {text: "Fungicide (ફૂગનાશક)", value: "Fungicide"},
                    {text: "Herbicide (નીંદણનાશક)", value: "Herbicide"}
                ];
            } else if (category === 'Fertilizers') {
                formulationBox.style.display = 'none';
                options = [
                    {text: "Granular (દાણાદાર)", value: "Granular"},
                    {text: "Water Soluble (પાણીમાં દ્રાવ્ય)", value: "Water Soluble"}
                ];
            } else {
                formulationBox.style.display = 'none';
                options = [
                    {text: "Certified Seeds (પ્રમાણિત બિયારણ)", value: "Certified Seeds"},
                    {text: "Hybrid Seeds (હાઈબ્રિડ બિયારણ)", value: "Hybrid Seeds"}
                ];
            }
            
            options.forEach(opt => {
                let el = document.createElement('option');
                el.textContent = opt.text;
                el.value = opt.value;
                subCategorySelect.appendChild(el);
            });

            checkLiquidSubCategory();
        }

        // 🎯 Check if Liquid formulation is selected to show Systemic/Contact option
        function checkLiquidSubCategory() {
            const formulation = document.getElementById('formulation_select').value;
            const liquidTypeBox = document.getElementById('liquid_type_box');
            
            if (formulation === 'Liquid') {
                liquidTypeBox.style.display = 'block';
            } else {
                liquidTypeBox.style.display = 'none';
            }
        }

        // ⚡ Automated Real-time State Population Observer
        function populateUpdateFields() {
            const select = document.getElementById('up_product_id');
            const selectedOption = select.options[select.selectedIndex];
            
            if(selectedOption.value !== "") {
                const currentStock = selectedOption.getAttribute('data-stock');
                const currentPrice = selectedOption.getAttribute('data-price');
                const currentDiscount = selectedOption.getAttribute('data-discount');
                
                document.getElementById('up_stock').value = currentStock;
                document.getElementById('up_price').value = currentPrice;
                document.getElementById('up_discount').value = currentDiscount;
            } else {
                document.getElementById('up_stock').value = "";
                document.getElementById('up_price').value = "";
                document.getElementById('up_discount').value = "";
            }
        }
    </script>
</body>
</html>