<?php
// includes/flang.php - Farmer Side Multi-Language Dictionary
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'gu'; 
}

if (isset($_GET['changelang'])) {
    $requested_lang = $_GET['changelang'];
    if (in_array($requested_lang, ['en', 'gu', 'hi'])) {
        $_SESSION['lang'] = $requested_lang;
    }
    $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: " . $redirect_url);
    exit;
}

$lang_arr = [
    'en' => [
        'welcome' => 'Welcome',
        'village' => 'Village Location',
        'land' => 'Operating Land',
        'latest_product' => 'LATEST TRACKING PRODUCT',
        'order_status' => 'ORDER PROCESSING STATUS',
        'quick_menu' => 'Quick Operations Menu',
        'marketplace' => '1. Input Marketplace',
        'order_history' => '2. Live Order History',
        'track_order' => '3. Track Order',
        'agri_experts' => '4. Agri Experts', // 🌟 Added Agri Experts Index
        'about' => '5. About Us',
        'logout' => 'Logout',
        'select_order' => 'Select Your Order',
        'no_orders' => 'No orders found.',
        'select_prompt' => 'Please select any order from the left list to track.',
        'order_tracking_title' => 'Order Tracking',
        'product' => 'Product',
        'order_date' => 'Order Placed Date',
        'est_delivery' => 'Estimated Delivery',
        'live_timeline' => 'Live Progress Timeline',
        'step_1' => '1. Order Placed',
        'desc_1' => 'Your order has been successfully received by Agro Input Hub.',
        'step_2' => '2. Order Approved & Processing',
        'desc_2_pending' => 'Admin verification is in progress (Pending)...',
        'desc_2_approved' => 'Order has been approved and items are being packed.',
        'step_3' => '3. Out for Delivery / Shipped',
        'desc_3' => 'Package is dispatched and will reach your village location shortly.',
        'step_4' => '4. Delivered Successfully',
        'desc_4_delivered' => 'Order has been successfully delivered!',
        'desc_4_pending' => 'Awaiting final delivery confirmation.',
        'cancelled_alert' => 'This order has been cancelled.'
    ],
    'gu' => [
        'welcome' => 'સ્વાગત છે',
        'village' => 'ગામ',
        'land' => 'જમીન',
        'latest_product' => 'છેલ્લી ટ્રેક કરેલી પ્રોડક્ટ',
        'order_status' => 'ઓર્ડર પ્રક્રિયા સ્થિતિ',
        'quick_menu' => 'ઝડપી ઓપરેશન્સ મેનુ',
        'marketplace' => '૧. ઇનપુટ માર્કેટપ્લેસ',
        'order_history' => '૨. લાઈવ ઓર્ડર ઇતિહાસ',
        'track_order' => '૩. ઓર્ડર ટ્રેક કરો',
        'agri_experts' => '૪. કૃષિ નિષ્ણાતો ', // 🌟 Added Agri Experts Index
        'about' => '૫. અમારા વિશે',
        'logout' => 'બહાર નીકળો (Logout)',
        'select_order' => 'તમારા ઓર્ડર પસંદ કરો',
        'no_orders' => 'કોઈ ઓર્ડર મળ્યો નથી.',
        'select_prompt' => 'ટ્રેક કરવા માટે ડાબી બાજુથી કોઈપણ ઓર્ડર પર ક્લિક કરો.',
        'order_tracking_title' => 'ઓર્ડર ટ્રેકિંગ',
        'product' => 'પ્રોડક્ટ',
        'order_date' => 'ઓર્ડર તારીખ',
        'est_delivery' => 'અંદાજિત ડિલિવરી',
        'live_timeline' => 'લાઈવ સ્ટેપ ટાઈમલાઈન',
        'step_1' => '૧. ઓર્ડર નોંધાયો',
        'desc_1' => 'તમારો ઓર્ડર Agro Input Hub માં સફળતાપૂર્વક સ્વીકારવામાં આવ્યો છે.',
        'step_2' => '૨. એડમિન દ્વારા મંજૂર',
        'desc_2_pending' => 'એડમિન દ્વારા ચકાસણી ચાલુ છે (Pending)...',
        'desc_2_approved' => 'ઓર્ડર મંજૂર થઈ ગયો છે અને પેકિંગ ચાલુ છે.',
        'step_3' => '૩. ડિલિવરી માટે રવાના',
        'desc_3' => 'પ્રોડક્ટ રવાના કરવામાં આવી છે અને ટૂંક સમયમાં તમારા ગામ સુધી પહોંચશે.',
        'step_4' => '૪. ડિલિવરી પૂર્ણ',
        'desc_4_delivered' => 'ઓર્ડર સફળતાપૂર્વક પહોંચી ગયો છે!',
        'desc_4_pending' => 'ડિલિવરી બાકી છે.',
        'cancelled_alert' => 'આ ઓર્ડર રદ કરવામાં આવ્યો છે (Cancelled).'
    ],
    'hi' => [
        'welcome' => 'स्वागत है',
        'village' => 'गाँव',
        'land' => 'भूमि',
        'latest_product' => 'नवीनतम ट्रैक किया गया उत्पाद',
        'order_status' => 'ऑर्डर प्रोसेसिंग स्थिति',
        'quick_menu' => 'त्वरित संचालन मेनू',
        'marketplace' => '१. इनपुट मार्केटप्लेस',
        'order_history' => '२. लाइव ऑर्डर इतिहास',
        'track_order' => '३. ऑर्डर ट्रैक करें',
        'agri_experts' => '४. कृषि विशेषज्ञ ', // 🌟 Added Agri Experts Index
        'about' => '५. हमारे बारे में',
        'logout' => 'बाहर निकलें (Logout)',
        'select_order' => 'अपना ऑर्डर चुनें',
        'no_orders' => 'कोई ऑर्डर नहीं मिला।',
        'select_prompt' => 'ट्रैक करने के लिए बाईं सूची से कोई भी ऑर्डर चुनें।',
        'order_tracking_title' => 'ऑर्डर ट्रैकिंग',
        'product' => 'उत्पाद',
        'order_date' => 'ऑर्डर तिथि',
        'est_delivery' => 'अनुमानित डिलीवरी',
        'live_timeline' => 'लाइव प्रोग्रेस टाइमलाइन',
        'step_1' => '१. ऑर्डर दिया गया',
        'desc_1' => 'Agro Input Hub द्वारा आपका ऑर्डर सफलतापूर्वक प्राप्त कर लिया गया है।',
        'step_2' => '२. ऑर्डर स्वीकृत और प्रोसेस हो रहा है',
        'desc_2_pending' => 'एडमिन द्वारा सत्यापन जारी है (Pending)...',
        'desc_2_approved' => 'ऑर्डर स्वीकृत हो गया है और सामान पैक किया जा रहा है।',
        'step_3' => '३. डिलीवरी के लिए रवाना',
        'desc_3' => 'पैकेज भेज दिया गया है और जल्द ही आपके गाँव पहुँच जाएगा।',
        'step_4' => '४. सफलतापूर्वक डिलीवर किया गया',
        'desc_4_delivered' => 'ऑर्डर सफलतापूर्वक डिलीवर कर दिया गया है!',
        'desc_4_pending' => 'डिलीवरी बाकी है।',
        'cancelled_alert' => 'यह ऑर्डर रद्द कर दिया गया है।'
    ]
];

function __($key) {
    global $lang_arr;
    $lang = $_SESSION['lang'];
    return isset($lang_arr[$lang][$key]) ? $lang_arr[$lang][$key] : $key;
}
?>