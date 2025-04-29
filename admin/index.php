<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>BH</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<style>
  body{
    text-decoration: none  ;
  }
</style>
<body>

  <main class="main bg-dark">
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4 ">
        <div class="container">
          <div class="row justify-content-center">
          <div class="d-flex justify-content-center mb-4">
                <h1 class="text-light">MECMEC BOARDING HOUSE MANAGEMENT SYSTEM</h1>
              </div>
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="card mb-1">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login Administrator</h5>
                   
                  </div>

                  <form class="row g-3 needs-validation" action="functions/login.php" method="POST" novalidate>
                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Email or Username</label>
                    <div class="input-group has-validation">
                      
                      <input type="text" name="username" class="form-control" id="yourUsername" required>
                      <div class="invalid-feedback">Please enter your username.</div>
                    </div>
                  </div>
                  <div class="col-12">
                    <label for="yourPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="yourPassword" required>
                    <div class="invalid-feedback">Please enter your password!</div>
                  </div>
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Login</button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  

  <?php

// Check if there's a logout message
if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
    <script>
        Swal.fire({
            icon: "<?php echo $_SESSION['status_icon']; ?>",
            title: "<?php echo $_SESSION['status']; ?>",
            confirmButtonText: "Ok"
        });
    </script>
    <?php
    // Clear the session status to prevent it from showing again on reload
    unset($_SESSION['status']);
    unset($_SESSION['status_icon']);
endif;
?>


</body>

</html>