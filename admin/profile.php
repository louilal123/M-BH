<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "includes-new/header.php" ?>
  <style>
    .tab-content > .tab-pane {
      display: none;
    }
    .tab-content > .active {
      display: block;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <?php include "includes-new/sidebar.php" ?>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Navigation -->
      <?php include "includes-new/topnav.php" ?>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-4 bg-gray-200">
        <div class="bg-white rounded-lg shadow-sm p-6">
          <!-- Bordered Tabs -->
          <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px" id="profileTabs">
              <li class="mr-2">
                <button class="inline-block p-4 border-b-2 border-indigo-600 rounded-t-lg text-indigo-600 active" 
                        data-tab="profile-edit">
                  Edit Profile
                </button>
              </li>
              <li class="mr-2">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" 
                        data-tab="profile-change-password">
                  Change Password
                </button>
              </li>
            </ul>
          </div>

          <div class="tab-content pt-4">
            <!-- Profile Edit Form -->
            <div class="tab-pane active" id="profile-edit">
              <form method="POST" action="functions/update_profile.php" enctype="multipart/form-data" class="space-y-8">
                <!-- Profile Image Section -->
                <div class="flex flex-col md:flex-row items-start gap-8">
                  <!-- Profile Image -->
                  <div class="w-full md:w-1/3 lg:w-1/4 space-y-4">
                    <div class="text-center">
                      <img src="assets/uploads/<?php echo htmlspecialchars($admin_photo); ?>" 
                           class="w-40 h-40 md:w-48 md:h-48 rounded-full object-cover border-4 border-indigo-100 mx-auto">
                      <div class="mt-4">
                        <label class="cursor-pointer">
                          <span class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 inline-flex items-center">
                            <i class="fas fa-camera mr-2"></i>
                            Change Photo
                            <input type="file" name="photo" class="hidden">
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <!-- Profile Details -->
                  <div class="w-full md:w-2/3 lg:w-3/4 space-y-6">
                    <!-- Full Name -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                      <label for="fullName" class="block text-sm font-medium text-gray-700 md:col-span-1">Full Name</label>
                      <div class="md:col-span-3">
                        <input name="fullName" type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                               id="fullName" 
                               value="<?php echo htmlspecialchars($admin_fullname); ?>" 
                               required 
                               oninput="validateName(this)">
                      </div>
                    </div>

                    <!-- Email -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                      <label for="Email" class="block text-sm font-medium text-gray-700 md:col-span-1">Email</label>
                      <div class="md:col-span-3">
                        <input name="email" type="email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                               id="Email" 
                               value="<?php echo htmlspecialchars($admin_email); ?>" 
                               required>
                      </div>
                    </div>

                    <!-- Username -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                      <label for="Username" class="block text-sm font-medium text-gray-700 md:col-span-1">Username</label>
                      <div class="md:col-span-3">
                        <input name="username" type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                               id="Username" 
                               value="<?php echo htmlspecialchars($admin_username); ?>" 
                               required 
                               oninput="validateAlphanumeric(this)">
                      </div>
                    </div>

                    <!-- Role -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                      <label for="Role" class="block text-sm font-medium text-gray-700 md:col-span-1">Role</label>
                      <div class="md:col-span-3">
                        <input name="role" type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                               id="Role" 
                               value="<?php echo htmlspecialchars($admin_role); ?>" 
                               required 
                               oninput="validateName(this)">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                  <button type="submit" 
                          class="px-6 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm font-medium">
                    Save Changes
                  </button>
                </div>
              </form>
            </div>

            <!-- Change Password Form -->
            <div class="tab-pane" id="profile-change-password">
              <form action="functions/change_password.php" method="POST" class="space-y-6">
                <!-- Current Password -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                  <label for="currentPassword" class="block text-sm font-medium text-gray-700 md:col-span-1">Current Password</label>
                  <div class="md:col-span-3">
                    <div class="relative">
                      <input name="current_password" type="password" 
                             class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                             id="currentPassword" 
                             required>
                      <button type="button" class="absolute right-3 top-2 text-gray-400 hover:text-gray-500" onclick="togglePassword('currentPassword')">
                        <i class="far fa-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- New Password -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                  <label for="newPassword" class="block text-sm font-medium text-gray-700 md:col-span-1">New Password</label>
                  <div class="md:col-span-3">
                    <div class="relative">
                      <input name="new_password" type="password" 
                             class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                             id="newPassword" 
                             required>
                      <button type="button" class="absolute right-3 top-2 text-gray-400 hover:text-gray-500" onclick="togglePassword('newPassword')">
                        <i class="far fa-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Re-enter New Password -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                  <label for="renewPassword" class="block text-sm font-medium text-gray-700 md:col-span-1">Re-enter New Password</label>
                  <div class="md:col-span-3">
                    <div class="relative">
                      <input name="renew_password" type="password" 
                             class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                             id="renewPassword" 
                             required>
                      <button type="button" class="absolute right-3 top-2 text-gray-400 hover:text-gray-500" onclick="togglePassword('renewPassword')">
                        <i class="far fa-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                  <button type="submit" 
                          class="px-6 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm font-medium">
                    Update Password
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <?php include "includes-new/footer.php" ?>

  <script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
      const tabs = document.querySelectorAll('[data-tab]');
      const tabContents = document.querySelectorAll('.tab-pane');
      
      tabs.forEach(tab => {
        tab.addEventListener('click', function() {
          // Remove active classes from all tabs and contents
          tabs.forEach(t => {
            t.classList.remove('border-indigo-600', 'text-indigo-600');
            t.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
          });
          tabContents.forEach(content => content.classList.remove('active'));
          
          // Add active class to clicked tab and corresponding content
          this.classList.add('border-indigo-600', 'text-indigo-600');
          this.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
          const tabId = this.getAttribute('data-tab');
          document.getElementById(tabId).classList.add('active');
        });
      });

      // Password visibility toggle
      window.togglePassword = function(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      };
    });

    // Validation functions
    function validateName(input) {
      input.value = input.value.replace(/[^A-Za-z\s]/g, '');
    }

    function validateNumeric(input) {
      input.value = input.value.replace(/[^0-9]/g, '');
    }

    function validateAlphanumeric(input) {
      input.value = input.value.replace(/[^A-Za-z0-9\s]/g, '');
    }

    function validateSpecialCharacters(input) {
      input.value = input.value.replace(/[A-Za-z0-9]/g, '');
    }

    function disableFutureDates(input) {
      const today = new Date().toISOString().split('T')[0];
      if (input.value > today) {
        input.value = today;
        alert('Future dates are not allowed.');
      }
    }
  </script>
</body>
</html>