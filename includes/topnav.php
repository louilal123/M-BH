
<!-- Amenities Modal (place this near your </body> tag) -->
<div id="amenitiesModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">All Amenities</h3>
                    <button onclick="closeAmenitiesModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 overflow-y-auto max-h-[60vh] pr-2">
                    <?php
                    $allAmenities = $conn->query("SELECT * FROM amenities ORDER BY name ASC");
                    while ($amenity = $allAmenities->fetch_assoc()):
                    ?>
                    <div class="flex items-center gap-3 p-3 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                        <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas <?php echo htmlspecialchars($amenity['icon']); ?> text-primary dark:text-primary-light"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-slate-800 dark:text-white">
                                <?php echo htmlspecialchars($amenity['name']); ?>
                            </h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                <?php echo htmlspecialchars($amenity['description']); ?>
                            </p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>  <!-- Login Modal -->
  <div id="login-modal" class="modal hidden">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Login</h2>
        <button onclick="closeModal('login-modal')" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <form id="login-form" class="space-y-4">

        <div>
          <label for="login-email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
          <input type="email" id="login-email" name="email" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
        </div>
        <div>
          <label for="login-password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
          <input type="password" id="login-password" name="password"  class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input type="checkbox" id="remember-me" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded">
            <label for="remember-me" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">Remember me</label>
          </div>
          <a href="#" class="text-sm text-primary hover:underline">Forgot password?</a>
        </div>
        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg transition duration-300">Sign in</button>
      </form>
      <div class="mt-6 text-center">
        <p class="text-sm text-slate-600 dark:text-slate-400">Don't have an account? <a href="#" onclick="openModal('signup-modal'); closeModal('login-modal');" class="text-primary hover:underline">Sign up</a></p>
      </div>
    </div>
  </div>
  
 <!-- Signup Modal -->
<div id="signup-modal" class="modal hidden">
  <div class="modal-content">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Sign Up</h2>
      <button onclick="closeModal('signup-modal')" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>

    <form id="signup-form" class="space-y-4">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="first-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">First Name</label>
          <input type="text" id="first-name" name="first_name" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
        </div>
        <div>
          <label for="last-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Last Name</label>
          <input type="text" id="last-name" name="last_name" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
        </div>
      </div>

      <div>
        <label for="signup-email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
        <input type="email" id="signup-email" name="email" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
      </div>

      <div>
        <label for="occupation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Occupation</label>
        <select id="occupation" name="occupation" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
          <option value="">Select your occupation</option>
          <option value="Student">Student</option>
          <option value="Working Professional">Working Professional</option>
          <option value="Business Owner">Business Owner</option>
          <option value="Freelancer">Freelancer</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div>
        <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
        <input type="text" id="phone" name="phone" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
      </div>

      <div>
        <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Address (Optional)</label>
        <textarea id="address" name="address" rows="2" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white"></textarea>
      </div>

      <div>
        <label for="photo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Upload Photo (Optional)</label>
        <input type="file" id="photo" name="photo" accept="image/*" class="block w-full text-sm text-slate-800 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
      </div>

    
      <div>
        <label for="signup-password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
        <input type="password" id="signup-password" name="password" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
      </div>

      <div>
        <label for="confirm-password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm_password" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
      </div>

      <div class="flex items-center">
        <input type="checkbox" id="terms" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded" required>
        <label for="terms" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">I agree to the <a href="#" class="text-primary hover:underline">Terms and Conditions</a></label>
      </div>

      <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg transition duration-300">Create Account</button>
    </form>

    <div class="mt-6 text-center">
      <p class="text-sm text-slate-600 dark:text-slate-400">Already have an account? <a href="#" onclick="openModal('login-modal'); closeModal('signup-modal');" class="text-primary hover:underline">Sign in</a></p>
    </div>
  </div>
</div>
<?php if (isset($_SESSION['show_login_modal']) && $_SESSION['show_login_modal']): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      openModal('login-modal');
    });
  </script>
  <?php unset($_SESSION['show_login_modal']); ?>
<?php endif; ?>

<header class="navbar fixed w-full top-0 z-50 transition-all duration-300 
  border-b border-white/20 dark:border-white/10">
  <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
    <a href="index.php" class="flex items-center gap-2">
    <span class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent 
              group-hover:from-blue-500 group-hover:to-blue-300 dark:  transition-all duration-500">
    MECMEC
  </span> <span class="text-orange-400 dark:text-orange-400 text-lg font-semibold mt-3">BH</span>
    </a>
    
    <!-- Desktop Navigation -->
    <ul class="hidden md:flex items-center gap-6">
      <li><a href="index.php##home" class="text-white hover:text-primary-light font-medium transition">Home</a></li>
      <li class="dropdown">
        <a href="rooms" class="text-white hover:text-primary-light font-medium transition flex items-center gap-1">
          Rooms
          
        </a>
       
      </li>
      <li class="dropdown">
    <a href="#amenities" class="text-white hover:text-primary-light font-medium transition flex items-center gap-1">
        Amenities
        <i class="fas fa-chevron-down text-xs mt-1"></i>
    </a>
    <div class="dropdown-content bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 top-full w-[400px]">
        <div class="grid grid-cols-2 gap-3">
            <?php
            include "admin/functions/connection.php";
            
            // Get limited amenities
            $limit = 6; // Even number works better for 2-column layout
            $stmt = $conn->prepare("SELECT * FROM amenities ORDER BY name ASC LIMIT ?");
            if ($stmt) {
                $stmt->bind_param("i", $limit);
                if ($stmt->execute()) {
                    $limitedAmenities = $stmt->get_result();
                    
                    while ($amenity = $limitedAmenities->fetch_assoc()):
                    ?>
                    <a href="#amenity-<?php echo htmlspecialchars($amenity['amenity_id']); ?>" 
                      class="flex flex-col items-center text-center gap-2 p-3 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                        <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center">
                            <i class="fas <?php echo htmlspecialchars($amenity['icon']); ?> text-primary dark:text-primary-light text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-slate-800 dark:text-white text-sm">
                                <?php echo htmlspecialchars($amenity['name']); ?>
                            </h4>
                        </div>
                    </a>
                    <?php endwhile;
                    
                    // Get total count
                    $countResult = $conn->query("SELECT COUNT(*) as total FROM amenities");
                    if ($countResult) {
                        $totalAmenities = $countResult->fetch_assoc()['total'];
                        $countResult->free();
                    }
                }
                $stmt->close();
            }
            ?>
        </div>
        <div class="border-t border-slate-200 dark:border-slate-700 pt-3 mt-3 text-center">
            <button onclick="openAmenitiesModal()" 
                    class="text-primary dark:text-primary-light hover:underline text-sm font-medium inline-flex items-center gap-1">
                View All <?php echo isset($totalAmenities) ? htmlspecialchars($totalAmenities) : ''; ?> Amenities
                <i class="fas fa-external-link-alt text-xs"></i>
            </button>
        </div>
    </div>
</li>

      <li><a href="#contact" class="text-white hover:text-primary-light font-medium transition">Contact</a></li>
      <li>
        <!-- Dark Mode Toggle -->
        <button id="theme-toggle" class="p-2 rounded-full hover:bg-white/10 text-white transition">
          <i class="fas fa-moon text-white hidden dark:inline"></i>
          <i class="fas fa-sun text-white dark:hidden"></i>
        </button>
      </li>
      
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <!-- User is logged in - show profile dropdown -->
        <li class="dropdown">
        <div class="flex items-center gap-2 cursor-pointer">
        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-600 to-blue-400 flex items-center justify-center">
          <span class="text-white text-xs font-bold">
            <?php 
              // Function to get initials
              function getInitials($name) {
                $names = explode(' ', $name);
                $initials = '';
                foreach ($names as $n) {
                  $initials .= strtoupper(substr($n, 0, 1));
                  if (strlen($initials) >= 2) break; // Limit to 2 initials
                }
                return $initials;
              }
              
              // Get initials from session name or tenant data
              $nameToUse = $_SESSION['name'] ?? $tenantData['name'] ?? '';
              echo htmlspecialchars(getInitials($nameToUse));
            ?>
          </span>
        </div>
        <span class="text-white"><?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></span>
        <i class="fas fa-chevron-down text-xs mt-1 text-white"></i>
      </div>
          <div class="dropdown-content bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 top-full right-0 w-48">
            <div class="space-y-2">
              <div class="px-2 py-1 text-sm text-slate-600 dark:text-slate-300">
                <p class="font-medium"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <p><?php echo htmlspecialchars($_SESSION['occupation']); ?></p>
              </div>
              <div class="border-t border-slate-200 dark:border-slate-700"></div>
              <a href="profile.php" class="block px-2 py-1 hover:bg-slate-100 dark:hover:bg-slate-700 rounded transition text-slate-700 dark:text-slate-300">
                <i class="fas fa-user-circle mr-2"></i> Profile
              </a>
              <a href="#" onclick="openNotificationsModal(event)" class="block px-2 py-1 hover:bg-slate-100 dark:hover:bg-slate-700 rounded transition text-slate-700 dark:text-slate-300">
                <i class="fas fa-bell mr-2"></i> Notifications
              </a>
              <a href="functions/logout.php" class="block px-2 py-1 hover:bg-slate-100 dark:hover:bg-slate-700 rounded transition text-slate-700 dark:text-slate-300">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
              </a>
            </div>
          </div>
        </li>
      <?php else: ?>
        <!-- User is not logged in - show login/signup buttons -->
        <li>
          <button onclick="openModal('login-modal')" class="ml-4 border border-white/30 text-white hover:bg-white/10 px-5 py-2 rounded-full transition">
            Login
          </button>
        </li>
        <li>
          <button onclick="openModal('signup-modal')" class="ml-0 bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-full shadow-md transition">
            Sign Up
          </button>
        </li>
      <?php endif; ?>
    </ul>
    
    <!-- Mobile Menu Button -->
    <div class="flex items-center gap-4 md:hidden">
      <button id="mobile-theme-toggle" class="p-2 rounded-full text-white hover:bg-white/10 transition">
        <i class="fas fa-moon text-white hidden dark:inline"></i>
        <i class="fas fa-sun text-white dark:hidden"></i>
      </button>
      <button id="mobile-menu-button" class="text-white">
        <i class="fas fa-bars text-xl"></i>
      </button>
    </div>
  </nav>
  
  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-slate-900 shadow-lg">
    <ul class="py-4 px-6 space-y-4">
      <li><a href="#home" class="block py-2 hover:text-primary dark:hover:text-primary-light font-medium">Home</a></li>
      <li>
        <div class="block py-2">
          <div class="flex items-center justify-between hover:text-primary dark:hover:text-primary-light font-medium cursor-pointer mobile-dropdown-toggle">
            <span>Rooms</span>
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
          <div class="pl-4 space-y-2 mt-2 hidden mobile-dropdown">
            <a href="#" class="block py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary-light">Single Room</a>
            <a href="#" class="block py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary-light">Double Room</a>
            <a href="#" class="block py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary-light">Premium Room</a>
          </div>
        </div>
      </li>
      <li>
        <div class="block py-2">
          <div class="flex items-center justify-between hover:text-primary dark:hover:text-primary-light font-medium cursor-pointer mobile-dropdown-toggle">
            <span>Amenities</span>
            <i class="fas fa-chevron-down text-xs"></i>
          </div>
          <div class="pl-4 space-y-2 mt-2 hidden mobile-dropdown">
            <a href="#" class="block py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary-light">WiFi</a>
            <a href="#" class="block py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary-light">Kitchen</a>
            <a href="#" class="block py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary-light">Lounge</a>
            <a href="#" class="block py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary-light">Laundry</a>
          </div>
        </div>
      </li>
      <li><a href="#gallery" class="block py-2 hover:text-primary dark:hover:text-primary-light font-medium">Gallery</a></li>
      <li><a href="#testimonials" class="block py-2 hover:text-primary dark:hover:text-primary-light font-medium">Testimonials</a></li>
      <li><a href="#contact" class="block py-2 hover:text-primary dark:hover:text-primary-light font-medium">Contact</a></li>
      
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <li class="border-t border-slate-200 dark:border-slate-700 pt-4 mt-4">
          <div class="flex items-center gap-3 px-2 py-3">
            <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
              <i class="fas fa-user text-primary"></i>
            </div>
            <div>
              <p class="font-medium"><?php echo htmlspecialchars($_SESSION['name']); ?></p>
              <p class="text-sm text-slate-500 dark:text-slate-400"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
          </div>
        </li>
        <li>
          <a href="profile.php" class="block w-full border border-primary text-primary dark:border-primary-light dark:text-primary-light text-center px-5 py-3 rounded-lg mb-2">
            Profile
          </a>
        </li>
        <li>
          <a href="functions/logout.php" class="block w-full bg-primary hover:bg-primary-dark text-white text-center px-5 py-3 rounded-lg">
            Logout
          </a>
        </li>
      <?php else: ?>
        <li class="border-t border-slate-200 dark:border-slate-700 pt-4 mt-4">
          <button onclick="openModal('login-modal')" class="block w-full border border-primary text-primary dark:border-primary-light dark:text-primary-light text-center px-5 py-3 rounded-lg mb-2">
            Login
          </button>
        </li>
        <li>
          <button onclick="openModal('signup-modal')" class="block w-full bg-primary hover:bg-primary-dark text-white text-center px-5 py-3 rounded-lg">
            Sign Up
          </button>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</header>
<!-- Notifications Modal -->
<div id="notificationsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-black bg-opacity-50"></div>
  
  <!-- Modal Container (centered) -->
  <div class="relative bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-md max-h-[80vh] flex flex-col">
    <!-- Modal Header -->
    <div class="flex justify-between items-center p-4 border-b border-slate-200 dark:border-slate-700">
      <h3 class="text-lg font-semibold">Notifications</h3>
      <button onclick="closeNotificationsModal()" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 text-xl">
        &times;
      </button>
    </div>
    
    <!-- Modal Content -->
    <div class="overflow-y-auto flex-1 p-4">
      <!-- Notification Items -->
      <div class="space-y-3">
        <!-- Sample Notification 1 -->
        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
          <div class="mt-1 flex-shrink-0">
            <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-full">
              <i class="fas fa-receipt text-blue-500 dark:text-blue-300 text-sm"></i>
            </div>
          </div>
          <div>
            <p class="font-medium">Payment Received</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Your payment of ₱8,000 was processed</p>
            <p class="text-xs text-slate-400 mt-2">2 hours ago</p>
          </div>
        </div>
        
        <!-- Sample Notification 2 -->
        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
          <div class="mt-1 flex-shrink-0">
            <div class="bg-green-100 dark:bg-green-900 p-2 rounded-full">
              <i class="fas fa-check-circle text-green-500 dark:text-green-300 text-sm"></i>
            </div>
          </div>
          <div>
            <p class="font-medium">Booking Confirmed</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Room #203 has been reserved for you</p>
            <p class="text-xs text-slate-400 mt-2">1 day ago</p>
          </div>
        </div>
        
        <!-- Sample Notification 3 -->
        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
          <div class="mt-1 flex-shrink-0">
            <div class="bg-yellow-100 dark:bg-yellow-900 p-2 rounded-full">
              <i class="fas fa-exclamation-circle text-yellow-500 dark:text-yellow-300 text-sm"></i>
            </div>
          </div>
          <div>
            <p class="font-medium">Payment Due</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Upcoming payment due in 3 days</p>
            <p class="text-xs text-slate-400 mt-2">3 days ago</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal Footer -->
    <div class="p-4 border-t border-slate-200 dark:border-slate-700 text-center">
      <button onclick="closeNotificationsModal()" class="text-primary hover:text-primary-dark font-medium px-4 py-2">
        Close Notifications
      </button>
    </div>
  </div>
</div>
<script>
  function openNotificationsModal(event) {
    event.preventDefault();
    document.getElementById('notificationsModal').classList.remove('hidden');
  }

  function closeNotificationsModal() {
    document.getElementById('notificationsModal').classList.add('hidden');
  }

  // Close modal when clicking outside
  document.getElementById('notificationsModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeNotificationsModal();
    }
  });
</script>

