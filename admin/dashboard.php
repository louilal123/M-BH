<?php
include 'functions/connection.php';
include 'includes/session.php';

$tenantCount = $conn->query("SELECT COUNT(*) FROM tenants")->fetch_row()[0];
$roomCount = $conn->query("SELECT COUNT(*) FROM rooms")->fetch_row()[0];
$occupiedCount = $conn->query("SELECT COUNT(*) FROM bookings WHERE status IN ('confirmed','active')")->fetch_row()[0];
$pendingBookingsCount = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetch_row()[0];

$currentMonth = date('Y-m');
$revenueQuery = $conn->prepare("SELECT SUM(amount_paid) FROM payments WHERE DATE_FORMAT(payment_date, '%Y-%m') = ?");
$revenueQuery->bind_param("s", $currentMonth);
$revenueQuery->execute();
$monthlyRevenue = $revenueQuery->get_result()->fetch_row()[0] ?? 0;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding House Management</title>
   <?php include "includes-new/header.php";?>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <?php include "includes-new/sidebar.php" ?>
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include "includes-new/topnav.php" ?>
            <main class="flex-1 overflow-y-auto p-4 bg-gray-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <i class="fas fa-download mr-2"></i>
                            <span class="hidden md:inline">Export</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-400 text-sm">Monthly Revenue</p>
                                <h3 class="text-3xl font-semibold text-indigo-900 dark:text-white">₱<?= number_format($monthlyRevenue, 2) ?></h3>
                                <p class="text-indigo-600 text-sm mt-1">
                                    <i class="fas fa-money-bill-wave mr-1"></i> Collections
                                </p>
                            </div>
                            <div class="bg-indigo-100 p-4 rounded-xl">
                                <i class="fas fa-chart-line text-indigo-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                  <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-400 text-sm">Pending Bookings</p>
                                <h3 class="text-3xl font-semibold text-indigo-900 dark:text-white"><?= $pendingBookingsCount ?></h3>
                                <p class="text-indigo-600 text-sm mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i> Awaiting Confirmation
                                </p>
                            </div>
                            <div class="bg-indigo-100 p-4 rounded-xl">
                                <i class="fas fa-hourglass-half text-indigo-500 text-xl"></i>
                            </div>
                        </div>
                    </div>


                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-400 text-sm">Total Tenants</p>
                                <h3 class="text-3xl font-semibold text-indigo-900 dark:text-white"><?= $tenantCount ?></h3>
                                <p class="text-indigo-600 text-sm mt-1">
                                    <i class="fas fa-users mr-1"></i> Registered
                                </p>
                            </div>
                            <div class="bg-indigo-100 p-4 rounded-xl">
                                <i class="fas fa-user-friends text-indigo-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-400 text-sm">Room Occupancy</p>
                                <h3 class="text-3xl font-semibold text-indigo-900 dark:text-white"><?= $occupiedCount ?>/<?= $roomCount ?></h3>
                                <p class="text-indigo-600 text-sm mt-1"><?= round(($occupiedCount / $roomCount) * 100) ?>% occupancy</p>
                            </div>
                            <div class="bg-indigo-100 p-4 rounded-xl">
                                <i class="fas fa-door-open text-indigo-500 text-xl"></i>
                            </div>
                        </div>
                    </div>


                  
                </div>

              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-5 lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700">Revenue Trends</h3>
                        <div class="flex space-x-2">
                            <button id="monthlyBtn" class="px-3 py-1 text-xs bg-indigo-100 text-indigo-600 rounded-md hover:bg-indigo-200">Monthly</button>
                            <button id="quarterlyBtn" class="px-3 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded-md">Quarterly</button>
                            <button id="yearlyBtn" class="px-3 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded-md">Yearly</button>
                        </div>
                    </div>
                    <div class="relative w-full h-[300px]"> <!-- Container with fixed height -->
                        <canvas id="revenue-chart" class="absolute top-0 left-0 w-full h-full"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-5">
                    <h3 class="font-bold text-gray-700 mb-4">Room Occupancy by Type</h3>
                    <div class="relative w-full h-[300px]"> <!-- Container with fixed height -->
                        <canvas id="occupancy-chart" class="absolute top-0 left-0 w-full h-full"></canvas>
                    </div>
                </div>
            </div>

            </main>
        </div>
    </div>

   <?php include "includes-new/footer.php" ?>
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function () {
    // === Revenue Chart ===
    try {
        const revenueRes = await fetch('functions/revenue-chart.php');
        const revenueData = await revenueRes.json();

        const revenueCtx = document.getElementById('revenue-chart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: revenueData.months,
                datasets: [{
                    label: 'Monthly Revenue (₱)',
                    data: revenueData.revenue,
                    borderColor: 'rgba(79, 70, 229, 1)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `₱${context.parsed.y.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    } catch (err) {
        console.error('Error loading revenue chart:', err);
    }

    // === Occupancy Chart ===
    try {
        const occupancyRes = await fetch('functions/roccupancy-chart.php');
        const occupancyData = await occupancyRes.json();

        const labels = occupancyData.occupancy.map(item => item.label);
        const values = occupancyData.occupancy.map(item => item.value);
        const colors = [
            'rgba(79, 70, 229, 0.7)',
            'rgba(99, 102, 241, 0.7)',
            'rgba(129, 140, 248, 0.7)',
            'rgba(165, 180, 252, 0.7)',
            'rgba(199, 210, 254, 0.7)'
        ];

        const occupancyCtx = document.getElementById('occupancy-chart').getContext('2d');
        new Chart(occupancyCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.parsed;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    } catch (err) {
        console.error('Error loading occupancy chart:', err);
    }
});
</script>


</body>
</html>