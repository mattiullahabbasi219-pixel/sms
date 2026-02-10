<?php
require_once 'config.php';

// Require login
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = trim($_POST['student_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $class_id = intval($_POST['class_id']);
    $section_id = intval($_POST['section_id']);
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    
    // Validation
    if (empty($student_id) || empty($first_name) || empty($last_name) || empty($class_id) || empty($section_id)) {
        $error = "Required fields are missing!";
    } else {
        // Check if student ID already exists
        $check_query = "SELECT id FROM students WHERE student_id = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $student_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $error = "Student ID already exists!";
        } else {
            // Hash password if provided
            $hashed_password = null;
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $error = "Password must be at least 6 characters long!";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                }
            }
            
            if (empty($error)) {
                // Insert student
                $insert_query = "INSERT INTO students (student_id, first_name, last_name, email, password, phone, address, class_id, section_id, date_of_birth, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insert_query);
                $email = empty($email) ? null : $email;
                $phone = empty($phone) ? null : $phone;
                $address = empty($address) ? null : $address;
                $date_of_birth = empty($date_of_birth) ? null : $date_of_birth;
                
                mysqli_stmt_bind_param($stmt, "sssssssiiss", $student_id, $first_name, $last_name, $email, $hashed_password, $phone, $address, $class_id, $section_id, $date_of_birth, $gender);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Student added successfully!" . (!empty($password) ? " Password has been set." : " Note: Password not set. Student can login after password is set.");
                    // Clear form data
                    $_POST = array();
                } else {
                    $error = "Failed to add student! Please try again.";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Get classes and sections for dropdown (after POST handling)
$classes = mysqli_query($conn, "SELECT * FROM classes ORDER BY class_name");

// Get sections based on class (for AJAX)
if (isset($_GET['class_id'])) {
    $class_id = intval($_GET['class_id']);
    $sections_query = mysqli_query($conn, "SELECT * FROM sections WHERE class_id = $class_id ORDER BY section_name");
    $sections_data = [];
    while ($row = mysqli_fetch_assoc($sections_query)) {
        $sections_data[] = $row;
    }
    echo json_encode($sections_data);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - SMS</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="bi bi-mortarboard"></i> SMS
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a class="nav-link" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-person-plus"></i> Add New Student</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="student_id" name="student_id" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password (for student login)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Leave empty to set later (min 6 characters)">
                                <small class="form-text text-muted">If provided, student can login using Student ID or Email with this password.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        <?php while ($class = mysqli_fetch_assoc($classes)): ?>
                                            <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                    <select class="form-select" id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="dashboard.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Add Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load sections when class is selected
        document.getElementById('class_id').addEventListener('change', function() {
            const classId = this.value;
            const sectionSelect = document.getElementById('section_id');
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            
            if (classId) {
                fetch('add_student.php?class_id=' + classId)
                    .then(response => response.json())
                    .then(sections => {
                        sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.textContent = section.section_name;
                            sectionSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
</body>
</html>
