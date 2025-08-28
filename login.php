<?php
session_start();
include("php/dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = md5($_POST['password']);

    // Admin check
    $admin = mysqli_query($conn, "SELECT * FROM admin WHERE username='$email' AND password='$password'");
    if (mysqli_num_rows($admin) == 1) {
        $admin_data = mysqli_fetch_assoc($admin);
        $_SESSION['admin'] = $email;
        $_SESSION['admin_name'] = 'Administrator';
        header("Location: php/admin_dashboard.php");
        exit();
    }

    // Student check
    $student = mysqli_query($conn, "SELECT * FROM students WHERE email='$email' AND password='$password'");
    if (mysqli_num_rows($student) == 1) {
        $row = mysqli_fetch_assoc($student);
        $_SESSION['student_id'] = $row['id'];
        $_SESSION['student_name'] = $row['name'];
        header("Location: php/student_dashboard.php");
        exit();
    }

    $error = "Invalid Credentials!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | MessFeast</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
            padding: 0 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .login-card:hover::before {
            left: 100%;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .brand-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem 1rem 1rem 3rem;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
            color: white;
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            z-index: 2;
        }

        .form-control:focus + .form-icon {
            color: var(--accent-color);
        }

        .btn-login {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #fecaca;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .register-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: white;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 60px;
            height: 60px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 80px;
            height: 80px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 80%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(180deg); }
        }

        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .brand-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="floating-elements">
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
</div>

<div class="login-container">
    <div class="login-card">
        <div class="brand-header">
            <div class="brand-logo">
                <i class="fas fa-utensils"></i>
            </div>
            <h1 class="brand-title">MessFeast <span class="tagline">Fast Feasts, Zero Queues.</span></h1>
            <p class="brand-subtitle">Welcome back! Please sign in to your account</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form method="post" id="loginForm">
            <div class="form-group">
                <input type="text" name="email" placeholder="Email or Admin Username" required class="form-control">
                <i class="fas fa-user form-icon"></i>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required class="form-control">
                <i class="fas fa-lock form-icon"></i>
            </div>
            
            <button type="submit" class="btn btn-login" id="loginBtn">
                <span class="btn-text">Sign In</span>
                <span class="loading">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </form>

        <div class="register-link">
            <p class="text-muted mb-0">
                New student? <a href="register.php">Create an account</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('loginBtn');
    const btnText = btn.querySelector('.btn-text');
    const loading = btn.querySelector('.loading');
    
    // Show loading state
    btnText.style.display = 'none';
    loading.classList.add('show');
    btn.disabled = true;
    
    // Simulate loading delay (remove in production)
    setTimeout(() => {
        // Form will submit normally
    }, 1000);
});

// Add floating animation to form elements
document.addEventListener('DOMContentLoaded', function() {
    const formElements = document.querySelectorAll('.form-control, .btn-login');
    
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.5s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Add focus effects
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});
</script>

</body>
</html>
