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

    <div id="content" class="flex-grow-1">
    
      <?php include "includes/topnav.php" ?>

      <div class="container-fluid mt-4">

        <div class="row mt-4">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header bg-dark">
              <h5 class="card-title text-light">Room Assignments</h5>
                
              </div>
              <div class="card-body">
                
                <div class="d-flex justify-content-end mb-3">
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                    Assign Tenant
                  </button>
                </div>

                <div class="">
                  <table class="table table-striped table-bordered" id="myTable">
                    <thead>
                      <tr>
                        <th> ID</th>
                        <th>Tenant</th>
                        <th>Room</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                       
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      include('functions/connection.php');

                      // Fetch all bookings from the database
                      $query = "SELECT b.booking_id, t.name AS tenant_name, r.room_number, b.check_in_date, b.check_out_date
                                FROM bookings b
                                JOIN tenants t ON b.tenant_id = t.tenant_id
                                JOIN rooms r ON b.room_id = r.room_id";
                      $result = $conn->query($query);

                      if ($result->num_rows > 0) {
                          $bookings = $result->fetch_all(MYSQLI_ASSOC);
                          foreach ($bookings as $booking): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($booking['booking_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                              <td><?php echo htmlspecialchars($booking['tenant_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                              <td><?php echo htmlspecialchars($booking['room_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                              <td><?php echo htmlspecialchars($booking['check_in_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                              <td><?php echo htmlspecialchars($booking['check_out_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                           
                              <td>
                              <button type="button" class="btn btn-success btn-sm editBookingBtn" data-bs-toggle="modal" data-bs-target="#editBookingModal" 
                                data-id="<?php echo $booking['booking_id']; ?>"
                                data-tenant="<?php echo htmlspecialchars($booking['tenant_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-room="<?php echo htmlspecialchars($booking['room_number'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-checkin="<?php echo htmlspecialchars($booking['check_in_date'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-checkout="<?php echo htmlspecialchars($booking['check_out_date'], ENT_QUOTES, 'UTF-8'); ?>"
                              >
                                Edit
                              </button>


                                <button 
                                  type="button" 
                                  class="btn btn-danger btn-sm deleteBookingBtn" 
                                  data-id="<?php echo $booking['booking_id']; ?>">
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
  </div>

  <div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookingModalLabel">Assign Tenant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>         
            <div class="modal-body">
            <form action="functions/add_booking.php" method="POST">
    <div class="mb-3">
        <label for="tenant" class="form-label">Tenant</label>
        <select name="tenant" id="tenant" class="form-control" required>
        <?php
            include "functions/connection.php";

            // Get current date
            $currentDate = date('Y-m-d'); // current date in YYYY-MM-DD format

            // Query to get tenants who do not have any active booking for the current date
            $tenantsQuery = "
                SELECT t.tenant_id, t.name 
                FROM tenants t
                LEFT JOIN bookings b ON t.tenant_id = b.tenant_id
                AND ((b.check_in_date <= '$currentDate' AND b.check_out_date >= '$currentDate') 
                OR (b.check_in_date BETWEEN '$currentDate' AND '$currentDate')
                OR (b.check_out_date BETWEEN '$currentDate' AND '$currentDate'))
                WHERE b.booking_id IS NULL OR b.booking_id IS NOT NULL
                GROUP BY t.tenant_id";
                
            $tenantsResult = $conn->query($tenantsQuery);

            // Check if query returns results
            if ($tenantsResult->num_rows > 0) {
                while ($tenant = $tenantsResult->fetch_assoc()) {
                    echo "<option value='{$tenant['tenant_id']}'>{$tenant['name']}</option>";
                }
            } else {
                echo "<option value=''>No tenants available</option>";  // Message if no tenants without bookings
            }
        ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="room" class="form-label">Room</label>
        <select name="room" id="room" class="form-control" required>
            <?php
                // Query to fetch rooms that are available
                $roomsQuery = "SELECT room_id, room_number FROM rooms WHERE availability = 'available'";
                $roomsResult = $conn->query($roomsQuery);

                // Display available rooms in dropdown
                while ($room = $roomsResult->fetch_assoc()):
                    echo "<option value='{$room['room_id']}'>{$room['room_number']}</option>";
                endwhile;
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="checkin" class="form-label">Start Date</label>
        <input type="date" name="checkin" id="checkin" class="form-control" required onchange="updateCheckoutDate()">
    </div>

    <div class="mb-3">
        <label for="checkout" class="form-label">End Date</label>
        <input type="date" name="checkout" id="checkout" class="form-control" readonly required>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

            </div>
        </div>
    </div>
</div>

<!-- Edit Booking Modal -->
<div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBookingModalLabel">Edit Room Assignment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="functions/edit_booking.php" method="POST">
          <input type="hidden" name="booking_id" id="editBookingId">
          <div class="mb-3">
            <label for="editTenant" class="form-label">Tenant</label>
            <select name="tenant" id="editTenant" class="form-control" required>
              <?php
                // Fetch tenants from database
                $tenantsResult = $conn->query("SELECT tenant_id, name FROM tenants");
                while ($tenant = $tenantsResult->fetch_assoc()):
                  echo "<option value='{$tenant['tenant_id']}'>{$tenant['name']}</option>";
                endwhile;
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="editRoom" class="form-label">Room</label>
            <select name="room" id="editRoom" class="form-control" required>
              <?php
                // Fetch rooms from database
                $roomsResult = $conn->query("SELECT room_id, room_number FROM rooms");
                while ($room = $roomsResult->fetch_assoc()):
                  echo "<option value='{$room['room_id']}'>{$room['room_number']}</option>";
                endwhile;
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="editCheckin" class="form-label">Check-in Date</label>
            <input type="date" name="checkin" id="editCheckin" class="form-control" required onchange="updateCheckoutDate1()">
          </div>
          <div class="mb-3">
            <label for="editCheckout" class="form-label">Check-out Date</label>
            <input type="date" name="checkout" id="editCheckout" class="form-control" required readonly>
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

  <?php include "includes/footer.php" ?>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
  // Get all the "Edit" buttons
  const editButtons = document.querySelectorAll('.editBookingBtn');
  
  editButtons.forEach(button => {
    button.addEventListener('click', function () {
      // Get the data attributes from the clicked button
      const bookingId = this.getAttribute('data-id');
      const tenantName = this.getAttribute('data-tenant');
      const roomNumber = this.getAttribute('data-room');
      const checkinDate = this.getAttribute('data-checkin');
      const checkoutDate = this.getAttribute('data-checkout');
      
      // Set the hidden booking id field
      document.getElementById('editBookingId').value = bookingId;
      
      // Set the tenant field (you might want to find the tenant_id based on tenantName)
      const tenantDropdown = document.getElementById('editTenant');
      for (let option of tenantDropdown.options) {
        if (option.text === tenantName) {
          option.selected = true;
          break;
        }
      }

      // Set the room field (similarly, find the room_id based on roomNumber)
      const roomDropdown = document.getElementById('editRoom');
      for (let option of roomDropdown.options) {
        if (option.text === roomNumber) {
          option.selected = true;
          break;
        }
      }

      // Set the check-in and check-out dates
      document.getElementById('editCheckin').value = checkinDate;
      document.getElementById('editCheckout').value = checkoutDate;
    });
  });
});

  </script>


<script>
  // Disable past dates for the check-in field
  document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('editCheckin').setAttribute('min', today);
  });

  // Automatically calculate the check-out date as 30 days after the selected check-in date
  function updateCheckoutDate1() {
    var checkinDate = document.getElementById('editCheckin').value;
    if (checkinDate) {
      var checkinDateObj = new Date(checkinDate);
      checkinDateObj.setDate(checkinDateObj.getDate() + 30); // Add 30 days
      var checkoutDate = checkinDateObj.toISOString().split('T')[0];
      document.getElementById('editCheckout').value = checkoutDate;
    }
  }
</script>
  <script>
    // Get today's date in the format YYYY-MM-DD
    const today = new Date().toISOString().split('T')[0];

    // Set the min attribute of the checkin input to today's date
    document.getElementById('checkin').setAttribute('min', today);
</script>
<script>
    // Automatically calculate the check-out date based on the check-in date
    function updateCheckoutDate() {
        const checkinDateInput = document.getElementById('checkin');
        const checkoutDateInput = document.getElementById('checkout');
        
        if (checkinDateInput.value) {
            const checkinDate = new Date(checkinDateInput.value);
            // Add 30 days to the check-in date
            checkinDate.setDate(checkinDate.getDate() + 30);
            // Format date to YYYY-MM-DD
            const formattedDate = checkinDate.toISOString().split('T')[0];
            // Set the checkout date value
            checkoutDateInput.value = formattedDate;
        }
    }
</script>

<script>
  $(document).ready(function () {
    $('.deleteBookingBtn').on('click', function (e) {
      e.preventDefault(); // Prevent default button behavior

      const bookingId = $(this).data('id'); // Get the ID of the booking

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
          // Redirect to the delete script with the booking ID
          window.location.href = `functions/delete_booking.php?id=${bookingId}`;
        }
      });
    });
  });
</script>
<?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
        <script>
            Swal.fire({
                icon: "<?php echo $_SESSION['status_icon']; ?>",
                title: "<?php echo $_SESSION['status']; ?>",
                confirmButtonText: "Ok"
            });
        </script>
        <?php
        unset($_SESSION['status']);
        unset($_SESSION['status_icon']);
        ?>
    <?php endif; ?>

</body>
</html>
