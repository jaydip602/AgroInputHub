<?php
// admin/audit_logs.php - Enhanced System Logs & Audit Trail (Staff & Admin Dynamic Version)
require_once '../config/db_config.php';
session_start();

try {
    $logs = $conn->query("SELECT * FROM audit_logs ORDER BY log_id DESC")->fetchAll();
} catch (Exception $e) {
    die("ડેટાબેઝ એરર: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trail Logs | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 110px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; }
        .table-responsive { max-height: 600px; overflow-y: auto; border-radius: 12px; }
        .table-custom-fix th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; font-weight: 600; position: sticky; top: 0; z-index: 10; }
        .table-custom-fix td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
        .badge-username { font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 30px; }
        .code-table { font-family: 'Courier New', monospace; font-weight: 700; padding: 4px 8px; background-color: #0d0f11; border: 1px solid #2d3238; border-radius: 6px; color: #38bdf8; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="premium-logo-container">
                <div class="premium-logo-icon-wrapper"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text">Agro <span>Security</span> Logs</div>
                    <div class="logo-sub-text">Security System Audit Trail</div>
                </div>
            </a>   
            <a href="admin_dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container my-4 animate__animated animate__fadeIn">
        <div class="card card-custom p-4 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                <h5 class="fw-bold text-white mb-0">
                    <i class="fa-solid fa-clock-rotate-left text-danger animate__animated animate__pulse animate__infinite d-inline-block me-2"></i> Ingestion Security Track System
                </h5>
                <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold small">Live Active Monitoring Enabled</span>
            </div>
            
            <p class="text-muted small mb-3 font-monospace">System Integrity Registry Mapping Table (Insert, Update, Delete Records Logs)</p>

            <div class="table-responsive border border-secondary shadow-sm">
                <table class="table table-dark table-hover table-bordered align-middle text-center table-custom-fix mb-0">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Log ID</th>
                            <th style="width: 160px;">Operator Username</th>
                            <th class="text-start">Action Description Log Performed</th>
                            <th style="width: 160px;">Affected Database</th>
                            <th style="width: 200px;">Timestamp Log</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($logs)): ?>
                            <tr><td colspan="5" class="text-muted py-5">No logs registered yet.</td></tr>
                        <?php else: foreach($logs as $l): ?>
                            <tr>
                                <td class="fw-bold text-muted font-monospace">#LOG-<?php echo $l['log_id']; ?></td>
                                <td>
                                    <span class="badge bg-dark border border-secondary text-primary badge-username">
                                        <i class="fa-solid fa-user-shield me-1"></i> 
                                        <?php 
                                            // યુઝરનું નામ ડિસ્પ્લે કરવા માટે
                                            $username = !empty($l['username']) ? $l['username'] : 'admin_main';
                                            echo htmlspecialchars($username); 
                                        ?>
                                    </span>
                                </td>
                                <td class="text-start text-white fw-bold" style="font-size: 14px;">
                                    <?php 
                                        $action = htmlspecialchars($l['action_performed']);
                                        if (strpos($action, 'ડીલીટ') !== false || strpos($action, 'Delete') !== false) {
                                            echo '<span class="text-danger"><i class="fa-solid fa-trash-can me-1"></i> ' . $action . '</span>';
                                        } elseif (strpos($action, 'નવી') !== false || strpos($action, 'ઉમેરી') !== false || strpos($action, 'ખોલ્યું') !== false) {
                                            echo '<span class="text-success"><i class="fa-solid fa-circle-plus me-1"></i> ' . $action . '</span>';
                                        } else {
                                            echo '<span class="text-warning"><i class="fa-solid fa-pen-to-square me-1"></i> ' . $action . '</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $db_table = htmlspecialchars($l['table_affected']);
                                    $icon = "📁"; 
                                    if ($db_table == 'ledger') { $icon = '📖'; }
                                    elseif ($db_table == 'orders') { $icon = '🛒'; }
                                    elseif ($db_table == 'bills') { $icon = '🧾'; }
                                    elseif ($db_table == 'products') { $icon = '📦'; }
                                    elseif ($db_table == 'farmers') { $icon = '👥'; }
                                    ?>
                                    <span class="code-table">
                                        <span style="margin-right: 5px;"><?php echo $icon; ?></span><?php echo $db_table; ?>
                                    </span>
                                </td>
                                <td class="small text-muted font-monospace fw-bold">
                                    <i class="fa-regular fa-calendar-days me-1"></i> 
                                    <?php echo date('d-m-Y H:i:s', strtotime($l['action_timestamp'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="text-center py-3 fixed-bottom-footer">
        &copy; 2026 Agro Input Hub | Control Panel Center | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja </b>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>