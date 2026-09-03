<?php
// admin/get_invoice_print.php - Complete Professional Agriculture Tax Invoice with 18% GST (9% CGST + 9% SGST)
require_once __DIR__ . '/../config/db_config.php';
session_start();

$bill_id = isset($_GET['bill_id']) ? intval($_GET['bill_id']) : 0;

try {
    // 1. બિલ અને ખેડૂતની વિગતો મેળવો[cite: 4]
    $stmt = $conn->prepare("SELECT b.*, f.farmer_name, f.village, f.phone_number, f.email FROM bills b JOIN farmers f ON b.farmer_id = f.farmer_id WHERE b.bill_id = ?");
    $stmt->execute([$bill_id]);
    $bill = $stmt->fetch();

    if (!$bill) {
        die("<div class='container text-center my-5 text-danger fw-bold'>❌ Invoice not found or invalid ID!</div>");
    }

    // 2. બિલની આઇટમ્સ મેળવો[cite: 4]
    $items_stmt = $conn->prepare("SELECT bi.*, p.product_name FROM bill_items bi JOIN products p ON bi.product_id = p.product_id WHERE bi.bill_id = ?");
    $items_stmt->execute([$bill_id]);
    $items = $items_stmt->fetchAll();

} catch (Exception $e) {
    die("<div class='container text-center my-5 text-danger fw-bold'>❌ Database Error: " . $e->getMessage() . "</div>");
}

// 🌟 ૩. સબટોટલની ચોક્કસ ગણતરી[cite: 4]
$calculated_subtotal = 0;
foreach($items as $item) {
    $calculated_subtotal += ($item['quantity'] * $item['price_per_unit']);
}

// જો આઇટમ્સ ખાલી હોય તો જ બેઝિક બિલ અમાઉન્ટ લેવી[cite: 4]
if ($calculated_subtotal <= 0) {
    $calculated_subtotal = floatval($bill['total_amount']);
}

// 🌟 જીએસટી ગણતરી અપડેટ (૧૮% GST: 9% CGST + 9% SGST)
$gst_rate = 18.0; 
$gst_amount = ($calculated_subtotal * $gst_rate) / 100;
$grand_total = $calculated_subtotal + $gst_amount;

// ડેટ માટે સેફ્ટી ચેક[cite: 4]
$bill_date = isset($bill['created_at']) && !empty($bill['created_at']) ? $bill['created_at'] : date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #BILL-0<?php echo $bill['bill_id']; ?> | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background: #ffffff; color: #000000; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #ddd; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); border-radius: 10px; background: #fff; }
        .invoice-header { border-bottom: 2px solid #28a745; padding-bottom: 15px; margin-bottom: 20px; }
        .table-custom th { background-color: #28a745 !important; color: white !important; text-align: center; }
        .table-custom td { text-align: center; vertical-align: middle; }
    </style>
</head>
<body>

<div class="invoice-box">
    <!-- Header Section -->
    <div class="row invoice-header align-items-center">
        <div class="col-6">
            <h3 class="fw-bold text-success m-0"><i class="fa-solid fa-wheat-awn me-1"></i> Agro Input Hub</h3>
            <p class="text-muted small m-0">Digital Kisan Marketplace & Billing Portal<br>Gujarat, India </p>
        </div>
        <div class="col-6 text-end">
            <h4 class="fw-bold text-dark m-0">AGRICULTURE INVOICE</h4>
            <span class="text-muted small font-monospace">Invoice No: <b>#BILL-0<?php echo $bill['bill_id']; ?></b></span><br>
            <span class="text-muted small font-monospace">Date: <b><?php echo date('M d, Y', strtotime($bill_date)); ?></b></span>
        </div>
    </div>

    <!-- Bill To Details -->
    <div class="row mb-4 p-3 rounded" style="background: #f8f9fa; border: 1px solid #e9ecef;">
        <div class="col-12 mb-2">
            <span class="text-success fw-bold text-uppercase small">Bill To:</span>
        </div>
        <div class="col-md-6">
            <p class="mb-1"><b>Name:</b> <?php echo htmlspecialchars($bill['farmer_name']); ?></p>
            <p class="mb-0"><b>Address / Village:</b> <?php echo htmlspecialchars($bill['village']); ?>, Gujarat</p>
        </div>
        <div class="col-md-6">
            <p class="mb-1"><b>Contact No:</b> <?php echo htmlspecialchars($bill['phone_number']); ?></p>
            <p class="mb-0"><b>Email:</b> <?php echo htmlspecialchars($bill['email'] ?? 'N/A'); ?></p>
        </div>
    </div>

    <!-- Items Table with GST -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th>Item No.</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price (₹)</th>
                    <th>Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($items)): ?>
                    <tr>
                        <td colspan="5" class="text-muted py-3">No items found for this invoice.</td>
                    </tr>
                <?php else: foreach($items as $index => $item): 
                    $row_total = $item['quantity'] * $item['price_per_unit'];
                ?>
                    <tr>
                        <td class="font-monospace">00-00<?php echo $item['product_id']; ?></td>
                        <td class="text-start fw-bold"><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹ <?php echo number_format($item['price_per_unit'], 2); ?></td>
                        <td class="fw-bold">₹ <?php echo number_format($row_total, 2); ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Totals & GST Summary Section -->
    <div class="row justify-content-end">
        <div class="col-md-5">
            <table class="table table-sm table-borderless">
                <tr>
                    <td class="text-muted">Subtotal:</td>
                    <td class="text-end font-monospace">₹ <?php echo number_format($calculated_subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td class="text-muted">CGST (9%):</td>
                    <td class="text-end font-monospace">₹ <?php echo number_format($gst_amount / 2, 2); ?></td>
                </tr>
                <tr>
                    <td class="text-muted pb-2 border-bottom">SGST (9%):</td>
                    <td class="text-end pb-2 border-bottom font-monospace">₹ <?php echo number_format($gst_amount / 2, 2); ?></td>
                </tr>
                <tr>
                    <td class="fw-bold fs-5 text-success">Grand Total:</td>
                    <td class="text-end fw-bold fs-5 text-success font-monospace">₹ <?php echo number_format($grand_total, 2); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Payment Method & Footer Note -->
    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
        <div>
            <span class="text-muted small">Method of Payment:</span><br>
            <span class="badge bg-success px-3 py-1"><?php echo htmlspecialchars($bill['payment_mode']); ?></span>
        </div>
        <div class="text-end">
            <button onclick="window.print()" class="btn btn-sm btn-dark px-4 rounded-pill d-print-none"><i class="fa-solid fa-print me-1"></i> Print Invoice</button>
        </div>
    </div>
</div>

</body>
</html>