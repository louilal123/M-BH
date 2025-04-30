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
      <div class="container-fluid mt-4">

        <!-- Payments Table -->
        <div class="row mt-4">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header bg-dark">
              
              <h5 class="card-title text-light">List of Payments</h5>
              </div>
              <div class="card-body">
                
                <table class="table" id="myTable">
                  <thead>
                    <tr>
                      <th>Tenant Name</th>
                      <th>Room Number</th>
                      <th>Amount Due</th>
                      <th>Amount Paid</th>
                      <th>Payment Date</th>
                      <th>Payment Method</th>
                      <th>Payment Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include('functions/connection.php');

                    // Fetch payment data
                    $query = "SELECT p.payment_id, t.name as tenant_name, r.room_number, p.amount_due, p.amount_paid, p.payment_date, p.payment_method, p.payment_status 
                              FROM payments p
                              JOIN tenants t ON p.tenant_id = t.tenant_id
                              JOIN bookings b ON p.booking_id = b.booking_id
                              JOIN rooms r ON b.room_id = r.room_id";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0):
                      $payments = $result->fetch_all(MYSQLI_ASSOC);
                      foreach ($payments as $payment): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($payment['tenant_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?php echo htmlspecialchars($payment['room_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?php echo "Php " . number_format($payment['amount_due'], 2); ?></td>
                          <td><?php echo "Php " . number_format($payment['amount_paid'], 2); ?></td>
                          <td><?php echo date('Y-m-d', strtotime($payment['payment_date'])); ?></td>

                          <td><?php echo htmlspecialchars($payment['payment_method'], ENT_QUOTES, 'UTF-8'); ?></td>
                          <td>
                            <?php 
                              $status = htmlspecialchars($payment['payment_status'], ENT_QUOTES, 'UTF-8');
                              if ($status == 'completed') {
                                  echo '<span class="badge bg-success">Completed</span>';
                              } else {
                                  echo '<span class="badge bg-secondary">' . $status . '</span>';
                              }
                            ?>
                          </td>

                          <td>
                        
                          <button 
                            type="button" 
                            class="btn btn-info btn-sm editPaymentBtn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editPaymentModal" 
                            data-id="<?php echo $payment['payment_id']; ?>"
                            data-tenant="<?php echo htmlspecialchars($payment['tenant_name'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-room="<?php echo htmlspecialchars($payment['room_number'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-amountdue="<?php echo $payment['amount_due']; ?>" 
                            data-amountpaid="<?php echo $payment['amount_paid']; ?>"
                            data-paymentmethod="<?php echo htmlspecialchars($payment['payment_method'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-paymentstatus="<?php echo htmlspecialchars($payment['payment_status'], ENT_QUOTES, 'UTF-8'); ?>"
                          >
                            View
                          </button>
                          <button 
                            type="button" 
                            class="btn btn-danger btn-sm deletePaymentBtn" 
                            data-id="<?php echo $payment['payment_id']; ?>">
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

  </div>

  <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
        <button 
        type="button" 
        class="btn btn-danger" 
        onclick="printPaymentDetails()">
        Print
      </button>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Print Button -->
      
      <hr>

      <div class="modal-body">
        <form action="functions/edit_payment.php" method="POST">
          <input type="hidden" name="payment_id" id="payment_id">

          <div class="mb-3">
            <label for="tenant" class="form-label">Tenant</label>
            <input type="text" class="form-control" id="tenant" name="tenant" readonly>
          </div>
          <div class="mb-3">
            <label for="room" class="form-label">Room Number</label>
            <input type="text" class="form-control" id="room" name="room" readonly>
          </div>
          <div class="mb-3">
            <label for="amount_due" class="form-label">Amount Due</label>
            <input type="number" class="form-control" id="amount_due" name="amount_due" required>
          </div>
          <div class="mb-3">
            <label for="amount_paid" class="form-label">Amount Paid</label>
            <input type="number" class="form-control" id="amount_paid" name="amount_paid" required>
          </div>
          <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
              <option value="cash">Cash</option>
              <option value="credit_card">Credit Card</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="payment_status" class="form-label">Payment Status</label>
            <select name="payment_status" id="payment_status" class="form-control" required>
              <option value="completed">Completed</option>
              <option value="pending">Pending</option>
              <option value="failed">Partial</option>
            </select>
          </div>
          
          <div class="modal-footer">
        
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript for Print functionality -->

<!-- JavaScript for Print functionality -->
<script>
  function printPaymentDetails() {
    // Get values from the modal
    var tenantName = document.getElementById("tenant").value;
    var roomNumber = document.getElementById("room").value;
    var amountDue = document.getElementById("amount_due").value;
    var amountPaid = document.getElementById("amount_paid").value;
    var paymentMethod = document.getElementById("payment_method").value;
    var paymentStatus = document.getElementById("payment_status").value;
    var currentDate = new Date().toLocaleDateString();

    // Create the printable content
    var printableContent = `
      <h2>MecMec Boarding House</h2>
      <h3>Payment Details</h3>
      <p>Date: ${currentDate}</p>
      <hr>
      <p><strong>Tenant:</strong> ${tenantName}</p>
      <p><strong>Room Number:</strong> ${roomNumber}</p>
      <p><strong>Amount Due:</strong> ${amountDue}</p>
      <p><strong>Amount Paid:</strong> ${amountPaid}</p>
      <p><strong>Payment Method:</strong> ${paymentMethod}</p>
      <p><strong>Payment Status:</strong> ${paymentStatus}</p>
    `;

    // Open a new window for printing
    var printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write('<html><head><style>h2, h3 { text-align: center; }</style><title>Print Payment Details</title></head><body>');
    printWindow.document.write(printableContent); // Include the printable content
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    // Wait for the document to load and then print
    printWindow.onload = function () {
      printWindow.print();
      printWindow.close();
    };
  }
</script>

  <?php include "includes/footer.php" ?>
 
  

  <script>
 $(document).ready(function () {
  // Edit button click handler
  $('.editPaymentBtn').on('click', function () {
    const paymentId = $(this).data('id');
    const tenant = $(this).data('tenant');
    const room = $(this).data('room');
    const amountDue = $(this).data('amountdue');
    const amountPaid = $(this).data('amountpaid');
    const paymentMethod = $(this).data('paymentmethod');
    const paymentStatus = $(this).data('paymentstatus');

    // Debugging: Log fetched values
    console.log({
      paymentId,
      tenant,
      room,
      amountDue,
      amountPaid,
      paymentMethod,
      paymentStatus,
    });

    // Populate the modal fields
    $('#editPaymentModal #payment_id').val(paymentId);
    $('#editPaymentModal #tenant').val(tenant);
    $('#editPaymentModal #room').val(room);
    $('#editPaymentModal #amount_due').val(amountDue);  // Set Amount Due
    $('#editPaymentModal #amount_paid').val(amountPaid);  // Set Amount Paid
    $('#editPaymentModal #payment_method').val(paymentMethod);  // Set Payment Method
    $('#editPaymentModal #payment_status').val(paymentStatus);  // Set Payment Status
  });
});

</script>
<script>
  $(document).ready(function () {
  // Handle delete payment button click
  $('.deletePaymentBtn').on('click', function (e) {
    e.preventDefault(); // Prevent default behavior

    const paymentId = $(this).data('id'); // Get the Payment ID

    // SweetAlert2 confirmation dialog
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
        // Redirect to the delete script with the payment ID
        window.location.href = `functions/delete_payment.php?id=${paymentId}`;
      }
    });
  });
});

</script>

<!-- Your existing HTML structure here -->

<script>
  // When the 'Edit' button is clicked
  const editPaymentButtons = document.querySelectorAll('.editPaymentBtn');

  editPaymentButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Get data from the button's data-* attributes
      const paymentId = this.getAttribute('data-id');
      const tenantName = this.getAttribute('data-tenant');
      const roomNumber = this.getAttribute('data-room');
      const amountDue = this.getAttribute('data-amountdue');
      const amountPaid = this.getAttribute('data-amountpaid');
      const paymentMethod = this.getAttribute('data-paymentmethod');
      const paymentStatus = this.getAttribute('data-paymentstatus');

      // Populate the modal fields with the data
      document.getElementById('payment_id').value = paymentId;
      document.getElementById('tenant').value = tenantName;
      document.getElementById('room').value = roomNumber;
      document.getElementById('amount_due').value = amountDue;
      document.getElementById('amount_paid').value = amountPaid;
      document.getElementById('payment_method').value = paymentMethod;
      document.getElementById('payment_status').value = paymentStatus;
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
