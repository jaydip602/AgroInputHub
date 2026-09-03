
<?php
// auth.php
// જો સેશન પહેલેથી શરૂ ન હોય તો જ તેને શરૂ કરો
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. ચેક કરો કે યુઝર લોગિન છે કે નહીં
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
// ... બાકીનું લોજિક ...
// 2. એડમિન પરમિશન ચેક કરવા માટેનું ફંક્શન
function checkAdminPermission() {
    if ($_SESSION['role_id'] != 1) { // 1 = Super Admin
        die("<html><body style='background:#0d0f11; color:white; text-align:center; padding-top:50px;'>
              <h2>Access Denied!</h2>
              <p>તમારી પાસે આ પેજ જોવાની પરમિશન નથી.</p>
              <a href='admin_dashboard.php' style='color:#28a745;'>Dashboard પર પાછા જાઓ</a>
              </body></html>");
    }
}
?>