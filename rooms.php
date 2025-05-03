<?php
session_start();
require_once 'admin/functions/connection.php';
require_once 'functions/load_tenant_data.php';

$tenantData = null;

if (isset($_SESSION['tenant_id'])) {
    $tenantData = loadTenantData($conn, $_SESSION['tenant_id']);
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <?php include "includes/header.php"; ?>
    <title>Room Listing | MECMEC Boarding House</title>
    <style>
        .room-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(226, 232, 240, 0.5);
        }
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
        }
        .navbar {
            background-color: rgba(30, 0, 113, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        /* Enhanced geometric pattern background */
        .geometric-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.4'%3E%3Cpath d='M0 0h40v40H0V0zm40 40h40v40H40V40zm0-40h2l-2 2V0zm0 4l4-4h2l-6 6V4zm0 4l8-8h2L40 10V8zm0 4L52 0h2L40 14v-2zm0 4L56 0h2L40 18v-2zm0 4L60 0h2L40 22v-2zm0 4L64 0h2L40 26v-2zm0 4L68 0h2L40 30v-2zm0 4L72 0h2L40 34v-2zm0 4L76 0h2L40 38v-2zm0 4L80 0v2L42 40h-2zm4 0L80 4v2L46 40h-2zm4 0L80 8v2L50 40h-2zm4 0l28-28v2L54 40h-2zm4 0l24-24v2L58 40h-2zm4 0l20-20v2L62 40h-2zm4 0l16-16v2L66 40h-2zm4 0l12-12v2L70 40h-2zm4 0l8-8v2l-6 6h-2zm4 0l4-4v2l-2 2h-2z'/%3E%3C/g%3E%3C/svg%3E");
        }
        .dark .geometric-pattern {
            background-color: #0f172a;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%231e293b' fill-opacity='0.4'%3E%3Cpath d='M0 0h40v40H0V0zm40 40h40v40H40V40zm0-40h2l-2 2V0zm0 4l4-4h2l-6 6V4zm0 4l8-8h2L40 10V8zm0 4L52 0h2L40 14v-2zm0 4L56 0h2L40 18v-2zm0 4L60 0h2L40 22v-2zm0 4L64 0h2L40 26v-2zm0 4L68 0h2L40 30v-2zm0 4L72 0h2L40 34v-2zm0 4L76 0h2L40 38v-2zm0 4L80 0v2L42 40h-2zm4 0L80 4v2L46 40h-2zm4 0L80 8v2L50 40h-2zm4 0l28-28v2L54 40h-2zm4 0l24-24v2L58 40h-2zm4 0l20-20v2L62 40h-2zm4 0l16-16v2L66 40h-2zm4 0l12-12v2L70 40h-2zm4 0l8-8v2l-6 6h-2zm4 0l4-4v2l-2 2h-2z'/%3E%3C/g%3E%3C/svg%3E");
        }
        /* Parallax effect for section background */
        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-all duration-300">

<?php include "includes/topnav.php"; ?>

<!-- Room Listing Section -->
<section class="geometric-pattern py-16 md:py-24 relative">
    <div class="absolute inset-0 bg-gradient-to-b from-white/80 to-white/20 dark:from-slate-900/90 dark:to-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="container mx-auto px-4 relative mt-8">  
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="inline-flex items-center justify-center mb-4">
                <div class="w-3 h-3 bg-accent rounded-full mr-2"></div>
                <div class="w-12 h-0.5 bg-accent mr-2"></div>
                <span class="text-accent text-sm font-medium tracking-wider">ACCOMMODATIONS</span>
                <div class="w-12 h-0.5 bg-accent ml-2"></div>
                <div class="w-3 h-3 bg-accent rounded-full ml-2"></div>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-800 dark:text-slate-100">
                Find Your Perfect Room
            </h2>
         
        </div>

        <!-- Search and Filter Bar - -->
        <div class="max-w-5xl mx-auto bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-12" data-aos="fade-up">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search Input (Wider) -->
                <div class="md:col-span-8">
                    <label for="search" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Find your perfect room</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-500 dark:text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" 
                                id="search" 
                                placeholder="Search rooms..." 
                                class="pl-10 w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/50 focus:border-primary/30 dark:bg-slate-800/80 transition-all duration-200 shadow-sm hover:shadow-md focus:shadow-lg placeholder-slate-400 dark:placeholder-slate-500">
                        </div>
                </div>
                
                <!-- Room Type Filter (Narrower) -->
                <div class="md:col-span-2">
                    <label for="room-type" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Room Type</label>
                    <div class="relative">
                        <select id="room-type" class="appearance-none w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 pr-10 transition-all shadow-sm hover:shadow-md">
                            <option value="">All Types</option>
                            <?php
                            $typeQuery = "SELECT * FROM room_types";
                            $typeResult = $conn->query($typeQuery);
                            while ($type = $typeResult->fetch_assoc()) {
                                echo '<option value="'.$type['room_type_id'].'">'.$type['type_name'].'</option>';
                            }
                            ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Sort By (Narrower) -->
                <div class="md:col-span-2">
                    <label for="sort-by" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Sort By</label>
                    <div class="relative">
                        <select id="sort-by" class="appearance-none w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 pr-10 transition-all shadow-sm hover:shadow-md">
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="room_number_asc">Room: A to Z</option>
                            <option value="room_number_desc">Room: Z to A</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Grid - 3x3 Layout -->
        <div id="room-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Rooms will be loaded here via AJAX -->
            
            <!-- Example room card for reference (will be replaced by AJAX) -->
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg overflow-hidden room-card" data-aos="fade-up">
                <div class="h-64 overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85" alt="Single Room" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    <div class="absolute top-4 right-4 bg-accent text-white text-sm font-bold px-3 py-1 rounded-full shadow-md">
                        Available
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Room 101</h3>
                        <span class="text-accent font-bold">₱5,000/mo</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 mb-4">
                        Perfect for students or professionals who value privacy and independence. Cozy yet functional space.
                    </p>
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Single Bed</span>
                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">12 m²</span>
                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Private Bathroom</span>
                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Study Desk</span>
                        </div>
                        <a href="#" class="block text-center bg-primary hover:bg-primary-dark text-white py-2 rounded-md transition duration-300 transform hover:-translate-y-1">Book Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary"></div>
            <p class="mt-2 text-slate-500 dark:text-slate-400">Loading rooms...</p>
        </div>

        <!-- No Results Message (hidden by default) -->
        <div id="no-results" class="hidden text-center py-12">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-slate-700 dark:text-slate-300">No rooms found</h3>
            <p class="mt-1 text-slate-500 dark:text-slate-400">Try adjusting your search or filter to find what you're looking for.</p>
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-center mt-12">
            <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm font-medium text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 bg-primary text-sm font-medium text-white">1</a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">2</a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">3</a>
                <span class="relative inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300">...</span>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">8</a>
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm font-medium text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </nav>
        </div>
    </div>
</section>

<?php include "includes/footer.php"; ?>

<!-- JavaScript for Live Filtering -->
<script>
$(document).ready(function() {
    // Debounce function to limit how often a function is called
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }

    // Function to load rooms based on current filters
    const loadRooms = debounce(function() {
        const search = $('#search').val();
        const roomType = $('#room-type').val();
        const sortBy = $('#sort-by').val();
        
        // Show loading indicator
        $('#room-container').html('');
        $('#loading').removeClass('hidden');
        $('#no-results').addClass('hidden');
        
        $.ajax({
            url: 'functions/get_rooms.php',
            method: 'GET',
            data: {
                search: search,
                room_type: roomType,
                sort_by: sortBy,
                page: 1 // Default to first page
            },
            success: function(response) {
                $('#loading').addClass('hidden');
                
                if (response.length > 0) {
                    $('#room-container').html(response);
                    $('#no-results').addClass('hidden');
                    
                    // Initialize any animations or hover effects for the new content
                    AOS.refresh();
                    
                    // Add hover effects for room images
                    $('.room-card').each(function() {
                        $(this).on('mouseenter', function() {
                            $(this).find('img').addClass('scale-110');
                        }).on('mouseleave', function() {
                            $(this).find('img').removeClass('scale-110');
                        });
                    });
                } else {
                    $('#room-container').html('');
                    $('#no-results').removeClass('hidden');
                }
            },
            error: function() {
                $('#loading').addClass('hidden');
                $('#no-results').removeClass('hidden');
            }
        });
    }, 300);

    // Initial load
    loadRooms();

    // Event listeners for live filtering
    $('#search, #room-type, #sort-by').on('input change', function() {
        loadRooms();
    });
    
    // Handle pagination clicks
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            // Update loadRooms function to handle pagination
            // You'll need to modify your PHP function to accept a page parameter
            loadRooms(page);
        }
    });
});
</script>
</body>
</html>