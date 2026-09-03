<?php
// farmerside/verify_order_otp.php
require_once __DIR__ . '/../config/db_config.php';
session_start();

if (!isset($_SESSION['order_otp']) || !isset($_SESSION['pending_order_data'])) {
    header("Location: shop_shop.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);

    if ($entered_otp == $_SESSION['order_otp']) {
        // OTP સાચો છે, હવે ડેટાબેઝમાં ઓર્ડર ફાઇનલ સેવ કરો
        $data = $_SESSION['pending_order_data'];
        $farmer_id = $data['farmer_id'];
        $grand_total = $data['grand_total'];
        $all_products_string = $data['all_products_string'];
        $payment_mode = $data['payment_mode'];

        try {
            $conn->beginTransaction();

            $stmt_bill = $conn->prepare("INSERT INTO bills (farmer_id, total_amount, discount, payment_mode) VALUES (?, ?, 0.00, 'Cash')");
            $stmt_bill->execute([$farmer_id, $grand_total]);
            $bill_id = $conn->lastInsertId(); 

            $stmt_order = $conn->prepare("INSERT INTO orders (farmer_id, product_name, total_amount, payment_mode, order_status, order_date) VALUES (?, ?, ?, ?, 'Pending', NOW())");
            $stmt_order->execute([$farmer_id, $all_products_string, $grand_total, $payment_mode]);
            $order_id = $conn->lastInsertId();

            $stmt_item = $conn->prepare("INSERT INTO bill_items (bill_id, product_id, price_per_unit, quantity) VALUES (?, ?, ?, ?)");
            $stmt_stock = $conn->prepare("UPDATE products SET stock_qty = stock_qty - ? WHERE product_id = ?");
            
            foreach ($_SESSION['cart'] as $p_id => $item) {
                $stmt_item->execute([$bill_id, $p_id, $item['price'], $item['qty']]);
                $stmt_stock->execute([$item['qty'], $p_id]);
            }

            $conn->commit();
            
            // સેશન ક્લિયર કરો
            unset($_SESSION['cart']);
            unset($_SESSION['order_otp']);
            unset($_SESSION['pending_order_data']);
            
            echo "<script>alert('Order Confirmed Successfully! Order ID: #ORD-0$order_id'); window.location='dashboard.php';</script>";
            exit;

        } catch (Exception $e) {
            $conn->rollBack();
            $message = "❌ Order Failed: " . $e->getMessage();
        }
    } else {
        $message = "❌ Invalid OTP! Please check your email and try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js"></script>
    
  <style>
        body { background-color: #0d0f11; color: #fff; padding-top: 50px; }
        .otp-card { background: #191c1f; border: 1px solid #2d3238; border-radius: 12px; max-width: 450px; margin: auto; padding: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="otp-card shadow-lg">
            <h4 class="fw-bold text-success mb-3 text-center">🔐 Enter Confirmation OTP</h4>
            <p class="text-muted small text-center mb-4">We have sent a 6-digit OTP to your registered email address. Please enter it below to confirm your order.</p>

            <?php if($message != ""): ?>
                <div class="alert alert-danger small"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="verify_order_otp.php" method="POST">
                <div class="mb-3">
                    <input type="text" name="otp" class="form-control text-center fs-3 tracking-widest bg-dark text-white border-secondary" placeholder="------" maxlength="6" required autofocus>
                </div>
                <button type="submit" name="verify_otp" class="btn btn-success w-100 py-2 fw-bold">Verify & Confirm Order</button>
            </form>
        </div>
    </div>
</body>
</html>