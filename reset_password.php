<?php
session_start();
require_once 'config/db.php';
require_once 'config/helpers.php';

if (isLoggedIn() || !isset($_SESSION['reset_email'])) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'];
    
    if (empty($otp) || empty($new_pass) || empty($confirm_pass)) {
        $error = "All fields are required.";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_pass) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $stmt = $conn->prepare("SELECT id, otp_code, otp_expires_at FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && $user['otp_code'] === $otp) {
            $current_time = new DateTime();
            $expire_time = new DateTime($user['otp_expires_at']);
            
            if ($current_time > $expire_time) {
                $error = "OTP has expired. Please request a new one.";
            } else {
                // valid!
                $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
                $update = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expires_at = NULL WHERE id = ?");
                $update->execute([$hashed, $user['id']]);
                
                unset($_SESSION['reset_email']);
                setFlash('success', 'Your password was securely reset! You can now log in.');
                redirect('index.php');
            }
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
    <title>Reset Password - ExamPro</title>
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
            flex: 1; background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
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
            <div class="display-3 mb-4"><i class="fas fa-key text-white-50"></i></div>
            <h1 class="display-4 fw-bold mb-3">Fresh Start</h1>
            <p class="lead text-white-50 mb-4">Create a strong, new and unguessable password. It is highly advised to avoid using details like your name or standard dictionary words.</p>
        </div>
    </div>
    
    <div class="login-right">
        <div class="login-box">
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Set New Password</h2>
                <p class="text-muted">You're almost there! Enter the 6-digit code sent to your email and your new password.</p>
            </div>

            <?php $mock = getFlash('otp_mock'); if ($mock): ?>
                <div class="alert alert-light border border-danger border-2 shadow-sm text-center fw-bold rounded-3 mb-4">
                    <span class="text-danger small text-uppercase d-block mb-1">Demo Recovery Code</span>
                    <span class="fs-2 text-dark letter-spacing-1"><?= $mock ?></span>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-custom shadow-sm"><i class="fas fa-exclamation-circle me-2"></i><?= sanitize($conn, $error) ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="otp" class="form-label text-muted fw-semibold small text-uppercase">6-Digit Code</label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm fs-4 letter-spacing-1" id="otp" name="otp" maxlength="6" required autocomplete="off" autofocus>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label text-muted fw-semibold small text-uppercase">New Password</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="new_password" name="new_password" required placeholder="••••••••">
                        <span class="input-group-text bg-white border-start-0 text-muted toggle-password" data-target="new_password" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="form-label text-muted fw-semibold small text-uppercase">Confirm Password</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-check"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="confirm_password" name="confirm_password" required placeholder="••••••••">
                        <span class="input-group-text bg-white border-start-0 text-muted toggle-password" data-target="confirm_password" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-danger btn-lg w-100 mb-4 shadow"><i class="fas fa-save me-2"></i> Confirm New Password</button>
                
                <div class="text-center">
                    <a href="index.php" class="text-muted text-decoration-none small">Cancel and return to login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
