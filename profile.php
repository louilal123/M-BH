<?php
session_start();
include "admin/functions/connection.php";
include 'functions/load_tenant_data.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$tenantData = loadTenantData($conn, $_SESSION['tenant_id']);

$bookings = [];
$stmt = $conn->prepare("SELECT b.*, r.room_number, r.price, r.room_type FROM bookings b JOIN rooms r ON b.room_id = r.room_id WHERE b.tenant_id = ? ORDER BY b.booking_date DESC");
$stmt->bind_param("i", $_SESSION['tenant_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result) $bookings = $result->fetch_all(MYSQLI_ASSOC);

$payments = [];
$stmt = $conn->prepare("SELECT p.* FROM payments p JOIN bookings b ON p.booking_id = b.booking_id WHERE b.tenant_id = ? ORDER BY p.payment_date DESC");
$stmt->bind_param("i", $_SESSION['tenant_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result) $payments = $result->fetch_all(MYSQLI_ASSOC);

$currentBooking = null;
foreach ($bookings as $booking) {
    if ($booking['status'] == 'active' || $booking['status'] == 'confirmed') {
        $currentBooking = $booking;
        break;
    }
}

$balanceText = "₱0.00 (No Active Booking)";

if ($currentBooking) {
    $totalPaid = 0;
    
    foreach ($payments as $payment) {
        if ($payment['booking_id'] == $currentBooking['booking_id']) {
            $totalPaid += (float)$payment['amount_paid'];
        }
    }

    $remainingBalance = (float)$currentBooking['total_amount'] - $totalPaid;

    if ($totalPaid == 0) {
        $balanceText = "₱" . number_format($currentBooking['total_amount'], 2) . " (Unpaid)";
    } 
    elseif ($remainingBalance > 0) {
        $balanceText = "₱" . number_format($remainingBalance, 2) . " (Due)";
    } 
    else {
        $balanceText = "₱0.00 (Paid)";
    }
    
    // Debug output
    error_log("Booking ID: " . $currentBooking['booking_id']);
    error_log("Total Amount: " . $currentBooking['total_amount']);
    error_log("Total Paid: " . $totalPaid);
    error_log("Remaining Balance: " . $remainingBalance);
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile - MECMEC Boarding House</title>
  <?php include "includes/header.php"; ?>
  <style>
     .navbar {
    background-color:#0f172a;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .profile-photo {
      width: 150px;
      height: 150px;
      object-fit: cover;
    }
    .tab-content {
      display: none;
    }
    .tab-content.active {
      display: block;
    }
    .tab-button.active {
  border-bottom: 2px solid #1e40af;
  color: #1e40af;
  font-weight: 600;
}
.dark .tab-button.active {
  border-bottom-color: #3b82f6;
  color: #3b82f6;
}
    #previewModal {
      transition: all 0.3s ease;
      justify-content: center;
      align-items: center;
    }
    .modal-backdrop {
      background-color: rgba(0, 0, 0, 0.5);
    }
    .status-active {
      background-color: #dcfce7;
      color: #166534;
    }
    .status-pending {
      background-color: #fef9c3;
      color: #854d0e;
    }
    .status-completed {
      background-color: #dbeafe;
      color: #1e40af;
    }
    .status-cancelled {
      background-color: #fee2e2;
      color: #991b1b;
    }
    .dark .status-active {
      background-color: #166534;
      color: #dcfce7;
    }
    .dark .status-pending {
      background-color: #854d0e;
      color: #fef9c3;
    }
    .dark .status-completed {
      background-color: #1e40af;
      color: #dbeafe;
    }
    .dark .status-cancelled {
      background-color: #991b1b;
      color: #fee2e2;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-all duration-300">
  <?php include 'includes/topnav.php'; ?>

  <main class="py-16 md:py-24 m-h-screen overflow-x">
    <div class="container mx-auto px-4">
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-primary-dark p-6 text-white">
          <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative group">
            <img src="uploads/tenants/<?php echo htmlspecialchars($tenantData['photo'] ?? 'default.jpg'); ?>" 
                   alt="Profile Photo" 
                   class="profile-photo rounded-full border-4 border-white dark:border-slate-200 shadow-lg">
              <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black bg-opacity-50 rounded-full">
                <label for="photoUpload" class="cursor-pointer p-2 bg-white bg-opacity-80 rounded-full text-primary hover:bg-opacity-100 transition">
                  <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="photoUpload" name="photo" class="hidden" accept="image/*">
              </div>
            </div>
            <div class="text-center md:text-left">
              <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($tenantData['name'] ?? 'default.jpg'); ?></h1>
              <p class="text-primary-light"><?php echo htmlspecialchars($tenantData['occupation']); ?></p>
              <p class="text-sm opacity-80 mt-2">Member since <?php echo date('F Y', strtotime($tenantData['created_at'])); ?></p>
            </div>
          </div>
        </div>

        <div class="border-b border-slate-200 dark:border-slate-700 overflow-x-auto">
        <div class="flex flex-wrap sm:flex-nowrap">
          <button class="tab-button active px-4 sm:px-6 py-3 text-sm font-medium whitespace-nowrap" onclick="openTab('overview-tab', event)">
            <i class="fas fa-chart-pie mr-2"></i>Overview
          </button>
          <button class="tab-button px-4 sm:px-6 py-3 text-sm font-medium whitespace-nowrap" onclick="openTab('bookings-tab', event)">
            <i class="fas fa-calendar-alt mr-2"></i>My Bookings
          </button>
          <button class="tab-button px-4 sm:px-6 py-3 text-sm font-medium whitespace-nowrap" onclick="openTab('transaction-tab', event)">
            <i class="fas fa-receipt mr-2"></i>Payments
          </button>
          <button class="tab-button px-4 sm:px-6 py-3 text-sm font-medium whitespace-nowrap" onclick="openTab('profile-tab', event)">
            <i class="fas fa-user mr-2"></i>Profile
          </button>
          <button class="tab-button px-4 sm:px-6 py-3 text-sm font-medium whitespace-nowrap" onclick="openTab('security-tab', event)">
            <i class="fas fa-lock mr-2"></i>Security
          </button>
          <button class="tab-button px-4 sm:px-6 py-3 text-sm font-medium whitespace-nowrap" onclick="openTab('history-tab', event)">
            <i class="fas fa-history mr-2"></i>Login History
          </button>
        </div>
      </div>

        <div id="overview-tab" class="tab-content active p-6">
            <div class="grid md:grid-cols-3 gap-6 mb-8">
  <!-- Card 1: Current Balance -->
  <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
    <h3 class="text-slate-500 dark:text-slate-300 text-sm font-medium flex items-center gap-2">
      <i class="fas fa-wallet"></i> Current Balance
    </h3>
    <p class="text-2xl font-bold mt-2"><?php echo $balanceText; ?></p>
  </div>

  <!-- Card 2: Current Room -->
  <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
    <h3 class="text-slate-500 dark:text-slate-300 text-sm font-medium flex items-center gap-2">
      <i class="fas fa-door-open"></i> Current Room
    </h3>
    <p class="text-2xl font-bold mt-2">
      <?php echo $currentBooking ? 'Room ' . htmlspecialchars($currentBooking['room_number']) : 'None'; ?>
    </p>
  </div>

  <!-- Card 3: Rent Price -->
  <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
    <h3 class="text-slate-500 dark:text-slate-300 text-sm font-medium flex items-center gap-2">
      <i class="fas fa-coins"></i> Rent Price
    </h3>
    <p class="text-2xl font-bold mt-2">
      <?php echo $currentBooking ? '₱' . number_format($currentBooking['price'], 2) : 'N/A'; ?>
    </p>
  </div>
</div>


          <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
              <h3 class="text-lg font-semibold mb-4">Room Details</h3>
              <div class="space-y-4">
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">Room:</span>
                  <span><?php echo $currentBooking ? 'Room ' . htmlspecialchars($currentBooking['room_number']) : 'None'; ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">Type:</span>
                  <span><?php echo $currentBooking ? htmlspecialchars($currentBooking['room_type']) : 'N/A'; ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">Rent:</span>
                  <span><?php echo $currentBooking ? '₱' . number_format($currentBooking['price'], 2) : 'N/A'; ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">Check-in Date:</span>
                  <span><?php echo $currentBooking ? date('M d, Y', strtotime($currentBooking['check_in_date'])) : 'N/A'; ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">Check-out Date:</span>
                  <span><?php echo $currentBooking ? date('M d, Y', strtotime($currentBooking['check_out_date'])) : 'N/A'; ?></span>
                </div>
                <div class="flex justify-between font-medium">
                  <span class="text-slate-600 dark:text-slate-300">Status:</span>
                  <span class="<?php echo $currentBooking ? 'text-green-600' : 'text-yellow-600'; ?>">
                    <?php echo $currentBooking ? 'Active' : 'No Active Booking'; ?>
                  </span>
                </div>
              </div>
            </div>
            <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
              <h3 class="text-lg font-semibold mb-4">Recent Payments</h3>
              <?php if (!empty(array_slice($payments, 0, 3))): ?>
                <div class="space-y-4">
                  <?php foreach (array_slice($payments, 0, 3) as $payment): ?>
                    <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-slate-600 rounded-lg">
                      <div>
                        <p class="font-medium">Payment #<?php echo htmlspecialchars($payment['payment_id']); ?></p>
                        <p class="text-sm text-slate-500 dark:text-slate-300"><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></p>
                      </div>
                      <div class="text-right">
                        <p class="font-bold">₱<?php echo number_format($payment['amount_paid'], 2); ?></p>
                        <span class="px-2 py-1 text-xs rounded-full <?php 
                          echo $payment['remarks'] == 'completed' ? 'status-completed' : 
                               ($payment['remarks'] == 'pending' ? 'status-pending' : 'status-cancelled');
                        ?>">
                          <?php echo ucfirst(htmlspecialchars($payment['remarks'])); ?>
                        </span>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="mt-4 text-right">
                  <a href="#"  onclick="openTab('transaction-tab', event)" class="text-primary hover:underline">View all payments</a>
                </div>
              <?php else: ?>
                <div class="h-64 flex items-center justify-center text-slate-400">
                  <p>No recent payments found</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Bookings Tab -->
<!-- Bookings Tab -->
<div id="bookings-tab" class="tab-content p-6">
  <div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold">My Bookings</h2>
    <a href="rooms.php" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
      <i class="fas fa-plus mr-2"></i>New Booking
    </a>
  </div>
  
  <?php if (!empty($bookings)): ?>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700" id="bookings-table">
        <thead class="bg-slate-50 dark:bg-slate-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Booking ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Room</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Dates</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Total Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
          <?php foreach ($bookings as $booking): ?>
            <tr data-booking-id="<?php echo $booking['booking_id']; ?>">
              <td class="px-6 py-4 whitespace-nowrap">#<?php echo htmlspecialchars($booking['booking_id']); ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                Room <?php echo htmlspecialchars($booking['room_number']); ?>
                <span class="block text-sm text-slate-500 dark:text-slate-400"><?php echo htmlspecialchars($booking['room_type']); ?></span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <?php echo date('M d, Y', strtotime($booking['check_in_date'])); ?> - 
                <?php echo date('M d, Y', strtotime($booking['check_out_date'])); ?>
                <span class="block text-sm text-slate-500 dark:text-slate-400">
                  Booked on <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?>
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">₱<?php echo number_format($booking['total_amount'], 2); ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs rounded-full <?php 
                  echo $booking['status'] == 'active' ? 'status-active' : 
                       ($booking['status'] == 'pending' ? 'status-pending' : 'status-cancelled');
                ?>">
                  <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button onclick="viewBookingDetails(<?php echo $booking['booking_id']; ?>)" 
                        class="text-primary hover:text-primary-dark mr-3">
                  <i class="fas fa-eye"></i> View
                </button>
                <?php if ($booking['status'] == 'pending'): ?>
                  <button onclick="cancelBooking(<?php echo $booking['booking_id']; ?>)" 
                          class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i> Cancel
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="text-center py-12">
      <i class="fas fa-calendar-times text-4xl text-slate-400 mb-4"></i>
      <h3 class="text-lg font-medium text-slate-600 dark:text-slate-300">No bookings found</h3>
      <p class="text-slate-500 dark:text-slate-400 mt-2">You haven't made any bookings yet.</p>
      <a href="rooms.php" class="mt-4 inline-block bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
        <i class="fas fa-plus mr-2"></i>Book a Room
      </a>
    </div>
  <?php endif; ?>
</div>



        <!-- Payments Tab -->
        <div id="transaction-tab" class="tab-content p-6">
          <h2 class="text-xl font-bold mb-6">Payment History</h2>
          
          <?php if (!empty($payments)): ?>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Payment ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                  <?php foreach ($payments as $payment): ?>
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap">#<?php echo htmlspecialchars($payment['payment_id']); ?></td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        Booking #<?php echo htmlspecialchars($payment['booking_id']); ?>
                        <span class="block text-sm text-slate-500 dark:text-slate-400">
                          Room <?php echo htmlspecialchars($payment['room_number']); ?>
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap"><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                      <td class="px-6 py-4 whitespace-nowrap">₱<?php echo number_format($payment['amount_paid'], 2); ?></td>
                      <td class="px-6 py-4 whitespace-nowrap"><?php echo ucfirst(htmlspecialchars($payment['payment_method'])); ?></td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full <?php 
                          echo $payment['remarks'] == 'completed' ? 'status-completed' : 
                               ($payment['remarks'] == 'pending' ? 'status-pending' : 'status-cancelled');
                        ?>">
                          <?php echo ucfirst(htmlspecialchars($payment['remarks'])); ?>
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="viewPaymentReceipt(<?php echo $payment['payment_id']; ?>)" 
                                class="text-primary hover:text-primary-dark mr-3">
                          <i class="fas fa-receipt"></i> Receipt
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center py-12">
              <i class="fas fa-receipt text-4xl text-slate-400 mb-4"></i>
              <h3 class="text-lg font-medium text-slate-600 dark:text-slate-300">No payments found</h3>
              <p class="text-slate-500 dark:text-slate-400 mt-2">You haven't made any payments yet.</p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Rest of the tabs (Profile, Security, Login History) remain the same -->
        <div id="profile-tab" class="tab-content p-6">
          <form action="functions/update_profile.php" method="post">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($tenantData['name']); ?>" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                <input type="email" value="<?php echo htmlspecialchars($tenantData['email']); ?>" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300" readonly>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($tenantData['phone']); ?>" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Occupation</label>
                <select name="occupation" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white">
                  <option value="Student" <?php echo $tenantData['occupation'] == 'Student' ? 'selected' : ''; ?>>Student</option>
                  <option value="Working Professional" <?php echo $tenantData['occupation'] == 'Working Professional' ? 'selected' : ''; ?>>Working Professional</option>
                  <option value="Business Owner" <?php echo $tenantData['occupation'] == 'Business Owner' ? 'selected' : ''; ?>>Business Owner</option>
                  <option value="Freelancer" <?php echo $tenantData['occupation'] == 'Freelancer' ? 'selected' : ''; ?>>Freelancer</option>
                  <option value="Other" <?php echo $tenantData['occupation'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Address</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white"><?php echo htmlspecialchars($tenantData['address']); ?></textarea>
              </div>
            </div>
            <div class="mt-6 flex justify-end">
              <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                Save Changes
              </button>
            </div>
          </form>
        </div>

        <div id="security-tab" class="tab-content p-6">
          <form action="functions/change_password.php" method="post">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Current Password</label>
                <input type="password" name="current_password" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">New Password</label>
                <input type="password" name="new_password" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Minimum 8 characters with at least one number</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirm New Password</label>
                <input type="password" name="confirm_password" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required>
              </div>
            </div>
            <div class="mt-6 flex justify-end">
              <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                Change Password
              </button>
            </div>
          </form>
        </div>

        <div id="history-tab" class="tab-content p-6">
            <?php
          include "admin/functions/connection.php";
          if (isset($_SESSION['tenant_id'])) {
              $stmt = $conn->prepare("
                  SELECT 
                      login_time,
                      login_status,
                      user_agent,
                      ip_address
                  FROM login_history 
                  WHERE tenant_id = ?
                  ORDER BY login_time DESC
                  LIMIT 50
              ");
              $stmt->bind_param("i", $_SESSION['tenant_id']);
              $stmt->execute();
              $login_history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
          }
          ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Device</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                <?php if (!empty($login_history)): ?>
                    <?php foreach ($login_history as $login): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php echo date('M d, Y h:i A', strtotime($login['login_time'])); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo $login['login_status'] == 'success' 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' ?>">
                                <?php 
                                $statusText = [
                                    'success' => 'Successful',
                                    'failed_password' => 'Failed (Wrong Password)',
                                    'failed_email' => 'Failed (Wrong Email)'
                                ];
                                echo htmlspecialchars($statusText[$login['login_status']] ?? $login['login_status']); 
                                ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php echo htmlspecialchars($login['ip_address']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php 
                            // Simple device detection from user agent
                            $tenantData_agent = $login['user_agent'];
                            $device = 'Unknown';
                            
                            if (strpos($tenantData_agent, 'Mobile') !== false) {
                                $device = 'Mobile';
                            } elseif (strpos($tenantData_agent, 'Tablet') !== false) {
                                $device = 'Tablet';
                            } elseif (strpos($tenantData_agent, 'Windows') !== false) {
                                $device = 'Windows PC';
                            } elseif (strpos($tenantData_agent, 'Macintosh') !== false) {
                                $device = 'Mac';
                            } elseif (strpos($tenantData_agent, 'Linux') !== false) {
                                $device = 'Linux PC';
                            }
                            
                            echo htmlspecialchars($device); 
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                            No login history found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
      </div>
    </div>
  </main>

  <!-- Booking Details Modal -->
  <div id="bookingModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 relative max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b border-slate-200 dark:border-slate-700 sticky top-0 bg-white dark:bg-slate-800 z-10">
          <h3 class="text-lg font-semibold">Booking Details</h3>
          <button onclick="closeBookingModal()" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-6" id="bookingDetailsContent">
          <!-- Content will be loaded here via AJAX -->
        </div>
        <div class="flex justify-end gap-3 p-4 border-t border-slate-200 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800">
          <button onclick="closeBookingModal()" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment Receipt Modal -->
  <div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 relative max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b border-slate-200 dark:border-slate-700 sticky top-0 bg-white dark:bg-slate-800 z-10">
          <h3 class="text-lg font-semibold">Payment Receipt</h3>
          <button onclick="closePaymentModal()" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-6" id="paymentDetailsContent">
          <!-- Content will be loaded here via AJAX -->
        </div>
        <div class="flex justify-end gap-3 p-4 border-t border-slate-200 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800">
          <button onclick="printReceipt()" class="px-4 py-2 text-sm font-medium text-primary hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg mr-2">
            <i class="fas fa-print mr-2"></i>Print
          </button>
          <button onclick="closePaymentModal()" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Photo Upload Confirmation Modal -->
  <div id="previewModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full mx-4 relative">
        <div class="flex justify-between items-center p-4 border-b border-slate-200 dark:border-slate-700">
          <h3 class="text-lg font-semibold">Confirm Profile Photo</h3>
          <button onclick="closeModal()" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-4">
          <img id="imagePreview" src="#" alt="Preview" class="preview-image mx-auto rounded-lg">
        </div>
        <div class="flex justify-end gap-3 p-4 border-t border-slate-200 dark:border-slate-700">
          <button onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg">
            Cancel
          </button>
          <button onclick="uploadPhoto()" class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-lg">
            Save Photo
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php include "includes/footer.php" ?>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
<script>
// Booking details modal functions
function viewBookingDetails(bookingId) {
  fetch('functions/get_booking_details.php?booking_id=' + bookingId)
    .then(response => response.text())
    .then(data => {
      document.getElementById('bookingDetailsContent').innerHTML = data;
      document.getElementById('bookingModal').classList.remove('hidden');
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load booking details'
      });
    });
}

function closeBookingModal() {
  document.getElementById('bookingModal').classList.add('hidden');
}

function cancelBooking(bookingId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to cancel this booking!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('functions/cancel_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'booking_id=' + bookingId
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update the specific row in the table
                    updateBookingRow(data.booking);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Cancelled!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw data;
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to cancel booking'
                });
            });
        }
    });
}

function updateBookingRow(booking) {
    // Find the row with the matching booking ID using data attribute
    const row = document.querySelector(`tr[data-booking-id="${booking.booking_id}"]`);
    
    if (row) {
        // Update status cell (5th td)
        const statusCell = row.querySelector('td:nth-child(5)');
        if (statusCell) {
            statusCell.innerHTML = `
                <span class="px-2 py-1 text-xs rounded-full status-cancelled">
                    ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                </span>
            `;
        }
        
        // Update actions cell (6th td) - remove the cancel button
        const actionsCell = row.querySelector('td:nth-child(6)');
        if (actionsCell) {
            actionsCell.innerHTML = `
                <button onclick="viewBookingDetails(${booking.booking_id})" 
                        class="text-primary hover:text-primary-dark mr-3">
                    <i class="fas fa-eye"></i> View
                </button>
            `;
        }
    }
}

// Payment receipt modal functions
function viewPaymentReceipt(paymentId) {
  fetch('functions/get_payment_receipt.php?payment_id=' + paymentId)
    .then(response => response.text())
    .then(data => {
      document.getElementById('paymentDetailsContent').innerHTML = data;
      document.getElementById('paymentModal').classList.remove('hidden');
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load payment receipt'
      });
    });
}

function closePaymentModal() {
  document.getElementById('paymentModal').classList.add('hidden');
}

function printReceipt() {
  const printContent = document.getElementById('paymentDetailsContent').innerHTML;
  const originalContent = document.body.innerHTML;
  
  document.body.innerHTML = printContent;
  window.print();
  document.body.innerHTML = originalContent;
  
  // Re-open the modal after printing
  document.getElementById('paymentModal').classList.remove('hidden');
}
</script>
  <script>
    // Tab switching functionality
    function openTab(tabId, event) {
      document.querySelectorAll('.tab-content').forEach(function(content) {
        content.classList.remove('active');
      });
      document.querySelectorAll('.tab-button').forEach(function(button) {
        button.classList.remove('active');
      });
      document.getElementById(tabId).classList.add('active');
      event.currentTarget.classList.add('active');
    }

    // Initialize first tab as active
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelector('.tab-button').classList.add('active');
      document.querySelector('.tab-content').classList.add('active');
      
      // Show SweetAlert notification if status exists
      <?php if (isset($_SESSION['status'])) : ?>
        setTimeout(function() {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: "<?php echo $_SESSION['status_icon']; ?>",
            title: "<?php echo $_SESSION['status']; ?>",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
          });
          
          // Clear the status after showing
          <?php 
            unset($_SESSION['status']);
            unset($_SESSION['status_icon']);
          ?>
        }, 100);
      <?php endif; ?>
    });

    // Photo upload preview and confirmation
    var photoUpload = document.getElementById('photoUpload');
    var previewModal = document.getElementById('previewModal');
    var imagePreview = document.getElementById('imagePreview');
    var selectedFile = null;

    photoUpload.addEventListener('change', function(event) {
      var file = event.target.files[0];
      if (!file) return;
      
      // Validate file type
      var validTypes = ['image/jpeg', 'image/png', 'image/gif'];
      if (validTypes.indexOf(file.type) === -1) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid File Type',
          text: 'Please upload a JPG, PNG, or GIF image'
        });
        photoUpload.value = '';
        return;
      }
      
      // Validate file size (5MB max)
      if (file.size > 5 * 1024 * 1024) {
        Swal.fire({
          icon: 'error',
          title: 'File Too Large',
          text: 'Maximum file size is 5MB'
        });
        photoUpload.value = '';
        return;
      }

      selectedFile = file;
      var reader = new FileReader();
      reader.onload = function(e) {
        imagePreview.src = e.target.result;
        previewModal.classList.remove('hidden');
      }
      reader.readAsDataURL(file);
    });

    function closeModal() {
      previewModal.classList.add('hidden');
      photoUpload.value = '';
      selectedFile = null;
    }

    function uploadPhoto() {
      if (!selectedFile) {
        closeModal();
        return;
      }

      const submitBtn = document.querySelector('#previewModal button[onclick="uploadPhoto()"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
      submitBtn.disabled = true;

      const formData = new FormData();
      formData.append('photo', selectedFile);

      // Show loading state
      Swal.fire({
        title: 'Uploading Photo',
        html: 'Please wait while we update your profile photo...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      fetch('functions/upload_photo.php', {  
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          return response.json().then(err => { throw err; });
        }
        return response.json();
      })
      .then(data => {
        if (data.status === 'success') {
          document.querySelector('.profile-photo').src = data.photo_url + '?' + new Date().getTime();
          
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            position: 'top',
            toast: true,
            text: data.message,
            timer: 2000,
            showConfirmButton: false
          });
          
          closeModal();
        } else {
          throw data;
        }
      })
      .catch(error => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.message || 'An error occurred during upload',
          timer: 3000,
          showConfirmButton: false
        });
      })
      .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
    }


    document.getElementById('logoutBtn').addEventListener('click', function(e) {
      e.preventDefault(); 

      Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to log out!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Logout',
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'functions/logout.php';
        }
      });
    });
  </script>

  <?php include "includes/chatbot.php" ?>
</body>
</html>