<?php
include 'includes/session.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "includes/header.php" ?>
</head>
<body>
  <div class="d-flex w-100 w-full">
    <!-- Sidebar -->
    <?php include "includes/sidebar.php" ?>

    <!-- Main Content -->
    <div id="content" class="flex-grow-1">
      <!-- Top Navigation -->
      <?php include "includes/topnav.php" ?>

      <!-- Dashboard -->
      <div class="container-fluid mt-4 ">

      
        <!-- Room Types Table -->
        <div class="row mt-4">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header bg-dark">
              <h5 class="card-title text-light">List of Admins</h5>
              </div>
              <div class="card-body">
             
<div class="d-flex justify-content-end mb-3">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
    Add New Admin
  </button>
</div>
<table class="table" id="myTable">
  <thead>
    <tr>
      <th>Photo</th>
      <th>Full Name</th>
      <th>Email</th>
      <th>Username</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    include('functions/connection.php');

    $query = "SELECT * FROM admin WHERE role='admin'";
    $result = $conn->query($query);

    if ($result->num_rows > 0):
      $admins = $result->fetch_all(MYSQLI_ASSOC);
      foreach ($admins as $admin): ?>
        <tr>
          <td><img src="uploads/<?php echo htmlspecialchars($admin['photo']); ?>" alt="Admin Photo" width="50"></td>
          <td><?php echo htmlspecialchars($admin['fullname'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars($admin['email'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars($admin['role'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td>
            <button type="button" class="btn btn-success btn-sm editAdminBtn" data-bs-toggle="modal" data-bs-target="#editAdminModal"
              data-id="<?php echo $admin['admin_id']; ?>"
              data-email="<?php echo htmlspecialchars($admin['email'], ENT_QUOTES, 'UTF-8'); ?>"
              data-username="<?php echo htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8'); ?>"
              data-fullname="<?php echo htmlspecialchars($admin['fullname'], ENT_QUOTES, 'UTF-8'); ?>"
              data-role="<?php echo htmlspecialchars($admin['role'], ENT_QUOTES, 'UTF-8'); ?>">
              Edit
            </button>
            <button type="button" class="btn btn-danger btn-sm deleteAdminBtn" data-id="<?php echo $admin['admin_id']; ?>">
              Delete
            </button>
          </td>
        </tr>
      <?php endforeach;
    endif;
    ?>
  </tbody>
</table>

              </div>
            </div>
          </div>
        </div>

      </div>
  </div>

  <?php include "includes/footer.php" ?>

<!-- Modal for Adding New Admin -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAdminModalLabel">Add New Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="functions/add_admin.php" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required oninput="validateAlphanumeric(this)">
          </div>
          <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required oninput="validateAlphanumeric(this)">
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role" required>
              <option value="admin">Admin</option>
              <option value="superadmin">Super Admin</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Admin</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Editing Admin -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="functions/update_admin.php" enctype="multipart/form-data">
          <input type="hidden" id="editAdminId" name="adminId">
          <div class="mb-3">
            <label for="editEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="editUsername" class="form-label">Username</label>
            <input type="text" class="form-control" id="editUsername" name="username" required oninput="validateAlphanumeric(this)">
          </div>
          <div class="mb-3">
            <label for="editFullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="editFullname" name="fullname" required oninput="validateAlphanumeric(this)">
          </div>
          <div class="mb-3">
            <label for="editRole" class="form-label">Role</label>
            <select class="form-control" id="editRole" name="role" required>
              <option value="admin">Admin</option>
              <option value="superadmin">Super Admin</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="editPhoto" class="form-label">Photo</label>
            <input type="file" class="form-control" id="editPhoto" name="photo">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Add validation functions
function validateAlphanumeric(input) {
  const regex = /^[a-zA-Z0-9\s]+$/;  // Allows alphanumeric characters and spaces
  if (!regex.test(input.value)) {
    input.setCustomValidity('Only alphanumeric characters and spaces are allowed.');
  } else {
    input.setCustomValidity('');
  }
}
</script>



  <script>
    $(document).ready(function () {
  $('.editAdminBtn').on('click', function () {
    const adminId = $(this).data('id');
    const email = $(this).data('email');
    const username = $(this).data('username');
    const fullname = $(this).data('fullname');
    const role = $(this).data('role');

    // Populate the modal fields
    $('#editAdminId').val(adminId);
    $('#editEmail').val(email);
    $('#editUsername').val(username);
    $('#editFullname').val(fullname);
    $('#editRole').val(role);
  });

  $('.deleteAdminBtn').on('click', function (e) {
    e.preventDefault();

    const adminId = $(this).data('id');

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
        window.location.href = `functions/delete_admin.php?id=${adminId}`;
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
