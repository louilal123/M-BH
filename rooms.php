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
    .navbar {
    background-color:#0f172a;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .room-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.5);
    }
    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
      
    }
  

    
    /* Modern semi-circle design with double circle effect */
    .section-decoration {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
    }
    
    .semi-circle {
        position: absolute;
        width: 1200px;
        height: 600px;
        border-radius: 50%;
        background: transparent;
        clip-path: circle(50% at 50% 0);
        opacity: 0.15;
        filter: blur(40px);
        left: 50%;
        transform: translateX(-50%);
        top: -200px;
    }
    
    .semi-circle::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 120px solid;
        border-color: rgba(59, 130, 246, 0.1);
        top: 0;
        left: 0;
        box-sizing: border-box;
    }
    
    .semi-circle::after {
        content: '';
        position: absolute;
        width: calc(100% - 240px);
        height: calc(100% - 240px);
        border-radius: 50%;
        border: 80px solid;
        border-color: rgba(236, 72, 153, 0.1);
        top: 120px;
        left: 120px;
        box-sizing: border-box;
    }
    
    .dark .semi-circle {
        opacity: 0.25;
    }
    
    .dark .semi-circle::before {
        border-color: rgba(99, 102, 241, 0.15);
    }
    
    .dark .semi-circle::after {
        border-color: rgba(236, 72, 153, 0.15);
    }

    
    </style>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-all duration-300">

<?php include "includes/topnav.php"; ?>

<!-- Room Listing Section -->
<section class="geometric-pattern py-16 md:py-24 relative min-h-screen mb-30 overflow-hidden">
    <!-- Modern semi-circle decoration -->
    <div class="section-decoration">
        <div class="semi-circle"></div>
    </div>
    
    <div class="absolute inset-0 bg-gradient-to-b from-white/80 to-white/20 dark:from-slate-900/90 dark:to-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="container mx-auto px-4 relative mt-8">  
        <div class="text-center mb-12" >
            <div class="inline-flex items-center justify-center mb-4">
               
            </div>
            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-800 dark:text-slate-100">
                Find Your Perfect Room
            </h2>
         
        </div>

        <!-- Search and Filter Bar - -->
        <div class="max-w-5xl mx-auto bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-12" >
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
                            // Get distinct room types from the rooms table
                            $typeQuery = "SELECT DISTINCT room_type FROM rooms WHERE room_type IS NOT NULL AND room_type != ''";
                            $typeResult = $conn->query($typeQuery);
                            while ($type = $typeResult->fetch_assoc()) {
                                echo '<option value="'.htmlspecialchars($type['room_type']).'">'.htmlspecialchars($type['room_type']).'</option>';
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
        <div id="room-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative z-10">
            <!-- Rooms will be loaded here via AJAX -->
           
        </div>

      <!-- Loading Indicator -->
        <div id="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
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
        
      
    </div>
</section>

  <!-- Footer -->
  <footer class="bg-slate-900 text-slate-300 pt-100">
    <div class="container mx-auto px-4 py-16">
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12">
        <div>
        <a href="#" class="flex items-center gap-2 mb-6">
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent 
              group-hover:from-blue-500 group-hover:to-blue-300 dark:  transition-all duration-500"> MECMEC</span>
            <span class="text-orange-400 dark:text-orange-400 text-lg font-semibold mt-1">BH</span>
          </a>
          <p class="mb-6 text-slate-400">
            Providing quality accommodation for students and young professionals since 2015.
          </p>
          <div class="flex space-x-4">
            <a href="#" class="text-slate-400 hover:text-primary transition">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-primary transition">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-primary transition">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-primary transition">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
        </div>
        
        <div>
          <h3 class="text-white text-lg font-bold mb-6">Quick Links</h3>
          <ul class="space-y-3">
            <li><a href="#home" class="text-slate-400 hover:text-primary transition">Home</a></li>
            <li><a href="#rooms" class="text-slate-400 hover:text-primary transition">Rooms</a></li>
            <li><a href="#amenities" class="text-slate-400 hover:text-primary transition">Amenities</a></li>
            <li><a href="#gallery" class="text-slate-400 hover:text-primary transition">Gallery</a></li>
            <li><a href="#testimonials" class="text-slate-400 hover:text-primary transition">Testimonials</a></li>
            <li><a href="#contact" class="text-slate-400 hover:text-primary transition">Contact</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-white text-lg font-bold mb-6">Contact Info</h3>
          <ul class="space-y-4">
            <li class="flex items-start">
              <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary"></i>
              <span>123 University Avenue<br>Metro City, 12345</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-phone mr-3 text-primary"></i>
              <span>+123 456 7890</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-envelope mr-3 text-primary"></i>
              <span>info@mecmecbh.com</span>
            </li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-white text-lg font-bold mb-6">Newsletter</h3>
          <p class="mb-4 text-slate-400">
            Subscribe to our newsletter to get updates on our latest offers.
          </p>
          <form class="flex flex-col space-y-3">
            <input type="email" placeholder="Your email" class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-white">
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 rounded-lg transition">
              Subscribe
            </button>
          </form>
        </div>
      </div>
      
      <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
        <p class="text-slate-400 mb-4 md:mb-0">
          &copy; 2025 MECMEC Boarding House. All rights reserved.
        </p>
        <div class="flex space-x-6">
          <a href="#" class="text-slate-400 hover:text-primary transition">Terms of Service</a>
          <a href="#" class="text-slate-400 hover:text-primary transition">Privacy Policy</a>
          <a href="#" class="text-slate-400 hover:text-primary transition">Cookie Policy</a>
        </div>
      </div>
    </div>
  </footer>


<!-- MODALS  -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Book Room</h3>
        <form id="bookingForm">
            <input type="hidden" id="bookingRoomId" name="room_id">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Check-in Date</label>
                <input type="date" id="checkInDate" name="check_in" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Check-out Date</label>
                <input type="date" id="checkOutDate" name="check_out" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Special Requests</label>
                <textarea name="special_requests" class="w-full p-2 border rounded"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeBookingModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

  <script>
$(document).ready(function() {
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

    const loadRooms = debounce(function() {
        const search = $('#search').val();
        const roomType = $('#room-type').val();
        const sortBy = $('#sort-by').val();

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
                page: 1
            },
            success: function(response) {
                $('#loading').addClass('hidden');

                if (response.length > 0) {
                    $('#room-container').html(response);
                    $('#no-results').addClass('hidden');
                    AOS.refresh();

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

    loadRooms();

    $('#search, #room-type, #sort-by').on('input change', function() {
        loadRooms();
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            loadRooms(page);
        }
    });
});
</script>

<?php include "includes/footer.php" ?>
<?php include "includes/chatbot.php" ?>
</body>
</html>