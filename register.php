<?php
// admin/register.php - Admin Registration Page with Password Toggle & Perfect CSS
require_once '../config/db_config.php';
$message = "";

if (isset($_POST['admin_reg_btn'])) {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];
    $role = $_POST['role'];

    // સર્વર-સાઇડ વેલિડેશન
    if (strlen($user) < 4) {
        $message = "<div class='alert alert-danger'>❌ યુઝરનેમ ઓછામાં ઓછા 4 અક્ષરનું હોવું જોઈએ!</div>";
    } elseif (strlen($pass) < 8) {
        $message = "<div class='alert alert-danger'>🔒 પાસવર્ડ ઓછામાં ઓછા 8 અક્ષરનો હોવો જોઈએ!</div>";
    } elseif ($pass !== $confirm_pass) {
        $message = "<div class='alert alert-danger'>❌ પાસવર્ડ મેચ થતા નથી!</div>";
    } else {
        try {
            // પાસવર્ડ હેશિંગ (સુરક્ષા માટે)[cite: 10]
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO shop_users (username, password, role_id) VALUES (?, ?, ?)");
            if ($stmt->execute([$user, $hashed_pass, $role])) {
                $message = "<div class='alert alert-success'>🎉 રજીસ્ટ્રેશન સફળ રહ્યું! <a href='admin_login.php'>Login Here</a></div>";
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>⚠️ Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; display: flex; justify-content: center; align-items: center; min-height: 100vh; font-family: sans-serif; }
        .reg-card { background-color: #1a1d20; border: 1px solid #2d3238; border-radius: 15px; width: 100%; max-width: 450px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .logo-box { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid #2d3238; padding-bottom: 20px; }
        .css-logo { width: 60px; height: 60px; background: linear-gradient(135deg, #28a745, #155724); color: white; display: flex; justify-content: center; align-items: center; font-size: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        .form-control, .form-select { background-color: #0f1112 !important; border: 1px solid #2d3238 !important; color: white !important; padding: 12px; margin-bottom: 15px; }
        
        /* 🎯 પરફેક્ટ પાસવર્ડ ઇનપુટ ગ્રુપ ડિઝાઇન (કોઈ ગેપ વગર) */
        .password-group {
            display: flex;
            align-items: center;
            background-color: #0f1112 !important;
            border: 1px solid #2d3238 !important;
            border-radius: 8px !important;
            overflow: hidden;
            margin-bottom: 15px;
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

        .btn-reg { background-color: #198754; color: white; border: none; padding: 12px; width: 100%; font-weight: bold; border-radius: 8px; }
        .footer-links { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; font-size: 14px; }
    </style>
</head>
<body>

<div class="reg-card">
    <?php if(!empty($message)) echo $message; ?>
    
    <div class="logo-box">
        <div class="css-logo"><i class="fa-solid fa-user-gear"></i></div>
        <div class="logo-text">
            <h4 class="text-white m-0">Agro Input Hub</h4>
            <p class="text-warning small fw-bold text-uppercase m-0">ADMIN REGISTRATION PORTAL</p>
        </div>
    </div>
    
    <form name="regForm" method="POST" onsubmit="return validateRegForm()">
        <label class="text-white fw-bold mb-1 small">Select Admin Role *</label>
        <select name="role" class="form-select" required>
            <option value="1">Super Admin</option>
            <option value="2">Shop Staff</option>
        </select>

        <label class="text-white fw-bold mb-1 small">Username *</label>
        <input type="text" name="username" class="form-control" placeholder="Create Unique Username" required>

        <label class="text-white fw-bold mb-1 small">Password *</label>
        <div class="input-group password-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="Min 8 chars (Upper, Num, Symbol)" required>
            <span class="input-group-text" id="togglePassword" title="Show/Hide Password">
                <i class="fa-solid fa-eye" id="eyeIcon"></i>
            </span>
        </div>

        <label class="text-white fw-bold mb-1 small">Confirm Password *</label>
        <div class="input-group password-group">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-type password" required>
            <span class="input-group-text" id="toggleConfirmPassword" title="Show/Hide Password">
                <i class="fa-solid fa-eye" id="eyeConfirmIcon"></i>
            </span>
        </div>

        <button type="submit" name="admin_reg_btn" class="btn-reg">
            <i class="fa-solid fa-user-plus me-1"></i> Register Admin Account
        </button>
    </form>
    
    <div class="footer-links">
        <a href="admin_login.php" class="text-success text-decoration-none fw-bold">Already registered? Sign-In</a>
        <a href="index.php" class="text-white text-decoration-none"><i class="fa-solid fa-house me-1"></i> Portal Home</a>
    </div>
</div>

<script>
function validateRegForm() {
    let password = document.forms["regForm"]["password"].value;
    let confirm_password = document.forms["regForm"]["confirm_password"].value;
    
    let hasUppercase = /[A-Z]/.test(password);
    let hasNumber = /[0-9]/.test(password);
    let hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    
    if (password.length < 8) {
        alert("🔒 Password must be at least 8 characters long!");
        return false;
    }
    if (!hasUppercase || !hasNumber || !hasSymbol) {
        alert("🛡️ Password must contain at least one Uppercase letter, one Number, and one Symbol!");
        return false;
    }
    if (password !== confirm_password) {
        alert("❌ Passwords do not match!");
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

// 🎯 કન્ફર્મ પાસવર્ડ શૉ અને હાઇડ કરવા માટેનું જાવાસ્ક્રિપ્ટ લોજિક
const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const confirmPasswordInput = document.getElementById('confirm_password');
const eyeConfirmIcon = document.getElementById('eyeConfirmIcon');

toggleConfirmPassword.addEventListener('click', function () {
    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordInput.setAttribute('type', type);
    if (type === 'text') {
        eyeConfirmIcon.classList.remove('fa-eye');
        eyeConfirmIcon.classList.add('fa-eye-slash');
    } else {
        eyeConfirmIcon.classList.remove('fa-eye-slash');
        eyeConfirmIcon.classList.add('fa-eye');
    }
});
</script>
</body>
</html>