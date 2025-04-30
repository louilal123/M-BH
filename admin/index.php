<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>BH | Login</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<style>
  :root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    --glass-effect: rgba(255, 255, 255, 0.15);
    --text-dark: #2d3748;
    --text-light: #f8fafc;
  }
  
  body {
    font-family: 'Poppins', sans-serif;
    background: var(--secondary-gradient);
    min-height: 100vh;
    margin: 0;
    color: var(--text-dark);
    text-decoration: none;
  }
  
  .main {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-gradient);
    position: relative;
    overflow: hidden;
  }
  
  .main::before {
    content: '';
    position: absolute;
    width: 200%;
    height: 200%;
    top: -50%;
    left: -50%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
    transform: rotate(30deg);
    z-index: 1;
  }
  
  .card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
    background-color: var(--glass-effect);
    z-index: 2;
    transition: transform 0.3s ease;
  }
 
  .card-body {
    padding: 2.5rem;
  }
  
  .system-title {
    font-weight: 700;
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    letter-spacing: 1px;
    position: relative;
    z-index: 2;
  }
  
  .login-title {
    font-weight: 600;
    color: white;
    margin-bottom: 1.5rem;
    position: relative;
  }
  
  .login-title::after {
    content: '';
    display: block;
    width: 50px;
    height: 3px;
    background: white;
    margin: 10px auto 0;
    border-radius: 3px;
  }
  
  .form-control {
    border: none;
    border-radius: 8px;
    padding: 12px 15px;
    background-color: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
  }
  
  .form-control:focus {
    background-color: white;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
  }
  
  .btn-login {
    background: dark;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
  }
  
  .btn-login:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }
  
  .password-wrapper {
    position: relative;
  }
  
  .password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-dark);
    cursor: pointer;
  }
  
  .invalid-feedback {
    font-size: 0.85rem;
  }
  
  @media (max-width: 768px) {
    .card-body {
      padding: 1.5rem;
    }
    
    .system-title {
      font-size: 1.5rem;
    }
  }
</style>

<body>
  <main class="main">
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
          <img src="assets/img/logo.png" style="height: 100px; width: 120px; margin: auto; border-radius: 100%;" alt="">
           
            <div class="d-flex justify-content-center mb-5 mt-2">
               <h1 class="system-title">MECMEC BOARDING HOUSE MANAGEMENT SYSTEM</h1>
            </div>
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-3 pb-2">
                    <h5 class="login-title text-center">Administrator Login</h5>
                  </div>

                  <form class="row g-3 needs-validation" action="functions/login.php" method="POST" novalidate>
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Email or Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <div class="password-wrapper">
                        <input type="password" name="password" class="form-control" id="yourPassword" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                          <i class="fas fa-eye"></i>
                        </button>
                        <div class="invalid-feedback">Please enter your password!</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-dark btn-login w-100" type="submit">Login</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  
  <script>
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('yourPassword');
      const icon = this.querySelector('i');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
    
    // Form validation
    (function() {
      'use strict';
      const forms = document.querySelectorAll('.needs-validation');
      
      Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>

  <?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
    <script>
      Swal.fire({
        icon: "<?php echo $_SESSION['status_icon']; ?>",
        title: "<?php echo $_SESSION['status']; ?>",
        confirmButtonText: "Ok",
        background: 'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)',
        backdrop: `
          rgba(0,0,0,0.4)
          url("/images/nyan-cat.gif")
          left top
          no-repeat
        `
      });
    </script>
    <?php
    unset($_SESSION['status']);
    unset($_SESSION['status_icon']);
  endif;
  ?>
</body>
</html>