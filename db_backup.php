<?php
// admin/db_backup.php - Pure PHP Database Backup (No mysqldump required, 100% Reliable)
require_once __DIR__ . '/../config/db_config.php';

// ૧. પ્રોજેક્ટ ફોલ્ડરની અંદર 'backup' ફોલ્ડર ચેક કરો અથવા બનાવો
$backupFolder = __DIR__ . "/backup";
if (!is_dir($backupFolder)) {
    mkdir($backupFolder, 0777, true);
}

$backupFilePath = $backupFolder . "/db_backup.sql";

try {
    // ૨. ડેટાબેઝની તમામ ટેબલ્સ મેળવો
    $tables = [];
    $stmt = $conn->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $sqlScript = "-- Agro Input Hub Database Backup\n";
    $sqlScript .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
    $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    // ૩. દરેક ટેબલનું સ્ટ્રક્ચર અને ડેટા ફેચ કરો
    foreach ($tables as $table) {
        // Table Structure
        $row = $conn->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
        $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlScript .= $row[1] . ";\n\n";

        // Table Data
        $result = $conn->query("SELECT * FROM `$table`");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sqlScript .= "INSERT INTO `$table` VALUES(";
            $comma = "";
            foreach ($row as $value) {
                if ($value === null) {
                    $sqlScript .= $comma . "NULL";
                } else {
                    $value = addslashes($value);
                    $value = str_replace(["\r", "\n"], ["\\r", "\\n"], $value);
                    $sqlScript .= $comma . "'" . $value . "'";
                }
                $comma = ",";
            }
            $sqlScript .= ");\n";
        }
        $sqlScript .= "\n\n";
    }

    $sqlScript .= "SET FOREIGN_KEY_CHECKS=1;\n";

    // ૪. ફાઇલમાં ડેટા સેવ (ઓવરરાઇટ) કરો
    if (file_put_contents($backupFilePath, $sqlScript) !== false) {
        echo "<script>
                alert('🎉 ડેટાબેઝ બેકઅપ સફળતાપૂર્વક admin/backup/db_backup.sql માં સેવ થઈ ગયું છે!'); 
                window.location.href='admin_dashboard.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ બેકઅપ ફાઇલ સેવ કરતી વખતે પરવાનગી (Permission) ની ભૂલ છે!'); 
                window.location.href='admin_dashboard.php';
              </script>";
    }

} catch (Exception $e) {
    echo "<script>
            alert('❌ બેકઅપ એરર: " . addslashes($e->getMessage()) . "'); 
            window.location.href='admin_dashboard.php';
          </script>";
}
exit;
?>