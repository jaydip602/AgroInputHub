<?php
// admin/product_reviews.php - View All Farmer Product Reviews
require_once __DIR__ . '/../config/db_config.php';
session_start();

// એડમિન લૉગિન ચેક (જો સેશન ન હોય તો જ લૉગિન પેજ પર જાય)
if(!isset($_SESSION['admin_logged_in']) && !isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

// રિવ્યૂ ડિલીટ કરવાની લોજિક
if(isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $stmt_del = $conn->prepare("DELETE FROM product_reviews WHERE review_id = ?");
    $stmt_del->execute([$del_id]);
    header("Location: product_reviews.php?msg=deleted");
    exit;
}

// તમામ રિવ્યૂ પ્રોડક્ટ અને ફાર્મરની વિગતો સાથે ફેચ કરો
$reviews_query = $conn->query("SELECT r.*, p.product_name, f.farmer_name, f.village 
                               FROM product_reviews r 
                               JOIN products p ON r.product_id = p.product_id 
                               JOIN farmers f ON r.farmer_id = f.farmer_id 
                               ORDER BY r.review_id DESC");
$all_reviews = $reviews_query->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Product Reviews | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-top: 30px; padding-bottom: 50px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; }
        .table-custom-fix th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; }
        .table-custom-fix td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-success"><i class="fa-solid fa-star me-2 text-warning"></i> Farmer Product Reviews & Ratings</h3>
            <a href="admin_dashboard.php" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold">Back to Dashboard</a>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success py-2">Review deleted successfully!</div>
        <?php endif; ?>

        <div class="card card-custom p-4 shadow">
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle table-custom-fix mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Farmer Name</th>
                            <th>Village</th>
                            <th>Product Name</th>
                            <th>Rating</th>
                            <th>Review Comment</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($all_reviews)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No reviews found from farmers yet.</td>
                            </tr>
                        <?php else: foreach($all_reviews as $rev): ?>
                            <tr>
                                <td><?php echo $rev['review_id']; ?></td>
                                <td class="fw-bold text-success"><?php echo htmlspecialchars($rev['farmer_name']); ?></td>
                                <td class="text-muted font-monospace"><?php echo htmlspecialchars($rev['village'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($rev['product_name']); ?></td>
                                <td>
                                    <span class="text-warning">
                                        <?php for($i=1; $i<=5; $i++) {
                                            echo ($i <= $rev['rating']) ? '<i class="fa-solid fa-star fa-xs"></i>' : '<i class="fa-regular fa-star fa-xs"></i>';
                                        } ?>
                                    </span>
                                    <span class="ms-1 font-monospace">(<?php echo $rev['rating']; ?>/5)</span>
                                </td>
                                <td style="max-width: 250px;"><?php echo htmlspecialchars($rev['review_text']); ?></td>
                                <td class="small text-muted font-monospace"><?php echo date('d-m-Y H:i', strtotime($rev['created_at'])); ?></td>
                                <td>
                                    <a href="product_reviews.php?delete_id=<?php echo $rev['review_id']; ?>" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this review?');">
                                        <i class="fa-solid fa-trash me-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>