<?php
session_start();
include("php/dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_email = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "Email already registered!";
        } else {
            // Insert new student
            $insert = mysqli_query($conn, "INSERT INTO students (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')");
            if ($insert) {
                header('Location: login.php?registered=1');
                exit();
            } else {
                $error = "Registration failed! Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | MessFeast</title>
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

        .register-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 500px;
            padding: 0 20px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .register-card:hover::before {
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

        .form-control.valid {
            border-color: var(--success-color);
        }

        .form-control.invalid {
            border-color: var(--danger-color);
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

        .form-control.valid + .form-icon {
            color: var(--success-color);
        }

        .form-control.invalid + .form-icon {
            color: var(--danger-color);
        }

        .validation-message {
            font-size: 0.8rem;
            margin-top: 0.5rem;
            padding-left: 3rem;
        }

        .validation-message.success {
            color: var(--success-color);
        }

        .validation-message.error {
            color: var(--danger-color);
        }

        .btn-register {
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

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .btn-register:disabled {
            background: #9ca3af;
            transform: none;
            box-shadow: none;
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

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #d1fae5;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: white;
        }

        .password-strength {
            margin-top: 0.5rem;
            padding-left: 3rem;
        }

        .strength-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak {
            background: var(--danger-color);
            width: 25%;
        }

        .strength-medium {
            background: var(--warning-color);
            width: 50%;
        }

        .strength-strong {
            background: var(--success-color);
            width: 75%;
        }

        .strength-very-strong {
            background: var(--success-color);
            width: 100%;
        }

        .strength-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
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
            .register-card {
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

<div class="register-container">
    <div class="register-card">
        <div class="brand-header">
            <div class="brand-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="brand-title">Join MessFeast <span class="tagline">Fast Feasts, Zero Queues.</span></h1>
            <p class="brand-subtitle">Create your account and start ordering delicious meals</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo $success; ?>
        </div>
        <?php endif; ?>

        <form method="post" id="registerForm">
            <div class="form-group">
                <input type="text" name="name" id="name" placeholder="Full Name" required class="form-control">
                <i class="fas fa-user form-icon"></i>
                <div class="validation-message" id="nameValidation"></div>
            </div>
            
            <div class="form-group">
                <input type="email" name="email" id="email" placeholder="Email Address" required class="form-control">
                <i class="fas fa-envelope form-icon"></i>
                <div class="validation-message" id="emailValidation"></div>
            </div>
            
            <div class="form-group">
                <input type="tel" name="phone" id="phone" placeholder="Phone Number" required class="form-control">
                <i class="fas fa-phone form-icon"></i>
                <div class="validation-message" id="phoneValidation"></div>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="Password" required class="form-control">
                <i class="fas fa-lock form-icon"></i>
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <div class="strength-text" id="strengthText">Password strength</div>
                </div>
            </div>
            
            <div class="form-group">
                <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm Password" required class="form-control">
                <i class="fas fa-lock form-icon"></i>
                <div class="validation-message" id="confirmPasswordValidation"></div>
            </div>
            
            <button type="submit" class="btn btn-register" id="registerBtn">
                <span class="btn-text">Create Account</span>
                <span class="loading">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </form>

        <div class="login-link">
            <p class="text-muted mb-0">
                Already have an account? <a href="login.php">Sign in here</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Form validation
const form = document.getElementById('registerForm');
const inputs = form.querySelectorAll('input');
const registerBtn = document.getElementById('registerBtn');

// Real-time validation
inputs.forEach(input => {
    input.addEventListener('blur', validateField);
    input.addEventListener('input', validateField);
});

function validateField() {
    const field = this;
    const value = field.value.trim();
    const fieldName = field.name;
    const validationElement = document.getElementById(fieldName + 'Validation');
    
    // Remove existing validation classes
    field.classList.remove('valid', 'invalid');
    
    switch(fieldName) {
        case 'name':
            if (value.length < 2) {
                showValidation(field, validationElement, 'Name must be at least 2 characters long', 'error');
            } else if (!/^[a-zA-Z\s]+$/.test(value)) {
                showValidation(field, validationElement, 'Name can only contain letters and spaces', 'error');
            } else {
                showValidation(field, validationElement, 'Name looks good!', 'success');
            }
            break;
            
        case 'email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showValidation(field, validationElement, 'Please enter a valid email address', 'error');
            } else {
                showValidation(field, validationElement, 'Email looks good!', 'success');
            }
            break;
            
        case 'phone':
            const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
            if (!phoneRegex.test(value)) {
                showValidation(field, validationElement, 'Please enter a valid phone number', 'error');
            } else {
                showValidation(field, validationElement, 'Phone number looks good!', 'success');
            }
            break;
            
        case 'password':
            validatePassword(value);
            break;
            
        case 'confirm_password':
            const password = document.getElementById('password').value;
            if (value !== password) {
                showValidation(field, validationElement, 'Passwords do not match', 'error');
            } else if (value.length > 0) {
                showValidation(field, validationElement, 'Passwords match!', 'success');
            }
            break;
    }
}

function showValidation(field, element, message, type) {
    field.classList.add(type === 'success' ? 'valid' : 'invalid');
    if (element) {
        element.textContent = message;
        element.className = `validation-message ${type}`;
    }
}

function validatePassword(password) {
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const passwordField = document.getElementById('password');
    
    let strength = 0;
    let feedback = '';
    
    // Check length
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 25;
    
    // Check for different character types
    if (/[a-z]/.test(password)) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[^A-Za-z0-9]/.test(password)) strength += 25;
    
    // Cap at 100%
    strength = Math.min(strength, 100);
    
    // Update UI
    strengthFill.className = 'strength-fill';
    if (strength <= 25) {
        strengthFill.classList.add('strength-weak');
        feedback = 'Weak password';
    } else if (strength <= 50) {
        strengthFill.classList.add('strength-medium');
        feedback = 'Medium strength';
    } else if (strength <= 75) {
        strengthFill.classList.add('strength-strong');
        feedback = 'Strong password';
    } else {
        strengthFill.classList.add('strength-very-strong');
        feedback = 'Very strong password';
    }
    
    strengthText.textContent = feedback;
    
    // Update field validation
    if (password.length >= 8) {
        passwordField.classList.add('valid');
        passwordField.classList.remove('invalid');
    } else {
        passwordField.classList.add('invalid');
        passwordField.classList.remove('valid');
    }
}

// Form submission
form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate all fields
    let isValid = true;
    inputs.forEach(input => {
        validateField.call(input);
        if (input.classList.contains('invalid')) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        alert('Please fix the validation errors before submitting.');
        return;
    }
    
    // Show loading state
    const btnText = registerBtn.querySelector('.btn-text');
    const loading = registerBtn.querySelector('.loading');
    
    btnText.style.display = 'none';
    loading.classList.add('show');
    registerBtn.disabled = true;
    
    // Submit form
    form.submit();
});

// Add floating animation to form elements
document.addEventListener('DOMContentLoaded', function() {
    const formElements = document.querySelectorAll('.form-control, .btn-register');
    
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
