<?php
session_start();
require_once 'config/db.php';
require_once 'config/helpers.php';

// If already logged in, redirect
if (isLoggedIn()) {
    if ($_SESSION['role'] === 'admin') redirect('admin/index.php');
    if ($_SESSION['role'] === 'teacher') redirect('teacher/index.php');
    if ($_SESSION['role'] === 'student') redirect('student/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $captcha = trim($_POST['captcha']);

    if (empty($email) || empty($password) || empty($captcha)) {
        $error = "Please fill in all fields.";
    } elseif (strtoupper($captcha) !== $_SESSION['captcha_code']) {
        $error = "Invalid captcha code. Please try again.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Generate 6-digit OTP
            $otp = rand(100000, 999999);
            $expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE id = ?");
            $stmt->execute([$otp, $expires, $user['id']]);
            
            // Set pending staging session
            $_SESSION['pending_user_id'] = $user['id'];
            $_SESSION['pending_role'] = $user['role'];
            $_SESSION['pending_name'] = $user['name'];
            
            // Mock email sending by flashing the OTP to the screen
            setFlash('otp_mock', $otp);
            redirect('verify_otp.php');
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Advanced Online Examination System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-split {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }
        .login-left::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
            animation: pulse 15s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: #ffffff;
        }
        .login-box {
            width: 100%;
            max-width: 440px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        .captcha-box {
            display: flex;
            align-items: stretch;
            gap: 10px;
        }
        .captcha-img-wrapper {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
            background: #f8f9fa;
        }
        .captcha-img {
            height: 100%;
            object-fit: cover;
        }
        .refresh-captcha {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
            background: #f1f5f9;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            color: #64748b;
            transition: all 0.2s;
        }
        .refresh-captcha:hover {
            background: #e2e8f0;
            color: #334155;
        }
        .floating-circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
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
        <ul class="floating-circles">
            <li></li><li></li><li></li><li></li><li></li><li></li>
        </ul>
        <div style="z-index: 10;">
            <div class="display-3 mb-4"><i class="fas fa-graduation-cap text-white-50"></i></div>
            <h1 class="display-4 fw-bold mb-3">Welcome to ExamPro</h1>
            <p class="lead text-white-50 mb-4">A powerful, modern, and robust platform to conduct seamless online examinations with precision and reliability.</p>
            <div class="d-flex align-items-center gap-3 mt-4">
                <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-pill">
                    <i class="fas fa-shield-alt text-success"></i> <span class="small">Secure Evaluator</span>
                </div>
                <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-pill">
                    <i class="fas fa-bolt text-warning"></i> <span class="small">Real-time Analytics</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="login-right">
        <div class="login-box">
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Sign In</h2>
                <p class="text-muted">Enter your credentials to access your dashboard.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-custom shadow-sm"><i class="fas fa-exclamation-circle me-2"></i><?= sanitize($error) ?></div>
            <?php endif; ?>
            <?php $msg = getFlash('success'); if ($msg): ?>
                <div class="alert alert-success alert-custom shadow-sm"><i class="fas fa-check-circle me-2"></i><?= sanitize($msg) ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label text-muted fw-semibold small text-uppercase">Email Address</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" required placeholder="you@example.com">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label text-muted fw-semibold small text-uppercase">Password</label>
                        <a href="forgot_password.php" class="small text-primary text-decoration-none fw-semibold">Forgot Password?</a>
                    </div>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password" name="password" required placeholder="••••••••">
                        <span class="input-group-text bg-white border-start-0 text-muted toggle-password" data-target="password" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
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
                    <input type="text" class="form-control form-control-lg shadow-sm" id="captcha" name="captcha" required placeholder="Enter the code shown above" autocomplete="off">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 shadow"><i class="fas fa-sign-in-alt me-2"></i> Access Dashboard</button>
                
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">Don't have an account? <a href="register.php" class="text-primary fw-bold text-decoration-none">Create Account</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
