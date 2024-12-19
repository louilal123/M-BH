<?php
include 'includes/session.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "includes/header.php" ?>
</head>
<body>
  <div class="d-flex w-100">
    <!-- Sidebar -->
    <?php include "includes/sidebar.php" ?>

    <!-- Main Content -->
    <div id="content" class="flex-grow-1">
      <!-- Top Navigation -->
      <?php include "includes/topnav.php" ?>

      <!-- Dashboard -->
      <div class="container-fluid mt-4">
        <!-- Room Types Table -->
        <div class="row mt-4">
          <div class="col-lg-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">


                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
             

              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">
                  <!-- Profile Edit Form -->
                  <form method="POST" action="functions/update_profile.php" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="assets/uploads/<?php echo htmlspecialchars($admin_photo); ?>" style="width: 100px; height: 100px;">
                        <div class="pt-2">
                          <input type="file" name="photo" class="btn btn-primary btn-sm" title="Upload new profile image">
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input 
                          name="fullName" 
                          type="text" 
                          class="form-control" 
                          id="fullName" 
                          value="<?php echo htmlspecialchars($admin_fullname); ?>" 
                          required 
                          oninput="validateName(this)">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input 
                          name="email" 
                          type="email" 
                          class="form-control" 
                          id="Email" 
                          value="<?php echo htmlspecialchars($admin_email); ?>" 
                          required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                      <div class="col-md-8 col-lg-9">
                        <input 
                          name="username" 
                          type="text" 
                          class="form-control" 
                          id="Username" 
                          value="<?php echo htmlspecialchars($admin_username); ?>" 
                          required 
                          oninput="validateAlphanumeric(this)">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Role" class="col-md-4 col-lg-3 col-form-label">Role</label>
                      <div class="col-md-8 col-lg-9">
                        <input 
                          name="role" 
                          type="text" 
                          class="form-control" 
                          id="Role" 
                          value="<?php echo htmlspecialchars($admin_role); ?>" 
                          required 
                          oninput="validateName(this)">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form action="functions/change_password.php" method="POST">
                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input 
                          name="current_password" 
                          type="password" 
                          class="form-control" 
                          id="currentPassword" 
                          required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input 
                          name="new_password" 
                          type="password" 
                          class="form-control" 
                          id="newPassword" 
                          required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input 
                          name="renew_password" 
                          type="password" 
                          class="form-control" 
                          id="renewPassword" 
                          required>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

            </div>
          </div>
          
          </div>
        </div>
      </div>
    </div>
  </div>



  <?php include "includes/footer.php" ?>


  <script>
  // Validation: Allow only alphabets and spaces
  function validateName(input) {
    input.value = input.value.replace(/[^A-Za-z\s]/g, ''); // Keep only alphabets and spaces
  }

  // Validation: Allow only numeric values
  function validateNumeric(input) {
    input.value = input.value.replace(/[^0-9]/g, ''); // Keep only numbers
  }

  // Validation: Allow alphanumeric values with spaces
  function validateAlphanumeric(input) {
    input.value = input.value.replace(/[^A-Za-z0-9\s]/g, ''); // Keep alphabets, numbers, and spaces
  }

  // Validation: Allow only special characters (example for specific cases)
  function validateSpecialCharacters(input) {
    input.value = input.value.replace(/[A-Za-z0-9]/g, ''); // Remove alphabets and numbers
  }

  // Validation: Disable future dates
  function disableFutureDates(input) {
    const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
    if (input.value > today) {
      input.value = today; // Reset to today's date if a future date is selected
      alert('Future dates are not allowed.');
    }
  }
</script>
  
</body>
</html>