<?php
// includes/whatsapp.php - Send Invoice PDF via WhatsApp API

function sendWhatsAppPDF($target_phone, $pdf_url, $caption_text) {
    // ⚠️ અહીં તમારું WhatsApp API Endpoint અને Token નાખવું
    $api_url = "https://api.ultramsg.com/instance_xxxxxx/messages/document"; 
    $token   = "your_api_token_here"; 

    $formatted_phone = preg_replace('/[^0-9]/', '', $target_phone);
    if (strlen($formatted_phone) == 10) {
        $formatted_phone = "91" . $formatted_phone;
    }

    $data = [
        'token'    => $token,
        'to'       => $formatted_phone,
        'filename' => 'Tax_Invoice.pdf',
        'document' => $pdf_url, // PDF ફાઇલની પબ્લિક લિંક (URL) હોવી જરૂરી છે
        'caption'  => $caption_text
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
?>