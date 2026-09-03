<?php
// includes/lang.php - Complete Multi-Language Dictionary (English, Gujarati, Hindi)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ૧. ડિફોલ્ટ ભાષા ગુજરાતી સેટ કરવી
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'gu';
}

// ૨. URL દ્વારા ભાષા બદલવાની વિનંતી હેન્ડલિંગ
if (isset($_GET['changelang'])) {
    $requested_lang = $_GET['changelang'];
    if (in_array($requested_lang, ['en', 'gu', 'hi'])) {
        $_SESSION['lang'] = $requested_lang;
    }
    $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: " . $redirect_url);
    exit;
}

// ૩. ત્રણેય ભાષાઓ માટેની સંપૂર્ણ ડિક્શનરી
$lang_arr = [
    'en' => [
        'dashboard'       => 'Dashboard',
        'farmers'         => 'Farmer Accounts',
        'products'        => 'Stock Manager',
        'billing'         => 'POS Billing Counter',
        'orders'          => 'Order Control',
        'welcome'         => 'Admin Command Center',
        'logout'          => 'Logout',
        'shop_control'    => 'Shop Side Control',
        'metrics'         => 'Live Business Ingestion Metrics',
        'total_sales'     => 'Total Store Sales',
        'active_farmers'  => 'Active Farmers',
        'pending_orders'  => 'Pending Orders',
        'low_stock'       => 'Low Stock Items',
        'core_modules'    => 'Core Module Operations',
        'reviews'         => 'Product Reviews',
        'verify_staff'    => 'Verify Staff',
        'reports'         => 'Business Reports',
        'audit_logs'      => 'Audit Logs',
        'db_backup'       => 'DB Backup',
        'returns'         => 'Return Requests',
        'pending_queue'   => 'Pending Live Order Queue Alerts',
        'view_queue'      => 'View Full Queue',
        'order_id'        => 'Order ID',
        'farmer_profile'  => 'Farmer Profile',
        'village'         => 'Village',
        'allocation'      => 'Items & Quantity Allocation',
        'timestamp'       => 'Timestamp',
        'live_status'     => 'Live Status',
        'no_orders'       => 'Excellent! No pending orders in the queue.',
        'pending'         => 'Pending'
    ],
    'gu' => [
        'dashboard'       => 'ડેશબોર્ડ',
        'farmers'         => 'ખેડૂત ખાતાં (Farmer Accounts)',
        'products'        => 'સ્ટોક મેનેજર',
        'billing'         => 'પીઓએસ બિલિંગ કાઉન્ટર',
        'orders'          => 'ઓર્ડર કંટ્રોલ',
        'welcome'         => 'એડમિન કમાન્ડ સેન્ટર',
        'logout'          => 'બહાર નીકળો (Logout)',
        'shop_control'    => 'દુકાન સાઈડ કંટ્રોલ',
        'metrics'         => 'લાઈવ બિઝનેસ એનાલિટિક્સ મેટ્રિક્સ',
        'total_sales'     => 'કુલ સ્ટોર વેચાણ',
        'active_farmers'  => 'સક્રિય ખેડૂતો',
        'pending_orders'  => 'પેન્ડિંગ ઓર્ડર્સ',
        'low_stock'       => 'લો સ્ટોક આઇટમ્સ',
        'core_modules'    => 'મુખ્ય મોડ્યુલ ઓપરેશન્સ',
        'reviews'         => 'પ્રોડક્ટ રિવ્યુઝ',
        'verify_staff'    => 'સ્ટાફ વેરિફિકેશન',
        'reports'         => 'બિઝનેસ રિપોર્ટ્સ',
        'audit_logs'      => 'ઓડિટ લોગ્સ',
        'db_backup'       => 'ડેટાબેઝ બેકઅપ',
        'returns'         => 'રીટર્ન વિનંતીઓ',
        'pending_queue'   => 'પેન્ડિંગ લાઈવ ઓર્ડર ક્યૂ એલર્ટ્સ',
        'view_queue'      => 'સંપૂર્ણ ક્યૂ જુઓ',
        'order_id'        => 'ઓર્ડર આઈડી',
        'farmer_profile'  => 'ખેડૂત પ્રોફાઇલ',
        'village'         => 'ગામ',
        'allocation'      => 'આઇટમ્સ અને જથ્થો (Quantity)',
        'timestamp'       => 'સમય (Timestamp)',
        'live_status'     => 'લાઈવ સ્ટેટસ',
        'no_orders'       => 'સરસ! ક્યૂમાં કોઈ પેન્ડિંગ ઓર્ડર નથી.',
        'pending'         => 'પેન્ડિંગ'
    ],
    'hi' => [
        'dashboard'       => 'डैशबोर्ड',
        'farmers'         => 'किसान खाते',
        'products'        => 'स्टॉक मैनेजर',
        'billing'         => 'पीओएस बिलिंग काउंटर',
        'orders'          => 'ऑर्डर नियंत्रण',
        'welcome'         => 'एडमिन कमांड सेंटर',
        'logout'          => 'बाहर निकलें (Logout)',
        'shop_control'    => 'दुकान साइड नियंत्रण',
        'metrics'         => 'लाइव बिजनेस मेट्रिक्स',
        'total_sales'     => 'कुल स्टोर बिक्री',
        'active_farmers'  => 'सक्रिय किसान',
        'pending_orders'  => 'लंबित ऑर्डर',
        'low_stock'       => 'कम स्टॉक आइटम',
        'core_modules'    => 'मुख्य मॉड्यूल संचालन',
        'reviews'         => 'उत्पाद समीक्षा',
        'verify_staff'    => 'स्टाफ सत्यापन',
        'reports'         => 'व्यापार रिपोर्ट',
        'audit_logs'      => 'ऑडिट लॉग',
        'db_backup'       => 'डेटाबेस बैकअप',
        'returns'         => 'वापसी अनुरोध',
        'pending_queue'   => 'लंबित लाइव ऑर्डर कतार अलर्ट',
        'view_queue'      => 'पूर्ण कतार देखें',
        'order_id'        => 'ऑर्डर आईडी',
        'farmer_profile'  => 'किसान प्रोफाइल',
        'village'         => 'गाँव',
        'allocation'      => 'आइटम और मात्रा',
        'timestamp'       => 'समय (Timestamp)',
        'live_status'     => 'लाइव स्थिति',
        'no_orders'       => 'बहुत बढ़िया! कतार में कोई लंबित ऑर्डर नहीं है।',
        'pending'         => 'लंबित'
    ]
];

// ૪. ટ્રાન્સલેશન પ્રિન્ટ હેલ્પર ફંક્શન
function __($key) {
    global $lang_arr;
    $lang = $_SESSION['lang'] ?? 'gu';
    return isset($lang_arr[$lang][$key]) ? $lang_arr[$lang][$key] : $key;
}
?>