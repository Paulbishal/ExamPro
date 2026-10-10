<?php
session_start();
require_once 'config/db.php';
require_once 'config/helpers.php';

// Ensure the user actually came from the registration step
if (!isset($_SESSION['pending_registration'])) {
    redirect('register.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = trim($_POST['otp']);
    
    if (empty($entered_otp)) {
        $error = "Please enter the OTP.";
    } else {
        $expected_otp = $_SESSION['pending_registration_otp'];
        $expires = $_SESSION['pending_registration_expires'];
        
        if (time() > $expires) {
            $error = "OTP has expired. Please register again.";
            unset($_SESSION['pending_registration']);
            unset($_SESSION['pending_registration_otp']);
            unset($_SESSION['pending_registration_expires']);
        } elseif ($entered_otp == $expected_otp) {
            // Success! Insert the user into the database
            $data = $_SESSION['pending_registration'];
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, school_name, academic_details, teaching_subject) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['name'], 
                $data['email'], 
                $data['password'], 
                $data['role'],
                $data['school_name'],
                $data['academic_details'],
                $data['teaching_subject']
            ]);
            
            $new_user_id = $conn->lastInsertId();
            
            // Clean up registration session vars
            unset($_SESSION['pending_registration']);
            unset($_SESSION['pending_registration_otp']);
            unset($_SESSION['pending_registration_expires']);
            
            // Log them in immediately
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['name'] = $data['name'];
            $_SESSION['role'] = $data['role'];
            session_regenerate_id(true);
            
            setFlash('success', 'Registration complete! Welcome to ExamPro.');
            
            if ($data['role'] === 'admin') redirect('admin/index.php');
            else if ($data['role'] === 'teacher') redirect('teacher/index.php');
            else redirect('student/index.php');
        } else {
            $error = "Invalid OTP code.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - ExamPro</title>
    <!-- Bootstrap 5 CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #f8fafc; min-height: 100vh; display: flex; align-items: center; }
        .login-split { display: flex; min-height: 100vh; width: 100%; }
        .login-left {
            flex: 1; background: linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%);
            color: white; display: flex; flex-direction: column; justify-content: center;
            padding: 4rem; position: relative; overflow: hidden;
        }
        .login-left::after {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
            animation: pulse 15s infinite;
        }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
        .login-right { flex: 1; display: flex; align-items: center; justify-content: center; padding: 3rem; background: #ffffff; }
        .login-box { width: 100%; max-width: 440px; animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(30px); }
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
        .letter-spacing-1 { letter-spacing: 0.5em; text-align: center; }
        @media (max-width: 991px) {
            .login-left { display: none; }
            .login-right { background: #f8fafc; }
            .login-box { background: white; padding: 2.5rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        }
    </style>
</head>
<body>

<div class="login-split">
    <div class="login-left">
        <ul class="floating-circles"><li></li><li></li><li></li><li></li><li></li><li></li></ul>
        <div style="z-index: 10;">
            <div class="display-3 mb-4"><i class="fas fa-check-double text-white-50"></i></div>
            <h1 class="display-4 fw-bold mb-3">Almost Done!</h1>
            <p class="lead text-white-50 mb-4">You're one step away from finishing your registration. Please verify your email address to ensure your account remains fully protected.</p>
        </div>
    </div>
    
    <div class="login-right">
        <div class="login-box">
            <div class="mb-4 text-center">
                <i class="fas fa-envelope-open-text text-primary mb-3 display-5"></i>
                <h2 class="fw-bold text-dark mb-1">Verify Email</h2>
                <p class="text-muted">Enter the 6-digit confirmation code we sent to your email.</p>
            </div>

            <?php $mock = getFlash('otp_mock'); if ($mock): ?>
                <div class="alert alert-light border border-primary border-2 shadow-sm text-center fw-bold rounded-3 mb-4">
                    <span class="text-primary small text-uppercase d-block mb-1">Demo Registration Code</span>
                    <span class="fs-2 text-dark letter-spacing-1"><?= $mock ?></span>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-custom shadow-sm"><i class="fas fa-exclamation-circle me-2"></i><?= sanitize($conn, $error) ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-4">
                    <label for="otp" class="form-label text-muted fw-semibold small text-uppercase">Confirmation Code</label>
                    <input type="text" class="form-control form-control-lg fs-3 letter-spacing-1 shadow-sm border-0 bg-light" id="otp" name="otp" maxlength="6" required autocomplete="off" autofocus>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100 mb-4 shadow fw-bold"><i class="fas fa-user-check me-2"></i> Finalize Registration</button>
                
                <div class="text-center">
                    <a href="register.php" class="text-muted text-decoration-none small"><i class="fas fa-edit me-1"></i> Return to Registration Details</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
