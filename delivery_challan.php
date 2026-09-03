<?php
// admin/delivery_challan.php - Official Agro Input Hub Delivery Challan
require_once '../config/db_config.php';
session_start();

// ઓર્ડર ID મેળવવો, જો ન હોય તો 1336 ડિફોલ્ટ
$challan_id = isset($_GET['id']) ? intval($_GET['id']) : 1336;
$farmer_name = "ખેડૂતનું નામ";
$village = "ગામનું નામ";
$order_date = date('d-m-Y');

// 📋 જો ડેટાબેઝમાંથી લાઈવ ઓર્ડર વિગત લાવવી હોય તો:
try {
    $stmt = $conn->prepare("SELECT o.*, f.farmer_name, f.village FROM orders o JOIN farmers f ON o.farmer_id = f.farmer_id WHERE o.order_id = ?");
    $stmt->execute([$challan_id]);
    $order = $stmt->fetch();
    if($order) {
        $farmer_name = $order['farmer_name'];
        $village = $order['village'];
        $order_date = date('d-m-Y', strtotime($order['order_date']));
    }
} catch (Exception $e) {
    // અગાઉના પેજ પરથી ઓર્ડર ન મળે તો ડિફોલ્ટ ડેટા ચાલુ રહેશે
}
?>
<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ડિલિવરી ચલણ - Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f5f5f5; font-family: 'Segoe UI', Arial, sans-serif; }
        
        /* 🖨️ પિંક પ્રિન્ટ બુક સ્ટાઇલ પેપર */
        .challan-container {
            width: 210mm;
            min-height: 297mm;
            padding: 20px;
            margin: 20px auto;
            background: #ffffff;
            border: 2px solid #ff4d6d;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            color: #d90429;
        }
        
        .challan-border {
            border: 2px solid #ff4d6d;
            padding: 15px;
            height: 100%;
        }

        .header-table td { border: none !important; padding: 2px !important; color: #d90429; font-size: 14px; }
        .main-title { font-size: 42px; font-weight: 900; color: #d90429; font-family: 'Segoe UI', sans-serif; text-shadow: 1px 1px #ffb3c1; letter-spacing: 1px; }
        .sub-title { font-size: 15px; font-weight: bold; border-top: 1px dashed #ff4d6d; border-bottom: 1px dashed #ff4d6d; padding: 5px 0; }
        
        .badge-challan {
            background-color: #d90429;
            color: white !important;
            padding: 5px 30px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            letter-spacing: 1px;
        }

        /* 📊 બુક જેવું પરફેક્ટ ચલણ ટેબલ */
        .table-challan { border: 2px solid #ff4d6d !important; margin-top: 15px; }
        .table-challan th { background-color: transparent; border: 2px solid #ff4d6d !important; color: #d90429 !important; font-weight: bold; font-size: 15px; text-align: center; }
        .table-challan td { border-left: 2px solid #ff4d6d !important; border-right: 2px solid #ff4d6d !important; border-top: none !important; border-bottom: none !important; color: #333 !important; font-weight: 600; height: 380px; vertical-align: top; padding: 12px; }
        .total-row td { border-top: 2px solid #ff4d6d !important; border-bottom: 2px solid #ff4d6d !important; height: auto !important; color: #d90429 !important; font-weight: bold; }

        .footer-note { font-size: 13px; font-weight: bold; line-height: 1.6; border-top: 2px solid #ff4d6d; padding-top: 15px; }
        
        @media print {
            body { background: none; }
            .no-print { display: none !important; }
            .challan-container { margin: 0; box-shadow: none; border: none; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="container text-center my-3 no-print">
        <button onclick="window.print();" class="btn btn-danger fw-bold px-4 shadow"><i class="fa-solid fa-print"></i> ચલણ પ્રિન્ટ / PDF ડાઉનલોડ કરો</button>
        <a href="orders_manage.php" class="btn btn-secondary fw-bold px-3 ms-2"><i class="fa-solid fa-arrow-left"></i> પાછા જાઓ</a>
    </div>

    <div class="challan-container">
        <div class="challan-border">
            
            <table class="table header-table mb-0">
                <tr>
                    <td style="width: 50%;">
                        <strong>GSTIN No. :</strong> 24BPKPK6739Q1Z2<br>
                        <strong>વિભાગ :</strong> ગોડાઉન સ્ટોક ડિસ્ટ્રિબ્યુશન
                    </td>
                    <td style="width: 50%; text-align: right; vertical-align: top;">
                        <span class="fw-bold">ડિજિટલ કૃષિ પોર્ટલ</span><br>
                        <small class="text-muted">Live Stock Management Mode</small>
                    </td>
                </tr>
            </table>

            <div class="text-center my-3">
                <div class="main-title"><i class="fa-solid fa-leaf"></i> Agro Input Hub</div>
                <div class="sub-title text-uppercase fw-bold">બિયારણ, ખાતર અને એગ્રો-કેમિકલ્સના ડિજિટલ વેપારી</div>
                
            </div>

            <div class="text-center my-3">
                <span class="badge-challan">ડિલીવરી ચલણ</span>
            </div>

            <table class="table header-table mb-2">
                <tr>
                    <td style="width: 50%;">
                        <span style="font-size: 17px;"><strong>ચલણ નં. :</strong> <span class="text-dark">#CH-0<?php echo $challan_id; ?></span></span><br>
                        <span style="font-size: 16px;"><strong>શ્રીમાન,</strong> <span class="text-dark fw-bold"><?php echo htmlspecialchars($farmer_name); ?></span></span>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <span style="font-size: 16px;"><strong>તારીખ :</strong> <span class="text-dark"><?php echo $order_date; ?></span></span><br>
                        <span style="font-size: 16px;"><strong>ગામ :</strong> <span class="text-dark fw-bold"><?php echo htmlspecialchars($village); ?></span></span>
                    </td>
                </tr>
            </table>

            <table class="table table-challan mb-3">
                <thead>
                    <tr>
                        <th style="width: 45%;">સામાનની વિગત (Product Details)</th>
                        <th style="width: 15%;">કેટેગરી</th>
                        <th style="width: 10%;">જથ્થો</th>
                        <th style="width: 15%;">ભાવ (Rate)</th>
                        <th style="width: 15%;">કુલ રકમ (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php 
                                if(isset($order['product_name'])) {
                                    echo htmlspecialchars($order['product_name']);
                                } else {
                                    echo "૧. નાઇટ્રોજન યુરિયા ખાતર (50 Kg Bag)<br>૨. કોરોજન પેસ્ટિસાઇડ લિક્વિડ";
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($order['category']) ? $order['category'] : 'Fertilizers'; ?>
                        </td>
                        <td class="text-center">
                            <?php 
                                // જો ડેટાબેઝ માંથી જથ્થો અલગથી મળતો હોય તો, નહીંતર બુકિંગ નંગ
                                echo "૧ બોરી"; 
                            ?>
                        </td>
                        <td class="text-end">350.00</td>
                        <td class="text-end">350.00</td>
                    </tr>
                    
                    <tr class="total-row">
                        <td colspan="4" class="text-start"><strong>અંકે રૂા. :</strong></td>
                       
                    </tr>
                </tbody>
            </table>

            <div class="row footer-note mt-5">
                <div class="col-7">
                    • જંતુનાશક દવાનો ઉપયોગ માત્ર ખેતીવાડી હેતુ માટે જ કરવો.<br>
                   
                    <strong class="text-dark d-block mt-4">માલ લેનાર ખેડૂતની સહી : ....................................</strong>
                </div>
                <div class="col-5 text-end d-flex flex-column justify-content-between align-items-end">
                    <span class="fw-bold">ફોર, Agro Input Hub</span>
                    <strong class="text-dark mt-5">ગોડાઉન મેનેજર સહી</strong>
                </div>
            </div>

        </div>
    </div>

</body>
</html>