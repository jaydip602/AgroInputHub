<?php
// farmerside/card_payment.php - Debit & Credit Card Payment Page with OTP Verification
require_once __DIR__ . '/../config/db_config.php';
session_start();

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

// જ્યારે કાર્ડની વિગતો સબમિટ થાય ત્યારે OTP જનરેટ કરીને ઈમેલ મોકલો અને OTP વેરિફિકેશન પેજ પર રીડાયરેક્ટ કરો
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_now'])) {
    $card_type = $_POST['card_type'] ?? 'Debit Card';
    $card_number = $_POST['card_number'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    $grand_total = 0;
    $product_names_arr = [];
    foreach ($_SESSION['cart'] as $item) {
        $grand_total += $item['price'] * $item['qty'];
        $product_names_arr[] = $item['name'] . " (" . $item['qty'] . " Pcs)";
    }
    $all_products_string = implode(", ", $product_names_arr);

    // ૬ અંકનો રેન્ડમ OTP જનરેટ કરો
    $otp = rand(100000, 999999);

    // સેશનમાં OTP અને ઓર્ડરની માહિતી સેવ કરો
    $_SESSION['order_otp'] = $otp;
    $_SESSION['pending_order_data'] = [
        'farmer_id' => $farmer_id,
        'grand_total' => $grand_total,
        'all_products_string' => $all_products_string,
        'payment_mode' => $card_type // Debit Card કે Credit Card સેવ થશે
    ];

    // ખેડૂતનો ઈમેલ મેળવો
    $stmt_email = $conn->prepare("SELECT email, farmer_name FROM farmers WHERE farmer_id = ?");
    $stmt_email->execute([$farmer_id]);
    $farmer_info = $stmt_email->fetch();
    $to_email = $farmer_info['email'] ?? '';
    $farmer_name = $farmer_info['farmer_name'] ?? 'Farmer';

    // ઈમેલ મોકલવાની પ્રોસેસ
    if (!empty($to_email)) {
        $subject = "Agro Input Hub - Order Confirmation OTP";
        $message_body = "Hello $farmer_name,\n\nYour OTP for confirming the card payment order on Agro Input Hub is: $otp\n\nPlease enter this OTP to complete your order.";
        $headers = "From: no-reply@agroinputhub.com";
        @mail($to_email, $subject, $message_body, $headers);
    }

    // OTP વેરિફિકેશન પેજ પર રીડાયરેક્ટ કરો
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
    <title>Card Payment | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0d0f11; padding-top: 20px; color: #e2e8f0; }
        .payment-card { background: #191c1f; border-radius: 12px; max-width: 600px; margin: auto; border: 1px solid #2d3238; }
        .card-option-box { border: 1px solid #2d3238; border-radius: 10px; padding: 15px; background-color: #0f1112; cursor: pointer; transition: all 0.3s; }
        .card-option-box.selected { border-color: #28a745; background-color: rgba(40,167,69,0.08); }
        .form-control { background-color: #0f1112; border-color: #2d3238; color: #fff; }
        .form-control:focus { background-color: #0f1112; border-color: #28a745; color: #fff; box-shadow: none; }
        .price-text { font-size: 20px; font-weight: 800; color: #28a745; }
    </style>
</head>
<body>

    <div class="container my-4">
        <div class="payment-card p-4 shadow-lg mb-5">
            
            <div class="d-flex align-items-center mb-4">
                <a href="payment_step.php" class="text-white text-decoration-none fs-4 me-3"><i class="fa-solid fa-arrow-left"></i></a>
                <h4 class="mb-0 fw-bold">Card Payment Portal</h4>
            </div>

            <?php if ($message != ""): ?>
                <?php echo $message; ?>
            <?php else: ?>
                
                <div class="d-flex justify-content-between align-items-center bg-black p-3 rounded mb-4 border border-secondary">
                    <span class="text-muted fw-bold">Amount to Pay:</span>
                    <span class="price-text">₹<?php echo number_format($display_total, 2); ?></span>
                </div>

                <form action="card_payment.php" method="POST">
                    <h6 class="text-white mb-3 fw-bold">Select Card Type</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="d-block w-100">
                                <div class="card-option-box text-center selected" id="box_debit">
                                    <input class="form-check-input d-none" type="radio" name="card_type" value="Debit Card" id="radio_debit" checked>
                                    <i class="fa-solid fa-credit-card fs-3 text-info mb-2"></i>
                                    <span class="fw-bold d-block text-white">Debit Card</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="d-block w-100">
                                <div class="card-option-box text-center" id="box_credit">
                                    <input class="form-check-input d-none" type="radio" name="card_type" value="Credit Card" id="radio_credit">
                                    <i class="fa-solid fa-id-card fs-3 text-warning mb-2"></i>
                                    <span class="fw-bold d-block text-white">Credit Card</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Card Details Form -->
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Card Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-credit-card"></i></span>
                            <input type="text" name="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label small text-muted fw-bold">Expiry Date</label>
                            <input type="text" name="expiry_date" class="form-control" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted fw-bold">CVV Code</label>
                            <input type="password" name="cvv" class="form-control" placeholder="123" maxlength="3" required>
                        </div>
                    </div>

                    <button type="submit" name="pay_now" class="btn w-100 fw-bold py-3 fs-5 text-white" style="background-color: #28a745; border-radius: 8px;">
                        Proceed to OTP Verification • ₹<?php echo number_format($display_total, 2); ?>
                    </button>
                </form>

            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const debitBox = document.getElementById('box_debit');
        const creditBox = document.getElementById('box_credit');
        const debitRadio = document.getElementById('radio_debit');
        const creditRadio = document.getElementById('radio_credit');

        debitBox.addEventListener('click', () => {
            debitRadio.checked = true;
            debitBox.classList.add('selected');
            creditBox.classList.remove('selected');
        });

        creditBox.addEventListener('click', () => {
            creditRadio.checked = true;
            creditBox.classList.add('selected');
            debitBox.classList.remove('selected');
        });
    </script>
</body>
</html>