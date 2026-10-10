<?php
session_start();
require_once 'config/db.php';
require_once 'config/helpers.php';

if (isLoggedIn()) {
    if ($_SESSION['role'] === 'admin') redirect('admin/index.php');
    if ($_SESSION['role'] === 'teacher') redirect('teacher/index.php');
    if ($_SESSION['role'] === 'student') redirect('student/index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $school_name = trim($_POST['school_name']);
    
    // Dynamic fields depending on role
    $academic_details = ($role === 'student' && isset($_POST['academic_details'])) ? trim($_POST['academic_details']) : null;
    $teaching_subject = ($role === 'teacher' && isset($_POST['teaching_subject'])) ? trim($_POST['teaching_subject']) : null;
    
    $captcha = trim($_POST['captcha']);

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($role) || empty($school_name) || empty($captcha)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif (strtoupper($captcha) !== $_SESSION['captcha_code']) {
        $error = "Invalid captcha code. Please try again.";
    } elseif (!in_array($role, ['teacher', 'student'])) {
        $error = "Invalid role selected.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            // Setup registration pending session
            $_SESSION['pending_registration'] = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => $role,
                'school_name' => $school_name,
                'academic_details' => $academic_details,
                'teaching_subject' => $teaching_subject
            ];
            
            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['pending_registration_otp'] = $otp;
            $_SESSION['pending_registration_expires'] = time() + (15 * 60); // 15 mins
            
            setFlash('otp_mock', $otp);
            redirect('register_verify_otp.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - ExamPro</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #f8fafc; min-height: 100vh; display: flex; align-items: center; }
        .login-split { display: flex; min-height: 100vh; width: 100%; }
        .login-left {
            flex: 1; background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white; display: flex; flex-direction: column; justify-content: center;
            padding: 4rem; position: relative; overflow: hidden;
        }
        .login-left::after {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
            animation: pulse 15s infinite;
        }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
        .login-right { flex: 1; display: flex; align-items: center; justify-content: center; padding: 3rem; background: #ffffff; overflow-y: auto; }
        .login-box { width: 100%; max-width: 550px; animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(30px); margin: auto; }
        .floating-circles li {
            position: absolute; display: block; list-style: none; width: 20px; height: 20px;
            background: rgba(255, 255, 255, 0.1); animation: animate 25s linear infinite; bottom: -150px;
        }
        .floating-circles li:nth-child(1){ left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .floating-circles li:nth-child(2){ left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .floating-circles li:nth-child(3){ left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .floating-circles li:nth-child(4){ left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .floating-circles li:nth-child(5){ left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .floating-circles li:nth-child(6){ left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        @keyframes animate {
            0%{ transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100%{ transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }
        .captcha-box { display: flex; align-items: stretch; gap: 10px; }
        .captcha-img-wrapper { border: 1px solid #dee2e6; border-radius: 6px; overflow: hidden; background: #f8f9fa; }
        .captcha-img { height: 100%; object-fit: cover; }
        .refresh-captcha { cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0 15px; background: #f1f5f9; border: 1px solid #dee2e6; border-radius: 6px; color: #64748b; transition: all 0.2s; }
        .refresh-captcha:hover { background: #e2e8f0; color: #334155; }
        @media (max-width: 991px) {
            .login-left { display: none; }
            .login-right { background: #f8fafc; padding: 2rem 1rem;}
            .login-box { background: white; padding: 2.5rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        }
    </style>
</head>
<body>

<div class="login-split">
    <div class="login-left">
        <ul class="floating-circles"><li></li><li></li><li></li><li></li><li></li><li></li></ul>
        <div style="z-index: 10;">
            <div class="display-3 mb-4"><i class="fas fa-user-graduate text-white-50"></i></div>
            <h1 class="display-4 fw-bold mb-3">Join ExamPro</h1>
            <p class="lead text-white-50 mb-4">Register as a Student to take seamless online examinations, or as a Teacher to conduct and grade comprehensive assessments.</p>
            <div class="d-flex align-items-center gap-3 mt-4">
                <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-pill">
                    <i class="fas fa-check-circle text-primary"></i> <span class="small">Instant Setup</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="login-right">
        <div class="login-box py-4">
            <div class="mb-4 text-center">
                <h2 class="fw-bold text-dark mb-1">Create Account</h2>
                <p class="text-muted">Fill out the details below to complete your registration.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-custom shadow-sm"><i class="fas fa-exclamation-circle me-2"></i><?= sanitize($conn, $error) ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label text-muted fw-semibold small text-uppercase">Full Name</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="name" name="name" required placeholder="John Doe">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label text-muted fw-semibold small text-uppercase">Email Address</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" required placeholder="you@example.com">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="school_name" class="form-label text-muted fw-semibold small text-uppercase">Institution / School Name</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-university"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="school_name" name="school_name" required placeholder="University of Example">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label text-muted fw-semibold small text-uppercase">Register As</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user-tag"></i></span>
                        <select class="form-select border-start-0 ps-0" id="role" name="role" required onchange="handleRoleChange()">
                            <option value="student" selected>Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>
                </div>

                <!-- Dynamic Student Fields -->
                <div class="mb-3" id="student_fields">
                    <label for="academic_details" class="form-label text-muted fw-semibold small text-uppercase">Academic Details (Program/Year)</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-id-card"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="academic_details" name="academic_details" placeholder="e.g. BCA 3rd Year">
                    </div>
                </div>

                <!-- Dynamic Teacher Fields -->
                <div class="mb-3" id="teacher_fields" style="display: none;">
                    <label for="teaching_subject" class="form-label text-muted fw-semibold small text-uppercase">Subject of Teaching</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-chalkboard-teacher"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="teaching_subject" name="teaching_subject" placeholder="e.g. Mathematics, Physics">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label text-muted fw-semibold small text-uppercase">Password</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password" name="password" required placeholder="••••••••">
                            <span class="input-group-text bg-white border-start-0 text-muted toggle-password" data-target="password" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label text-muted fw-semibold small text-uppercase">Confirm Password</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-check"></i></span>
                            <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="confirm_password" name="confirm_password" required placeholder="••••••••">
                            <span class="input-group-text bg-white border-start-0 text-muted toggle-password" data-target="confirm_password" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="captcha" class="form-label text-muted fw-semibold small text-uppercase">Security Check</label>
                    <div class="captcha-box mb-2">
                        <div class="captcha-img-wrapper shadow-sm">
                            <img src="captcha.php" alt="captcha" id="captcha-img" class="captcha-img">
                        </div>
                        <button type="button" class="refresh-captcha shadow-sm" onclick="document.getElementById('captcha-img').src='captcha.php?'+Math.random();" title="Refresh Captcha">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control shadow-sm" id="captcha" name="captcha" required placeholder="Enter the code shown above" autocomplete="off">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 shadow"><i class="fas fa-user-plus me-2"></i> Register Account</button>
                
                <div class="text-center">
                    <p class="text-muted mb-0">Already have an account? <a href="index.php" class="text-primary fw-bold text-decoration-none">Log In</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
    function handleRoleChange() {
        const role = document.getElementById('role').value;
        const studentFields = document.getElementById('student_fields');
        const teacherFields = document.getElementById('teacher_fields');
        const academicInput = document.getElementById('academic_details');
        const teachingInput = document.getElementById('teaching_subject');

        if (role === 'student') {
            studentFields.style.display = 'block';
            teacherFields.style.display = 'none';
            academicInput.setAttribute('required', 'required');
            teachingInput.removeAttribute('required');
        } else {
            studentFields.style.display = 'none';
            teacherFields.style.display = 'block';
            teachingInput.setAttribute('required', 'required');
            academicInput.removeAttribute('required');
        }
    }
    // Set initial state
    handleRoleChange();
</script>
</body>
</html>
