<?php
// farmer_register.php - Secure Farmer Registration Page with Email, Strict Validation & Password Toggle
require_once __DIR__ . '/../config/db_config.php';
session_start();

$message = "";

if (isset($_POST['register_btn'])) {
    $farmer_name   = trim($_POST['farmer_name']);
    $email         = trim($_POST['email']); // 🎯 ઈમેલ ફીલ્ડ મેળવો
    $phone_number  = trim($_POST['phone_number']);
    $village       = trim($_POST['village']);
    $land_vigha    = floatval($_POST['land_vigha']);
    $password      = $_POST['password'];
    $confirm_pass  = $_POST['confirm_password'];

    if (empty($farmer_name) || empty($email) || empty($phone_number) || empty($village) || empty($land_vigha) || empty($password)) {
        $message = "<div class='alert alert-danger fw-bold text-center small py-2'>❌ All mandatory parameters must be completed!</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger fw-bold text-center small py-2'>❌ Please enter a valid email address!</div>";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone_number)) {
        $message = "<div class='alert alert-danger fw-bold text-center small py-2'>❌ Mobile number mapping must be exactly 10 digits!</div>";
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9]/', $password)) {
        $message = "<div class='alert alert-danger fw-bold text-center small py-2'>❌ Password must contain 1 Uppercase, 1 Number, and 1 Special symbol!</div>";
    } elseif ($password !== $confirm_pass) {
        $message = "<div class='alert alert-danger fw-bold text-center small py-2'>❌ Password configuration check mismatch!</div>";
    } else {
        try {
            $chk_stmt = $conn->prepare("SELECT farmer_id FROM farmers WHERE phone_number = ? OR email = ?");
            $chk_stmt->execute([$phone_number, $email]);
            
            if ($chk_stmt->rowCount() > 0) {
                $message = "<div class='alert alert-warning fw-bold text-center small py-2'>⚠️ Identity Error: This mobile number or email is already registered!</div>";
            } else {
                // 🎯 ડેટાબેઝમાં email કોલમ સાથે ઇન્સર ક્વારી
                $ins_query = "INSERT INTO farmers (farmer_name, email, phone_number, village, land_vigha, password) VALUES (?, ?, ?, ?, ?, ?)";
                $ins_stmt = $conn->prepare($ins_query);
                
                if ($ins_stmt->execute([$farmer_name, $email, $phone_number, $village, $land_vigha, $password])) {
                    $message = "<div class='alert alert-success fw-bold text-center small py-2'>🎉 <strong>Registration Successful!</strong> Account activated. Redirecting...</div>";
                    header("refresh:2;url=farmer_login.php");
                }
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger fw-bold text-center small py-2'>❌ System Fault: " . $e->getMessage() . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registration | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { 
            background-color: #0d0f11; 
            color: #e2e8f0; 
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px; 
        }
        .card-custom { 
            background: #191c1f !important; 
            border: 1px solid #2d3238 !important; 
            border-radius: 15px !important; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
        }
        .register-card { max-width: 460px; width: 100%; border-radius: 15px; overflow: hidden; }
        
        .form-control {
            background-color: #0d0f11 !important;
            border: 1px solid #2d3238 !important;
            color: #ffffff !important;
        }
        .form-control:focus {
            border-color: #28a745 !important;
            box-shadow: 0 0 8px rgba(40,167,69,0.2) !important;
        }
        .error-text { color: #dc3545; font-size: 11px; font-weight: 600; margin-top: 4px; display: none; }

        .password-group .input-group-text {
            background-color: #0d0f11 !important;
            border: 1px solid #2d3238 !important;
            color: #a0aec0 !important;
            cursor: pointer;
        }
        .password-group .input-group-text:hover {
            color: #28a745 !important;
        }
    </style>
</head>
<body>

    <div class="card card-custom register-card p-4 animate__animated animate__fadeInUp">
        <div class="text-center mb-3 border-bottom border-secondary pb-3">
            <div class="premium-logo-container d-inline-flex align-items-center justify-content-center text-start mb-2">
                <div class="premium-logo-icon-wrapper">
                    <i class="fa-solid fa-wheat-awn"></i>
                </div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Input</span> Hub</div>
                    <div class="logo-sub-text">Digital Kisan Portal</div>
                </div>
            </div>   
            <br>
            <span class="text-muted small fw-bold">Open Digital Kisan Ledger Account</span>
        </div>

        <?php echo $message; ?>

        <form action="farmer_register.php" method="POST" id="regForm" onsubmit="return validateRegisterForm()">
            <div class="mb-2">
                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-user me-1"></i> Farmer Full Name <span class="text-danger">*</span></label>
                <input type="text" name="farmer_name" id="farmer_name" class="form-control form-control-sm text-white font-monospace fw-bold" placeholder="e.g. Ramesh Patel" required>
            </div>

            <!-- 🎯 ઈમેલ એડ્રેસ ઇનપુટ ફીલ્ડ -->
            <div class="mb-2">
                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-envelope me-1"></i> Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control form-control-sm text-white" placeholder="e.g. ramesh@gmail.com" required>
            </div>

            <div class="mb-2">
                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-phone me-1"></i> Mobile Number (Login ID) <span class="text-danger">*</span></label>
                <input type="text" name="phone_number" id="phone_number" class="form-control form-control-sm text-success fw-bold fs-6 text-center" maxlength="10" placeholder="10 Digits Mandatory" required>
                <div id="phone_error" class="error-text">❌ Please verify entry matches a clean 10-digit number structure!</div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-6">
                    <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-location-dot me-1"></i> Village Name <span class="text-danger">*</span></label>
                    <input type="text" name="village" id="village" class="form-control form-control-sm text-white" placeholder="e.g. Kondh" required>
                </div>
                <div class="col-6">
                    <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-mountain me-1"></i> Operating Land (Vigha) <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" name="land_vigha" id="land_vigha" class="form-control form-control-sm text-white" placeholder="e.g. 12.5" required>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-lock me-1"></i> Establish Password <span class="text-danger">*</span></label>
                <div class="input-group password-group">
                    <input type="password" name="password" id="password" class="form-control form-control-sm" placeholder="Minimum 6 characters" required>
                    <span class="input-group-text" id="togglePassword" title="Show/Hide Password">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                <div id="password_error" class="error-text">❌ Must include 1 UpperCase, 1 Number, and 1 Special Character!</div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-circle-check me-1"></i> Confirm Secret Password <span class="text-danger">*</span></label>
                <div class="input-group password-group">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-sm" placeholder="Re-type password" required>
                    <span class="input-group-text" id="toggleConfirmPassword" title="Show/Hide Password">
                        <i class="fa-solid fa-eye" id="eyeConfirmIcon"></i>
                    </span>
                </div>
                <div id="confirm_error" class="error-text">❌ Input mismatch detected. Passwords must be identical!</div>
            </div>

            <button type="submit" name="register_btn" class="btn btn-success w-100 fw-bold shadow-sm mb-3 rounded-pill py-2">
                <i class="fa-solid fa-user-plus"></i> Submit Registration Record
            </button>

            <div class="text-center small border-top border-secondary pt-2">
                <span class="text-muted">Already registered?</span>
                <a href="farmer_login.php" class="text-success fw-bold text-decoration-none">Sign-In Here</a>
            </div>
        </form>
    </div>

    <script>
    function validateRegisterForm() {
        let phone = document.getElementById('phone_number').value.trim();
        let password = document.getElementById('password').value.trim();
        let confirmPassword = document.getElementById('confirm_password').value.trim();
        let isValid = true;

        let phonePattern = /^[0-9]{10}$/;
        if (!phonePattern.test(phone)) {
            document.getElementById('phone_error').style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('phone_error').style.display = 'none';
        }

        let hasUpper = /[A-Z]/.test(password);
        let hasNumber = /[0-9]/.test(password);
        let hasSpecial = /[^a-zA-Z0-9]/.test(password);

        if (!hasUpper || !hasNumber || !hasSpecial || password.length < 6) {
            document.getElementById('password_error').style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('password_error').style.display = 'none';
        }

        if (password !== confirmPassword || confirmPassword === "") {
            document.getElementById('confirm_error').style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('confirm_error').style.display = 'none';
        }

        return isValid;
    }

    document.getElementById('phone_number').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

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