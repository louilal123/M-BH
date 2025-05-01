<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <?php include "includes/header.php"; ?>
  <!-- Additional dashboard styles -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head><style>
     .navbar {
      background-color: rgba(42, 0, 159, 0.95) !important;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-all duration-300">
  <?php include "includes/topnav.php"; ?>
  
  <!-- Dashboard Main Section -->
  <section id="dashboard" class="pt-24 pb-16  md:px-8 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
      <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">My Dashboard</h1>
      <div class="flex items-center mt-4 md:mt-0">
        <span class="mr-2">Last login: </span>
        <span class="font-medium"><?php echo date('M d, Y H:i'); ?></span>
      </div>
    </div>

    <!-- Mini Dashboard / Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Bookings</p>
            <h3 class="text-2xl font-bold mt-1">28</h3>
          </div>
          <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-green-500 text-sm font-medium flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            12% increase
          </span>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Spent</p>
            <h3 class="text-2xl font-bold mt-1">$1,245</h3>
          </div>
          <div class="bg-indigo-100 dark:bg-indigo-900/30 p-3 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-green-500 text-sm font-medium flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            8.3% increase
          </span>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Active Bookings</p>
            <h3 class="text-2xl font-bold mt-1">3</h3>
          </div>
          <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-yellow-500 text-sm font-medium flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
            No change
          </span>
        </div>
      </div>
      
      <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Account Status</p>
            <h3 class="text-2xl font-bold mt-1">Premium</h3>
          </div>
          <div class="bg-indigo-100 dark:bg-indigo-900/30 p-3 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-blue-500 text-sm font-medium">Valid until: Dec 31, 2025</span>
        </div>
      </div>
    </div>

    <!-- Main Content Area with Tabs -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-100 dark:border-slate-700">
      <!-- Tabs Header -->
      <div class="border-b border-slate-200 dark:border-slate-700">
        <nav class="flex -mb-px overflow-x-auto" aria-label="Tabs">
          <button class="text-blue-600 dark:text-blue-400 border-b-2 border-blue-500 py-4 px-6 font-medium text-sm whitespace-nowrap" aria-current="page">
            Dashboard
          </button>
          <button class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 border-b-2 border-transparent py-4 px-6 font-medium text-sm whitespace-nowrap">
            Booking History
          </button>
          <button class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 border-b-2 border-transparent py-4 px-6 font-medium text-sm whitespace-nowrap">
            Payment History
          </button>
          <button class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 border-b-2 border-transparent py-4 px-6 font-medium text-sm whitespace-nowrap">
            Profile
          </button>
          <button class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 border-b-2 border-transparent py-4 px-6 font-medium text-sm whitespace-nowrap">
            Login History
          </button>
          <button class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 border-b-2 border-transparent py-4 px-6 font-medium text-sm whitespace-nowrap">
            Settings
          </button>
        </nav>
      </div>
      
      <!-- Tab Content - Dashboard -->
      <div class="p-6">
        <!-- Chart Section -->
        <div class="mb-8">
          <h2 class="text-lg font-medium mb-4">Monthly Booking Overview</h2>
          <div class="h-64 w-full">
            <canvas id="bookingChart"></canvas>
          </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="mb-8">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium">Recent Bookings</h2>
            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all</a>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
              <thead>
                <tr>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">ID</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Service</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amount</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                <tr>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">#BK-2504</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Premium Consultation</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Apr 28, 2025</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Confirmed</span>
                  </td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">$149.00</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">View</a>
                  </td>
                </tr>
                <tr>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">#BK-2496</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Standard Package</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Apr 23, 2025</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Completed</span>
                  </td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">$89.00</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">View</a>
                  </td>
                </tr>
                <tr>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">#BK-2487</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Premium Consultation</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Apr 16, 2025</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Completed</span>
                  </td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">$149.00</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">View</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
        <!-- Recent Payments -->
        <div>
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium">Recent Payments</h2>
            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all</a>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
              <thead>
                <tr>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Invoice</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Method</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amount</th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                <tr>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">#INV-2504</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Apr 28, 2025</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Credit Card</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">$149.00</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Paid</span>
                  </td>
                </tr>
                <tr>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">#INV-2496</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Apr 23, 2025</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">PayPal</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">$89.00</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Paid</span>
                  </td>
                </tr>
                <tr>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">#INV-2487</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Apr 16, 2025</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">Credit Card</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">$149.00</td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Paid</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <?php include "includes/footer.php" ?>

  <script>
    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('bookingChart').getContext('2d');
      const bookingChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
            label: 'Bookings',
            data: [4, 6, 8, 5, 7, 9],
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
          }, {
            label: 'Revenue',
            data: [400, 600, 850, 500, 750, 950],
            backgroundColor: 'rgba(79, 70, 229, 0.2)',
            borderColor: 'rgba(79, 70, 229, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            yAxisID: 'y1'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Bookings'
              },
              grid: {
                display: true,
                color: 'rgba(107, 114, 128, 0.1)'
              }
            },
            y1: {
              beginAtZero: true,
              position: 'right',
              title: {
                display: true,
                text: 'Revenue ($)'
              },
              grid: {
                display: false
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          },
          plugins: {
            legend: {
              position: 'top',
            }
          }
        }
      });
      
      // Apply theme to chart based on dark mode
      function updateChartTheme() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        bookingChart.options.scales.y.grid.color = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(107, 114, 128, 0.1)';
        bookingChart.options.scales.x.grid.color = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(107, 114, 128, 0.1)';
        bookingChart.options.scales.y.ticks.color = isDarkMode ? 'rgba(255, 255, 255, 0.7)' : 'rgba(107, 114, 128, 0.7)';
        bookingChart.options.scales.x.ticks.color = isDarkMode ? 'rgba(255, 255, 255, 0.7)' : 'rgba(107, 114, 128, 0.7)';
        bookingChart.options.scales.y1.ticks.color = isDarkMode ? 'rgba(255, 255, 255, 0.7)' : 'rgba(107, 114, 128, 0.7)';
        bookingChart.options.plugins.legend.labels.color = isDarkMode ? 'rgba(255, 255, 255, 0.9)' : 'rgba(0, 0, 0, 0.9)';
        
        bookingChart.update();
      }
      
      // Update chart on theme toggle
      const darkModeObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.attributeName === 'class') {
            updateChartTheme();
          }
        });
      });
      
      darkModeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
      });
      
      // Initial theme setup
      updateChartTheme();
    });
  </script>
</body>
</html>