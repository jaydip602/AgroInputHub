<?php
function getFarmerBalance($conn, $farmer_id) {
    // 1. POS ઉધાર બિલ્સ
    $stmt_pos = $conn->prepare("SELECT SUM(total_amount) as total FROM bills WHERE farmer_id = ? AND payment_mode = 'Credit'");
    $stmt_pos->execute([$farmer_id]);
    $due_pos = $stmt_pos->fetch()['total'] ?? 0;

    // 2. ઓનલાઈન ઉધાર ઓર્ડર્સ
    $stmt_online = $conn->prepare("SELECT SUM(total_amount) as total FROM orders WHERE farmer_id = ? AND payment_mode = 'Credit'");
    $stmt_online->execute([$farmer_id]);
    $due_online = $stmt_online->fetch()['total'] ?? 0;

    // 3. જમા કરેલ રકમ (Ledger)
    $stmt_ledger = $conn->prepare("SELECT SUM(paid_amount) as total FROM ledger WHERE farmer_id = ?");
    $stmt_ledger->execute([$farmer_id]);
    $paid = $stmt_ledger->fetch()['total'] ?? 0;

    // ચોક્કસ ગણતરી: (ઉધાર બિલ + ઉધાર ઓર્ડર) - જમા રકમ
    return ($due_pos + $due_online) - $paid;
}
?>