<?php
// farmerside/staff_experts.php - Farmer Side Bilingual Staff & Expert Showcase
require_once __DIR__ . '/../config/db_config.php';
session_start();

// 1. Language Handling (Default: English)
if (isset($_GET['lang'])) {
    $_SESSION['site_lang'] = ($_GET['lang'] === 'gu') ? 'gu' : 'en';
}
$lang = $_SESSION['site_lang'] ?? 'en';

// 2. Fetch Only Admin Approved Staff
$stmt = $conn->query("SELECT * FROM agro_staff WHERE approval_status = 'Approved' AND status = 'Active' ORDER BY staff_id ASC");
$staff_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agri Experts & Staff | AgroInputHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(rgba(10, 20, 15, 0.95), rgba(8, 15, 12, 0.97)),
                        url('../assets/images/hero_banner.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding-top: 85px;
            padding-bottom: 90px;
            min-height: 100vh;
        }

        .staff-card {
            background: rgba(17, 30, 23, 0.96) !important;
            backdrop-filter: blur(14px);
            border: 1px solid rgba(46, 204, 113, 0.25) !important;
            border-radius: 18px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .staff-card:hover {
            transform: translateY(-5px);
            border-color: #2ecc71 !important;
        }

        .staff-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #2ecc71;
        }

        .foundation-box {
            background: #0b1710;
            border: 1px solid #1c3826;
            border-radius: 12px;
            padding: 12px;
        }

        .best-badge {
            background: rgba(241, 196, 15, 0.15);
            border: 1px solid #f1c40f;
            color: #f1c40f;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            display: inline-block;
            margin: 3px;
        }

        .custom-footer {
            background: rgba(8, 15, 12, 0.98);
            border-top: 1px solid #1c3826;
            padding: 14px 0;
            font-size: 12.5px;
            color: #94a3b8;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 100;
        }
    </style>
</head>
<body>

    <!-- Navbar with Language Switcher -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-3" style="background: rgba(10, 20, 15, 0.96); border-bottom: 1px solid #1c3826;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="navbar-brand d-flex align-items-center gap-2 text-decoration-none">
                <i class="fa-solid fa-seedling text-success fs-4"></i>
                <span class="fw-bold text-white fs-5">Agro<span class="text-success">InputHub</span></span>
            </a>   
            
            <div class="d-flex align-items-center gap-2">
                <a href="index.php" class="btn btn-sm btn-outline-secondary text-light rounded-pill px-3">
                    Home
                </a>
                
                <!-- 🌐 Language Toggle -->
                <a href="staff_experts.php?lang=gu" class="btn btn-sm <?php echo ($lang === 'gu') ? 'btn-success text-dark fw-bold' : 'btn-outline-secondary text-light'; ?> rounded-pill px-3">ગુજરાતી</a>
                <a href="staff_experts.php?lang=en" class="btn btn-sm <?php echo ($lang === 'en') ? 'btn-success text-dark fw-bold' : 'btn-outline-secondary text-light'; ?> rounded-pill px-3">English</a>
            </div>
        </div>
    </nav>

    <div class="container text-start">
        
        <!-- Top Heading -->
        <div class="mb-4">
            <h2 class="fw-bold text-success mb-1">
                🌾 Our Experienced Agri Experts & Staff
            </h2>
            <p class="text-light opacity-75 small">
                Get expert direct advisory on crop protection, soil nutrition, fertilizers, and seed selection.
            </p>
        </div>

        <!-- Staff Cards Grid -->
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <?php foreach($staff_list as $st): 
                $img = (filter_var($st['profile_image'], FILTER_VALIDATE_URL)) ? $st['profile_image'] : '../uploads/'.$st['profile_image'];
                
                // Filter data based on selected language
                $name        = ($lang === 'gu') ? $st['staff_name_gu'] : $st['staff_name_en'];
                $designation = ($lang === 'gu') ? $st['designation_gu'] : $st['designation_en'];
                $foundation  = ($lang === 'gu') ? $st['foundation_gu'] : $st['foundation_en'];
                $best_in     = ($lang === 'gu') ? $st['best_in_gu'] : $st['best_in_en'];
                $best_array  = explode(',', $best_in);
            ?>
            <div class="col">
                <div class="card staff-card h-100 p-4 d-flex flex-column justify-content-between">
                    <div>
                        <!-- 1. Staff Photo and Name -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($name); ?>" class="staff-img">
                            <div>
                                <h5 class="fw-bold text-white mb-0"><?php echo htmlspecialchars($name); ?></h5>
                                <div class="text-success small fw-bold mb-1"><?php echo htmlspecialchars($designation); ?></div>
                                <span class="badge bg-dark border border-secondary text-warning" style="font-size: 11px;">
                                    <?php echo $st['experience_years']; ?>+ Years Experience
                                </span>
                            </div>
                        </div>

                        <!-- 2. Foundation / Degree -->
                        <div class="foundation-box mb-3">
                            <div class="text-success small fw-bold mb-1">
                                <i class="fa-solid fa-building-columns me-1"></i> Foundation & Degree:
                            </div>
                            <div class="text-light small"><?php echo htmlspecialchars($foundation); ?></div>
                        </div>

                        <!-- 3. Key Expertise (Best In) -->
                        <div class="mb-3">
                            <label class="small text-warning fw-bold d-block mb-1">
                                <i class="fa-solid fa-star me-1"></i> Key Expertise (Best In):
                            </label>
                            <div>
                                <?php foreach($best_array as $skill): ?>
                                    <span class="best-badge"><i class="fa-solid fa-check me-1"></i><?php echo trim($skill); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- Footer -->
    <footer class="custom-footer text-center">
        &copy; 2026 Agro Input Hub | Dedicated Digital Farming Ecosystem
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
