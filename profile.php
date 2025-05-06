 <?php
// profile.php
session_start();
include "admin/functions/connection.php";
include 'functions/load_tenant_data.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$tenantData = loadTenantData($conn, $_SESSION['tenant_id']);

?>

<!-- Your HTML/PHP for the profile page -->
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
              <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($tenantData['name'] ?? 'default.jpg'); ?>
            
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
          <button class="tab-button px-4 sm:px-6 py-3 text-sm font-medium whitespace-nowrap" onclick="openTab('transaction-tab', event)">
            <i class="fas fa-receipt mr-2"></i>Transaction History
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
            <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
              <h3 class="text-slate-500 dark:text-slate-300 text-sm font-medium">Current Balance</h3>
              <p class="text-2xl font-bold mt-2">₱<?php echo number_format($balance, 2); ?></p>
            </div>
            <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
              <h3 class="text-slate-500 dark:text-slate-300 text-sm font-medium">Current Room</h3>
              <p class="text-2xl font-bold mt-2"><?php echo $room_data ? htmlspecialchars($room_data['room_name']) : 'None'; ?></p>
            </div>
            <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
              <h3 class="text-slate-500 dark:text-slate-300 text-sm font-medium">Rent Price</h3>
              <p class="text-2xl font-bold mt-2"><?php echo $room_data ? '₱' . number_format($room_data['price'], 2) : 'N/A'; ?></p>
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
              <h3 class="text-lg font-semibold mb-4">Room Details</h3>
              <div class="space-y-4">
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">Room:</span>
                  <span><?php echo $room_data ? htmlspecialchars($room_data['room_name']) : 'None'; ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">Rent:</span>
                  <span><?php echo $room_data ? '₱' . number_format($room_data['price'], 2) : 'N/A'; ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">Start Date:</span>
                  <span><?php echo $room_data ? date('M d, Y', strtotime($room_data['start_date'])) : 'N/A'; ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-600 dark:text-slate-300">End Date:</span>
                  <span><?php echo $room_data ? date('M d, Y', strtotime($room_data['end_date'])) : 'N/A'; ?></span>
                </div>
                <div class="flex justify-between font-medium">
                  <span class="text-slate-600 dark:text-slate-300">Status:</span>
                  <span class="<?php echo $room_data ? 'text-green-600' : 'text-yellow-600'; ?>">
                    <?php echo $room_data ? 'Active' : 'No Active Booking'; ?>
                  </span>
                </div>
              </div>
            </div>
            <div class="bg-white dark:bg-slate-700 p-6 rounded-lg shadow border border-slate-100 dark:border-slate-600">
              <h3 class="text-lg font-semibold mb-4">Recent Payments</h3>
              <div class="h-64 flex items-center justify-center text-slate-400">
                <p>Payment history chart will be displayed here</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Add this new tab content with the others -->
        <div id="transaction-tab" class="tab-content p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
              <thead class="bg-slate-50 dark:bg-slate-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Transaction ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Amount</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                <!-- Sample transaction 1 -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">2023-06-15</td>
                  <td class="px-6 py-4 whitespace-nowrap">TRX-789456</td>
                  <td class="px-6 py-4 whitespace-nowrap">Booking Payment</td>
                  <td class="px-6 py-4 whitespace-nowrap">₱8,000.00</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                      Completed
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <button class="text-primary hover:text-primary-dark mr-3">
                      <i class="fas fa-eye"></i> View
                    </button>
                    <button class="text-red-500 hover:text-red-700">
                      <i class="fas fa-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                <!-- Sample transaction 2 -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">2023-05-28</td>
                  <td class="px-6 py-4 whitespace-nowrap">TRX-123456</td>
                  <td class="px-6 py-4 whitespace-nowrap">Monthly Rent</td>
                  <td class="px-6 py-4 whitespace-nowrap">₱8,000.00</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                      Completed
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <button class="text-primary hover:text-primary-dark mr-3">
                      <i class="fas fa-eye"></i> View
                    </button>
                    <button class="text-red-500 hover:text-red-700">
                      <i class="fas fa-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                <!-- Sample transaction 3 -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">2023-05-01</td>
                  <td class="px-6 py-4 whitespace-nowrap">TRX-654321</td>
                  <td class="px-6 py-4 whitespace-nowrap">Security Deposit</td>
                  <td class="px-6 py-4 whitespace-nowrap">₱5,000.00</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                      Pending
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <button class="text-primary hover:text-primary-dark mr-3">
                      <i class="fas fa-eye"></i> View
                    </button>
                    <button class="text-red-500 hover:text-red-700">
                      <i class="fas fa-trash"></i> Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
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
        <div id="history-tab" class="tab-content p-6">
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
</body>
</html>
