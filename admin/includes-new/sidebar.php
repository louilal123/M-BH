<!-- Sidebar -->
<aside id="sidebar" class="hidden md:flex flex-col w-64 bg-white text-gray-900 transition-all duration-300 border-r border-gray-200">
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <div class="flex items-center space-x-2">
            <h1 class="text-2xl text-indigo-700 font-bold">Mec</h1>
            <span class="font-bold text-xl">Mec BH</span>
        </div>
        <button id="collapse-btn" class="text-gray-900 p-1 ml-8 rounded md:hidden">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-4 space-y-2">
            <a href="dashboard.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-tachometer-alt w-5 text-center"></i>
                    <span>Dashboard</span>
                </div>
            </a>
            <a href="tenants.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'tenants.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span>Tenants</span>
                </div>
            </a>
            <a href="rooms.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-door-open w-5 text-center"></i>
                    <span>Rooms</span>
                </div>
            </a>
            <a href="room_types.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'room_types.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-door-open w-5 text-center"></i>
                    <span>Room Types</span>
                </div>
            </a>
            <a href="payments.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
                    <span>Payments</span>
                </div>
            </a>
            <a href="maintenance.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'maintenance.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-wrench w-5 text-center"></i>
                    <span>Maintenance</span>
                </div>
            </a>
            <a href="bookings.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-calendar-alt w-5 text-center"></i>
                    <span>Bookings</span>
                </div>
            </a>
            <a href="reports.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span>Reports</span>
                </div>
            </a>
            <a href="users.php" class="block">
                <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span>Manage Admins</span>
                </div>
            </a>
        </nav>
    </div>
    <div class="p-4 border-t border-gray-200">
        <a href="settings.php" class="block">
            <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100'; ?>">
                <i class="fas fa-cog w-5 text-center"></i>
                <span>Settings</span>
            </div>
        </a>
        <a href="logout.php" class="block">
            <div class="sidebar-item p-3 flex items-center space-x-3 rounded cursor-pointer hover:bg-gray-100">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span>Logout</span>
            </div>
        </a>
    </div>
</aside>
