<?php
?>
<!-- Top Navigation -->
<style>
  .dt-empty {
    align-items: center;
    justify-content: center;
    padding: 1rem;
    margin-top: 1rem;
    text-align: center;
  }
</style>

<header class="bg-white dark:bg-gray-900 shadow-sm z-10">
  <div class="flex items-center justify-between px-4 py-3">
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="md:hidden text-gray-600 dark:text-white focus:outline-none">
      <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Search Bar -->
    <div class="hidden md:flex relative flex-1 mx-5">
      <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400">
        <i class="fas fa-search"></i>
      </span>
      <input type="text" placeholder="Search..." class="w-[96] pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all">
    </div>

    <!-- Right Section -->
    <div class="flex items-center space-x-4">
      <!-- Notification -->
      <div class="relative">
        <button id="notification-button" class="relative p-2 text-gray-600 dark:text-white hover:bg-black/10 dark:hover:bg-white/10 rounded-full transition">
          <i class="fas fa-bell text-xl"></i>
          <span id="notification-badge" class="absolute -top-1 -right-2 bg-red-500 text-white text-xs font-semibold rounded-full h-5 w-5 flex items-center justify-center">3</span>
        </button>

        <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-[36rem] bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-white/20 z-50 overflow-hidden transition-all">
          <div class="p-3 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="font-semibold text-black dark:text-white">Notifications</h3>
            <button class="text-xs text-blue-500 hover:text-blue-400">Mark all as read</button>
          </div>

          <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-white/10">
            <div class="notification-item unread bg-blue-100 dark:bg-blue-900/10 cursor-pointer p-3 flex items-start hover:bg-blue-200 dark:hover:bg-white/5 transition">
              <img src="https://randomuser.me/api/portraits/women/1.jpg" class="h-10 w-10 rounded-full mr-3" alt="Profile">
              <div class="flex-1">
                <p class="text-black dark:text-white font-medium">Jane liked your post</p>
                <p class="text-gray-600 dark:text-white/70 text-sm">"Great work on the project!"</p>
                <p class="text-xs text-blue-500 dark:text-blue-400 mt-1">1 min ago</p>
              </div>
              <span class="h-2 w-2 bg-blue-500 rounded-full mt-2 unread-indicator"></span>
            </div>
          </div>

          <div class="p-3 border-t border-gray-200 dark:border-white/10 text-center">
            <button class="text-sm text-blue-500 hover:text-blue-400">View all notifications</button>
          </div>
        </div>
      </div>

      <!-- Profile -->
      <div class="relative">
        <button id="profile-menu-btn" class="flex items-center space-x-2 focus:outline-none">
          <img src="assets/uploads/<?php echo htmlspecialchars($admin_photo); ?>" class="h-8 w-8 rounded-full" alt="User">
          <span class="hidden md:inline font-medium text-gray-800 dark:text-white"><?php echo htmlspecialchars($admin_fullname); ?></span>
          <i class="fas fa-chevron-down ml-1 text-sm text-gray-700 dark:text-gray-300"></i>
        </button>

        <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 transition-all">
          <a href="profile.php" class="block px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition"><i class="fas fa-user"></i> Profile</a>
          <a href="settings.php" class="block px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition"><i class="fas fa-gear"></i> Settings</a>
          <a href="logout.php" class="block px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition"><i class="fas fa-sign-out"></i> Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Dropdown Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const notifBtn = document.getElementById('notification-button');
  const notifDropdown = document.getElementById('notification-dropdown');
  const notifBadge = document.getElementById('notification-badge');
  const profileBtn = document.getElementById('profile-menu-btn');
  const profileDropdown = document.getElementById('profile-dropdown');

  notifBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    notifDropdown.classList.toggle('hidden');
    notifBadge.textContent = '0';
    notifBadge.classList.replace('bg-red-500', 'bg-gray-500');
  });

  document.addEventListener('click', function (e) {
    if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
      notifDropdown.classList.add('hidden');
    }
    if (!profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
      profileDropdown.classList.add('hidden');
    }
  });

  profileBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    profileDropdown.classList.toggle('hidden');
  });

  document.querySelectorAll('.notification-item.unread').forEach(item => {
    item.addEventListener('click', () => {
      item.classList.remove('unread', 'bg-blue-100', 'dark:bg-blue-900/10');
      const indicator = item.querySelector('.unread-indicator');
      if (indicator) indicator.remove();
    });
  });
});
</script>
