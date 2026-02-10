<?php
require_once 'config.php';

// Require student login
requireStudentLogin();

// Get student info
$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];
$student_student_id = $_SESSION['student_student_id'];
$class_id = $_SESSION['student_class_id'];
$section_id = $_SESSION['student_section_id'];

// Get student details with class and section information
$query = "SELECT s.*, c.class_name, sec.section_name 
          FROM students s 
          LEFT JOIN classes c ON s.class_id = c.id 
          LEFT JOIN sections sec ON s.section_id = sec.id 
          WHERE s.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .info-card {
            border-left: 4px solid;
        }
        .info-card.class-info {
            border-left-color: #667eea;
        }
        .info-card.section-info {
            border-left-color: #f093fb;
        }
        .info-card.student-info {
            border-left-color: #4facfe;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="student_dashboard.php">
                <i class="bi bi-mortarboard"></i> Student Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($student_name); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Welcome Banner -->
        <div class="welcome-banner text-center">
            <h2><i class="bi bi-person-check"></i> Welcome, <?php echo htmlspecialchars($student_name); ?>!</h2>
            <p class="mb-0">Student ID: <?php echo htmlspecialchars($student_student_id); ?></p>
        </div>

        <!-- Student Information Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card info-card class-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Class</h6>
                                <h3 class="mb-0">
                                    <?php 
                                    if ($student && !empty($student['class_name'])) {
                                        echo htmlspecialchars($student['class_name']);
                                    } else {
                                        echo '<span class="text-muted">Not Assigned</span>';
                                    }
                                    ?>
                                </h3>
                            </div>
                            <div class="text-primary" style="font-size: 3rem;">
                                <i class="bi bi-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card info-card section-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Section</h6>
                                <h3 class="mb-0">
                                    <?php 
                                    if ($student && !empty($student['section_name'])) {
                                        echo htmlspecialchars($student['section_name']);
                                    } else {
                                        echo '<span class="text-muted">Not Assigned</span>';
                                    }
                                    ?>
                                </h3>
                            </div>
                            <div class="text-danger" style="font-size: 3rem;">
                                <i class="bi bi-layers"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card info-card student-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Full Name</h6>
                                <h5 class="mb-0"><?php echo htmlspecialchars($student_name); ?></h5>
                            </div>
                            <div class="text-info" style="font-size: 3rem;">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> My Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-card-heading"></i> Student ID:</strong>
                                <p class="mb-0"><?php echo htmlspecialchars($student['student_id']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-person"></i> Full Name:</strong>
                                <p class="mb-0"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-book"></i> Class:</strong>
                                <p class="mb-0">
                                    <?php 
                                    if (!empty($student['class_name'])) {
                                        echo htmlspecialchars($student['class_name']);
                                    } else {
                                        echo '<span class="text-muted">Not Assigned</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-layers"></i> Section:</strong>
                                <p class="mb-0">
                                    <?php 
                                    if (!empty($student['section_name'])) {
                                        echo htmlspecialchars($student['section_name']);
                                    } else {
                                        echo '<span class="text-muted">Not Assigned</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <?php if (!empty($student['email'])): ?>
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-envelope"></i> Email:</strong>
                                <p class="mb-0"><?php echo htmlspecialchars($student['email']); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($student['phone'])): ?>
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-telephone"></i> Phone:</strong>
                                <p class="mb-0"><?php echo htmlspecialchars($student['phone']); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($student['date_of_birth'])): ?>
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-calendar"></i> Date of Birth:</strong>
                                <p class="mb-0"><?php echo date('F d, Y', strtotime($student['date_of_birth'])); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($student['gender'])): ?>
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-gender-ambiguous"></i> Gender:</strong>
                                <p class="mb-0"><?php echo htmlspecialchars($student['gender']); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($student['address'])): ?>
                            <div class="col-md-12 mb-3">
                                <strong><i class="bi bi-geo-alt"></i> Address:</strong>
                                <p class="mb-0"><?php echo htmlspecialchars($student['address']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
