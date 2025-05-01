<?php
// Check if the user is logged in, if not then redirect to login page
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include 'admin/functions/connection.php';
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <title>Profile - MECMEC Boarding House</title>
  <?php include 'includes/header.php'; ?>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-all duration-300">
  <!-- Include your navigation -->
  <?php include 'includes/topnav.php'; ?>

  <section class="py-16 md:py-24">
    <div class="container mx-auto px-4">
      <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">My Profile</h2>
        
        <div class="grid md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Personal Information</h3>
            <div class="space-y-2">
              <p><span class="font-medium">Name:</span> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
              <p><span class="font-medium">Email:</span> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
              <p><span class="font-medium">Occupation:</span> <?php echo htmlspecialchars($_SESSION['occupation']); ?></p>
            </div>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Account Actions</h3>
            <div class="space-y-3">
              <a href="change-password.php" class="block w-full bg-primary/10 hover:bg-primary/20 text-primary dark:text-primary-light px-4 py-2 rounded-lg transition">
                Change Password
              </a>
              <a href="update-profile.php" class="block w-full bg-primary/10 hover:bg-primary/20 text-primary dark:text-primary-light px-4 py-2 rounded-lg transition">
                Update Profile
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Include your footer -->
  <?php include 'includes/footer.php'; ?>
</body>
</html>