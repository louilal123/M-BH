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
              <div class="card-header bg-dark">
              <h5 class="card-title text-light">List of Rooms</h5>
              </div>
              <div class="card-body">
              
                <div class="d-flex justify-content-end mb-3">
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                    Add New Room
                  </button>
                </div>
                <table class="table table-responsive" id="myTable">
                  <thead>
                    <tr>
                      <th>Room Number</th>
                      <th>Room Type</th>
                      <th>Rent Price</th>
                      <th>Availability</th>
                      <th>Description</th>
                      <th>Photo</th> <!-- Added Photo column -->
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include('functions/connection.php');

                    // Fetch rooms from the database
                    $query = "SELECT r.room_id, r.room_number, rt.type_name, r.price, r.availability, r.description, r.photo 
                            FROM rooms r
                            JOIN room_types rt ON r.room_type = rt.type_id";
                    $result = $conn->query($query);

                    // Check if there are rooms to display
                    if ($result->num_rows > 0) {
                        $rooms = $result->fetch_all(MYSQLI_ASSOC);  // Fetch all rooms as an associative array

                        foreach ($rooms as $room): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($room['room_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($room['type_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo "Php " . number_format($room['price'], 2); ?></td>
                                <td>
                                    <?php 
                                    if ($room['availability'] === 'available') {
                                        echo '<span class="badge bg-success">Available</span>';
                                    } elseif ($room['availability'] === 'occupied') {
                                        echo '<span class="badge bg-warning">Occupied</span>';
                                    } elseif ($room['availability'] === 'under maintenance') {
                                        echo '<span class="badge bg-secondary">Under Maintenance</span>';
                                    }
                                    ?>
                                </td>


                                <td><?php echo htmlspecialchars($room['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                  <img src="assets/uploads/<?php echo htmlspecialchars($room['photo'], ENT_QUOTES, 'UTF-8'); ?>" 
                                       alt="Room Image" style="width: 50px; height: 50px;">
                                </td>
                                <td>
                                  <button class="btn btn-success btn-sm editRoomBtn" 
                                          data-id="<?php echo $room['room_id']; ?>"
                                          data-roomnumber="<?php echo htmlspecialchars($room['room_number'], ENT_QUOTES, 'UTF-8'); ?>"
                                          data-roomtype="<?php echo htmlspecialchars($room['type_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                          data-price="<?php echo htmlspecialchars($room['price'], ENT_QUOTES, 'UTF-8'); ?>"
                                          data-description="<?php echo htmlspecialchars($room['description'], ENT_QUOTES, 'UTF-8'); ?>"
                                          data-photo="<?php echo $room['photo']; ?>"
                                          data-availability="<?php echo $room['availability']; ?>" >
                                      Edit
                                  </button>
                                  <button type="button" class="btn btn-danger btn-sm deleteRoomBtn" 
                                          data-id="<?php echo $room['room_id']; ?>"> 
                                      Delete
                                  </button>
                                </td>
                            </tr>
                        <?php endforeach;
                    } 
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <?php include "includes/footer.php" ?>

<!-- Modal for Adding New Room -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoomModalLabel">Add New Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="functions/add_room.php" enctype="multipart/form-data">
          <!-- Room Number -->
          <div class="mb-3">
            <label for="roomNumber" class="form-label">Room Number</label>
            <input type="text" class="form-control" id="roomNumber" name="roomNumber" required oninput="validateNumeric(this)">
          </div>
          <!-- Room Type -->
          <div class="mb-3">
            <label for="roomType" class="form-label">Room Type</label>
            <select class="form-select" id="roomType" name="roomType" required>
              <option value="" disabled selected>Select Room Type</option>
              <?php
              $query = "SELECT * FROM room_types";
              $result = $conn->query($query);
              while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['type_id'] . "'>" . $row['type_name'] . "</option>";
              }
              ?>
            </select>
          </div>
          <!-- Price -->
          <div class="mb-3">
            <label for="roomPrice" class="form-label">Price</label>
            <input type="number" class="form-control" id="roomPrice" name="price" required oninput="validateNumeric(this)">
          </div>
          <!-- Description -->
          <div class="mb-3">
            <label for="roomDescription" class="form-label">Description</label>
            <textarea class="form-control" id="roomDescription" name="description" rows="3" required oninput="validateAlphanumeric(this)"></textarea>
          </div>
          <!-- Photo -->
          <div class="mb-3">
            <label for="roomPhoto" class="form-label">Photo</label>
            <input type="file" class="form-control" id="roomPhoto" name="photo" accept="image/*">
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="save_room" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Editing Room -->
<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="functions/update_room.php" enctype="multipart/form-data">
          <input type="hidden" id="editRoomId" name="roomId">
          
          <!-- Room Number -->
          <div class="mb-3">
            <label for="editRoomNumber" class="form-label">Room Number</label>
            <input type="text" class="form-control" id="editRoomNumber" name="roomNumber" required oninput="validateNumeric(this)">
          </div>

          <!-- Room Type -->
          <div class="mb-3">
            <label for="editRoomType" class="form-label">Room Type</label>
            <select class="form-select" id="editRoomType" name="roomType" required>
              <option value="" disabled>Select Room Type</option>
              <?php
                include 'functions/connection.php'; // Include database connection

                $query = "SELECT * FROM room_types";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                  echo "<option value='" . $row['type_id'] . "'>" . $row['type_name'] . "</option>";
                }
              ?>
            </select>
          </div>

          <!-- Price -->
          <div class="mb-3">
            <label for="editRoomPrice" class="form-label">Rent Amount</label>
            <input type="number" class="form-control" id="editRoomPrice" name="price" required oninput="validateNumeric(this)">
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label for="editRoomDescription" class="form-label">Description</label>
            <textarea class="form-control" id="editRoomDescription" name="description" rows="3" required oninput="validateAlphanumeric(this)"></textarea>
          </div>

          <!-- Availability -->
          <select class="form-select" id="editRoomAvailability" name="availability" required>
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="under maintenance">Under Maintenance</option>
            <option value="reserved">Reserved</option>
          </select>

          <!-- Photo -->
          <div class="mb-3">
            <label for="editRoomPhoto" class="form-label">Photo</label>
            <input type="file" class="form-control" id="editRoomPhoto" name="photo" accept="image/*">
            <img src="" id="currentRoomPhoto" alt="Current Room Photo" class="mt-3" style="max-width: 100px;">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="save_room" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>





  <script>
  $(document).ready(function () {
    $('.addRoomModal').on('click', function () {
      
      $('#addRoomModal').modal('show');
    });
  });
</script>
<script>
  $(document).ready(function () {
    $('.editRoomBtn').on('click', function () {
      const roomId = $(this).data('id');
      const roomNumber = $(this).data('roomnumber');
      const roomType = $(this).data('roomtype');  // This is the type_name
      const price = $(this).data('price');
      const description = $(this).data('description');
      const photo = $(this).data('photo');
      const availability = $(this).data('availability');  

      // Set the values in the modal
      $('#editRoomId').val(roomId);
      $('#editRoomNumber').val(roomNumber);
      $('#editRoomPrice').val(price);
      $('#editRoomDescription').val(description);
      $('#currentRoomPhoto').attr('src', 'assets/uploads/' + photo);
      $('#editRoomAvailability').val(availability);  

      // Set the room type in the dropdown by matching type_name
      $('#editRoomType option').each(function() {
        if ($(this).text() === roomType) {
          $(this).prop('selected', true);
        }
      });

      // Show the modal
      $('#editRoomModal').modal('show');
    });
  });
</script>


<script>
  $(document).ready(function () {
    $('.deleteRoomBtn').on('click', function (e) {
      e.preventDefault(); // Prevent default button behavior

      const roomId = $(this).data('id'); // Get the ID of the room

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
          // Redirect to the delete script with the room ID
          window.location.href = `functions/delete_room.php?id=${roomId}`;
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