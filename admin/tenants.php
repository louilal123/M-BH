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

      <div class="container-fluid mt-4">
        <div class="row mt-4">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header bg-dark">
              <h5 class="card-title text-light">List of Tenants</h5>
              </div>
              <div class="card-body">
               
                <div class="d-flex justify-content-end mb-3">
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnewtenant">
                    Add New Tenant
                  </button>
                </div>
</button>


                <div class="">
                <table class="table table-striped table-bordered" id="myTable">
                 <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      
                      include('functions/connection.php');
                      $query = "SELECT tenant_id, name, email, contact, address, created_at, status, photo FROM tenants";
                      $result = $conn->query($query);

                      if ($result->num_rows > 0):
                          $tenants = $result->fetch_all(MYSQLI_ASSOC);
                          foreach ($tenants as $tenant): ?>
                              <tr>
                    <td><?php echo htmlspecialchars($tenant['tenant_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php if (!empty($tenant['photo'])): ?>
                            <img src="uploads/tenants/<?php echo htmlspecialchars($tenant['photo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Tenant Photo" class="img-thumbnail" width="50" height="50">
                        <?php else: ?>
                            <span>No photo</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($tenant['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($tenant['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($tenant['contact'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($tenant['address'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($tenant['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php 
                        $status = htmlspecialchars($tenant['status'], ENT_QUOTES, 'UTF-8'); 
                        if ($status === 'active') {
                            echo '<span class="badge bg-success">Active</span>';
                        } else {
                            echo '<span class="badge bg-secondary">Inactive</span>';
                        } 
                        ?>
                          </td>
                          <td>
                          <button 
                          type="button" 
                          class="btn btn-success btn-sm editTenantBtn" 
                          data-bs-toggle="modal" 
                          data-bs-target="#editTenantModal" 
                          data-id="<?php echo $tenant['tenant_id']; ?>"
                          data-name="<?php echo htmlspecialchars($tenant['name'], ENT_QUOTES, 'UTF-8'); ?>"
                          data-email="<?php echo htmlspecialchars($tenant['email'], ENT_QUOTES, 'UTF-8'); ?>"
                          data-contact="<?php echo htmlspecialchars($tenant['contact'], ENT_QUOTES, 'UTF-8'); ?>"
                          data-address="<?php echo htmlspecialchars($tenant['address'], ENT_QUOTES, 'UTF-8'); ?>"
                          data-status="<?php echo htmlspecialchars($tenant['status'], ENT_QUOTES, 'UTF-8'); ?>"
                          data-photo="<?php echo htmlspecialchars($tenant['photo'], ENT_QUOTES, 'UTF-8'); ?>"> 
                          Edit
                      </button>

                                            <button 
                                              type="button" 
                                              class="btn btn-danger btn-sm deleteTenantBtn" 
                                              data-id="<?php echo $tenant['tenant_id']; ?>">
                                              Delete
                                            </button>
                                    </td>
                                </tr>
                            <?php endforeach;
                       ?>
                         
                        <?php endif; ?>
                    </tbody>
                </table>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 

  <div class="modal fade" id="editTenantModal" tabindex="-1" aria-labelledby="editTenantModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTenantModalLabel">Edit Tenant Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="functions/edit_tenant.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="tenant_id" id="tenant_id">

          <!-- Full Name -->
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required oninput="validateAlphanumeric(this)">
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>

          <!-- Phone -->
          <div class="mb-3">
            <label for="contact" class="form-label">Phone</label>
            <input type="text" class="form-control" id="contact" name="contact" required oninput="validatePhone(this)">
          </div>

          <!-- Address -->
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required oninput="validateAlphanumeric(this)">
          </div>

          <!-- Status -->
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

          <!-- Current Photo -->
          <div class="mb-3">
            <label for="currentPhoto" class="form-label">Current Photo</label>
            <div>
              <img id="currentPhotoPreview" src="uploads/tenants/default.png" alt="Tenant Photo" class="img-thumbnail" style="max-width: 100px;">
            </div>
          </div>

          <!-- Change Photo -->
          <div class="mb-3">
            <label for="photo" class="form-label">Change Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            <small class="text-muted">Upload a JPEG, PNG, or GIF image. Max size: 2MB.</small>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="save_tenant" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addnewtenant" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="signupModalLabel">Add New Tenant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="functions/signup.php" enctype="multipart/form-data" method="POST" class="mt-3">
          <!-- Full Name -->
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required oninput="validateAlphanumeric(this)">
          </div>

          <!-- Email Address -->
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>

          <!-- Hidden Password -->
          <input type="hidden" name="password" id="password" class="form-control" value="123">

         <!-- Contact Number -->
<div class="mb-3">
  <label for="contact" class="form-label">Contact Number</label>
  <input type="tel" name="contact" id="contact" class="form-control" required oninput="validatePhoneNumber(this)">
</div>

<script>
  function validatePhoneNumber(input) {
    // Ensure the input only contains numbers and is limited to 11 digits
    input.value = input.value.replace(/[^0-9]/g, '').slice(0, 11);
  }
</script>


          <!-- Address -->
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control" rows="3" required oninput="validateAlphanumeric(this)"></textarea>
          </div>

          <!-- Photo -->
          <div class="mb-3">
            <label for="photo" class="form-label">Upload Photo</label>
            <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="edit_event" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



  <?php include "includes/footer.php" ?>

 <script>
  // JavaScript to handle modal population with tenant data
$(document).ready(function () {
    $('.editTenantBtn').on('click', function () {
        var tenantId = $(this).data('id');
        var name = $(this).data('name');
        var email = $(this).data('email');
        var contact = $(this).data('contact');
        var address = $(this).data('address');
        var status = $(this).data('status');
        var photo = $(this).data('photo'); // Add this to handle the photo

        // Populate modal fields with tenant data
        $('#tenant_id').val(tenantId);
        $('#name').val(name);
        $('#email').val(email);
        $('#contact').val(contact);
        $('#address').val(address);
        $('#status').val(status);

        // Update the photo preview if a photo exists
        if (photo) {
            $('#currentPhotoPreview').attr('src', 'uploads/tenants/' + photo);
        } else {
            $('#currentPhotoPreview').attr('src', 'uploads/tenants/default.png');
        }
    });
});

 </script>

  <script>
    $(document).ready(function () {
      // When the delete button is clicked
      $('.deleteTenantBtn').on('click', function (e) {
        e.preventDefault(); // Prevent default button behavior

        const tenantId = $(this).data('id'); // Get the ID of the tenant

        Swal.fire({
          title: 'Are you sure?',
          text: 'This action cannot be undone!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Delete',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            // Redirect to the delete script with the tenant ID
            window.location.href = `functions/delete_tenant.php?id=${tenantId}`;
          }
        });
      });
    });
  </script>
  
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
