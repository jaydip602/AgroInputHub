<?php
// forgot_password.php - 3 Step Secure Password Recovery with PHPMailer Gmail OTP
require_once __DIR__ . '/../config/db_config.php';
session_start();

// PHPMailer Correct Path Include
require_once __DIR__ . '/../includes/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../includes/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../includes/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";
$step = $_SESSION['recovery_step'] ?? 1; 

// સ્ટેપ ૧: મોબાઈલ અને વિલેજ વેરિફિકેશન કરીને PHPMailer દ્વારા Gmail પર OTP મોકલવો
if (isset($_POST['send_otp_btn'])) {
    $mobile = trim($_POST['phone_number']);
    $village = trim($_POST['village_name']);

    if (empty($mobile) || empty($village)) {
        $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2 animate__animated animate__shakeX'>❌ Please provide both Mobile Number and Village Name!</div>";
    } else {
        try {
            $stmt = $conn->prepare("SELECT farmer_id, farmer_name, phone_number FROM farmers WHERE phone_number = ? AND LOWER(village) = LOWER(?) LIMIT 1");
            $stmt->execute([$mobile, $village]);
            $farmer = $stmt->fetch();

            if ($farmer) {
                $otp = rand(100000, 999999);
                
                $_SESSION['reset_farmer_id'] = $farmer['farmer_id'];
                $_SESSION['generated_otp'] = $otp;
                $_SESSION['otp_expiry'] = time() + 300; // 5 મિનિટ વેલિડ

                // 📧 PHPMailer Setup માટે Gmail SMTP ગોઠવણી
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'jaydipparmar2042006@gmail.com'; // 👈 તમારું જીમેઈલ અહીં નાખો
                    $mail->Password   = 'kbtr dlwi nrgu xhoy';    // 👈 જીમેઈલની 16 અંકની App Password અહીં નાખો
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    // Recipients
                    $mail->setFrom('your_email@gmail.com', 'Agro Input Hub');
                    $mail->addAddress('your_email@gmail.com', $farmer['farmer_name']); // ટેસ્ટિંગ માટે તમારું ઈમેઈલ એડ્રેસ લખો

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Recovery OTP - Agro Input Hub';
                    $mail->Body    = "<h3>Hello " . htmlspecialchars($farmer['farmer_name']) . ",</h3>
                                      <p>Your OTP for password recovery is:</p>
                                      <h2 style='color: #28a745;'>" . $otp . "</h2>
                                      <p>This OTP is valid for 5 minutes. Do not share it with anyone.</p>";

                    $mail->send();

                    $_SESSION['recovery_step'] = 2;
                    $step = 2;
                    $message = "<div class='alert alert-success fw-bold border-0 text-center small p-2 animate__animated animate__fadeIn'>✅ Verification successful! OTP has been sent to your Gmail via PHPMailer.</div>";

                } catch (Exception $e) {
                    $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2'>❌ Mail Error: {$mail->ErrorInfo}</div>";
                }

            } else {
                $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2 animate__animated animate__shakeX'>❌ Verification failed! Incorrect Mobile Number or Village.</div>";
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2'>❌ System Error: " . $e->getMessage() . "</div>";
        }
    }
}

// સ્ટેપ ૨: OTP વેરિફિકેશન
if (isset($_POST['verify_otp_btn'])) {
    $entered_otp = trim($_POST['otp_code']);
    $step = 2;

    if (isset($_SESSION['generated_otp']) && $_SESSION['generated_otp'] == $entered_otp) {
        if (time() <= $_SESSION['otp_expiry']) {
            $_SESSION['otp_verified'] = true;
            $_SESSION['recovery_step'] = 3;
            $step = 3;
            $message = "<div class='alert alert-success fw-bold border-0 text-center small p-2 animate__animated animate__fadeIn'>✅ OTP Verified Successfully! Set your new password.</div>";
        } else {
            $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2 animate__animated animate__shakeX'>❌ OTP has expired! Please request a new one.</div>";
            $_SESSION['recovery_step'] = 1;
            $step = 1;
        }
    } else {
        $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2 animate__animated animate__shakeX'>❌ Invalid OTP Code! Please try again.</div>";
    }
}

// સ્ટેપ ૩: નવો પાસવર્ડ સેવ કરવો
if (isset($_POST['reset_btn']) && isset($_SESSION['reset_farmer_id']) && isset($_SESSION['otp_verified'])) {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $step = 3;

    if (empty($new_pass) || empty($confirm_pass)) {
        $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2 animate__animated animate__shakeX'>❌ Passwords cannot be empty!</div>";
    } elseif (strlen($new_pass) < 6) {
        $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2 animate__animated animate__shakeX'>❌ Password must be at least 6 characters long!</div>";
    } elseif ($new_pass !== $confirm_pass) {
        $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2 animate__animated animate__shakeX'>❌ Passwords do not match! Try again.</div>";
    } else {
        try {
            $upd_stmt = $conn->prepare("UPDATE farmers SET password = ? WHERE farmer_id = ?");
            if ($upd_stmt->execute([$new_pass, $_SESSION['reset_farmer_id']])) {
                unset($_SESSION['reset_farmer_id']);
                unset($_SESSION['generated_otp']);
                unset($_SESSION['otp_expiry']);
                unset($_SESSION['otp_verified']);
                unset($_SESSION['recovery_step']);

                header("Location: farmer_login.php?reset=success");
                exit;
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger fw-bold border-0 text-center small p-2'>❌ Reset Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important; }
        .recovery-card { max-width: 450px; width: 100%; overflow: hidden; }
        .recovery-header { background: #191c1f !important; padding: 25px; text-align: center; border-bottom: 1px solid #2d3238 !important; }
        .form-control { background-color: #0d0f11 !important; border: 1px solid #2d3238 !important; color: #ffffff !important; }
        .form-control:focus { border-color: #28a745 !important; box-shadow: 0 0 8px rgba(40,167,69,0.2) !important; }
        .recovery-card .form-control::placeholder { color: #a0aec0 !important; opacity: 0.7 !important; }
        .animated-key-box { background-color: #0d0f11 !important; border: 1px solid #2d3238 !important; }
        .password-group .input-group-text { background-color: #0d0f11 !important; border: 1px solid #2d3238 !important; color: #a0aec0 !important; cursor: pointer; }
        .password-group .input-group-text:hover { color: #28a745 !important; }
    </style>
</head>
<body>

    <div class="card card-custom recovery-card animate__animated animate__fadeInUp">
        <div class="recovery-header">
            <div class="premium-logo-container d-inline-flex align-items-center justify-content-center text-start mb-3">
                <div class="premium-logo-icon-wrapper"><i class="fa-solid fa-wheat-awn"></i></div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Input</span> Hub</div>
                    <div class="logo-sub-text">Digital Kisan Portal</div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-2">
                <div class="animated-key-box d-inline-block p-3 rounded-circle border border-warning shadow animate__animated animate__pulse animate__infinite">
                    <i class="fa-solid fa-envelope-circle-check text-warning fs-3"></i>
                </div>
            </div>
            <h4 class="fw-bold text-success mt-3 mb-1">Account Recovery</h4>
            <span class="text-muted small fw-bold">Secure PHPMailer Gmail OTP Verification.</span>
        </div>

        <div class="p-4">
            <?php echo $message; ?>

            <?php if($step == 1): ?>
                <form action="forgot_password.php" method="POST">
                    <p class="text-info small fw-bold text-center mb-3 font-monospace">Step 1: Verify your registered details</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-phone me-1"></i> Registered Mobile Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control text-success fw-bold text-center fs-5" maxlength="10" placeholder="10 Digit Number" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-location-dot me-1"></i> Your Registered Village</label>
                        <input type="text" name="village_name" class="form-control text-center text-white" placeholder="e.g. Kondh" required>
                    </div>

                    <button type="submit" name="send_otp_btn" class="btn btn-warning w-100 fw-bold shadow-sm mb-3 rounded-pill py-2.5 text-dark">
                        <i class="fa-solid fa-paper-plane me-1"></i> Send OTP via Gmail
                    </button>
                </form>

            <?php elseif($step == 2): ?>
                <form action="forgot_password.php" method="POST" class="animate__animated animate__fadeIn">
                    <p class="text-warning small fw-bold text-center mb-3 font-monospace">Step 2: Enter 6-digit OTP sent to Gmail</p>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-key me-1"></i> Enter Verification OTP</label>
                        <input type="text" name="otp_code" class="form-control text-center text-success fw-bold fs-3 font-monospace" maxlength="6" placeholder="------" required>
                    </div>

                    <button type="submit" name="verify_otp_btn" class="btn btn-success w-100 fw-bold shadow-sm mb-3 rounded-pill py-2.5">
                        <i class="fa-solid fa-check-circle me-1"></i> Verify OTP
                    </button>
                </form>

            <?php else: ?>
                <form action="forgot_password.php" method="POST" class="animate__animated animate__fadeIn">
                    <p class="text-success small fw-bold text-center mb-3 font-monospace">Step 3: Create New Password</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-lock me-1"></i> New Secure Password</label>
                        <div class="input-group password-group">
                            <input type="password" name="new_password" id="new_password" class="form-control text-center text-white" placeholder="Minimum 6 characters" required>
                            <span class="input-group-text" id="toggleNewPassword" title="Show/Hide Password">
                                <i class="fa-solid fa-eye" id="eyeNew"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-circle-check me-1"></i> Confirm New Password</label>
                        <div class="input-group password-group">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control text-center text-white" placeholder="Re-type password" required>
                            <span class="input-group-text" id="toggleConfirmPassword" title="Show/Hide Password">
                                <i class="fa-solid fa-eye" id="eyeConfirm"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" name="reset_btn" class="btn btn-success w-100 fw-bold shadow-sm mb-3 rounded-pill py-2.5">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save New Password
                    </button>
                </form>
            <?php endif; ?>

            <div class="text-center small border-top border-secondary pt-3 mt-2">
                <span class="text-muted">Remembered your password?</span>
                <a href="farmer_login.php" class="text-success fw-bold text-decoration-none ms-1">Back to Login</a>
            </div>
        </div>
    </div>

    <script>
    if(document.getElementById('phone_number')) {
        document.getElementById('phone_number').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    const toggleNewPassword = document.getElementById('toggleNewPassword');
    if (toggleNewPassword) {
        const newPasswordInput = document.getElementById('new_password');
        const eyeNew = document.getElementById('eyeNew');

        toggleNewPassword.addEventListener('click', function () {
            const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            newPasswordInput.setAttribute('type', type);
        });
    }

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    if (toggleConfirmPassword) {
        const confirmPasswordInput = document.getElementById('confirm_password');
        const eyeConfirm = document.getElementById('eyeConfirm');

        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
        });
    }
    </script>
</body>
</html>