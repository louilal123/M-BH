
<?php

include 'functions/connection.php'; 
include 'includes/session.php';
$queryAdmins = "SELECT COUNT(*) as adminCount FROM admin";
$resultAdmins = mysqli_query($conn, $queryAdmins);
$rowAdmins = mysqli_fetch_assoc($resultAdmins);
$adminCount = $rowAdmins['adminCount'];

$queryRooms = "SELECT COUNT(*) as roomCount FROM rooms";
$resultRooms = mysqli_query($conn, $queryRooms);
$rowRooms = mysqli_fetch_assoc($resultRooms);
$roomCount = $rowRooms['roomCount'];

$queryTenants = "SELECT COUNT(*) as tenantCount FROM tenants";
$resultTenants = mysqli_query($conn, $queryTenants);
$rowTenants = mysqli_fetch_assoc($resultTenants);
$tenantCount = $rowTenants['tenantCount'];

$queryBookings = "SELECT COUNT(*) as bookingCount FROM bookings";
$resultBookings = mysqli_query($conn, $queryBookings);
$rowBookings = mysqli_fetch_assoc($resultBookings);
$bookingstCount = $rowBookings['bookingCount'];

// Query for the total income (sum of amount_paid)
$queryIncome = "SELECT SUM(amount_paid) as totalIncome FROM payments WHERE payment_status = 'completed'";
$resultIncome = mysqli_query($conn, $queryIncome);
$rowIncome = mysqli_fetch_assoc($resultIncome);
$totalIncome = $rowIncome['totalIncome'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding House Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
       
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
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

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
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
                                <img src="assets/uploads/<?php echo htmlspecialchars($admin_photo); ?>">
                                <span class="hidden md:inline font-medium"><?php echo htmlspecialchars($admin_fullname); ?></span>
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

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 bg-gray-200">
                <!-- Dashboard Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                      
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Add Tenant</span>
                        </button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <i class="fas fa-download mr-2"></i>
                            <span class="hidden md:inline">Export</span>
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-5 animate-fade-in" style="animation-delay: 0s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Tenants</p>
                                <h3 class="text-2xl font-bold"><?php echo $tenantCount; ?> </h3>
                                <p class="text-green-600 text-sm mt-1 flex items-center">
                                   
                                    <span>8% from last month</span>
                                </p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-users text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 animate-fade-in" style="animation-delay: 0.1s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Occupied Rooms</p>
                                <h3 class="text-2xl font-bold">28/30</h3>
                                <p class="text-green-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>93% occupancy rate</span>
                                </p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-door-open text-purple-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 animate-fade-in" style="animation-delay: 0.2s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Monthly Revenue</p>
                                <h3 class="text-2xl font-bold"><?php echo number_format($totalIncome, 2); ?> </h3>
                                <p class="text-green-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>12% from last month</span>
                                </p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-dollar-sign text-green-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 animate-fade-in" style="animation-delay: 0.3s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">ROOM COUNT</p>
                                <h3 class="text-2xl font-bold"><?php echo $roomCount; ?></h3>
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>2 overdue</span>
                                </p>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts & Analytics -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-5 lg:col-span-2 animate-fade-in" style="animation-delay: 0.4s;">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-700">Revenue Trends</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs bg-indigo-100 text-indigo-600 rounded-md hover:bg-indigo-200">Monthly</button>
                                <button class="px-3 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded-md">Quarterly</button>
                                <button class="px-3 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded-md">Yearly</button>
                            </div>
                        </div>
                        <div class="chart-container" id="revenue-chart"></div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-5 animate-fade-in" style="animation-delay: 0.5s;">
                        <h3 class="font-bold text-gray-700 mb-4">Room Occupancy</h3>
                        <div class="chart-container" id="occupancy-chart"></div>
                    </div>
                </div>

           

                <!-- Recent Tenants Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden animate-fade-in" style="animation-delay: 0.8s;">
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-gray-700">Recent Tenants</h3>
                            <div class="flex space-x-2">
                                <div class="relative">
                                    <select class="appearance-none bg-gray-100 py-2 pl-3 pr-8 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option>All Tenants</option>
                                        <option>Active Tenants</option>
                                        <option>Pending Payments</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                                <button class="text-indigo-600 text-sm hover:text-indigo-800">View All</button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-medium">JD</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">John Doe</div>
                                                <div class="text-sm text-gray-500">john.doe@example.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Room 201</div>
                                        <div class="text-sm text-gray-500">Second Floor</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Jan 15, 2025</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="text-green-600 font-medium">Paid</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-indigo-600 hover:text-indigo-900"><i class="fas fa-edit"></i></button>
                                            <button class="text-gray-500 hover:text-gray-900"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                                <span class="text-pink-600 font-medium">JS</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Jane Smith</div>
                                                <div class="text-sm text-gray-500">jane.smith@example.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Room 105</div>
                                        <div class="text-sm text-gray-500">First Floor</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Feb 02, 2025</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="text-yellow-600 font-medium">Pending</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-indigo-600 hover:text-indigo-900"><i class="fas fa-edit"></i></button>
                                            <button class="text-gray-500 hover:text-gray-900"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <span class="text-green-600 font-medium">RJ</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Robert Johnson</div>
                                                <div class="text-sm text-gray-500">robert@example.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Room 302</div>
                                        <div class="text-sm text-gray-500">Third Floor</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Mar 10, 2025</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="text-green-600 font-medium">Paid</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-indigo-600 hover:text-indigo-900"><i class="fas fa-edit"></i></button>
                                            <button class="text-gray-500 hover:text-gray-900"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <span class="text-yellow-600 font-medium">EW</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Emily Wilson</div>
                                                <div class="text-sm text-gray-500">emily@example.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Room 205</div>
                                        <div class="text-sm text-gray-500">Second Floor</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Apr 05, 2025</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Overdue</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="text-red-600 font-medium">3 days late</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button class="text-indigo-600 hover:text-indigo-900"><i class="fas fa-edit"></i></button>
                                            <button class="text-gray-500 hover:text-gray-900"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-3 border-t border-gray-200 flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                            <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">1</span> to <span class="font-medium">4</span> of <span class="font-medium">42</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left text-xs"></i>
                                    </a>
                                    <a href="#" aria-current="page" class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        1
                                    </a>
                                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        2
                                    </a>
                                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium">
                                        3
                                    </a>
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                        ...
                                    </span>
                                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        8
                                    </a>
                                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="dashboard.js"></script>
  <script>
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