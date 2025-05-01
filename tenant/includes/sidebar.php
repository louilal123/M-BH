 <!-- Sidebar -->
        <aside id="sidebar" class="hidden md:flex flex-col w-64 bg-indigo-900 text-white transition-all duration-300">
            <div class="flex items-center justify-between p-4 border-b border-indigo-800">
                <div class="flex items-center space-x-2">
                  <h1 class="text-2xl text-yellow-600">Mec </h1>
                    <span class="font-bold text-xl">Mec BH</span>
                </div>
                <button id="collapse-btn" class="text-gray-900 p-1 ml-8 rounded  md:hidden">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="px-4 space-y-2">
                    <div class="sidebar-item active p-3 flex items-center space-x-3 cursor-pointer">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i>
                        <span>Dashboard</span>
                    </div>
                    <div class="sidebar-item p-3 flex items-center space-x-3 cursor-pointer">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>Tenants</span>
                    </div>
                    <div class="sidebar-item p-3 flex items-center space-x-3 cursor-pointer">
                        <i class="fas fa-door-open w-5 text-center"></i>
                        <span>Rooms</span>
                    </div>
                    <div class="sidebar-item p-3 flex items-center space-x-3 cursor-pointer">
                        <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
                        <span>Payments</span>
                    </div>
                    <div class="sidebar-item p-3 flex items-center space-x-3 cursor-pointer">
                        <i class="fas fa-wrench w-5 text-center"></i>
                        <span>Maintenance</span>
                    </div>
                    <div class="sidebar-item p-3 flex items-center space-x-3 cursor-pointer">
                        <i class="fas fa-calendar-alt w-5 text-center"></i>
                        <span>Bookings</span>
                    </div>
                    <div class="sidebar-item p-3 flex items-center space-x-3 cursor-pointer">
                        <i class="fas fa-chart-line w-5 text-center"></i>
                        <span>Reports</span>
                    </div>
                </nav>
            </div>
            <div class="p-4 border-t border-indigo-800">
                <div class="sidebar-item p-3 flex items-center space-x-3 cursor-pointer">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span>Settings</span>
                </div>
                <div class="sidebar-item p-3 flex items-center space-x-3 cursor-pointer">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span>Logout</span>
                </div>
            </div>
        </aside>