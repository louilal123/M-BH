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
              <h5 class="card-title text-light">List of Room Types</h5>
              </div>
              <div class="card-body">
              
                <div class="d-flex justify-content-end mb-3">
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal">
                    Add New
                  </button>
                </div>
                <table class="table" id="myTable">
                  <thead>
                    <tr>
                      <th>Room Type Name</th>
                      <th>Description</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include('functions/connection.php');

                    $query = "SELECT * FROM room_types";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0):
                      $roomTypes = $result->fetch_all(MYSQLI_ASSOC);
                      foreach ($roomTypes as $roomType): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($roomType['type_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?php echo htmlspecialchars($roomType['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                          <td>
                            <button 
                              type="button" 
                              class="btn btn-success btn-sm editRoomTypeBtn" 
                              data-bs-toggle="modal" 
                              data-bs-target="#editRoomTypeModal" 
                              data-id="<?php echo $roomType['type_id']; ?>"
                              data-name="<?php echo htmlspecialchars($roomType['type_name'], ENT_QUOTES, 'UTF-8'); ?>"
                              data-description="<?php echo htmlspecialchars($roomType['description'], ENT_QUOTES, 'UTF-8'); ?>">
                              Edit
                            </button>
                            <button 
                              type="button" 
                              class="btn btn-danger btn-sm deleteRoomTypeBtn" 
                              data-id="<?php echo $roomType['type_id']; ?>">
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

 <!-- Modal for Adding New Room Type -->
<div class="modal fade" id="addRoomTypeModal" tabindex="-1" aria-labelledby="addRoomTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoomTypeModalLabel">Add New Room Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="functions/add_room_type.php">
          <!-- Room Type form fields -->
          <div class="mb-3">
            <label for="typeName" class="form-label">Room Type Name</label>
            <input 
              type="text" 
              class="form-control" 
              id="typeName" 
              name="typeName" 
              required 
              oninput="validateAlphanumeric(this)">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea 
              class="form-control" 
              id="description" 
              name="description" 
              required 
              oninput="validateAlphanumeric(this)"></textarea>
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

<!-- Modal for Editing Room Type -->
<div class="modal fade" id="editRoomTypeModal" tabindex="-1" aria-labelledby="editRoomTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRoomTypeModalLabel">Edit Room Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="functions/update_room_type.php">
          <input type="hidden" id="editTypeId" name="typeId">
          <!-- Room Type form fields -->
          <div class="mb-3">
            <label for="editTypeName" class="form-label">Room Type Name</label>
            <input 
              type="text" 
              class="form-control" 
              id="editTypeName" 
              name="typeName" 
              required 
              oninput="validateAlphanumeric(this)">
          </div>
          <div class="mb-3">
            <label for="editDescription" class="form-label">Description</label>
            <textarea 
              class="form-control" 
              id="editDescription" 
              name="description" 
              required 
              oninput="validateAlphanumeric(this)"></textarea>
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
  $(document).ready(function () {
    $('.editRoomTypeBtn').on('click', function () {
      const typeId = $(this).data('id');
      const typeName = $(this).data('name');
      const description = $(this).data('description');

      // Populate the modal fields
      $('#editTypeId').val(typeId);
      $('#editTypeName').val(typeName);
      $('#editDescription').val(description);
    });
  });
</script>

<script>
  $(document).ready(function () {
    $('.deleteRoomTypeBtn').on('click', function (e) {
      e.preventDefault(); // Prevent default button behavior

      const typeId = $(this).data('id'); // Get the ID of the room type

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
          // Redirect to the delete script with the type ID
          window.location.href = `functions/delete_room_type.php?id=${typeId}`;
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
