<?php
// admin/admin_login.php - Admin Login Page with Password Toggle & Perfect CSS
require_once '../config/db_config.php';
session_start();

$message = "";

if (isset($_POST['admin_login_btn'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role_id  = $_POST['role'];

    // સર્વર-સાઇડ વેલિડેશન
    if (empty($username) || empty($password)) {
        $message = "<div class='alert alert-danger'>⚠️ Please fill in all fields!</div>";
    } elseif (strlen($password) < 6) {
        $message = "<div class='alert alert-danger'>🔒 Password must be at least 6 characters long!</div>";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM shop_users WHERE username = ? AND password = ? AND role_id = ? LIMIT 1");
            $stmt->execute([$username, $password, $role_id]);
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['admin_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id']  = $user['role_id'];
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $message = "<div class='alert alert-danger'>❌ Invalid Credentials or Role!</div>";
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>⚠️ Database Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; display: flex; justify-content: center; align-items: center; min-height: 100vh; font-family: sans-serif; }
        .login-card { background-color: #1a1d20; border: 1px solid #2d3238; border-radius: 15px; width: 100%; max-width: 450px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .logo-box { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; }
        .css-logo { width: 60px; height: 60px; background: linear-gradient(135deg, #28a745, #155724); color: white; display: flex; justify-content: center; align-items: center; font-size: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        .form-control, .form-select { background-color: #0f1112 !important; border: 1px solid #2d3238 !important; color: white !important; padding: 12px; margin-bottom: 20px; }
        
        /* 🎯 પરફેક્ટ પાસવર્ડ ઇનપુટ ગ્રુપ ડિઝાઇન (કોઈ ગેપ વગર) */
        .password-group {
            display: flex;
            align-items: center;
            background-color: #0f1112 !important;
            border: 1px solid #2d3238 !important;
            border-radius: 8px !important;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .password-group:focus-within {
            border-color: #28a745 !important;
            box-shadow: 0 0 8px rgba(40,167,69,0.2) !important;
        }
        .password-group .form-control {
            background-color: transparent !important;
            border: none !important;
            color: white !important;
            padding: 12px;
            box-shadow: none !important;
            margin-bottom: 0 !important;
            flex-grow: 1;
        }
        .password-group .input-group-text {
            background-color: transparent !important;
            border: none !important;
            color: #a0aec0 !important;
            cursor: pointer;
            padding: 0 15px;
        }
        .password-group .input-group-text:hover { color: #28a745 !important; }

        .btn-auth { background-color: #198754; color: white; border: none; padding: 12px; width: 100%; font-weight: bold; border-radius: 8px; }
        .footer-links { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
    </style>
</head>
<body>

<div class="login-card">
    <?php if(!empty($message)) echo $message; ?>
    
    <div class="logo-box">
        <div class="css-logo"><i class="fa-solid fa-user-gear"></i></div>
        <div class="logo-text">
            <h4 class="text-white m-0">Agro Input Hub</h4>
            <p class="text-muted small m-0">ADMIN CONTROL PORTAL</p>
        </div>
    </div>

    <form name="loginForm" method="POST" onsubmit="return validateForm()">
        <label class="form-label text-light small fw-bold">Admin Role</label>
        <select name="role" class="form-select" required>
            <option value="1">Super Admin</option>
            <option value="2">Shop Staff</option>
        </select>

        <label class="form-label text-light small fw-bold">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter Username" required>

        <label class="form-label text-light small fw-bold">Security Password</label>
        <div class="input-group password-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            <span class="input-group-text" id="togglePassword" title="Show/Hide Password">
                <i class="fa-solid fa-eye" id="eyeIcon"></i>
            </span>
        </div>

        <button type="submit" name="admin_login_btn" class="btn-auth">
            <i class="fa-solid fa-shield-halved me-1"></i> Sign-In Authenticate
        </button>
    </form>

    <div class="footer-links">
        <a href="register.php" class="text-success text-decoration-none fw-bold small">Create New Account</a>
        <a href="index.php" class="text-white text-decoration-none small"><i class="fa-solid fa-house me-1"></i> Portal Home</a>
    </div>
</div>

<script>
function validateForm() {
    let username = document.forms["loginForm"]["username"].value;
    let password = document.forms["loginForm"]["password"].value;
    
    let hasUppercase = /[A-Z]/.test(password);
    let hasNumber = /[0-9]/.test(password);
    let hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    
    if (username == "" || password == "") {
        alert("⚠️ Please fill in all fields!");
        return false;
    }
    
    if (password.length < 8) {
        alert("🔒 Password must be at least 8 characters long!");
        return false;
    }
    if (!hasUppercase) {
        alert("⬆️ Password must contain at least one Uppercase letter!");
        return false;
    }
    if (!hasNumber) {
        alert("🔢 Password must contain at least one Number!");
        return false;
    }
    if (!hasSymbol) {
        alert("🛡️ Password must contain at least one Symbol!");
        return false;
    }
    
    return true;
}

// 🎯 પાસવર્ડ શૉ અને હાઇડ કરવા માટેનું જાવાસ્ક્રિપ્ટ લોજિક
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');

togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    if (type === 'text') {
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
});
</script>
</body>
</html>