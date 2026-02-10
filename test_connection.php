<?php
/**
 * Test Database Connection File
 * Use this file to test if your database connection is working
 * Access: http://localhost/cursorai/test_connection.php
 */

// Database Configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'sms_db2';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body { padding: 20px; background-color: #f8f9fa; }
        .card { margin-top: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <div class='card-header bg-primary text-white'>
                <h3>Database Connection Test</h3>
            </div>
            <div class='card-body'>";

// Test 1: Check PHP Version
echo "<h5>1. PHP Version Check</h5>";
echo "<p>PHP Version: <strong>" . phpversion() . "</strong></p>";
if (version_compare(phpversion(), '7.4.0', '>=')) {
    echo "<p class='text-success'>✓ PHP version is compatible</p>";
} else {
    echo "<p class='text-danger'>✗ PHP version should be 7.4 or higher</p>";
}

// Test 2: Check MySQL Extension
echo "<hr><h5>2. MySQL Extension Check</h5>";
if (extension_loaded('mysqli')) {
    echo "<p class='text-success'>✓ MySQLi extension is loaded</p>";
} else {
    echo "<p class='text-danger'>✗ MySQLi extension is not loaded. Please enable it in php.ini</p>";
}

// Test 3: Test Database Connection
echo "<hr><h5>3. Database Connection Test</h5>";
echo "<p>Host: <strong>$host</strong></p>";
echo "<p>User: <strong>$user</strong></p>";
echo "<p>Database: <strong>$db</strong></p>";

$conn = @mysqli_connect($host, $user, $pass);

if ($conn) {
    echo "<p class='text-success'>✓ Successfully connected to MySQL server</p>";
    
    // Check if database exists
    $db_check = mysqli_select_db($conn, $db);
    if ($db_check) {
        echo "<p class='text-success'>✓ Database '$db' exists and is accessible</p>";
        
        // Check tables
        $tables = ['admins', 'classes', 'sections', 'students'];
        echo "<hr><h5>4. Database Tables Check</h5>";
        foreach ($tables as $table) {
            $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
            if (mysqli_num_rows($result) > 0) {
                echo "<p class='text-success'>✓ Table '$table' exists</p>";
            } else {
                echo "<p class='text-warning'>⚠ Table '$table' does not exist. Please import database.sql</p>";
            }
        }
    } else {
        echo "<p class='text-warning'>⚠ Database '$db' does not exist. Creating it now...</p>";
        $create_db = mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $db");
        if ($create_db) {
            echo "<p class='text-success'>✓ Database '$db' created successfully</p>";
            echo "<p class='text-info'>ℹ Please import database.sql to create tables</p>";
        } else {
            echo "<p class='text-danger'>✗ Failed to create database: " . mysqli_error($conn) . "</p>";
        }
    }
    mysqli_close($conn);
} else {
    echo "<p class='text-danger'>✗ Failed to connect to MySQL server</p>";
    echo "<p class='text-danger'>Error: " . mysqli_connect_error() . "</p>";
    echo "<p class='text-info'>ℹ Make sure XAMPP MySQL is running</p>";
}

// Test 4: File Permissions
echo "<hr><h5>5. File Check</h5>";
$required_files = ['config.php', 'login.php', 'register.php', 'dashboard.php'];
foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "<p class='text-success'>✓ File '$file' exists</p>";
    } else {
        echo "<p class='text-danger'>✗ File '$file' is missing</p>";
    }
}

echo "</div></div>";

// Quick Links
echo "<div class='card mt-3'>
    <div class='card-body'>
        <h5>Quick Links:</h5>
        <ul>
            <li><a href='index.php'>Index Page</a></li>
            <li><a href='login.php'>Login Page</a></li>
            <li><a href='register.php'>Register Page</a></li>
        </ul>
    </div>
</div>";

echo "</div></body></html>";
?>
