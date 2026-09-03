<?php
// farmerside/farmer_logout.php
session_start();

// 1. બધા સેસન વેરીએબલ્સ ખાલી કરો
$_SESSION = array();

// 2. જો સેસન કૂકીઝ હોય તો તેને પણ નાશ કરો
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. સેસન ડિસ્ટ્રોય કરો
session_destroy();

// 4. ખેડૂતને લોગિન પેજ પર મોકલી દો
header("Location: index.php");
exit;
?>