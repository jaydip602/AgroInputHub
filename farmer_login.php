<?php
// farmer_login.php - Two-Factor Authentication (Password + Email OTP Verification via PHPMailer)
require_once __DIR__ . '/../config/db_config.php';
session_start();

// PHPMailer Include
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../includes/phpmailer-master/src/Exception.php';
require_once __DIR__ . '/../includes/phpmailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../includes/phpmailer-master/src/SMTP.php';

if (isset($_SESSION['farmer_id']) && !empty($_SESSION['farmer_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error_message = "";
$success_message = "";

// 📧 ઈમેલ માસ્કિંગ ફંક્શન (દા.ત. ja***95@gmail.com)
function maskEmailAddress($email) {
    if (empty($email)) return "registered email";
    $parts = explode("@", $email);
    $name = $parts[0];
    $domain = $parts[1] ?? '';
    $len = strlen($name);
    if ($len <= 3) {
        $masked_name = substr($name, 0, 1) . '***';
    } else {
        $masked_name = substr($name, 0, 2) . str_repeat('*', $len - 4) . substr($name, -2);
    }
    return $masked_name . '@' . $domain;
}

// 🔄 OTP કેન્સલ કરીને પાછું જવા માટે
if (isset($_GET['cancel_2fa'])) {
    unset($_SESSION['temp_2fa_farmer_id']);
    unset($_SESSION['temp_2fa_otp']);
    unset($_SESSION['temp_2fa_email']);
    unset($_SESSION['temp_2fa_name']);
    unset($_SESSION['temp_2fa_phone']);
    header("Location: farmer_login.php");
    exit;
}

// 🔐 પગલું ૧: મોબાઈલ નંબર અને પાસવર્ડ વેરિફિકેશન
if (isset($_POST['verify_credentials_btn'])) {
    $mobile   = trim($_POST['mobile_num']);
    $password = trim($_POST['farmer_password']);

    if (empty($mobile) || empty($password)) {
        $error_message = "⚠️ Please fill in all mandatory fields!";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $error_message = "❌ Mobile number must be exactly 10 digits long!";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM farmers WHERE phone_number = ? LIMIT 1");
            $stmt->execute([$mobile]);
            $farmer = $stmt->fetch();

            if ($farmer) {
                // પાસવર્ડ ચેક કરો
                if ($farmer['password'] === $password || password_verify($password, $farmer['password']) || md5($password) === $farmer['password']) {
                    
                    if (empty($farmer['email'])) {
                        $error_message = "❌ No email address is linked to this account! Please contact admin.";
                    } else {
                        // ૬ અંકનો OTP જનરેટ કરો
                        $generated_otp = rand(100000, 999999);
                        $_SESSION['temp_2fa_farmer_id'] = $farmer['farmer_id'];
                        $_SESSION['temp_2fa_name']      = $farmer['farmer_name'];
                        $_SESSION['temp_2fa_email']     = $farmer['email'];
                        $_SESSION['temp_2fa_phone']     = $farmer['phone_number'];
                        $_SESSION['temp_2fa_otp']       = $generated_otp;

                        // PHPMailer દ્વારા ઈમેલ મોકલો
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'jaydipparmar2042006@gmail.com';     // 🔑 તમારું Gmail ID અહીં લખો
                        $mail->Password   = 'kbtr dlwi nrgu xhoy';        // 🔑 Gmail 16-Digit App Password અહીં લખો
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;
                        $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

$mail->setFrom('your_email@gmail.com', 'Agro Input Hub Security');
$mail->addAddress($farmer['email'], $farmer['farmer_name']);
// ... બાકીનો કોડ એમનેમ રાખો
                        
                        $mail->isHTML(true);
                        $mail->Subject = "Login Verification OTP | Agro Input Hub";
                        $mail->Body    = "
                        <div style='background: #f4f6f8; padding: 25px; font-family: Arial, sans-serif;'>
                            <div style='max-width: 500px; background: #ffffff; padding: 30px; margin: auto; border-radius: 10px; border: 1px solid #e0e0e0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>
                                <div style='text-align: center; border-bottom: 2px solid #28a745; padding-bottom: 15px; margin-bottom: 20px;'>
                                    <h2 style='color: #28a745; margin: 0;'>Agro Input Hub</h2>
                                    <p style='color: #666; font-size: 12px; margin: 5px 0 0 0;'>2-Step Authentication Gateway</p>
                                </div>
                                <p style='font-size: 15px; color: #333;'>Hello <b>" . htmlspecialchars($farmer['farmer_name']) . "</b>,</p>
                                <p style='font-size: 14px; color: #555;'>We received a login request for your account. Please use the following 6-digit verification code to complete sign-in:</p>
                                <div style='background: #e8f5e9; padding: 15px; text-align: center; font-size: 26px; font-weight: bold; letter-spacing: 6px; color: #2e7d32; margin: 25px 0; border: 1.5px dashed #2e7d32; border-radius: 6px;'>
                                    {$generated_otp}
                                </div>
                                <p style='color: #888; font-size: 12px; line-height: 1.5;'>This code is confidential. If you did not attempt to login, please secure your password immediately.</p>
                            </div>
                        </div>";

                        $mail->send();
                        $masked = maskEmailAddress($farmer['email']);
                        $success_message = "📬 Password verified! A 6-digit login OTP has been dispatched to <b>$masked</b>. Please check your Inbox / Spam folder.";
                    }

                } else {
                    $error_message = "❌ Invalid security password credentials!";
                }
            } else {
                $error_message = "❌ This mobile number is not registered!";
            }
        } catch (Exception $e) {
            $error_message = "⚠️ Mailer / System Exception: " . $e->getMessage();
        }
    }
}

// ✅ પગલું ૨: Email OTP વેરિફિકેશન અને ફાઇનલ લોગઇન
if (isset($_POST['verify_email_otp_btn'])) {
    $entered_otp = trim($_POST['entered_otp']);

    if (empty($entered_otp)) {
        $error_message = "❌ Please enter the 6-digit OTP code received in your email!";
    } elseif (isset($_SESSION['temp_2fa_otp']) && $_SESSION['temp_2fa_otp'] == $entered_otp) {
        // OTP સાચો છે -> Session set કરો
        $_SESSION['farmer_id']    = $_SESSION['temp_2fa_farmer_id'];
        $_SESSION['farmer_name']  = $_SESSION['temp_2fa_name'];
        $_SESSION['phone_number'] = $_SESSION['temp_2fa_phone'];

        unset($_SESSION['temp_2fa_farmer_id']);
        unset($_SESSION['temp_2fa_otp']);
        unset($_SESSION['temp_2fa_email']);
        unset($_SESSION['temp_2fa_name']);
        unset($_SESSION['temp_2fa_phone']);

        header("Location: dashboard.php");
        exit;
    } else {
        $error_message = "❌ Invalid verification OTP! Please check your email and enter the correct code.";
    }
}

$is_2fa_step = isset($_SESSION['temp_2fa_otp']) && !empty($_SESSION['temp_2fa_otp']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Login 2FA | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js" defer></script>
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
        .login-card { max-width: 440px; width: 100%; overflow: hidden; }
        
        .login-header { 
            background: #191c1f !important; 
            padding: 20px; 
            text-align: center; 
            border-bottom: 1px solid #2d3238 !important; 
        }
        
        .form-control {
            background-color: #0d0f11 !important;
            border: 1px solid #2d3238 !important;
            color: #ffffff !important;
        }
        .form-control:focus {
            border-color: #28a745 !important;
            box-shadow: 0 0 8px rgba(40,167,69,0.2) !important;
        }
        
        .password-group .input-group-text {
            background-color: #0d0f11 !important;
            border: 1px solid #2d3238 !important;
            color: #a0aec0 !important;
            cursor: pointer;
        }
        .password-group .input-group-text:hover { color: #28a745 !important; }
        
        .step-badge {
            display: inline-block;
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
            border: 1px solid #28a745;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="card card-custom login-card animate__animated animate__fadeInUp">
        <div class="login-header">
            <div class="premium-logo-container d-inline-flex align-items-center justify-content-center text-start">
                <div class="premium-logo-icon-wrapper">
                    <i class="fa-solid fa-wheat-awn"></i>
                </div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Input</span> Hub</div>
                    <div class="logo-sub-text">2-Step Email OTP Login</div>
                </div>
            </div>   
        </div>

        <div class="p-4">
            <?php if(!empty($success_message)): ?>
                <div class="alert alert-success text-center fw-bold border-0 shadow-sm small py-2 mb-3">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($error_message)): ?>
                <div class="alert alert-danger text-center fw-bold border-0 shadow-sm small py-2 mb-3 animate__animated animate__shakeX">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if(!$is_2fa_step): ?>
                <!-- 🔑 STEP 1: MOBILE NUMBER & PASSWORD FORM -->
                <div class="text-center">
                    <span class="step-badge"><i class="fa-solid fa-lock me-1"></i> STEP 1: CREDENTIALS AUTHENTICATION</span>
                </div>

                <form action="farmer_login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-phone me-1"></i> Mobile Number</label>
                        <input type="text" name="mobile_num" id="mobile_num" class="form-control fw-bold text-success text-center fs-5" placeholder="Enter 10 Digits" maxlength="10" required autocomplete="off">
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-key me-1"></i> Security Password</label>
                        <div class="input-group password-group">
                            <input type="password" name="farmer_password" id="farmer_password" class="form-control text-center text-white" placeholder="••••••••" required>
                            <span class="input-group-text toggle-pwd" data-target="farmer_password" title="Show/Hide Password">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="text-end mb-4">
                        <a href="forgot_password.php" class="text-warning small fw-bold text-decoration-none">
                            <i class="fa-solid fa-circle-question me-1"></i> Forgot Password?
                        </a>
                    </div>

                    <button type="submit" name="verify_credentials_btn" class="btn btn-success w-100 py-2.5 rounded-pill shadow-sm mb-2 fw-bold">
                        Continue & Send OTP <i class="fa-solid fa-paper-plane ms-1"></i>
                    </button>
                </form>

            <?php else: ?>
                <!-- 📧 STEP 2: EMAIL OTP VERIFICATION FORM -->
                <div class="text-center">
                    <span class="step-badge" style="border-color:#ffc107; color:#ffc107; background:rgba(255,193,7,0.1);"><i class="fa-solid fa-envelope-circle-check me-1"></i> STEP 2: EMAIL OTP VERIFICATION</span>
                </div>

                <div class="text-center mb-3">
                    <p class="small text-muted mb-1">Enter the 6-digit code dispatched to:</p>
                    <div class="fw-bold text-info font-monospace fs-6"><?php echo htmlspecialchars(maskEmailAddress($_SESSION['temp_2fa_email'] ?? '')); ?></div>
                </div>

                <form action="farmer_login.php" method="POST">
                    <div class="mb-3">
                        <input type="text" name="entered_otp" id="entered_otp" class="form-control fw-bold font-monospace text-center fs-3 text-warning" placeholder="• • • • • •" maxlength="6" required autofocus autocomplete="off">
                    </div>

                    <button type="submit" name="verify_email_otp_btn" class="btn btn-success w-100 py-2.5 rounded-pill shadow-sm mb-3 fw-bold">
                        <i class="fa-solid fa-circle-check me-1"></i> Verify OTP & Sign In
                    </button>

                    <div class="text-center">
                        <a href="farmer_login.php?cancel_2fa=1" class="text-secondary small text-decoration-none">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Password Login
                        </a>
                    </div>
                </form>
            <?php endif; ?>

            <hr class="opacity-25 border-secondary">

            <div class="d-flex justify-content-between text-center mt-3">
                <a href="farmer_register.php" class="text-success fw-bold text-decoration-none small">Create New Account</a>
                <a href="index.php" class="text-muted text-decoration-none small"><i class="fa-solid fa-house"></i> Portal Home</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Numbers only input filter
    const mobInput = document.getElementById('mobile_num');
    if (mobInput) {
        mobInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    const otpInput = document.getElementById('entered_otp');
    if (otpInput) {
        otpInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Password show/hide toggle
    document.querySelectorAll('.toggle-pwd').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const pwdField = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (pwdField.getAttribute('type') === 'password') {
                pwdField.setAttribute('type', 'text');
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwdField.setAttribute('type', 'password');
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    </script>
</body>
</html>