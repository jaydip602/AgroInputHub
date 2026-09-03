<?php
// farmerside/payment_step.php - Payment Selection with PHPMailer OTP Integration
require_once __DIR__ . '/../config/db_config.php';
session_start();

// 🌟 PHPMailer ના namespaces અહીં ફાઇલની શરુઆતમાં જ ડિક્લેર કરવા
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: shop_shop.php");
    exit;
}

$farmer_id = $_SESSION['farmer_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_order'])) {
    $payment_mode = $_POST['payment_mode'] ?? 'Cash';
    
    if ($payment_mode == 'UPI Online') {
        header("Location: payment_details.php");
        exit;
    }
    
    if ($payment_mode == 'Card Payment') {
        header("Location: card_payment.php");
        exit;
    }
    
    $grand_total = 0;
    $product_names_arr = [];
    foreach ($_SESSION['cart'] as $item) {
        $grand_total += $item['price'] * $item['qty'];
        $product_names_arr[] = $item['name'] . " (" . $item['qty'] . " Pcs)";
    }
    $all_products_string = implode(", ", $product_names_arr);

    // 🌟 Cash (COD) માટે OTP જનરેટ કરો
    $otp = rand(100000, 999999);
    $_SESSION['order_otp'] = $otp;
    $_SESSION['pending_order_data'] = [
        'farmer_id' => $farmer_id,
        'grand_total' => $grand_total,
        'all_products_string' => $all_products_string,
        'payment_mode' => $payment_mode
    ];

    $stmt_email = $conn->prepare("SELECT email, farmer_name FROM farmers WHERE farmer_id = ?");
    $stmt_email->execute([$farmer_id]);
    $farmer_info = $stmt_email->fetch();
    $to_email = $farmer_info['email'] ?? '';
    $farmer_name = $farmer_info['farmer_name'] ?? 'Farmer';

    if (!empty($to_email)) {
        // PHPMailer ફાઇલો ઇન્ક્લુડ કરો
       // 🌟 સાચા ફોલ્ડર નામ 'PHPMailer-master' સાથેનો પાથ
        require_once __DIR__ . '/../includes/PHPMailer-master/src/Exception.php';
        require_once __DIR__ . '/../includes/PHPMailer-master/src/PHPMailer.php';
        require_once __DIR__ . '/../includes/PHPMailer-master/src/SMTP.php';
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jaydipparmar2042006@gmail.com'; // તમારું જીમેલ આઈડી નાખો
            $mail->Password   = 'kbtr dlwi nrgu xhoy';    // જીમેલ એપ પાસવર્ડ નાખો
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('tmaro_email@gmail.com', 'Agro Input Hub');
            $mail->addAddress($to_email, $farmer_name);

            $mail->isHTML(true);
            $mail->Subject = 'Agro Input Hub - Order Confirmation OTP';
            $mail->Body    = "<h3>Hello <b>$farmer_name</b>,</h3>
                              <p>Your OTP for confirming the order on Agro Input Hub is:</p>
                              <h2 style='color: #28a745;'>$otp</h2>
                              <p>Please enter this OTP to complete your order.</p>";

            $mail->send();
        } catch (Exception $e) {
            // Error handling
        }
    }

    header("Location: verify_order_otp.php");
    exit;
}

$display_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $display_total += $item['price'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Selection | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js"></script>
    
   <style>
        body { background-color: #0d0f11; padding-top: 20px; color: #e2e8f0; }
        .payment-card { background: #191c1f; border-radius: 12px; max-width: 600px; margin: auto; border: 1px solid #2d3238; }
        .step-container { display: flex; justify-content: space-between; position: relative; margin-bottom: 30px; padding: 0 20px; }
        .step-container::before { content: ''; position: absolute; top: 15px; left: 40px; right: 40px; height: 2px; background: #2d3238; z-index: 1; }
        .step { text-align: center; position: relative; z-index: 2; }
        .step-circle { width: 30px; height: 30px; border-radius: 50%; background: #2d3238; color: #a0aec0; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px; font-weight: bold; font-size: 14px; }
        .step.active .step-circle { background: #28a745; color: #fff; border: 2px solid #fff; }
        .step.done .step-circle { background: #0d6efd; color: #fff; }
        .step.active .step-text, .step.done .step-text { color: #fff; font-weight: 600; }
        .step-text { font-size: 12px; color: #a0aec0; }
        .payment-box { border: 1px solid #2d3238; border-radius: 10px; padding: 15px 20px; margin-bottom: 15px; background-color: #0f1112; cursor: pointer; transition: all 0.3s; }
        .payment-box:hover { border-color: #28a745; }
        .payment-box.selected { border-color: #28a745; background-color: rgba(40,167,69,0.08); }
        h6.text-muted { color: #cbd5e1 !important; }
        .payment-box span.text-white { color: #ffffff !important; font-weight: 600; }
        .payment-box span.text-muted { color: #94a3b8 !important; }
        .price-text { font-size: 20px; font-weight: 800; color: #28a745; }
    </style>
</head>
<body>

    <div class="container">
        <div class="payment-card p-4 shadow-lg mb-5">
            
            <div class="d-flex align-items-center mb-4">
                <a href="address.php" class="text-white text-decoration-none fs-4 me-3"><i class="fa-solid fa-arrow-left"></i></a>
                <h4 class="mb-0 fw-bold">Payments</h4>
            </div>

            <div class="step-container">
                <div class="step done"><div class="step-circle"><i class="fa-solid fa-check"></i></div><div class="step-text">Address</div></div>
                <div class="step done"><div class="step-circle"><i class="fa-solid fa-check"></i></div><div class="step-text">Summary</div></div>
                <div class="step active"><div class="step-circle">3</div><div class="step-text">Payment</div></div>
            </div>

            <?php if ($message != ""): ?>
                <?php echo $message; ?>
            <?php else: ?>
                
                <div class="d-flex justify-content-between align-items-center bg-black p-3 rounded mb-4 border border-secondary">
                    <span class="text-muted fw-bold">Total Amount Payable:</span>
                    <span class="price-text">₹<?php echo number_format($display_total, 2); ?></span>
                </div>

                <form action="payment_step.php" method="POST">
                    <h6 class="text-muted mb-3 fw-bold">Select Payment Method</h6>
                    
                    <label class="d-block w-100 mb-3">
                        <div class="payment-box d-flex align-items-center gap-3">
                            <input class="form-check-input fs-5 m-0" type="radio" name="payment_mode" value="UPI Online" required>
                            <div class="w-100">
                                <span class="fw-bold fs-6 d-block text-white"><i class="fa-solid fa-qrcode text-primary me-2"></i> UPI (QR / GPay / PhonePe)</span>
                                <span class="text-muted small">Pay instantly via QR or UPI ID</span>
                            </div>
                        </div>
                    </label>

                    <label class="d-block w-100 mb-3">
                        <div class="payment-box d-flex align-items-center gap-3">
                            <input class="form-check-input fs-5 m-0" type="radio" name="payment_mode" value="Card Payment" required>
                            <div class="w-100">
                                <span class="fw-bold fs-6 d-block text-white"><i class="fa-solid fa-credit-card text-info me-2"></i> Debit Card / Credit Card</span>
                                <span class="text-muted small">Pay securely using your Visa, MasterCard or RuPay card</span>
                            </div>
                        </div>
                    </label>

                    <label class="d-block w-100 mb-4">
                        <div class="payment-box d-flex align-items-center gap-3">
                            <input class="form-check-input fs-5 m-0" type="radio" name="payment_mode" value="Cash" checked required>
                            <div class="w-100">
                                <span class="fw-bold fs-6 d-block text-white"><i class="fa-solid fa-money-bill-wave text-success me-2"></i> Cash on Delivery (COD)</span>
                                <span class="text-muted small">Pay cash when the order arrives</span>
                            </div>
                        </div>
                    </label>

                    <button type="submit" name="confirm_order" class="btn w-100 fw-bold py-3 fs-5" style="background-color: #ff6161; color: white; border-radius: 8px;">
                        Proceed to OTP Verification • ₹<?php echo number_format($display_total, 2); ?>
                    </button>
                </form>

            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('input[name="payment_mode"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-box').forEach(box => box.classList.remove('selected'));
                if (this.checked) {
                    this.closest('.payment-box').classList.add('selected');
                }
            });
        });
        document.querySelector('input[name="payment_mode"]:checked').closest('.payment-box').classList.add('selected');
    </script>
</body>
</html>