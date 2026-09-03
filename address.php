<?php
// farmerside/address.php
require_once __DIR__ . '/../config/db_config.php';
session_start();

if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}

$farmer_id = $_SESSION['farmer_id'];
$message = "";

// 🎯 ઓટોમેટિક બેક લિંક નક્કી કરવા માટેનું લોજીક
$back_url = "cart.php"; // ડિફોલ્ટ કાર્ટ રાખીએ

// જો યુઝર પ્રોડક્ટ ડીટેલ પેજ પરથી આવ્યો હોય તો સેશનમાં સેવ કરો
if (isset($_POST['product_id']) || isset($_GET['product_id'])) {
    $_SESSION['last_product_id'] = $_POST['product_id'] ?? $_GET['product_id'];
}

// જો સેશનમાં પ્રોડક્ટ આઈડી હોય, તો બેક બટન પ્રોડક્ટ ડીટેલ પર જ જવું જોઈએ
if (isset($_SESSION['last_product_id']) && !empty($_SESSION['last_product_id'])) {
    $back_url = "product_details.php?id=" . $_SESSION['last_product_id'];
} else {
    // જો કાર્ટ ખાલી હોય અથવા કાર્ટમાંથી આવ્યા હોય તો HTTP Referer ચેક કરો
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'cart.php') !== false) {
        $back_url = "cart.php";
    }
}

// Form સબમિટ થાય ત્યારે ડેટા સેવ કરો
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_address'])) {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);
    $house_no = trim($_POST['house_no']);
    $road_name = trim($_POST['road_name']);
    $address_type = trim($_POST['address_type']);

    try {
        $stmt_update = $conn->prepare("UPDATE farmers SET farmer_name = ?, phone_number = ?, state = ?, city = ?, house_no = ?, road_name = ?, address_type = ? WHERE farmer_id = ?");
        $stmt_update->execute([$full_name, $phone, $state, $city, $house_no, $road_name, $address_type, $farmer_id]);
        
        header("Location: payment_step.php");
        exit;
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
    }
}

try {
    $stmt_fetch = $conn->prepare("SELECT farmer_name, phone_number, state, city, house_no, road_name, address_type FROM farmers WHERE farmer_id = ?");
    $stmt_fetch->execute([$farmer_id]);
    $farmer = $stmt_fetch->fetch();
} catch (Exception $e) {
    die("Data Fetch Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Delivery Address | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js"></script>
    
   <style>
        body { background-color: #0d0f11; padding-top: 20px; padding-bottom: 90px; color: #e2e8f0; }
        .address-card { background: #191c1f; border-radius: 12px; max-width: 600px; margin: auto; border: 1px solid #2d3238; }
        .step-container { display: flex; justify-content: space-between; position: relative; margin-bottom: 30px; padding: 0 20px; }
        .step-container::before { content: ''; position: absolute; top: 15px; left: 40px; right: 40px; height: 2px; background: #2d3238; z-index: 1; }
        .step { text-align: center; position: relative; z-index: 2; }
        .step-circle { width: 30px; height: 30px; border-radius: 50%; background: #2d3238; color: #a0aec0; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px; font-weight: bold; font-size: 14px; }
        .step.active .step-circle { background: #0d6efd; color: #fff; }
        .step.active .step-text { color: #0d6efd; font-weight: 600; }
        .step-text { font-size: 12px; color: #a0aec0; }
        .form-floating > .form-control { background-color: #0f1112; border: 1px solid #2d3238; color: #fff; }
        .form-floating > label { color: #a0aec0; }
        .form-control:focus { border-color: #28a745; box-shadow: none; }
        .btn-check:checked + .btn-outline-secondary { background-color: #28a745; color: white; border-color: #28a745; }
        .btn-outline-secondary { border-color: #2d3238; color: #a0aec0; }
    </style>
</head>
<body>

    <div class="container">
        <div class="address-card p-4 shadow-lg">
            
            <div class="d-flex align-items-center mb-4">
                <a href="<?php echo $back_url; ?>" class="text-white text-decoration-none fs-4 me-3"><i class="fa-solid fa-arrow-left"></i></a>
                <h4 class="mb-0 fw-bold">Add delivery address</h4>
            </div>

            <div class="step-container">
                <div class="step active"><div class="step-circle">1</div><div class="step-text">Address</div></div>
                <div class="step"><div class="step-circle">2</div><div class="step-text">Summary</div></div>
                <div class="step"><div class="step-circle">3</div><div class="step-text">Payment</div></div>
            </div>

            <?php echo $message; ?>

            <form action="address.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" name="full_name" class="form-control" id="fullName" placeholder="Full Name" value="<?php echo htmlspecialchars($farmer['farmer_name'] ?? ''); ?>" required>
                    <label for="fullName">Full Name (Required) *</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number" value="<?php echo htmlspecialchars($farmer['phone_number'] ?? ''); ?>" required>
                    <label for="phone">Phone number (Required) *</label>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="form-floating">
                            <input type="text" name="state" class="form-control" id="state" placeholder="State" value="<?php echo htmlspecialchars($farmer['state'] ?? 'Gujarat'); ?>" required>
                            <label for="state">State *</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating">
                            <input type="text" name="city" class="form-control" id="city" placeholder="City" value="<?php echo htmlspecialchars($farmer['city'] ?? ''); ?>" required>
                            <label for="city">City *</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="house_no" class="form-control" id="houseNo" placeholder="House No." value="<?php echo htmlspecialchars($farmer['house_no'] ?? ''); ?>" required>
                    <label for="houseNo">House No., Building Name *</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="text" name="road_name" class="form-control" id="roadName" placeholder="Road name" value="<?php echo htmlspecialchars($farmer['road_name'] ?? ''); ?>" required>
                    <label for="roadName">Road name, Area, Colony *</label>
                </div>
                <div class="mb-4">
                    <p class="text-muted small mb-2">Type of address</p>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="address_type" id="home" value="Home" <?php echo (($farmer['address_type'] ?? 'Home') == 'Home') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary rounded-pill px-4" for="home"><i class="fa-solid fa-house me-1"></i> Home</label>
                        <input type="radio" class="btn-check" name="address_type" id="work" value="Work" <?php echo (($farmer['address_type'] ?? '') == 'Work') ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary rounded-pill px-4" for="work"><i class="fa-solid fa-building me-1"></i> Work</label>
                    </div>
                </div>
                <button type="submit" name="save_address" class="btn w-100 fw-bold py-3 fs-5" style="background-color: #ff6161; color: white; border-radius: 8px;">
                    Save Address & Continue
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>