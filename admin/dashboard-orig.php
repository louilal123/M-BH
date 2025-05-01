
<?php

include 'functions/connection.php'; 
include 'includes/session.php';
$queryAdmins = "SELECT COUNT(*) as adminCount FROM admin";
$resultAdmins = mysqli_query($conn, $queryAdmins);
$rowAdmins = mysqli_fetch_assoc($resultAdmins);
$adminCount = $rowAdmins['adminCount'];

$queryRooms = "SELECT COUNT(*) as roomCount FROM rooms";
$resultRooms = mysqli_query($conn, $queryRooms);
$rowRooms = mysqli_fetch_assoc($resultRooms);
$roomCount = $rowRooms['roomCount'];

$queryTenants = "SELECT COUNT(*) as tenantCount FROM tenants";
$resultTenants = mysqli_query($conn, $queryTenants);
$rowTenants = mysqli_fetch_assoc($resultTenants);
$tenantCount = $rowTenants['tenantCount'];

$queryBookings = "SELECT COUNT(*) as bookingCount FROM bookings";
$resultBookings = mysqli_query($conn, $queryBookings);
$rowBookings = mysqli_fetch_assoc($resultBookings);
$bookingstCount = $rowBookings['bookingCount'];

// Query for the total income (sum of amount_paid)
$queryIncome = "SELECT SUM(amount_paid) as totalIncome FROM payments WHERE payment_status = 'completed'";
$resultIncome = mysqli_query($conn, $queryIncome);
$rowIncome = mysqli_fetch_assoc($resultIncome);
$totalIncome = $rowIncome['totalIncome'];

?>
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
    <div id="content" class="flex-grow-1 ">
      <!-- Top Navigation -->
      <?php include "includes/topnav.php" ?>

      <!-- Dashboard -->
      <div class="container-fluid mt-2">
        <h1 class="mb-2">Dashboard</h1>

        <div class="row ">
          <!-- Box 1: Rooms -->

           <!-- Box 2: Users -->
           <div class="col-xxl-3 col-md-6">
            <div class="card bg-light text-dark h-100 shadow-sm">
              <div class="card-body d-flex align-items-center">
                <div class="ps-3">
                  <h6 class="card-title">Total Collected</h6>
                  <h1><?php echo number_format($totalIncome, 2); ?> </h1>
                </div>
                <div class="card-icon ms-auto">
                  <i class="icon fas fa-dollar text-success"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-3 col-md-6">
            <div class="card bg-light text-dark h-100 shadow-sm">
              <div class="card-body d-flex align-items-center">
                <div class="ps-3">
                  <h6 class="card-title">Rooms</h6>
                  <h1><?php echo $roomCount; ?></h1>
                </div>
                <div class="card-icon ms-auto">
                  <i class="icon fas fa-home text-primary"></i>
                </div>
              </div>
            </div>
          </div>

        

          <!-- Box 3: Tenants -->
          <div class="col-xxl-3 col-md-6">
            <div class="card bg-light text-dark h-100 shadow-sm">
              <div class="card-body d-flex align-items-center">
                <div class="ps-3">
                  <h6 class="card-title">Tenants</h6>
                  <h1><?php echo $tenantCount; ?></h1>
                </div>
                <div class="card-icon ms-auto">
                  <i class="icon fas fa-users text-warning"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Box 4: Bookings -->
          <div class="col-xxl-3 col-md-6">
            <div class="card bg-light text-dark h-100 shadow-sm">
              <div class="card-body d-flex align-items-center">
                <div class="ps-3">
                  <h6 class="card-title">System Users</h6>
                  <h1><?php echo $adminCount; ?><h1>
                </div>
                <div class="card-icon ms-auto">
                  <i class="icon fas fa-calendar-check text-danger"></i>
                </div>
              </div>
            </div>
          </div>

         

        </div>


        <div class="row mt-3">

       
        <div class="col-12 ">
            <div class="card w-full">
                <div class="card-header bg-dark ">
                <h5 class="card-title text-light">Recent Room Assignments</h5>
                </div>
                <div class="card-body">
                <div class="">
                  <table class="table table-striped table-bordered" id="">
                    <thead>
                      <tr>
                        <th> ID</th>
                        <th>Tenant</th>
                        <th>Room</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                       
                       
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

        <div class="col-md-12 mt-4 mb-4">
          <div class="card">
            <div class="card-body">
              <div id="chart">

              </div>
            </div>
          </div>
        </div>

  

     </div>

      </div>
    </div>
  </div>

  <?php include "includes/footer.php" ?>

  <?php
include 'functions/connection.php';

// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Calculate the last 6 months
$months = [];
$completedPaymentsData = [];
$pendingPaymentsData = [];

for ($i = 0; $i < 6; $i++) {
    $month = date('m', strtotime("-$i month"));
    $year = date('Y', strtotime("-$i month"));
    $months[] = date('M', strtotime("-$i month"));  // Add month abbreviation to array

    // Query for the total completed payments for the month
    $queryCompleted = "SELECT SUM(amount_paid) AS totalIncome FROM payments WHERE payment_status = 'completed' AND MONTH(payment_date) = ? AND YEAR(payment_date) = ?";
    $stmtCompleted = $conn->prepare($queryCompleted);
    $stmtCompleted->bind_param("ii", $month, $year);
    $stmtCompleted->execute();
    $resultCompleted = $stmtCompleted->get_result();
    $rowCompleted = $resultCompleted->fetch_assoc();

    $completedPaymentsData[] = $rowCompleted['totalIncome'] ? (float)$rowCompleted['totalIncome'] : 0; // Ensure 0 if no data

    // Query for the total pending payments for the month
    $queryPending = "SELECT SUM(amount_due) AS totalPending FROM payments WHERE payment_status = 'pending' AND MONTH(payment_date) = ? AND YEAR(payment_date) = ?";
    $stmtPending = $conn->prepare($queryPending);
    $stmtPending->bind_param("ii", $month, $year);
    $stmtPending->execute();
    $resultPending = $stmtPending->get_result();
    $rowPending = $resultPending->fetch_assoc();

    $pendingPaymentsData[] = $rowPending['totalPending'] ? (float)$rowPending['totalPending'] : 0; // Ensure 0 if no data
}

// Reverse the arrays to have the most recent month on the right side
$months = array_reverse($months);
$completedPaymentsData = array_reverse($completedPaymentsData);
$pendingPaymentsData = array_reverse($pendingPaymentsData);
?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: [{
            name: "Completed Payments",
            data: <?php echo json_encode($completedPaymentsData); ?>
        },
        {
            name: "Pending Payments",
            data: <?php echo json_encode($pendingPaymentsData); ?>
        }],
        chart: {
            height: 350,
            type: 'bar',  // Line chart to display trends over time
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: 'Payments by Month (Completed vs Pending)',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // Row colors
                opacity: 0.5
            },
        },
        xaxis: {
            categories: <?php echo json_encode($months); ?>,
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>



  
</body>
</html>
