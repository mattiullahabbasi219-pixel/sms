<?php
require_once 'config.php';

/* Redirect students to student dashboard */
if (isStudentLoggedIn()) {
    header("Location: student_dashboard.php");
    exit();
}

/* Require admin login */
requireLogin();

/* Admin info */
$admin_id = $_SESSION['admin_id'];
$admin_username = $_SESSION['admin_username'];

/* Statistics */
$students_count = mysqli_query($conn, "SELECT COUNT(*) AS count FROM students")->fetch_assoc()['count'];
$classes_count  = mysqli_query($conn, "SELECT COUNT(*) AS count FROM classes")->fetch_assoc()['count'];
$sections_count = mysqli_query($conn, "SELECT COUNT(*) AS count FROM sections")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - SMS</title>

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background-color: #f8f9fa;
    margin: 0;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 240px;
    height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding-top: 20px;
    color: #fff;
}

.sidebar .brand {
    font-size: 1.3rem;
    font-weight: 600;
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.sidebar a {
    display: block;
    color: #fff;
    padding: 12px 20px;
    text-decoration: none;
    font-size: 0.95rem;
}

.sidebar a i {
    margin-right: 10px;
}

.sidebar a:hover,
.sidebar a.active {
    background: rgba(255,255,255,0.15);
}

.sidebar .logout {
    position: absolute;
    bottom: 20px;
    width: 100%;
}

/* Main content */
.main-content {
    margin-left: 240px;
    padding: 25px;
}

/* Cards */
.card {
    border: none;
    border-radius: 10px;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.stat-card {
    border-left: 4px solid;
}

.stat-card.students { border-left-color: #667eea; }
.stat-card.classes  { border-left-color: #f093fb; }
.stat-card.sections { border-left-color: #4facfe; }
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand">
        <i class="bi bi-mortarboard"></i> SMS Admin
    </div>

    <a href="dashboard.php" class="active">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="add_student.php">
        <i class="bi bi-person-plus"></i> Add Student
    </a>

    <a href="add_class.php">
        <i class="bi bi-book"></i> Add Class
    </a>

    <a href="add_section.php">
        <i class="bi bi-layers"></i> Add Section
    </a>

    <a href="#">
        <i class="bi bi-person-circle"></i>
        <?php echo htmlspecialchars($admin_username); ?>
    </a>

    <a href="logout.php" class="logout">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
<div class="container-fluid">

<h2 class="mb-4">
    <i class="bi bi-speedometer2"></i>
    Welcome, <?php echo htmlspecialchars($admin_username); ?>!
</h2>

<!-- STATISTICS -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card students">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Students</h6>
                    <h2><?php echo $students_count; ?></h2>
                </div>
                <i class="bi bi-people text-primary" style="font-size:3rem;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card stat-card classes">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Classes</h6>
                    <h2><?php echo $classes_count; ?></h2>
                </div>
                <i class="bi bi-book text-danger" style="font-size:3rem;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card stat-card sections">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Sections</h6>
                    <h2><?php echo $sections_count; ?></h2>
                </div>
                <i class="bi bi-layers text-info" style="font-size:3rem;"></i>
            </div>
        </div>
    </div>
</div>

<!-- QUICK ACTIONS -->
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-person-plus" style="font-size:3rem;color:#667eea;"></i>
                <h5 class="mt-3">Add Student</h5>
                <p class="text-muted">Register a new student</p>
                <a href="add_student.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Student
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-book" style="font-size:3rem;color:#f093fb;"></i>
                <h5 class="mt-3">Add Class</h5>
                <p class="text-muted">Create a new class</p>
                <a href="add_class.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Class
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-layers" style="font-size:3rem;color:#4facfe;"></i>
                <h5 class="mt-3">Add Section</h5>
                <p class="text-muted">Create a new section</p>
                <a href="add_section.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Section
                </a>
            </div>
        </div>
    </div>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
