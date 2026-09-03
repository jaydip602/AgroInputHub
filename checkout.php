<?php
// farmerside/checkout.php - Order Placement with Fixed Ledger Injection for Cart
require_once __DIR__ . '/../config/db_config.php';
session_start();

if(!isset($_SESSION['farmer_id'])) { 
    header("Location: farmer_login.php"); 
    exit; 
}

$farmer_id = $_SESSION['farmer_id'];
$message = "";

if (isset($_POST['place_order_btn'])) {
    $product_details = $_POST['product_details']; 
    $grand_total     = floatval($_POST['grand_total']);
    $payment_mode    = $_POST['payment_mode']; 
    $order_status    = "Pending"; 

    try {
        $conn->beginTransaction(); // સુરક્ષા માટે ટ્રાન્ઝેક્શન ચાલુ કર્યું

        // orders ટેબલમાં ડેટા ઇન્સર્ટ કરવો
        $query = "INSERT INTO orders (farmer_id, product_name, total_amount, payment_mode, order_status, order_date) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        
        if ($stmt->execute([$farmer_id, $product_details, $grand_total, $payment_mode, $order_status])) {
            
            // 🎯 ફિક્સ લોચો: જો 'Baki (Credit)' ઓપ્શન લીધો હોય, તો કિસાન લેજરમાં અને farmers ટેબલમાં ઓટોમેટીક એન્ટ્રી પાડો
            if($payment_mode == 'Baki (Credit)') {
                // farmers ટેબલ બેલેન્સ અપડેટ
                $up_farmer = $conn->prepare("UPDATE farmers SET total_baki = total_baki + ? WHERE farmer_id = ?");
                $up_farmer->execute([$grand_total, $farmer_id]);

                // 🎯 લેજર ટેબલમાં ડાયરેક્ટ ડ્યુ અમાઉન્ટ ઇન્સર્ટ ક્વેરી
                $ins_ledger = $conn->prepare("INSERT INTO ledger (farmer_id, bill_id, due_amount, paid_amount, entry_date) VALUES (?, 0, ?, 0.00, NOW())");
                $ins_ledger->execute([$farmer_id, $grand_total]);
            }

            $conn->commit(); // ટ્રાન્ઝેક્શન સબમિટ

            $message = "<div class='alert alert-success fw-bold text-center shadow-sm'>
                            🎉 ઓર્ડર સફળતાપૂર્વક નોંધાઈ ગયો છે! <br>
                            પેમેન્ટ પદ્ધતિ: <span class='badge bg-dark'>$payment_mode</span>
                        </div>";
            
            unset($_SESSION['cart']);
            header("refresh:2;url=order_history.php");
        }
    } catch (Exception $e) {
        $conn->rollBack(); // લોચો થાય તો રોલબેક
        $message = "<div class='alert alert-danger'>❌ ઓર્ડર ફેઈલ: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container my-5" style="max-width: 600px;">
        <?php echo $message; ?>
        
        <div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
            <h5 class="fw-bold text-success mb-3 border-bottom pb-2">
                <i class="fa-solid fa-cart-check"></i> ઓર્ડર બિલિંગ અને પેમેન્ટ વિગત
            </h5>

            <form action="checkout.php" method="POST">
                <!-- ટેસ્ટિંગ માટે ડાયનેમિક ડેટા મોકલવા અહીં સેટિંગ છે -->
                <input type="hidden" name="product_details" value="યુરિયા ખાતર [જથ્થો: 2]">
                <input type="hidden" name="grand_total" value="700.00">

                <div class="p-3 bg-light rounded mb-4">
                    <div class="d-flex justify-content-between fw-bold text-secondary">
                        <span>કુલ રકમ ચૂકવવાપાત્ર:</span>
                        <span class="text-success fs-5">₹700.00</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark"><i class="fa-solid fa-wallet text-muted me-1"></i> પેમેન્ટનો પ્રકાર પસંદ કરો:</label>
                    <div class="row g-2">
                        
                        <div class="col-4">
                            <input type="radio" class="btn-check" name="payment_mode" id="pay_cod" value="COD" checked>
                            <label class="btn btn-outline-success w-100 p-3 fw-bold" for="pay_cod">
                                <i class="fa-solid fa-hand-holding-dollar d-block fs-4 mb-1"></i> COD
                            </label>
                        </div>

                        <div class="col-4">
                            <input type="radio" class="btn-check" name="payment_mode" id="pay_baki" value="Baki (Credit)">
                            <label class="btn btn-outline-danger w-100 p-3 fw-bold" for="pay_baki">
                                <i class="fa-solid fa-book d-block fs-4 mb-1"></i> બાકી રાખવું
                            </label>
                        </div>

                        <div class="col-4">
                            <input type="radio" class="btn-check" name="payment_mode" id="pay_upi" value="UPI Online">
                            <label class="btn btn-outline-primary w-100 p-3 fw-bold" for="pay_upi">
                                <i class="fa-solid fa-qrcode d-block fs-4 mb-1"></i> UPI / ઓનલાઇન
                            </label>
                        </div>

                    </div>
                </div>

                <button type="submit" name="place_order_btn" class="btn btn-success btn-lg w-100 fw-bold shadow">
                    <i class="fa-solid fa-circle-check"></i> ઓર્ડર કન્ફર્મ કરો
                </button>
            </form>
        </div>
    </div>

</body>
</html>