<?php
// farmerside/place_direct_order.php - Fixed with Automatic Ledger Integration
require_once __DIR__ . '/../config/db_config.php';
session_start();

if (!isset($_SESSION['farmer_id']) || !isset($_POST['submit_direct_order'])) {
    header("Location: shop_shop.php");
    exit;
}

$farmer_id   = $_SESSION['farmer_id'];
$product_id  = intval($_POST['product_id']);
$p_name      = $_POST['product_name'];
$price       = floatval($_POST['price']);
$qty         = intval($_POST['quantity']);
$payment_mode= $_POST['payment_mode'];

$total_amount = $price * $qty;
$order_string = $p_name . " [જથ્થો: " . $qty . "]";

try {
    $conn->beginTransaction();

    // ૧. લાઈવ સ્ટોક વેરિફિકેશન કરો
    $stmt = $conn->prepare("SELECT stock_qty FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $current_stock = $stmt->fetchColumn();

    if ($current_stock < $qty || $qty <= 0) {
        $_SESSION['error_msg'] = "⚠️ ક્ષમા કરશો! ગોડાઉનમાં પૂરતો સ્ટોક નથી. (હાજર સ્ટોક: $current_stock)";
        header("Location: product_details.php?id=" . $product_id);
        exit;
    }

    // ૨. `orders` ટેબલમાં નવો ઓર્ડર ઇન્સર્ટ કરો
    $ins_order = $conn->prepare("INSERT INTO orders (farmer_id, product_name, total_amount, payment_mode, order_status, order_date) VALUES (?, ?, ?, ?, 'Pending', NOW())");
    $ins_order->execute([$farmer_id, $order_string, $total_amount, $payment_mode]);

    // ૩. પ્રોડક્ટ ટેબલમાંથી સ્ટોક ઘટાડો (Deduct Stock)
    $up_stock = $conn->prepare("UPDATE products SET stock_qty = stock_qty - ? WHERE product_id = ?");
    $up_stock->execute([$qty, $product_id]);

    // 🎯 ૪. ફિક્સ લોચો: જો પેમેન્ટ મોડ "બાકી (Credit)" હોય, તો farmers ટેબલની સાથે ledger ટેબલમાં પણ ફરજિયાત એન્ટ્રી કરો
    if ($payment_mode == 'Baki (Credit)') {
        // farmers ટેબલ અપડેટ
        $up_baki = $conn->prepare("UPDATE farmers SET total_baki = total_baki + ? WHERE farmer_id = ?");
        $up_baki->execute([$total_amount, $farmer_id]);

        // 🎯 લેજર ટેબલમાં અસલી એન્ટ્રી (જેથી ડેશબોર્ડ અને લેજર પેજ પર પ્લસ થાય)
        $ins_ledger = $conn->prepare("INSERT INTO ledger (farmer_id, bill_id, due_amount, paid_amount, entry_date) VALUES (?, 0, ?, 0.00, NOW())");
        $ins_ledger->execute([$farmer_id, $total_amount]);
    }

    $conn->commit();
    $_SESSION['success_msg'] = "🎉 ઓર્ડર સફળતાપૂર્વક નોંધાઈ ગયો છે! પેમેન્ટ પ્રકાર: $payment_mode";
    header("Location: order_history.php");
    exit;

} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error_msg'] = "❌ ઓર્ડર પ્રોસેસમાં ભૂલ આવી: " . $e->getMessage();
    header("Location: product_details.php?id=" . $product_id);
    exit;
}