<?php
// Central database connection file
$servername = "localhost";
$username = "root";
$password = "";
$dbname   = "keilas_db";

/* Report mysqli errors as exceptions so we can handle them gracefully */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo "<h1>Database connection failed</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Possible causes: the database <code>" . htmlspecialchars($dbname) . "</code> doesn't exist or the MySQL credentials are incorrect.</p>";
    echo "<p>To create the missing database using phpMyAdmin: open <code>http://localhost/phpmyadmin</code>, then create a database named <code>" . htmlspecialchars($dbname) . "</code>.</p>";
    echo "<p>Or from PowerShell with the MySQL client (XAMPP):</p>";
    echo "<pre>& 'C:\\xampp\\mysql\\bin\\mysql.exe' -u root -p -e 'CREATE DATABASE " . htmlspecialchars($dbname) . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'</pre>";
    echo "<p>After creating the database, reload this page.</p>";
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
