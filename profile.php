<?php
// Check if the user is logged in, if not then redirect to login page
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include 'admin/functions/connection.php';

// Get current user data
$tenant_id = $_SESSION['tenant_id'];
$stmt = $conn->prepare("SELECT * FROM tenants WHERE tenant_id = ?");
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile photo upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['photo'])) {
    $target_dir = "uploads/tenants/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . time() . '_' . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['status'] = "File is not an image.";
        $_SESSION['status_icon'] = "error";
        $uploadOk = 0;
    }
    
    // Check file size (5MB max)
    if ($_FILES["photo"]["size"] > 5000000) {
        $_SESSION['status'] = "Sorry, your file is too large.";
        $_SESSION['status_icon'] = "error";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $_SESSION['status'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $_SESSION['status_icon'] = "error";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Update database with new photo
            $update_stmt = $conn->prepare("UPDATE tenants SET photo = ? WHERE tenant_id = ?");
            $update_stmt->bind_param("si", $target_file, $tenant_id);
            if ($update_stmt->execute()) {
                $_SESSION['photo'] = $target_file;
                $_SESSION['status'] = "Profile photo updated successfully.";
                $_SESSION['status_icon'] = "success";
            } else {
                $_SESSION['status'] = "Error updating profile photo.";
                $_SESSION['status_icon'] = "error";
            }
            $update_stmt->close();
        } else {
            $_SESSION['status'] = "Sorry, there was an error uploading your file.";
            $_SESSION['status_icon'] = "error";
        }
    }
    header("Location: profile.php");
    exit();
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
      background-color: rgba(42, 0, 159, 0.95) !important;
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
  </style>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-all duration-300">
  <!-- Navigation -->
  <?php include 'includes/topnav.php'; ?>

  <main class="py-16 md:py-24">
    <div class="container mx-auto px-4">


      <div class=" bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-primary to-primary-dark p-6 text-white">
          <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative group">
              <img src="<?php echo htmlspecialchars($user['photo']); ?>" 
                   alt="Profile Photo" 
                   class="profile-photo rounded-full border-4 border-white dark:border-slate-200 shadow-lg">
              <form id="photoForm" method="post" enctype="multipart/form-data" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black bg-opacity-50 rounded-full">
                <label for="photoUpload" class="cursor-pointer p-2 bg-white bg-opacity-80 rounded-full text-primary hover:bg-opacity-100 transition">
                  <i class="fas fa-camera"></i>
                  <input type="file" id="photoUpload" name="photo" class="hidden" accept="image/*" onchange="document.getElementById('photoForm').submit()">
                </label>
              </form>
            </div>
            <div class="text-center md:text-left">
              <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($user['name']); ?></h1>
              <p class="text-primary-light"><?php echo htmlspecialchars($user['occupation']); ?></p>
              <p class="text-sm opacity-80 mt-2">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
            </div>
          </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-slate-200 dark:border-slate-700">
          <div class="flex">
            <button class="tab-button active px-6 py-4 text-sm font-medium" onclick="openTab('profile-tab')">
              <i class="fas fa-user mr-2"></i>Profile
            </button>
            <button class="tab-button px-6 py-4 text-sm font-medium" onclick="openTab('security-tab')">
              <i class="fas fa-lock mr-2"></i>Security
            </button>
          </div>
        </div>

        <!-- Profile Tab Content -->
        <div id="profile-tab" class="tab-content active p-6">
          <form action="functions/update_profile.php" method="post">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300" readonly>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" 
                       class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Occupation</label>
                <select name="occupation" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white">
                  <option value="Student" <?php echo $user['occupation'] == 'Student' ? 'selected' : ''; ?>>Student</option>
                  <option value="Working Professional" <?php echo $user['occupation'] == 'Working Professional' ? 'selected' : ''; ?>>Working Professional</option>
                  <option value="Business Owner" <?php echo $user['occupation'] == 'Business Owner' ? 'selected' : ''; ?>>Business Owner</option>
                  <option value="Freelancer" <?php echo $user['occupation'] == 'Freelancer' ? 'selected' : ''; ?>>Freelancer</option>
                  <option value="Other" <?php echo $user['occupation'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Address</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white"><?php echo htmlspecialchars($user['address']); ?></textarea>
              </div>
            </div>
            <div class="mt-6 flex justify-end">
              <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                Save Changes
              </button>
            </div>
          </form>
        </div>

        <!-- Security Tab Content -->
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
      </div>
    </div>
  </main>

  <?php include "includes/footer.php" ?>
  <script>
    function openTab(tabId) {
      // Hide all tab contents
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      
      // Remove active class from all tab buttons
      document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
      });
      
      // Show the selected tab content
      document.getElementById(tabId).classList.add('active');
      
      // Add active class to the clicked tab button
      event.currentTarget.classList.add('active');
    }
  </script>
</body>
</html>