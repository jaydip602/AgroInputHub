<?php
// db_config.php - database Connection using PDO Architectural Pattern

// ૧. ડેટાબેઝ સેટિંગ્સ કન્ફિગરેશન
$host     = "localhost";
$dbname   = "agro_input_hub";
$username = "root";
$password = ""; // જો XAMPP માં પાસવર્ડ રાખ્યો હોય તો અહીં લખવો, સામાન્ય રીતે ખાલી હોય છે.

try {
    // ૨. PDO ઓબ્જેક્ટ બનાવવો અને કનેક્શન ઓપન કરવું
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // ૩. Error Handling મોડ સેટ કરવો જેથી કોઈ ક્વેરીમાં ભૂલ હોય તો Exception પકડાય
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ૪. ડિફોલ્ટ ફેચ મોડ સેટ કરવો જેથી ડેટા ઓટોમેટિક Associative Array માં મળે
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    // જો કનેક્શન ફેલ થાય તો એક્ઝામિનર સમજી શકે તેવી સ્પષ્ટ એરર બતાવવી
    die("❌ ડેટાબેઝ જોડાણ નિષ્ફળ ગયું છે! કૃપા કરીને ચેક કરો: " . $e->getMessage());
}
?>