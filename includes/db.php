<?php
/**
 * Smart Hospital Management System
 * Database Connection Helper (PDO) & Auto-Installer
 * XAMPP Compatible (Default Host: localhost, User: root, Pass: '')
 */

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'hospital_db';

try {
    // 1. Attempt connecting to the specific database
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // 2. Database does not exist yet -> Auto-create database & import schema
    try {
        $pdo_root = new PDO("mysql:host=$db_host;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        
        // Create database first
        $pdo_root->exec("CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        
        // Connect to the newly created database
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // Import hospital_db.sql
        $sql_file = __DIR__ . '/../database/hospital_db.sql';
        if (file_exists($sql_file)) {
            $sql_content = file_get_contents($sql_file);

            // Execute schema statements in PDO cleanly
            execute_sql_file($pdo, $sql_content);
        }
    } catch (PDOException $ex) {
        die("<div style='font-family:sans-serif; padding:20px; background:#fee2e2; border:1px solid #ef4444; border-radius:8px; margin:20px; color:#991b1b;'>
                <h3>Database Connection Failed</h3>
                <p><strong>Error:</strong> " . htmlspecialchars($ex->getMessage()) . "</p>
                <p>Please ensure MySQL (XAMPP/WAMP) is running.</p>
             </div>");
    }
}

/**
 * Clean SQL execution helper for PDO (Handles stored procedures & triggers)
 */
function execute_sql_file($pdo, $sql) {
    // Split block statements by DELIMITER //
    $blocks = preg_split('/DELIMITER\s+\/\//i', $sql);
    
    foreach ($blocks as $block) {
        $sub_blocks = preg_split('/DELIMITER\s+;/i', $block);
        foreach ($sub_blocks as $query_block) {
            $query_block = trim($query_block);
            if (empty($query_block)) continue;
            
            // Check if this block contains // at the end (Trigger/Procedure body)
            if (strpos($query_block, '//') !== false) {
                $statement = trim(str_replace('//', '', $query_block));
                if (!empty($statement)) {
                    try { $pdo->exec($statement); } catch (Exception $ex) {}
                }
            } else {
                // Regular semicolon-separated statements
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
