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
        <!-- Payments Report -->
        <div class="row mt-4">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Reports</h5>
                <div class="d-flex justify-content-end mb-3 col-md-3">
                  <!-- Filter Button and Input for Month and Year -->
                  <input type="month" class="form-control" id="filterMonthYear">
                  <button type="button" class="btn btn-primary ml-2" onclick="filterPayments()">Filter</button>
                  <!-- Print Button -->
                  <button type="button" class="btn btn-secondary ml-2 ms-end" onclick="printTable()">Print</button>
                </div>
                <table class="table" id="paymentTable">
                  <thead>
                    <tr>
                      <th>Payment ID</th>
                      <th>Booking ID</th>
                      <th>Tenant ID</th>
                      <th>Amount Due</th>
                      <th>Amount Paid</th>
                      <th>Payment Method</th>
                     <th>Payment Status</th>
                      <th>Payment Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Table rows will be populated dynamically based on the query -->
                  </tbody>
                </table>

                <!-- Totals Below the Table -->
                <div id="paymentTotals" class="mt-3">
                  <strong>Total Amount Due: </strong><span id="totalAmountDue">0</span><br>
                  <strong>Total Amount Paid: </strong><span id="totalAmountPaid">0</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <?php include "includes/footer.php" ?>

    <script>
  function printTable() {
    var filterMonthYear = document.getElementById('filterMonthYear').value;
    if (!filterMonthYear) {
      alert("Please select a month and year to filter.");
      return;
    }

    var year = filterMonthYear.split("-")[0];
    var month = filterMonthYear.split("-")[1];

    // Get the table data
    var tableContent = document.getElementById("paymentTable").outerHTML;

    // Get total amounts
    var totalAmountDue = document.getElementById("totalAmountDue").textContent;
    var totalAmountPaid = document.getElementById("totalAmountPaid").textContent;

    // Create the print window and write the content
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Payment Report</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
    printWindow.document.write('h1, h2 { text-align: center; margin-bottom: 10px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
    printWindow.document.write('table, th, td { border: 1px solid black; }');
    printWindow.document.write('th, td { padding: 8px; text-align: left; }');
    printWindow.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
    printWindow.document.write('.total { margin-top: 20px; font-size: 16px; font-weight: bold; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>MECMEC BOARDING HOUSE MANAGEMENT SYSTEM</h1>');
    printWindow.document.write('<h2>Payment Report</h2>');
    printWindow.document.write('<p><strong>Month and Year:</strong> ' + filterMonthYear + '</p>');
    printWindow.document.write('<p><strong>Email:</strong> mecmecbh@gmail.com</p>');
    printWindow.document.write('<p><strong>Phone:</strong> 09451295199</p>');
    printWindow.document.write('<hr>');
    printWindow.document.write(tableContent); // Add table content
    printWindow.document.write('<div class="total">');
    printWindow.document.write('<p><strong>Total Amount Due: </strong> Php ' + totalAmountDue + '</p>');
    printWindow.document.write('<p><strong>Total Amount Paid: </strong> Php ' + totalAmountPaid + '</p>');
    printWindow.document.write('</div>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
  }
</script>

<script>
function filterPayments() {
  var filterMonthYear = document.getElementById('filterMonthYear').value;

  if (!filterMonthYear) {
    alert("Please select a month and year to filter.");
    return;
  }

  var year = filterMonthYear.split("-")[0];
  var month = filterMonthYear.split("-")[1];

  // Make AJAX request to fetch the filtered payments based on the selected month and year
  $.ajax({
    url: 'functions/filter_payments.php',
    type: 'GET',
    data: { month: month, year: year },
    success: function (response) {
      var payments = JSON.parse(response);

      // Clear previous table data
      $('#paymentTable tbody').empty();
      $('#paymentTotals').hide(); // Hide totals initially

      // Variables for calculating totals
      var totalAmountDue = 0;
      var totalAmountPaid = 0;

      // Populate table with filtered data
      if (payments.length > 0) {
        payments.forEach(function (payment) {
          // Format the payment date (YYYY-MM-DD)
          var formattedDate = payment.payment_date.split(' ')[0];

          // Check if payment_method is null, if so, set to 'N/A'
          var paymentMethod = payment.payment_method ? payment.payment_method : 'N/A';

          // Add payment row
          var row = '<tr>';
          row += '<td>' + payment.payment_id + '</td>';
          row += '<td>' + payment.booking_id + '</td>';
          row += '<td>' + payment.tenant_id + '</td>';
          row += '<td>' + payment.amount_due + '</td>';
          row += '<td>' + payment.amount_paid + '</td>';
          row += '<td>' + paymentMethod + '</td>';
          row += '<td>' + payment.payment_status.charAt(0).toUpperCase() + payment.payment_status.slice(1) + '</td>';
          row += '<td>' + formattedDate + '</td>';
          row += '</tr>';
          $('#paymentTable tbody').append(row);

          // Update totals
          totalAmountDue += parseFloat(payment.amount_due);
          totalAmountPaid += parseFloat(payment.amount_paid);
        });

        // Show totals
        $('#paymentTotals').show();
        $('#totalAmountDue').text(totalAmountDue.toFixed(2));
        $('#totalAmountPaid').text(totalAmountPaid.toFixed(2));
      } else {
        $('#paymentTable tbody').append('<tr><td colspan="8">No payments found for the selected month and year.</td></tr>');
      }
    }
  });
}
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
  // Get current date
  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const currentMonth = currentDate.getMonth() + 1; // Months are 0-based, so add 1

  // Format the current date as YYYY-MM for the max value
  const maxDate = `${currentYear}-${currentMonth.toString().padStart(2, '0')}`;

  // Set the max attribute to prevent future month/year selection
  document.getElementById('filterMonthYear').setAttribute('max', maxDate);
});

</script>

</body>
</html>
