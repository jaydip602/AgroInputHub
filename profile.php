<?php
// profile.php - Luxury Glass-Theme (With Camera & File Profile Photo Upload, Email/Phone Update & Privacy Center)
require_once '../config/db_config.php';
session_start();

// PHPMailer Include for OTP Mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../includes/phpmailer-master/src/Exception.php';
require_once __DIR__ . '/../includes/phpmailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../includes/phpmailer-master/src/SMTP.php';

if(!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}
$farmer_id = $_SESSION['farmer_id'];
$message = "";

// 📸 Upload Directory Path Setup
$upload_dir = '../uploads/profile/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

try {
    $stmt = $conn->prepare("SELECT * FROM farmers WHERE farmer_id = ? LIMIT 1");
    $stmt->execute([$farmer_id]);
    $farmer = $stmt->fetch();

    if(!$farmer) {
        die("Profile dataset sync error.");
    }
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// 📸 1. Handle Profile Image Upload (File / Gallery OR Live Camera)
if (isset($_POST['save_photo_btn'])) {
    $saved_image_name = null;

    // Option A: Live Camera Snapshot (Base64)
    if (!empty($_POST['camera_photo_data'])) {
        $base64_data = $_POST['camera_photo_data'];
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_data, $type)) {
            $base64_data = substr($base64_data, strpos($base64_data, ',') + 1);
            $type = strtolower($type[1]);
            $decoded_image = base64_decode($base64_data);
            if ($decoded_image !== false) {
                $saved_image_name = 'farmer_' . $farmer_id . '_' . time() . '.png';
                file_put_contents($upload_dir . $saved_image_name, $decoded_image);
            }
        }
    }
    // Option B: File Upload (From Storage / Gallery)
    elseif (isset($_FILES['profile_file']) && $_FILES['profile_file']['error'] === 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $file_ext = strtolower(pathinfo($_FILES['profile_file']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            $saved_image_name = 'farmer_' . $farmer_id . '_' . time() . '.' . $file_ext;
            move_uploaded_file($_FILES['profile_file']['tmp_name'], $upload_dir . $saved_image_name);
        } else {
            $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Invalid file type! Allowed formats: JPG, PNG, WEBP.</div>";
        }
    }

    // Save to Database
    if ($saved_image_name) {
        try {
            $upd_photo = $conn->prepare("UPDATE farmers SET profile_image = ? WHERE farmer_id = ?");
            $upd_photo->execute([$saved_image_name, $farmer_id]);
            $farmer['profile_image'] = $saved_image_name;
            $message = "<div class='alert alert-success fw-bold border-0 shadow'>🎉 Profile photo updated successfully!</div>";
        } catch (Exception $e) {
            // Auto-add column if not exists
            try {
                $conn->exec("ALTER TABLE farmers ADD COLUMN profile_image VARCHAR(255) NULL");
                $upd_photo = $conn->prepare("UPDATE farmers SET profile_image = ? WHERE farmer_id = ?");
                $upd_photo->execute([$saved_image_name, $farmer_id]);
                $farmer['profile_image'] = $saved_image_name;
                $message = "<div class='alert alert-success fw-bold border-0 shadow'>🎉 Profile photo updated successfully!</div>";
            } catch (Exception $ex) {
                $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ DB Error: " . $ex->getMessage() . "</div>";
            }
        }
    }
}

// 🎯 Email & Phone Number Update Logic[cite: 6]
if (isset($_POST['update_contact_btn'])) {
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone_number']);

    if (empty($new_email) || empty($new_phone)) {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Email and Phone number cannot be empty!</div>";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Please enter a valid email address!</div>";
    } elseif (!preg_match('/^[0-9]{10}$/', $new_phone)) {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Mobile number must be exactly 10 digits!</div>";
    } else {
        try {
            $chk = $conn->prepare("SELECT farmer_id FROM farmers WHERE (email = ? OR phone_number = ?) AND farmer_id != ?");
            $chk->execute([$new_email, $new_phone, $farmer_id]);
            
            if ($chk->rowCount() > 0) {
                $message = "<div class='alert alert-warning fw-bold border-0 shadow'>⚠️ This email or mobile number is already registered by another user!</div>";
            } else {
                $upd_contact = $conn->prepare("UPDATE farmers SET email = ?, phone_number = ? WHERE farmer_id = ?");
                if ($upd_contact->execute([$new_email, $new_phone, $farmer_id])) {
                    $message = "<div class='alert alert-success fw-bold border-0 shadow'>🎉 Contact credentials updated successfully!</div>";
                    $farmer['email'] = $new_email;
                    $farmer['phone_number'] = $new_phone;
                }
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Error: " . $e->getMessage() . "</div>";
        }
    }
}

// Password Update Engine Logic[cite: 6]
if (isset($_POST['update_pwd_btn'])) {
    $current_pwd = $_POST['current_password'];
    $new_pwd     = $_POST['new_password'];
    $confirm_pwd = $_POST['confirm_password'];

    if (empty($current_pwd) || empty($new_pwd) || empty($confirm_pwd)) {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ All input fields are required!</div>";
    } elseif ($farmer['password'] !== $current_pwd && !password_verify($current_pwd, $farmer['password']) && md5($current_pwd) !== $farmer['password']) {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Current security credentials mismatch!</div>";
    } elseif (strlen($new_pwd) < 6) {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ New security hash must be at least 6 characters!</div>";
    } elseif ($new_pwd !== $confirm_pwd) {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Confirm password does not match!</div>";
    } else {
        try {
            $upd_stmt = $conn->prepare("UPDATE farmers SET password = ? WHERE farmer_id = ?");
            if ($upd_stmt->execute([$new_pwd, $farmer_id])) {
                $message = "<div class='alert alert-success fw-bold border-0 shadow'>🎉 Password credentials updated successfully!</div>";
                $farmer['password'] = $new_pwd;
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Error: " . $e->getMessage() . "</div>";
        }
    }
}

// 🟡 De-activate Account Logic[cite: 6]
if (isset($_POST['deactivate_account_btn'])) {
    try {
        $upd_status = $conn->prepare("UPDATE farmers SET status = 'Inactive' WHERE farmer_id = ?");
        $upd_status->execute([$farmer_id]);
        session_unset();
        session_destroy();
        header("Location: farmer_login.php?deactivated=1");
        exit;
    } catch (Exception $e) {
        session_unset();
        session_destroy();
        header("Location: farmer_login.php?deactivated=1");
        exit;
    }
}

// 📧 Step 1: Request Deletion OTP via Email[cite: 6]
if (isset($_POST['request_delete_otp'])) {
    if (empty($farmer['email'])) {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Cannot send OTP: No email address is linked with this account! Please update your email first.</div>";
    } else {
        $otp = rand(100000, 999999);
        $_SESSION['delete_account_otp'] = $otp;
        $_SESSION['otp_farmer_id'] = $farmer_id;

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@gmail.com';     // તમારું Gmail ID અહીં લખો[cite: 4, 6]
            $mail->Password   = 'your_app_password';        // Gmail 16-Digit App Password અહીં લખો[cite: 4, 6]
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('your_email@gmail.com', 'Agro Input Hub');
            $mail->addAddress($farmer['email'], $farmer['farmer_name']);

            $mail->isHTML(true);
            $mail->Subject = "Account Deletion OTP | Agro Input Hub";
            $mail->Body    = "
            <div style='background: #f8f9fa; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='max-width: 500px; background: #ffffff; padding: 25px; margin: auto; border-radius: 10px; border: 1px solid #ddd;'>
                    <h3 style='color: #dc3545; text-align: center;'>Account Deletion Request</h3>
                    <p>Dear <b>".htmlspecialchars($farmer['farmer_name'])."</b>,</p>
                    <p>We received a request to permanently delete your Agro Input Hub account. Use the verification code below to authorize this action:</p>
                    <div style='background: #f8f9fa; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #28a745; margin: 20px 0; border: 1px dashed #28a745; border-radius: 5px;'>
                        {$otp}
                    </div>
                    <p style='color: #666; font-size: 12px;'>If you did not request this, please ignore this email.</p>
                </div>
            </div>";

            $mail->send();
            $message = "<div class='alert alert-success fw-bold border-0 shadow'>📬 Verification OTP has been successfully sent to your email (" . htmlspecialchars($farmer['email']) . ")!</div>";
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Mail Error: {$mail->ErrorInfo}</div>";
        }
    }
}

// 🗑️ Step 2: Verify OTP and Permanently Delete Account[cite: 6]
if (isset($_POST['verify_and_delete_btn'])) {
    $entered_otp = trim($_POST['otp_code']);

    if (isset($_SESSION['delete_account_otp']) && $_SESSION['delete_account_otp'] == $entered_otp && $_SESSION['otp_farmer_id'] == $farmer_id) {
        try {
            $del_stmt = $conn->prepare("DELETE FROM farmers WHERE farmer_id = ?");
            if ($del_stmt->execute([$farmer_id])) {
                unset($_SESSION['delete_account_otp']);
                unset($_SESSION['otp_farmer_id']);
                session_unset();
                session_destroy();
                header("Location: farmer_login.php?account_deleted=1");
                exit;
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Cannot delete account due to active data dependencies!</div>";
        }
    } else {
        $message = "<div class='alert alert-danger fw-bold border-0 shadow'>❌ Invalid Verification OTP code entered! Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisan Profile | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
<script src="../assets/js/dynamic_bg.js"></script>
    
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 70px; padding-top: 100px; }
        
        .main-header-title { font-size: 2.2rem; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; }
        .main-header-subtitle { color: #718096; font-size: 0.95rem; margin-bottom: 35px; }

        .luxury-identity-card { 
            background: linear-gradient(135deg, #191c1f 0%, #0f1112 100%); 
            border: 1px solid #2d3238; 
            border-radius: 20px; 
            padding: 35px 25px; 
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        
        /* 📸 Profile Avatar Styling */
        .avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }
        .luxury-avatar-box { 
            width: 140px; 
            height: 140px; 
            border-radius: 50%; 
            background: #1a1f24; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            border: 3px solid #28a745;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
            overflow: hidden;
        }
        .luxury-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .luxury-avatar-box i { font-size: 4.5rem; color: #28a745; }

        /* Camera Edit Button Icon Overlay */
        .btn-edit-photo {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #28a745;
            color: white;
            border: 2px solid #191c1f;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }
        .btn-edit-photo:hover {
            background: #218838;
            transform: scale(1.1);
            color: white;
        }

        .farmer-title-name { font-size: 1.6rem; font-weight: 700; color: #ffffff; margin-bottom: 4px; }
        .farmer-type-tag { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #a0aec0; background: rgba(255,255,255,0.05); padding: 4px 14px; border-radius: 30px; display: inline-block; border: 1px solid #2d3238; }
        .identity-meta-text { color: #a0aec0 !important; font-weight: 600; }

        .glass-details-container { 
            background: #191c1f; 
            border: 1px solid #2d3238; 
            border-radius: 20px; 
            padding: 35px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        .glass-card-title { font-size: 1.25rem; font-weight: 700; color: #ffffff; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
        .glass-card-title i { color: #28a745; }
        .live-status-dot { width: 8px; height: 8px; background-color: #28a745; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #28a745; }

        .info-row-item { border-bottom: 1px solid #2d3238; padding-bottom: 15px; margin-bottom: 15px; }
        .info-row-item:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        .meta-label { font-size: 0.85rem; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; }
        .meta-value { font-size: 1.05rem; font-weight: 600; color: #ffffff; margin-top: 2px; }

        .luxury-security-card { background: #191c1f; border: 1px solid #2d3238; border-radius: 20px; padding: 35px; margin-top: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); }
        .luxury-security-card .form-label { color: #a0aec0 !important; font-weight: 600 !important; }

        .form-control {
            background-color: #0d0f11 !important;
            border: 1px solid #2d3238 !important;
            color: white !important;
        }
        .form-control:focus {
            border-color: #28a745 !important;
            box-shadow: 0 0 8px rgba(40,167,69,0.2) !important;
        }

        .password-group {
            display: flex;
            align-items: center;
            background-color: #0d0f11 !important;
            border: 1px solid #2d3238 !important;
            border-radius: 8px !important;
            overflow: hidden;
        }
        .password-group:focus-within {
            border-color: #28a745 !important;
            box-shadow: 0 0 8px rgba(40,167,69,0.2) !important;
        }
        .password-group .form-control {
            background-color: transparent !important;
            border: none !important;
            color: white !important;
            padding: 10px;
            box-shadow: none !important;
            flex-grow: 1;
        }
        .password-group .input-group-text {
            background-color: transparent !important;
            border: none !important;
            color: #a0aec0 !important;
            cursor: pointer;
            padding: 0 12px;
        }
        .password-group .input-group-text:hover { color: #28a745 !important; }

        /* Camera Box Styles */
        #camera-stream-box {
            width: 100%;
            max-height: 280px;
            background: #000;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #28a745;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark custom-nav py-3 shadow-sm mb-4 fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container">
            <span class="navbar-brand fw-bold text-success"><i class="fa-solid fa-shield-halved me-2"></i> Private Account Console</span>
            <a href="dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container my-5 pt-2" style="max-width: 1100px;">
        
        <?php echo $message; ?>

        <div class="profile-title-section">
            <div class="main-header-title">Profile Settings</div>
            <div class="main-header-subtitle font-monospace">Manage secure land identity coordinates, photo and account cryptography.</div>
        </div>

        <div class="row g-4">
            <!-- Left Grid: ID Card Profile View -->
            <div class="col-lg-4">
                <div class="luxury-identity-card h-100">
                    
                    <!-- 📸 Avatar Container with Camera / File Upload Trigger -->
                    <div class="avatar-container">
                        <div class="luxury-avatar-box">
                            <?php 
                            $photo_file = !empty($farmer['profile_image']) ? $upload_dir . $farmer['profile_image'] : '';
                            if (!empty($farmer['profile_image']) && file_exists($photo_file)): ?>
                                <img src="<?php echo htmlspecialchars($photo_file); ?>" alt="Profile Photo">
                            <?php else: ?>
                                <i class="fa-solid fa-user-shield"></i>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn-edit-photo" data-bs-toggle="modal" data-bs-target="#photoUploadModal" title="Change Profile Photo (Camera or File)">
                            <i class="fa-solid fa-camera"></i>
                        </button>
                    </div>

                    <div class="farmer-title-name mb-2"><?php echo htmlspecialchars($farmer['farmer_name']); ?></div>
                    <div class="farmer-type-tag">Verified Farmer</div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#photoUploadModal">
                            <i class="fa-solid fa-image me-1"></i> Change Photo
                        </button>
                    </div>

                    <div class="mt-4 pt-3 border-top border-secondary text-start small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="identity-meta-text">System Node:</span>
                            <span class="text-white font-monospace fw-bold">Agro-Node-01</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="identity-meta-text">Registry State:</span>
                            <span class="text-success fw-bold"><span class="live-status-dot me-1"></span> Secured</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Grid: Attributes & Form Box -->
            <div class="col-lg-8">
                <div class="glass-details-container">
                    <div class="glass-card-title">
                        <i class="fa-solid fa-server"></i> Central Database Attributes
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6 info-row-item">
                            <div class="meta-label">Mobile Identifier (Username ID)</div>
                            <div class="meta-value font-monospace text-success"><?php echo htmlspecialchars($farmer['phone_number']); ?></div>
                        </div>

                        <div class="col-sm-6 info-row-item">
                            <div class="meta-label">Email Address</div>
                            <div class="meta-value font-monospace text-info"><?php echo htmlspecialchars($farmer['email'] ?? 'Not Set'); ?></div>
                        </div>
                        
                        <div class="col-sm-6 info-row-item">
                            <div class="meta-label">Geographic Region Location</div>
                            <div class="meta-value"><?php echo htmlspecialchars($farmer['village'] ?? 'Kondh'); ?>, Gujarat, IN</div>
                        </div>

                        <div class="col-sm-6 info-row-item">
                            <div class="meta-label">Total Allocated Land Area</div>
                            <div class="meta-value text-white font-monospace"><?php echo htmlspecialchars($farmer['land_vigha'] ?? '120.00'); ?> Vigha</div>
                        </div>
                    </div>
                </div>

                <!-- 🎯 Edit Email & Phone Number Panel[cite: 6] -->
                <div class="luxury-security-card">
                    <h5 class="fw-bold text-success mb-4 border-bottom border-secondary pb-2">
                        <i class="fa-solid fa-address-book me-1"></i> Update Contact Credentials (Email & Phone)
                    </h5>
                    
                    <form action="" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Email Address</label>
                                <input type="email" name="email" class="form-control text-white" value="<?php echo htmlspecialchars($farmer['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Mobile Number (10 Digits)</label>
                                <input type="text" name="phone_number" class="form-control text-white font-monospace" maxlength="10" value="<?php echo htmlspecialchars($farmer['phone_number']); ?>" required>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" name="update_contact_btn" class="btn btn-outline-success fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Update Contact Info
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Encryption Form Panel with Show/Hide Toggle[cite: 6] -->
                <div class="luxury-security-card">
                    <h5 class="fw-bold text-warning mb-4 border-bottom border-secondary pb-2">
                        <i class="fa-solid fa-key me-1"></i> Modify Access Credentials (Password)
                    </h5>
                    
                    <form action="" method="POST">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Current Password</label>
                                <div class="input-group password-group">
                                    <input type="password" name="current_password" id="current_password" class="form-control text-center text-white" placeholder="••••••••" required>
                                    <span class="input-group-text toggle-pwd" data-target="current_password" title="Show/Hide Password">
                                        <i class="fa-solid fa-eye" id="eye_current"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">New Secure Passcode</label>
                                <div class="input-group password-group">
                                    <input type="password" name="new_password" id="new_password" class="form-control text-center text-white" placeholder="Min 6 characters" required>
                                    <span class="input-group-text toggle-pwd" data-target="new_password" title="Show/Hide Password">
                                        <i class="fa-solid fa-eye" id="eye_new"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Re-type Passcode</label>
                                <div class="input-group password-group">
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control text-center text-white" placeholder="Verify password" required>
                                    <span class="input-group-text toggle-pwd" data-target="confirm_password" title="Show/Hide Password">
                                        <i class="fa-solid fa-eye" id="eye_confirm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" name="update_pwd_btn" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-lock-open me-1"></i> Update Access Layer
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 🛡️ Privacy Center: De-activate & Delete Account Options[cite: 6] -->
                <div class="luxury-security-card border-secondary">
                    <h5 class="fw-bold text-success mb-3 border-bottom border-secondary pb-2">
                        <i class="fa-solid fa-user-shield me-1"></i> Privacy Center & Account Management
                    </h5>
                    
                    <!-- 🟡 De-activate Account Option[cite: 6] -->
                    <div class="mb-4 p-3 rounded" style="background: rgba(255, 193, 7, 0.05); border: 1px solid rgba(255, 193, 7, 0.2);">
                        <h6 class="fw-bold text-warning mb-1"><i class="fa-solid fa-power-off me-1"></i> De-activate My Account</h6>
                        <p class="text-muted small mb-3">Temporarily disable your profile. You can reactivate your account anytime by logging back in.</p>
                        <form action="" method="POST" onsubmit="return confirm('⚠️ Are you sure you want to temporarily deactivate your account?');">
                            <button type="submit" name="deactivate_account_btn" class="btn btn-outline-warning btn-sm fw-bold px-4 rounded-pill">
                                De-activate Account
                            </button>
                        </form>
                    </div>

                    <!-- 🔴 Delete Account via Email OTP Option[cite: 6] -->
                    <div class="p-3 rounded border-danger" style="background: rgba(220, 53, 69, 0.05); border: 1px solid rgba(220, 53, 69, 0.2);">
                        <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-user-xmark me-1"></i> Delete My Account (Permanent)</h6>
                        <p class="text-muted small mb-3">Permanently wipe out all records. Click "Send Deletion OTP" to receive a verification code on your email.</p>
                        
                        <form action="" method="POST" class="mb-3">
                            <button type="submit" name="request_delete_otp" class="btn btn-outline-danger btn-sm fw-bold px-3 rounded-pill">
                                <i class="fa-solid fa-paper-plane me-1"></i> Send Deletion OTP to Email
                            </button>
                        </form>

                        <form action="" method="POST" onsubmit="return confirm('⚠️ CRITICAL WARNING: Are you sure you want to permanently delete your account using this OTP?');">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <input type="text" name="otp_code" class="form-control form-control-sm font-monospace text-center text-white" placeholder="Enter 6-Digit Email OTP" maxlength="6" required>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" name="verify_and_delete_btn" class="btn btn-danger btn-sm fw-bold px-4 rounded-pill shadow-sm w-100">
                                        Confirm & Delete Account
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- 📸 Profile Photo Upload & Live Camera Modal -->
    <div class="modal fade" id="photoUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #191c1f; border: 1px solid #2d3238; color: white;">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title fw-bold text-success"><i class="fa-solid fa-camera-rotate me-2"></i> Update Profile Photo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopCamera()"></button>
                </div>
                
                <form action="" method="POST" enctype="multipart/form-data" id="photoForm">
                    <div class="modal-body text-center">
                        
                        <!-- Nav Tabs for Selection -->
                        <ul class="nav nav-pills justify-content-center mb-3 gap-2" id="photoTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="btn btn-sm btn-outline-success active rounded-pill px-3" id="tab-file" data-bs-toggle="pill" data-bs-target="#fileUploadTab" type="button" role="tab" onclick="stopCamera()">
                                    <i class="fa-solid fa-folder-open me-1"></i> From Files / Storage
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" id="tab-camera" data-bs-toggle="pill" data-bs-target="#cameraTab" type="button" role="tab" onclick="startCamera()">
                                    <i class="fa-solid fa-camera me-1"></i> Live Camera Capture
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">
                            <!-- Tab 1: File Upload -->
                            <div class="tab-pane fade show active" id="fileUploadTab" role="tabpanel">
                                <div class="p-3 border border-secondary rounded-3 text-center" style="background: #0d0f11;">
                                    <i class="fa-solid fa-cloud-arrow-up fs-1 text-success mb-2"></i>
                                    <p class="small text-muted mb-2">Select JPG, PNG, or WEBP photo from your device</p>
                                    <input type="file" name="profile_file" id="profile_file" class="form-control form-control-sm text-white" accept="image/*" onchange="previewFileImage(this)">
                                    <div id="filePreviewContainer" class="mt-3 d-none">
                                        <img id="filePreviewImg" src="" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 2px solid #28a745;">
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Live Camera -->
                            <div class="tab-pane fade" id="cameraTab" role="tabpanel">
                                <div class="p-2 border border-secondary rounded-3 text-center" style="background: #0d0f11;">
                                    <video id="camera-stream-box" autoplay playsinline class="d-none"></video>
                                    <canvas id="camera-canvas" class="d-none"></canvas>
                                    <div id="cameraSnapshotPreview" class="d-none my-2">
                                        <img id="capturedImg" src="" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 2px solid #28a745;">
                                        <p class="small text-success mt-1 fw-bold">✓ Snapshot Captured!</p>
                                    </div>
                                    <div id="cameraStatusMsg" class="small text-warning my-2">Starting Camera Feed...</div>
                                    
                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" id="btnCapture" onclick="captureSnapshot()">
                                            <i class="fa-solid fa-camera-retro me-1"></i> Capture Photo
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold d-none" id="btnRetake" onclick="retakePhoto()">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Retake
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden input for base64 camera image -->
                        <input type="hidden" name="camera_photo_data" id="camera_photo_data" value="">

                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal" onclick="stopCamera()">Cancel</button>
                        <button type="submit" name="save_photo_btn" class="btn btn-sm btn-success rounded-pill px-4 fw-bold">
                            <i class="fa-solid fa-check me-1"></i> Save Photo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom text-center py-3" style="background: #191c1f; border-top: 1px solid #2d3238; color: #718096; font-size: 0.85rem;">
        &copy; 2026 Agro Input Hub | Security Architecture Layer
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // 🎯 Password Show/Hide Toggle[cite: 6]
    document.querySelectorAll('.toggle-pwd').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            let targetId = this.getAttribute('data-target');
            let inputField = document.getElementById(targetId);
            let icon = this.querySelector('i');

            if (inputField.getAttribute('type') === 'password') {
                inputField.setAttribute('type', 'text');
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                inputField.setAttribute('type', 'password');
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // 📸 File Upload Preview
    function previewFileImage(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('filePreviewImg').src = e.target.result;
                document.getElementById('filePreviewContainer').classList.remove('d-none');
                // Clear camera data if file is selected
                document.getElementById('camera_photo_data').value = '';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 🎥 Live Camera Stream & Capture Logic
    let videoStream = null;

    function startCamera() {
        const video = document.getElementById('camera-stream-box');
        const statusMsg = document.getElementById('cameraStatusMsg');
        document.getElementById('profile_file').value = ''; // clear file input

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } } })
            .then(function(stream) {
                videoStream = stream;
                video.srcObject = stream;
                video.classList.remove('d-none');
                statusMsg.classList.add('d-none');
                document.getElementById('btnCapture').classList.remove('d-none');
                document.getElementById('cameraSnapshotPreview').classList.add('d-none');
            })
            .catch(function(err) {
                statusMsg.classList.remove('d-none');
                statusMsg.innerHTML = "❌ Camera access denied or not available.";
            });
        } else {
            statusMsg.classList.remove('d-none');
            statusMsg.innerHTML = "❌ Camera not supported on this browser.";
        }
    }

    function stopCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            videoStream = null;
        }
        const video = document.getElementById('camera-stream-box');
        video.classList.add('d-none');
    }

    function captureSnapshot() {
        const video = document.getElementById('camera-stream-box');
        const canvas = document.getElementById('camera-canvas');
        const capturedImg = document.getElementById('capturedImg');
        const hiddenData = document.getElementById('camera_photo_data');

        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/png');
        hiddenData.value = dataUrl;
        capturedImg.src = dataUrl;

        // UI toggles
        stopCamera();
        document.getElementById('cameraSnapshotPreview').classList.remove('d-none');
        document.getElementById('btnCapture').classList.add('d-none');
        document.getElementById('btnRetake').classList.remove('d-none');
    }

    function retakePhoto() {
        document.getElementById('camera_photo_data').value = '';
        document.getElementById('cameraSnapshotPreview').classList.add('d-none');
        document.getElementById('btnRetake').classList.add('d-none');
        startCamera();
    }
    </script>
</body>
</html>