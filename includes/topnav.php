  <!-- Login Modal -->
  <div id="login-modal" class="modal">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Login</h2>
        <button onclick="closeModal('login-modal')" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <form class="space-y-4">
        <div>
          <label for="login-email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
          <input type="email" id="login-email" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
        </div>
        <div>
          <label for="login-password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
          <input type="password" id="login-password" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
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
  <div id="signup-modal" class="modal">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Sign Up</h2>
        <button onclick="closeModal('signup-modal')" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <form action="functions/signup_process.php" method="post" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="first-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">First Name</label>
            <input type="text" id="first-name" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
          </div>
          <div>
            <label for="last-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Last Name</label>
            <input type="text" id="last-name" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
          </div>
        </div>
        <div>
          <label for="signup-email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
          <input type="email" id="signup-email" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
        </div>
        <div>
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
          <label for="signup-password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
          <input type="password" id="signup-password" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
        </div>
        <div>
          <label for="confirm-password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirm Password</label>
          <input type="password" id="confirm-password" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-800 text-slate-800 dark:text-white" required>
        </div>
        <div class="flex items-center">
          <input type="checkbox" id="terms" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded">
          <label for="terms" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">I agree to the <a href="#" class="text-primary hover:underline">Terms and Conditions</a></label>
        </div>
        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg transition duration-300">Create Account</button>
      </form>
      <div class="mt-6 text-center">
        <p class="text-sm text-slate-600 dark:text-slate-400">Already have an account? <a href="#" onclick="openModal('login-modal'); closeModal('signup-modal');" class="text-primary hover:underline">Sign in</a></p>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <header class="navbar fixed w-full top-0 z-50 navbar-transparent transition-all duration-300">
    <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
      <a href="#" class="flex items-center gap-2">
        <span class="text-white text-2xl font-bold font-heading">MECMEC</span>
        <span class="text-sm bg-accent text-white px-2 py-1 rounded">BH</span>
      </a>
      
      <!-- Desktop Navigation -->
      <ul class="hidden md:flex items-center gap-6">
        <li><a href="#home" class="text-white hover:text-primary-light font-medium transition">Home</a></li>
        <li class="dropdown">
          <a href="#rooms" class="text-white hover:text-primary-light font-medium transition flex items-center gap-1">
            Rooms
            <i class="fas fa-chevron-down text-xs mt-1"></i>
          </a>
          <div class="dropdown-content bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 top-full">
            <div class="grid grid-cols-1 gap-3">
              <a href="#" class="flex items-center gap-3 p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                <div class="w-12 h-12 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center">
                  <i class="fas fa-bed text-primary dark:text-primary-light"></i>
                </div>
                <div>
                  <h4 class="font-medium text-slate-800 dark:text-white">Single Room</h4>
                  <p class="text-sm text-slate-500 dark:text-slate-400">Perfect for individual stay</p>
                </div>
              </a>
              <a href="#" class="flex items-center gap-3 p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                <div class="w-12 h-12 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center">
                  <i class="fas fa-users text-primary dark:text-primary-light"></i>
                </div>
                <div>
                  <h4 class="font-medium text-slate-800 dark:text-white">Double Room</h4>
                  <p class="text-sm text-slate-500 dark:text-slate-400">Share with a friend</p>
                </div>
              </a>
              <a href="#" class="flex items-center gap-3 p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                <div class="w-12 h-12 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center">
                  <i class="fas fa-crown text-primary dark:text-primary-light"></i>
                </div>
                <div>
                  <h4 class="font-medium text-slate-800 dark:text-white">Premium Room</h4>
                  <p class="text-sm text-slate-500 dark:text-slate-400">Ultimate comfort</p>
                </div>
              </a>
              <div class="border-t border-slate-200 dark:border-slate-700 pt-2 mt-2">
                <a href="#rooms" class="text-primary dark:text-primary-light hover:underline text-sm font-medium">
                  View All Room Options →
                </a>
              </div>
            </div>
          </div>
        </li>
        <li class="dropdown">
          <a href="#amenities" class="text-white hover:text-primary-light font-medium transition flex items-center gap-1">
            Amenities
            <i class="fas fa-chevron-down text-xs mt-1"></i>
          </a>
          <div class="dropdown-content bg-white dark:bg-slate-800 rounded-lg shadow-lg p-4 top-full">
            <div class="grid grid-cols-2 gap-3">
              <a href="#" class="flex items-center gap-3 p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center">
                  <i class="fas fa-wifi text-primary dark:text-primary-light"></i>
                </div>
                <span class="text-slate-800 dark:text-white">WiFi</span>
              </a>
              <a href="#" class="flex items-center gap-3 p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center">
                  <i class="fas fa-kitchen-set text-primary dark:text-primary-light"></i>
                </div>
                <span class="text-slate-800 dark:text-white">Kitchen</span>
              </a>
              <a href="#" class="flex items-center gap-3 p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center">
                  <i class="fas fa-couch text-primary dark:text-primary-light"></i>
                </div>
                <span class="text-slate-800 dark:text-white">Lounge</span>
              </a>
              <a href="#" class="flex items-center gap-3 p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center">
                  <i class="fas fa-washer text-primary dark:text-primary-light"></i>
                </div>
                <span class="text-slate-800 dark:text-white">Laundry</span>
              </a>
            </div>
            <div class="border-t border-slate-200 dark:border-slate-700 pt-2 mt-2">
              <a href="#amenities" class="text-primary dark:text-primary-light hover:underline text-sm font-medium">
                View All Amenities →
              </a>
            </div>
          </div>
        </li>
        <li><a href="#gallery" class="text-white hover:text-primary-light font-medium transition">Gallery</a></li>
        <li><a href="#testimonials" class="text-white hover:text-primary-light font-medium transition">Testimonials</a></li>
        <li><a href="#contact" class="text-white hover:text-primary-light font-medium transition">Contact</a></li>
        <li>
          <!-- Dark Mode Toggle -->
          <button id="theme-toggle" class="p-2 rounded-full hover:bg-white/10 text-white transition">
            <i class="fas fa-moon text-white hidden dark:inline"></i>
            <i class="fas fa-sun text-white dark:hidden"></i>
          </button>
        </li>
        <li>
          <button onclick="openModal('login-modal')" class="ml-4 border border-white/30 text-white hover:bg-white/10 px-5 py-2 rounded-full transition">
            Login
          </button>
        </li>
        <li>
          <button onclick="openModal('signup-modal')" class="ml-4 bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-full shadow-md transition">
            Sign Up
          </button>
        </li>
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
      </ul>
    </div>
  </header>