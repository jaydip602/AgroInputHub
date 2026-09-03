<?php
// admin/logout.php - Secure Admin Logout
session_start();

// એડમિનના સેશન વેરિએબલ્સ ખાલી કરવા
$_SESSION = array();

// જો સેશન કુકીઝ હોય તો તેને પણ નાશ કરવી
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// સેશન પૂરો કરવો
session_destroy();

// 🎯 લોગઆઉટ થયા પછી એડમિનના મેઈન ઇન્ડેક્સ (લોગિન) પેજ પર મોકલવો
header("Location: index.php");
exit;
?>