<?php
session_start();
require_once 'config/db.php';
require_once 'config/helpers.php';

if (isLoggedIn()) redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Generate OTP
            $otp = rand(100000, 999999);
            $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            
            $update = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE id = ?");
            $update->execute([$otp, $expires, $user['id']]);
            
            $_SESSION['reset_email'] = $email;
            setFlash('otp_mock', $otp);
            redirect('reset_password.php');
        } else {
            // Display same generic message for security to prevent email enumeration
            $error = "If an account matches this email, an OTP has been sent. (For demo: Email not found!)";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - ExamPro</title>
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
            flex: 1; background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
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
            <div class="display-3 mb-4"><i class="fas fa-envelope-open-text text-white-50"></i></div>
            <h1 class="display-4 fw-bold mb-3">Account Recovery</h1>
            <p class="lead text-white-50 mb-4">Don't worry if you've lost your password. Provide your registered email and we'll send you a secure verification code to help you regain access swiftly.</p>
        </div>
    </div>
    
    <div class="login-right">
        <div class="login-box">
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Forgot Password?</h2>
                <p class="text-muted">Enter your email address to receive your OTP.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-custom shadow-sm"><i class="fas fa-exclamation-circle me-2"></i><?= sanitize($conn, $error) ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-4">
                    <label for="email" class="form-label text-muted fw-semibold small text-uppercase">Email Address</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-at"></i></span>
                        <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" required placeholder="you@example.com" autofocus>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-warning btn-lg w-100 mb-4 shadow text-dark fw-bold"><i class="fas fa-paper-plane me-2"></i> Send Recovery Code</button>
                
                <div class="text-center">
                    <span class="text-muted small">Remembered your password?</span> <a href="index.php" class="text-warning fw-bold text-decoration-none small">Return to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
