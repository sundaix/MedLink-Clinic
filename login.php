<?php
session_start();
require_once 'firebase-php-api.php';

$firebase = new FirebasePHP();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $result = $firebase->read('/admins');
    $admins = $result['data'] ?? [];
    
    $authenticated = false;
    $adminData = null;
    
    foreach ($admins as $adminId => $admin) {
        if ($admin['email'] === $email && $admin['password'] === $password) {
            if (isset($admin['status']) && $admin['status'] === 'inactive') {
                echo json_encode(['success' => false, 'message' => 'Account is deactivated']);
                exit;
            }
            
            $_SESSION['admin_id'] = $adminId;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_name'] = $admin['name'] ?? 'Administrator';
            $_SESSION['admin_role'] = $admin['role'] ?? 'admin';
            $authenticated = true;
            $adminData = $admin;
            break;
        }
    }
    
    if ($authenticated) {
        $firebase->update('/admins/' . $_SESSION['admin_id'], [
            'last_login' => date('Y-m-d H:i:s'),
            'last_login_ip' => $_SERVER['REMOTE_ADDR']
        ]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Login successful',
            'user' => [
                'name' => $_SESSION['admin_name'],
                'email' => $_SESSION['admin_email'],
                'role' => $_SESSION['admin_role']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - MedLink Clinic</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
        --primary-color: #7dd3c9;
        --secondary-color: #5bc0b5;
        --accent-color: #4a9d96;
        --text-dark: #50372c;
        --sidebar-bg: #2c3e50;
        --sidebar-hover: #34495e;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }

    /* ===== LOGIN SCREEN ===== */
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
        padding: 20px;
    }

    .login-box {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 400px;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    /* ===== FORM CONTROLS ===== */
    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(125, 211, 201, 0.25);
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%);
        border: none;
        transition: all 0.3s ease;
        padding: 12px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* ===== ALERT MESSAGES ===== */
    .alert {
        border-radius: 8px;
        border: none;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #721c24;
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.1);
        border: 1px solid rgba(40, 167, 69, 0.3);
        color: #155724;
    }

    /* Loading spinner */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }

    /* Responsive design */
    @media (max-width: 576px) {
        .login-box {
            margin: 20px;
            padding: 30px 20px;
        }
        
        .login-container {
            padding: 10px;
        }
    }

    /* Icon styling */
    .fa-user-shield {
        color: var(--primary-color);
    }

    /* Form label styling */
    .form-label {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    /* Input group styling */
    .form-control {
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(125, 211, 201, 0.25);
    }
  </style>
</head>
<body>
  <!-- Login Screen - CLEAN VERSION -->
  <div class="login-container">
    <div class="login-box">
      <div class="text-center mb-4">
        <i class="fas fa-user-shield fa-3x text-primary mb-3"></i>
        <h2 class="fw-bold" style="color: var(--text-dark);">Admin Login</h2>
        <p class="text-muted">MedLink Clinic Dashboard</p>
      </div>
      
<!-- Add this after the existing login form, before the closing div of login-box -->
<div class="text-center mt-4 pt-3 border-top">
  <p class="text-muted mb-2">Patient Access</p>
  <a href="register.html" class="btn btn-outline-primary w-100 mb-2">
    <i class="fas fa-user-plus me-2"></i>Register as New Patient
  </a>
  <small class="text-muted">For admin login, use the form above</small>
</div>

      <form id="loginForm">
        <div class="mb-3">
          <label for="adminEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="adminEmail" required placeholder="Enter your email">
        </div>
        <div class="mb-3">
          <label for="adminPassword" class="form-label">Password</label>
          <input type="password" class="form-control" id="adminPassword" required placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      <div id="loginError" class="alert alert-danger mt-3 d-none"></div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = document.getElementById('adminEmail').value;
      const password = document.getElementById('adminPassword').value;
      const errorDiv = document.getElementById('loginError');
      const submitBtn = this.querySelector('button[type="submit"]');
      
      // Clear previous errors
      errorDiv.classList.add('d-none');
      errorDiv.textContent = '';
      
      // Show loading state
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Logging in...';
      
      // Create form data
      const formData = new FormData();
      formData.append('email', email);
      formData.append('password', password);
      
      fetch('login.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success message
          errorDiv.className = 'alert alert-success mt-3';
          errorDiv.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + data.message + 
                              '<br><small>Welcome back, ' + data.user.name + '!</small>';
          errorDiv.classList.remove('d-none');
          
          // Redirect to admin dashboard
          setTimeout(() => {
            window.location.href = 'admin.php';
          }, 1500);
        } else {
          // Show error message
          errorDiv.className = 'alert alert-danger mt-3';
          errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> ' + data.message;
          errorDiv.classList.remove('d-none');
          
          // Restore button
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Login';
        }
      })
      .catch(error => {
        console.error('Login error:', error);
        errorDiv.className = 'alert alert-danger mt-3';
        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Login failed. Please try again.';
        errorDiv.classList.remove('d-none');
        
        // Restore button
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Login';
      });
    });

    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
      const loginBox = document.querySelector('.login-box');
      loginBox.style.opacity = '0';
      loginBox.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        loginBox.style.transition = 'all 0.5s ease';
        loginBox.style.opacity = '1';
        loginBox.style.transform = 'translateY(0)';
      }, 100);
    });
  </script>
</body>
</html>