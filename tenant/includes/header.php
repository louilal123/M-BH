  <!-- Top Navigation -->
  <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between p-3">
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="md:hidden text-gray-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Search Bar -->
                    <div class="hidden md:flex relative flex-1 mx-5 pl-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" placeholder="Search..." class="w-sm pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                    </div>
                    
                    <div class="flex items-center space-x-4 relative">
                        <button class="relative text-gray-600 hover:text-gray-800 focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                        </button>

                        <!-- Profile Dropdown Trigger -->
                        <div class="relative mr-2">
                            <button id="profile-menu-btn" class="flex items-center space-x-2 focus:outline-none">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40' fill='%236366F1'%3E%3Ccircle cx='20' cy='20' r='20' fill='%23ddd'/%3E%3Cpath d='M20 21a8 8 0 100-16 8 8 0 000 16zm0 4c-5.34 0-16 2.68-16 8v2h32v-2c0-5.32-10.66-8-16-8z' fill='%236366F1'/%3E%3C/svg%3E" alt="User" class="h-8 w-8 rounded-full">
                                <span class="hidden md:inline font-medium">Admin User</span>
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50">
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">  <i class="fas fa-user"></i> Profile </a>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"> <i class="fas fa-gear"></i> Settings</a>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"> <i class="fas fa-sign-out"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
