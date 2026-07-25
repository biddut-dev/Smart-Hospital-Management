<?php
/**
 * Smart Hospital Management System
 * Database Connection Helper (PDO) & Cloud MySQL Support (Aiven, Render, etc.)
 */

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: '3306';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db_name = getenv('DB_NAME') ?: 'hospital_db';

try {
    // Attempt connecting to specified database with port support
    $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Self-healing check: Auto-create tables if doctors table is missing
    $table_check = false;
    try {
        $table_check = $pdo->query("SHOW TABLES LIKE 'doctors'")->fetch();
    } catch (Exception $e) {}

    if (!$table_check) {
        $sql_file = __DIR__ . '/../database/infinityfree_import.sql';
        if (file_exists($sql_file)) {
            $sql_content = file_get_contents($sql_file);
            $statements = array_filter(array_map('trim', explode(';', $sql_content)));
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    try { $pdo->exec($stmt); } catch (Exception $ex) {}
                }
            }
        }
    }
} catch (PDOException $e) {
    // If running on local XAMPP and database does not exist, try to auto-create
    try {
        $pdo_root = new PDO("mysql:host=$db_host;port=$db_port;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        
        $pdo_root->exec("CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        
        $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        $sql_file = __DIR__ . '/../database/hospital_db.sql';
        if (file_exists($sql_file)) {
            $sql_content = file_get_contents($sql_file);
            execute_sql_file($pdo, $sql_content);
        }
    } catch (PDOException $ex) {
        die("<div style='font-family:sans-serif; padding:20px; background:#fee2e2; border:1px solid #ef4444; border-radius:8px; margin:20px; color:#991b1b;'>
                <h3>Database Connection Failed</h3>
                <p><strong>Error:</strong> " . htmlspecialchars($ex->getMessage()) . "</p>
                <p>Please check your Aiven / MySQL Environment Variables (DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME).</p>
             </div>");
    }
}

/**
 * Clean SQL execution helper for PDO
 */
function execute_sql_file($pdo, $sql) {
    $blocks = preg_split('/DELIMITER\s+\/\//i', $sql);
    foreach ($blocks as $block) {
        $sub_blocks = preg_split('/DELIMITER\s+;/i', $block);
        foreach ($sub_blocks as $query_block) {
            $query_block = trim($query_block);
            if (empty($query_block)) continue;
            
            if (strpos($query_block, '//') !== false) {
                $statement = trim(str_replace('//', '', $query_block));
                if (!empty($statement)) {
                    try { $pdo->exec($statement); } catch (Exception $ex) {}
                }
            } else {
                $statements = explode(';', $query_block);
                foreach ($statements as $stmt) {
                    $stmt = trim($stmt);
                    if (!empty($stmt)) {
                        try { $pdo->exec($stmt); } catch (Exception $ex) {}
                    }
                }
            }
        }
    }
}
