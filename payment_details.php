<?php
require_once __DIR__ . '/../config/db_config.php';
session_start();

// PHPMailer namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}

// જો કાર્ટ ખાલી હોય તો પાછા માર્કેટમાં મોકલો
if (empty($_SESSION['cart'])) {
    header("Location: shop_shop.php");
    exit;
}

$farmer_id = $_SESSION['farmer_id'];

// 1. કાર્ટમાંથી ટોટલ અમાઉન્ટ ગણો[cite: 6]
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['qty'];
}

// 2. તમારું UPI ID અને નામ સેટ કરો[cite: 6]
$merchant_upi_id = "7862914314@axl"; 
$merchant_name = "Agro Input Hub";

// 3. ઓટોમેટિક રકમ વાળો ડાયનેમિક UPI QR કોડ બનાવો[cite: 6]
$upi_link = "upi://pay?pa=" . $merchant_upi_id . "&pn=" . urlencode($merchant_name) . "&am=" . $total_amount . "&cu=INR";
$qr_image_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($upi_link);

// 4. જ્યારે ખેડૂત "Verify & Pay Now" દબાવે ત્યારે ડાયરેક્ટ ઓર્ડર સેવ કરવાના બદલે OTP મોકલો
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_payment'])) {
    $entered_upi = trim($_POST['upi_id'] ?? '');

    $product_names_arr = [];
    foreach ($_SESSION['cart'] as $item) {
        $product_names_arr[] = $item['name'] . " (" . $item['qty'] . " Pcs)";
    }
    $all_products_string = implode(", ", $product_names_arr);

    // 🌟 UPI માટે OTP જનરેટ કરો અને સેશનમાં સેવ કરો
    $otp = rand(100000, 999999);
    $_SESSION['order_otp'] = $otp;
    $_SESSION['pending_order_data'] = [
        'farmer_id' => $farmer_id,
        'grand_total' => $total_amount,
        'all_products_string' => $all_products_string,
        'payment_mode' => 'UPI Online'
    ];

    // ખેડૂતનો ઈમેલ મેળવો
    $stmt_email = $conn->prepare("SELECT email, farmer_name FROM farmers WHERE farmer_id = ?");
    $stmt_email->execute([$farmer_id]);
    $farmer_info = $stmt_email->fetch();
    $to_email = $farmer_info['email'] ?? '';
    $farmer_name = $farmer_info['farmer_name'] ?? 'Farmer';

    if (!empty($to_email)) {
        require_once __DIR__ . '/../includes/PHPMailer-master/src/Exception.php';
        require_once __DIR__ . '/../includes/PHPMailer-master/src/PHPMailer.php';
        require_once __DIR__ . '/../includes/PHPMailer-master/src/SMTP.php';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jaydipparmar2042006@gmail.com'; // તમારું જીમેલ આઈડી નાખો
            $mail->Password   = 'kbtr dlwi nrgu xhoy';    // તમારું એપ પાસવર્ડ નાખો
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('tmaro_email@gmail.com', 'Agro Input Hub');
            $mail->addAddress($to_email, $farmer_name);

            $mail->isHTML(true);
            $mail->Subject = 'Agro Input Hub - UPI Order Confirmation OTP';
            $mail->Body    = "<h3>Hello <b>$farmer_name</b>,</h3>
                              <p>Your OTP for confirming the UPI order is:</p>
                              <h2 style='color: #28a745;'>$otp</h2>
                              <p>Please enter this OTP to complete your order.</p>";

            $mail->send();
        } catch (Exception $e) {}
    }

    // OTP વેરિફિકેશન પેજ પર રીડાયરેક્ટ કરો
    header("Location: verify_order_otp.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure UPI Payment | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { 
            background-color: #0d0f11; 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        
        .login-box {
            background: #191c1f;
            border: 1px solid #2d3238;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 450px;
            padding: 30px;
        }

        .login-box .title-text { color: #e2e8f0; font-weight: 500; font-size: 14px; }
        .login-box label { color: #cbd5e1 !important; font-weight: 600 !important; }
        
        .login-box .form-control {
            background-color: #0f1112 !important;
            border: 1px solid #2d3238 !important;
            color: #ffffff !important;
            padding: 12px;
        }

        .login-box .form-control::placeholder { color: #9ca3af !important; opacity: 1 !important; }
        .login-box .form-control:focus { border-color: #28a745 !important; box-shadow: 0 0 0 0.25rem rgba(40,167,69,0.25) !important; }

        .qr-box {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 12px;
            display: inline-block;
            border: 2px dashed #28a745;
            margin-bottom: 15px;
        }

        .total-amt {
            font-size: 24px;
            font-weight: 800;
            color: #28a745;
            margin-bottom: 20px;
        }

        .back-link { color: #cbd5e1 !important; text-decoration: none; font-weight: 500; }
        .back-link:hover { color: #28a745 !important; }

        .payment-box-label { 
            cursor: pointer; 
            border: 1px solid #2d3238; 
            border-radius: 10px; 
            padding: 12px 10px; 
            display: block; 
            font-weight: 600; 
            background-color: #0f1112; 
            text-align: center; 
            color: #cbd5e1; 
            transition: all 0.2s; 
        }
        .payment-box-label:hover { border-color: #28a745; background-color: rgba(40,167,69,0.05); }
        .btn-check:checked + .payment-box-label { 
            border-color: #28a745; 
            background-color: rgba(40,167,69,0.15); 
            color: #28a745; 
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2); 
        }
    </style>
</head>
<body>

    <div class="login-box text-center">
        <h3 class="fw-bold text-success mb-2"><i class="fa-solid fa-shield-halved me-2"></i>Secure Payment</h3>
        <p class="title-text mb-3">Pay securely via UPI</p>

        <div class="total-amt">
            Total to Pay: ₹<?php echo number_format($total_amount, 2); ?>[cite: 6]
        </div>

        <form action="payment_details.php" method="POST">
            
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <input type="radio" class="btn-check" name="upi_method" id="method_qr" value="qr" checked>
                    <label class="payment-box-label shadow-sm" for="method_qr">
                        <i class="fa-solid fa-qrcode d-block fs-4 mb-1"></i> QR Code
                    </label>
                </div>
                <div class="col-6">
                    <input type="radio" class="btn-check" name="upi_method" id="method_id" value="id">
                    <label class="payment-box-label shadow-sm" for="method_id">
                        <i class="fa-brands fa-google-pay d-block fs-4 mb-1"></i> UPI ID
                    </label>
                </div>
            </div>

            <div id="qr_section">
                <div class="qr-box">
                    <img src="<?php echo $qr_image_url; ?>" alt="UPI QR" class="img-fluid" style="width: 170px; height: 170px;">[cite: 6]
                </div>
                <p class="small text-muted mb-4">Scan QR code using Google Pay, PhonePe, or Paytm</p>
            </div>

            <div id="id_section" class="text-start d-none mb-4">
                <label class="form-label small">Enter your UPI ID / Number:</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-success"><i class="fa-brands fa-google-pay fs-4"></i></span>
                    <input type="text" name="upi_id" id="upi_input" class="form-control" placeholder="e.g. 9876543210@ybl">
                </div>
                <small class="text-muted d-block mt-2" style="font-size: 11px;">You will receive a payment request on your UPI app.</small>
            </div>

            <button type="submit" name="verify_payment" class="btn btn-success w-100 fw-bold py-3 rounded-pill shadow-sm fs-5">
                Proceed to OTP Verification
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="payment_step.php" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> Change Payment Method</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const methodQr = document.getElementById('method_qr');
        const methodId = document.getElementById('method_id');
        const qrSection = document.getElementById('qr_section');
        const idSection = document.getElementById('id_section');
        const upiInput = document.getElementById('upi_input');

        methodQr.addEventListener('change', function() {
            if (this.checked) {
                qrSection.classList.remove('d-none');
                idSection.classList.add('d-none');
                upiInput.removeAttribute('required');
            }
        });

        methodId.addEventListener('change', function() {
            if (this.checked) {
                idSection.classList.remove('d-none');
                qrSection.classList.add('d-none');
                upiInput.setAttribute('required', 'required');
            }
        });
    </script>
</body>
</html>