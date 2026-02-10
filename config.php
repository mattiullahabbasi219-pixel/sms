<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sms_db2');

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "<br>Please check:<br>1. MySQL is running in XAMPP<br>2. Database 'sms_db2' exists<br>3. Run test_connection.php to diagnose issues");
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

// Function to check if student is logged in
function isStudentLoggedIn() {
    return isset($_SESSION['student_id']) && isset($_SESSION['student_name']);
}

// Function to check if any user (admin or student) is logged in
function isLoggedIn() {
    return isAdminLoggedIn() || isStudentLoggedIn();
}

// Function to redirect if not logged in (for admin pages)
function requireLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to redirect if not logged in (for student pages)
function requireStudentLogin() {
    if (!isStudentLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to redirect if not logged in (for any user)
function requireAnyLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to redirect if already logged in
function requireLogout() {
    if (isAdminLoggedIn()) {
        header("Location: dashboard.php");
        exit();
    }
    if (isStudentLoggedIn()) {
        header("Location: student_dashboard.php");
        exit();
    }
}

?>
