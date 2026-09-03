<?php
// admin/billing.php - POS Automated Billing Center (Multi-Item Order Sync Fixed)
require_once __DIR__ . '/../config/db_config.php';
session_start();

// PHPMailer Include
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../includes/phpmailer-master/src/Exception.php';
require_once __DIR__ . '/../includes/phpmailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../includes/phpmailer-master/src/SMTP.php';

$message = "";
$print_bill_id = 0;

// 1. Handle Direct Email Send with Mobile-Optimized Email Template
if (isset($_POST['send_direct_email']) && isset($_POST['target_bill_id'])) {
    $target_bill_id = intval($_POST['target_bill_id']);
    try {
        $stmt_em = $conn->prepare("SELECT b.*, f.farmer_name, f.village, f.phone_number, f.email FROM bills b JOIN farmers f ON b.farmer_id = f.farmer_id WHERE b.bill_id = ?");
        $stmt_em->execute([$target_bill_id]);
        $bill_data = $stmt_em->fetch();

        $stmt_items = $conn->prepare("SELECT bi.*, p.product_name FROM bill_items bi JOIN products p ON bi.product_id = p.product_id WHERE bi.bill_id = ?");
        $stmt_items->execute([$target_bill_id]);
        $items_data = $stmt_items->fetchAll();

        if ($bill_data && !empty($bill_data['email'])) {
            $calculated_subtotal = 0;
            foreach($items_data as $it) {
                $calculated_subtotal += ($it['quantity'] * $it['price_per_unit']);
            }
            if ($calculated_subtotal <= 0) {
                $calculated_subtotal = floatval($bill_data['total_amount']);
            }
            
            $gst_rate = 18.0; 
            $gst_amount = ($calculated_subtotal * $gst_rate) / 100;
            $grand_total = $calculated_subtotal + $gst_amount;
            $bill_date = isset($bill_data['created_at']) && !empty($bill_data['created_at']) ? $bill_data['created_at'] : date('Y-m-d H:i:s');

            $items_html = '';
            if(empty($items_data)) {
                $items_html = "<tr><td colspan='2' style='text-align:center; color:#777; padding:12px;'>No items found.</td></tr>";
            } else {
                foreach($items_data as $item) {
                    $row_total = $item['quantity'] * $item['price_per_unit'];
                    $items_html .= "
                    <tr>
                        <td style='border-bottom: 1px solid #eee; padding: 8px 4px; text-align: left; font-size: 12px; color: #333;'>
                            <b>".htmlspecialchars($item['product_name'])."</b><br>
                            <span style='color: #777; font-size: 10px;'>Qty: {$item['quantity']} × ₹ ".number_format($item['price_per_unit'], 2)."</span>
                        </td>
                        <td style='border-bottom: 1px solid #eee; padding: 8px 4px; text-align: right; font-size: 12px; font-weight: bold; color: #111; vertical-align: middle;'>
                            ₹ ".number_format($row_total, 2)."
                        </td>
                    </tr>";
                }
            }

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jaydipparmar2042006@gmail.com';     // તમારું જીમેલ આઈડી અહીં નાખો
            $mail->Password   = 'kbtr dlwi nrgu xhoy';        // જીમેલનો ૧૬ અંકનો App Password અહીં નાખો
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('your_email@gmail.com', 'Agro Input Hub');
            $mail->addAddress($bill_data['email'], $bill_data['farmer_name']);

            $mail->isHTML(true);
            $mail->Subject = "Tax Invoice #BILL-0" . $bill_data['bill_id'] . " | Agro Input Hub";
            $mail->Body    = "
            <table width='100%' cellpadding='0' cellspacing='0' style='background: #f4f4f4; padding: 10px; font-family: Arial, sans-serif;'>
                <tr>
                    <td align='center'>
                        <table width='100%' cellpadding='0' cellspacing='0' style='max-width: 500px; background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #ddd;'>
                            <tr>
                                <td style='border-bottom: 2px solid #28a745; padding-bottom: 10px;'>
                                    <table width='100%' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td>
                                                <h3 style='color: #28a745; margin: 0; font-size: 16px;'>Agro Input Hub</h3>
                                                <p style='color: #666; font-size: 10px; margin: 2px 0 0 0;'>Digital Kisan Portal, Gujarat</p>
                                            </td>
                                            <td style='text-align: right;'>
                                                <h4 style='color: #333; margin: 0; font-size: 13px;'>TAX INVOICE</h4>
                                                <span style='color: #777; font-size: 10px;'>#BILL-0{$bill_data['bill_id']}</span><br>
                                                <span style='color: #777; font-size: 10px;'>".date('d M Y', strtotime($bill_date))."</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 10px 0; font-size: 11px; color: #444;'>
                                    <b>Billed To:</b> ".htmlspecialchars($bill_data['farmer_name'])."<br>
                                    <b>Village:</b> ".htmlspecialchars($bill_data['village'])."<br>
                                    <b>Contact:</b> ".htmlspecialchars($bill_data['phone_number'])."
                                </td>
                            </tr>
                            <tr>
                                <td style='padding-top: 5px;'>
                                    <table width='100%' cellpadding='0' cellspacing='0' style='border-collapse: collapse;'>
                                        <tr style='background: #28a745; color: #fff; font-size: 11px;'>
                                            <th style='padding: 6px; text-align: left;'>Product Description</th>
                                            <th style='padding: 6px; text-align: right;'>Amount</th>
                                        </tr>
                                        {$items_html}
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding-top: 10px;'>
                                    <table width='100%' cellpadding='0' cellspacing='0' style='font-size: 12px;'>
                                        <tr>
                                            <td style='padding: 3px 0; color: #666;'>Subtotal:</td>
                                            <td style='padding: 3px 0; text-align: right;'>₹ ".number_format($calculated_subtotal, 2)."</td>
                                        </tr>
                                        <tr>
                                            <td style='padding: 3px 0; color: #666;'>CGST (9%):</td>
                                            <td style='padding: 3px 0; text-align: right;'>₹ ".number_format($gst_amount / 2, 2)."</td>
                                        </tr>
                                        <tr>
                                            <td style='padding: 3px 0; color: #666; border-bottom: 1px solid #ddd; padding-bottom: 5px;'>SGST (9%):</td>
                                            <td style='padding: 3px 0; text-align: right; border-bottom: 1px solid #ddd; padding-bottom: 5px;'>₹ ".number_format($gst_amount / 2, 2)."</td>
                                        </tr>
                                        <tr>
                                            <td style='padding: 8px 0; font-weight: bold; font-size: 14px; color: #28a745;'>Grand Total:</td>
                                            <td style='padding: 8px 0; text-align: right; font-weight: bold; font-size: 14px; color: #28a745;'>₹ ".number_format($grand_total, 2)."</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding-top: 15px; border-top: 1px solid #eee; font-size: 11px; color: #555;'>
                                    <b>Payment Mode:</b> <span style='background: #28a745; color: #fff; padding: 2px 8px; border-radius: 3px;'>".htmlspecialchars($bill_data['payment_mode'])."</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>";

            $mail->send();
            $message = "<div class='alert alert-success py-2 small fw-bold text-center'>🎉 Professional Tax Invoice #BILL-0$target_bill_id successfully emailed to " . $bill_data['email'] . "!</div>";
        } else {
            $message = "<div class='alert alert-warning py-2 small fw-bold text-center'>⚠️ Farmer email address is missing or empty!</div>";
        }
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger py-1 small fw-bold text-center'>❌ Mail Error: {$mail->ErrorInfo}</div>";
    }
}

if (isset($_GET['printed_bill_id'])) {
    $print_bill_id = intval($_GET['printed_bill_id']);
    $message = "<div class='alert alert-success py-2 small fw-bold text-center'>
                    🎉 Auto-Billing Sync Completed Successfully! ID: #BILL-0$print_bill_id <br><br>
                    <button onclick='printInvoice($print_bill_id)' class='btn btn-sm btn-success fw-bold rounded-pill px-3'><i class='fa-solid fa-print me-1'></i> Print Invoice Receipt</button>
                </div>";
}

if (isset($_GET['auto_create']) && intval($_GET['auto_create']) == 1) {
    $farmer_id = intval($_GET['farmer_id']);
    $total     = floatval($_GET['amount']);
    $raw_mode  = trim($_GET['payment']); 
    $order_id  = isset($_GET['from_order']) ? intval($_GET['from_order']) : 0;

    $pay_mode = 'Cash';
    $check_mode = strtolower($raw_mode);
    if (stripos($check_mode, 'credit') !== false || stripos($check_mode, 'baki') !== false || stripos($check_mode, 'udhaar') !== false) {
        $pay_mode = 'Credit';
    } elseif (stripos($check_mode, 'upi') !== false || stripos($check_mode, 'online') !== false) {
        $pay_mode = 'UPI';
    }

    if ($farmer_id > 0 && $total > 0 && $order_id > 0) {
        try {
            $conn->beginTransaction();

            $stmt_check = $conn->prepare("SELECT bill_id FROM bills WHERE order_id = ? LIMIT 1");
            $stmt_check->execute([$order_id]);
            $existing_bill_id = $stmt_check->fetchColumn();

            if ($existing_bill_id) {
                $bill_id = $existing_bill_id;
                $conn->commit();
            } else {
                $stmt_ord_det = $conn->prepare("SELECT product_name, total_amount FROM orders WHERE order_id = ?");
                $stmt_ord_det->execute([$order_id]);
                $ord_det = $stmt_ord_det->fetch();
                
                // બિલ હેડર ઇન્સર્ટ કરો
                $ins_bill = $conn->prepare("INSERT INTO bills (farmer_id, total_amount, discount, payment_mode, order_id) VALUES (?, ?, 0.00, ?, ?)");
                $ins_bill->execute([$farmer_id, $total, $pay_mode, $order_id]);
                $bill_id = $conn->lastInsertId();

                // ડેટાબેઝની બધી એક્ટિવ પ્રોડક્ટ્સ મેળવો
                $stmt_pcheck = $conn->query("SELECT product_id, product_name, price FROM products WHERE status = 'Active'");
                $all_db_products = $stmt_pcheck->fetchAll(PDO::FETCH_ASSOC);

                // 🌟 ઓર્ડર સ્ટ્રિંગમાંથી કોમા આધારિત મલ્ટીપલ આઇટમ્સ અલગ કરો અને દરેકને લૂપમાં ચલાવો
                $raw_pname_str = $ord_det['product_name'] ?? '';
                $individual_items = explode(',', $raw_pname_str);

                $idx = 0;
                foreach ($individual_items as $single_item_str) {
                    $single_item_str = trim($single_item_str);
                    if (empty($single_item_str)) continue;

                    $item_qty = 1;
                    if (preg_match('/(?:\(|qty[:\s]*|pcs[:\s]*)([0-9]+)\s*(?:pcs|\)|)/i', $single_item_str, $matches)) {
                        $item_qty = intval($matches[1]);
                    }

                    // પ્રોડક્ટ મેચ કરો
                    $matched_product_id = 0;
                    $unit_price = 0;

                    foreach ($all_db_products as $db_p) {
                        if (stripos($single_item_str, $db_p['product_name']) !== false) {
                            $matched_product_id = $db_p['product_id'];
                            $unit_price = floatval($db_p['price']);
                            break;
                        }
                    }

                    // જો ડેટાબેઝમાં એક્ઝેક્ટ નામ મેચ ન થાય, તો લૂપના ઇન્ડેક્સ મુજબ અલગ પ્રોડક્ટ આઇડી ફાળવો જેથી ઓવરરાઇટ ન થાય
                    if ($matched_product_id <= 0) {
                        $fallback_p = $all_db_products[$idx % count($all_db_products)] ?? null;
                        if ($fallback_p) {
                            $matched_product_id = $fallback_p['product_id'];
                            $unit_price = floatval($fallback_p['price']);
                        } else {
                            $matched_product_id = 1;
                            $unit_price = $total / max($item_qty, 1);
                        }
                    }

                    if ($unit_price <= 0) {
                        $unit_price = 100.00; 
                    }

                    // દરેક આઇટમ અલગથી bill_items માં ઇન્સર્ટ થશે (કોલિઝન વગર)
                    $ins_item = $conn->prepare("INSERT INTO bill_items (bill_id, product_id, quantity, price_per_unit) VALUES (?, ?, ?, ?)");
                    $ins_item->execute([$bill_id, $matched_product_id, $item_qty, $unit_price]);

                    $idx++;
                }

                if ($pay_mode == 'Credit') {
                    $up_baki = $conn->prepare("UPDATE farmers SET total_baki = total_baki + ? WHERE farmer_id = ?");
                    $up_baki->execute([$total, $farmer_id]);
                    
                    $ins_ledger = $conn->prepare("INSERT INTO ledger (farmer_id, bill_id, due_amount, paid_amount) VALUES (?, ?, ?, 0.00)");
                    $ins_ledger->execute([$farmer_id, $bill_id, $total]);
                }

                $conn->commit();
            }

            $up_ord = $conn->prepare("UPDATE orders SET order_status = 'Delivered' WHERE order_id = ?");
            $up_ord->execute([$order_id]);

            header("Location: billing.php?printed_bill_id=" . $bill_id);
            exit;

        } catch (Exception $e) {
            $conn->rollBack();
            $message = "<div class='alert alert-danger py-1 small fw-bold'>❌ Sync Fault: " . $e->getMessage() . "</div>";
        }
    }
}

$recent_bills = $conn->query("SELECT b.*, f.farmer_name, f.village, f.email,
                                     (SELECT COUNT(*) FROM ledger l WHERE l.bill_id = b.bill_id AND l.due_amount > 0) AS is_ledger_credit
                              FROM bills b 
                              JOIN farmers f ON b.farmer_id = f.farmer_id 
                              WHERE b.order_id IS NOT NULL AND b.order_id > 0
                              ORDER BY b.bill_id DESC LIMIT 10")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Digital Billing | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 110px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; color: white; }
        .table-custom-fix th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; font-weight: 600; }
        .table-custom-fix td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>POS</span> Gate</div>
                    <div class="logo-sub-text">Automated Online Order Billing</div>
                </div>
            </a>   
            <a href="admin_dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <?php if(!empty($message)) echo "<div class='mb-4'>$message</div>"; ?>
                
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                        <h5 class="fw-bold text-success m-0"><i class="fa-solid fa-folder-open me-1"></i> Online Orders Automated Digital Bills Registry</h5>
                        <a href="orders_manage.php" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold text-white"><i class="fa-solid fa-cart-flatbed me-1"></i> Go to Orders Queue</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-bordered text-center align-middle table-custom-fix small mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Order Reference</th>
                                    <th>Farmer Identity</th>
                                    <th>Total Ledger Value</th>
                                    <th>Payment Mode</th>
                                    <th>Action Modules</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recent_bills)): ?>
                                    <tr><td colspan="6" class="text-muted p-4">No online order bills generated yet. Go to Order Control to sync bills.</td></tr>
                                <?php else: foreach($recent_bills as $rb): ?>
                                    <tr>
                                        <td class="font-monospace text-success fw-bold">#BILL-0<?php echo $rb['bill_id']; ?></td>
                                        <td class="font-monospace text-warning fw-bold">#ORD-0<?php echo $rb['order_id']; ?></td>
                                        <td class="text-start">
                                            <span class="fw-bold text-white"><?php echo htmlspecialchars($rb['farmer_name']); ?></span><br>
                                            <small class="text-muted font-monospace">Loc: <?php echo htmlspecialchars($rb['village']); ?></small>
                                        </td>
                                        <td class="fw-bold text-success font-monospace">₹ <?php echo number_format($rb['total_amount'], 2); ?></td>
                                        <td>
                                            <?php 
                                            $m = isset($rb['payment_mode']) ? trim($rb['payment_mode']) : '';
                                            $is_credit = intval($rb['is_ledger_credit']);
                                            if ($m == 'Credit' || $is_credit > 0) {
                                                echo "<span class='badge bg-danger fw-bold text-white px-3 py-1.5 shadow-sm'>Credit</span>";
                                            } elseif ($m == 'UPI') {
                                                echo "<span class='badge bg-primary fw-bold text-white px-3 py-1.5 shadow-sm'>UPI</span>";
                                            } else {
                                                echo "<span class='badge bg-success fw-bold text-white px-3 py-1.5 shadow-sm'>Cash</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Print Button -->
                                                <button onclick="printInvoice(<?php echo $rb['bill_id']; ?>)" class="btn btn-sm btn-outline-success py-1 px-3 fw-bold text-white rounded-pill" title="Print Invoice"><i class="fa-solid fa-print me-1"></i> Print</button>
                                                
                                                <!-- Email Button -->
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="target_bill_id" value="<?php echo $rb['bill_id']; ?>">
                                                    <button type="submit" name="send_direct_email" class="btn btn-sm btn-outline-primary py-1 px-3 fw-bold text-white rounded-pill" title="Send Email with Invoice Details">
                                                        <i class="fa-solid fa-envelope me-1"></i> Email Bill
                                                    </button>
                                                </form>
                                            </div>
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

    <script>
    function printInvoice(billId) {
        fetch('./get_invoice_print.php?bill_id=' + billId)
        .then(response => response.text())
        .then(html => {
            let printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write('<html><head><title>Print Invoice #' + billId + '</title>');
            printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
            printWindow.document.write('</head><body style="background:white; color:black; padding:30px;">');
            printWindow.document.write(html);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 500);
        });
    }
    </script>
</body>
</html>