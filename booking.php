<?php
session_start();
require_once 'admin/functions/connection.php';
require_once 'functions/load_tenant_data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['room_id'])) {
    header("Location: rooms.php");
    exit;
}

$roomId = $_POST['room_id'];
// Get room details
$roomQuery = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$roomQuery->bind_param("i", $roomId);
$roomQuery->execute();
$room = $roomQuery->get_result()->fetch_assoc();

if (!$room) {
    header("Location: rooms.php?error=room_not_found");
    exit;
}

$tenantData = null;
if (isset($_SESSION['tenant_id'])) {
    $tenantQuery = $conn->prepare("SELECT tenant_id, name, email, phone FROM tenants WHERE tenant_id = ?");
    $tenantQuery->bind_param("i", $_SESSION['tenant_id']);
    $tenantQuery->execute();
    $tenantResult = $tenantQuery->get_result();
    $tenantData = $tenantResult->fetch_assoc();
}

$imagesQuery = $conn->prepare("SELECT image_path FROM room_images WHERE room_id = ?");
$imagesQuery->bind_param("i", $roomId);
$imagesQuery->execute();
$imagesResult = $imagesQuery->get_result();
$roomImages = $imagesResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <?php include "includes/header.php"; ?>
    <title>Book Room <?php echo htmlspecialchars($room['room_number']); ?> | MECMEC Boarding House</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="booking.css">
<style>
    .navbar {
    background-color:#0f172a;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-colors duration-300">
    <?php include "includes/topnav.php"; ?>

    <div class="pt-24 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Title -->
            <div class="text-center mb-10 animate-fade-in">
                <h1 class="text-3xl md:text-4xl font-bold mb-2 mt-8">Book Your Stay</h1>
                <p class="text-slate-500 dark:text-slate-400">Room <?php echo htmlspecialchars($room['room_number']); ?> | MECMEC Boarding House</p>
            </div>
            
            <!-- Progress Steps -->
            <div class="relative mb-12">
                <div class="flex justify-between items-center">
                    <div class="progress-step active text-center z-10 w-1/3">
                        <div class="step-number bg-blue-600 text-white mx-auto mb-2">1</div>
                        <span class="text-sm font-medium">Booking Details</span>
                    </div>
                    <div class="progress-step text-center z-10 w-1/3">
                        <div class="step-number bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 mx-auto mb-2">2</div>
                        <span class="text-sm font-medium">Payment</span>
                    </div>
                    <div class="progress-step text-center z-10 w-1/3">
                        <div class="step-number bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 mx-auto mb-2">3</div>
                        <span class="text-sm font-medium">Success</span>
                    </div>
                </div>
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 -z-10">
                    <div class="progress-bar" style="width: 33%"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Form -->
                <div class="lg:col-span-2 animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="card p-6 md:p-8 mb-8">
                        <div class="flex items-center mb-8">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold ml-4">Booking Information</h2>
                        </div>
                        
                        <?php if ($tenantData): ?>
                            <div class="mb-8 p-6 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800/50 flex items-start">
                                <i class="fas fa-check-circle text-green-500 text-xl mt-1 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-lg mb-2">Booking as registered tenant</h4>
                                    <p class="font-bold text-xl mb-1"><?php echo htmlspecialchars($tenantData['name']); ?></p>
                                    <p class="text-slate-500 dark:text-slate-400"><?php echo htmlspecialchars($tenantData['email']); ?></p>
                                </div>
                            </div>
                            <?php else: ?>
                                <div class="mb-8 p-6 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800/50 flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mt-1 mr-4"></i>
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2">You're not logged in</h4>
                                        <p>Please 
                                            <a href="#" onclick="openModal('login-modal')" class="text-primary hover:underline font-medium">login</a> 
                                            or 
                                            <a href="#" onclick="openModal('signup-modal')" class="text-primary hover:underline font-medium">register</a> 
                                            to book a room.
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                           
                        <form id="bookingForm" method="POST" action="booking_payment.php" class="space-y-6">
                        <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
                        
                            <!-- Check-in Date -->
                            <div class="form-group">
                                <label for="check_in_date" class="form-label flex items-center">
                                    <i class="fas fa-calendar-day mr-2 text-primary"></i>
                                    Check-in Date
                                </label>
                                <input type="date" id="check_in_date" name="check_in_date" 
                                    min="<?php echo date('Y-m-d'); ?>" 
                                    class="form-control" required>
                                <div class="invalid-feedback">Please select a valid check-in date</div>
                            </div>
                           
                            <!-- Stay Duration -->
                            <div class="form-group">
                                <label for="stay_duration" class="form-label flex items-center">
                                    <i class="fas fa-clock mr-2 text-primary"></i>
                                    Stay Duration
                                </label>
                                <select id="stay_duration" name="stay_duration" class="form-control">
                                    <option value="1">1 Month</option>
                                    <option value="2">2 Months</option>
                                    <option value="3">3 Months</option>
                                    <option value="6" selected>6 Months</option>
                                    <option value="12">12 Months</option>
                                </select>
                            </div>
                           
                            <!-- Calculated Check-out Date (display only) -->
                            <div class="p-6 bg-slate-100 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 mb-6">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-calendar-week text-lg text-primary mr-3"></i>
                                    <p class="font-semibold text-lg">Your stay period</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="p-4 bg-white dark:bg-slate-700 rounded-lg shadow-sm">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Check-in</p>
                                        <p id="check_in_display" class="font-bold text-primary text-xl">-</p>
                                    </div>
                                    <div class="p-4 bg-white dark:bg-slate-700 rounded-lg shadow-sm">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Check-out</p>
                                        <p id="check_out_display" class="font-bold text-primary text-xl">-</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden check_out_date field that gets submitted -->
                            <input type="hidden" id="check_out_date" name="check_out_date">
                            
                            <!-- Special Requests -->
                            <div class="form-group">
                                <label for="special_requests" class="form-label flex items-center">
                                    <i class="fas fa-comment-dots mr-2 text-primary"></i>
                                    Special Requests
                                </label>
                                <textarea id="special_requests" name="special_requests" rows="3" 
                                        class="form-control"
                                        placeholder="Any special requirements or notes (e.g., preferred floor, accessibility needs)..."></textarea>
                            </div>
                            
                            <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <a href="room_details.php?room_id=<?php echo $roomId; ?>" class="text-primary hover:underline font-medium flex items-center transition-all hover:translate-x-1">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to room details
                                </a>
                                <button type="submit" class="btn-primary w-full md:w-auto px-8" <?php echo !$tenantData ? 'disabled' : ''; ?>>
                                    <i class="fas fa-credit-card mr-2"></i> Continue to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Room Summary -->
                <div class="lg:col-span-1 animate-fade-in" style="animation-delay: 0.4s;">
                    <div class="card p-6 sticky-summary">
                        <h2 class="text-xl font-bold mb-6 flex items-center">
                            <i class="fas fa-home mr-3 text-primary"></i>
                            Room Summary
                        </h2>
                        
                        <!-- Room Gallery -->
                        <div class="room-gallery mb-6 overflow-hidden rounded-xl">
                            <img src="uploads/rooms/<?php echo htmlspecialchars($room['photo']); ?>" 
                                 alt="Room <?php echo htmlspecialchars($room['room_number']); ?>" 
                                 class="main-image hover:opacity-90 transition-opacity">
                            <div class="thumbnails grid grid-cols-3 gap-2">
                                <?php foreach ($roomImages as $index => $image): ?>
                                    <?php if ($index < 3): ?>
                                        <img src="uploads/rooms/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                             alt="Room <?php echo htmlspecialchars($room['room_number']); ?>" 
                                             class="thumbnail hover:opacity-80 transition-opacity">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-2">Room <?php echo htmlspecialchars($room['room_number']); ?></h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4"><?php echo htmlspecialchars($room['description']); ?></p>
                        
                        <div class="mb-6">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                <i class="fas fa-tag mr-2"></i>
                                <?php echo htmlspecialchars($room['room_type']); ?>
                            </span>
                        </div>
                        
                        <div class="divider"></div>
                        
                        <!-- Pricing Summary -->
                        <div class="space-y-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400 flex items-center">
                                    <i class="fas fa-calendar mr-2"></i> Monthly Rate
                                </span>
                                <span class="font-medium">₱<?php echo number_format($room['price'], 2); ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500 dark:text-slate-400 flex items-center">
                                    <i class="fas fa-clock mr-2"></i> Duration
                                </span>
                                <span id="duration-display" class="font-medium">6 Months</span>
                            </div>
                            
                            <div class="divider"></div>
                            
                            <div class="flex justify-between items-center pt-2">
                                <span class="flex items-center font-semibold">
                                    <i class="fas fa-file-invoice-dollar mr-2"></i> Total Due:
                                </span>
                                <span id="total-amount" class="price-highlight">₱<?php echo number_format($room['price'] * 6, 2); ?></span>
                            </div>
                        </div>
                        
                        <!-- Help Section -->
                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/30">
                            <div class="flex items-center">
                                <i class="fas fa-headset text-blue-500 text-lg mr-3"></i>
                                <div>
                                    <h4 class="font-medium mb-1">Need assistance?</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        Contact our support team at 
                                        <a href="tel:+1234567890" class="text-primary hover:underline">(123) 456-7890</a> or 
                                        <a href="mailto:info@mecmec.com" class="text-primary hover:underline">info@mecmec.com</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="mt-10 animate-fade-in" style="animation-delay: 0.6s;">
                <div class="p-6 bg-white dark:bg-slate-800 rounded-xl shadow-md border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-info-circle text-primary text-xl mr-3"></i>
                        <h3 class="text-xl font-bold">Booking Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium mb-1">Free cancellation</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Cancel up to 24 hours before check-in</p>
                            </div>
                        </div>
                        <div class="flex">
                            <i class="fas fa-shield-alt text-blue-500 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium mb-1">Secure payment</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Your data is protected with SSL encryption</p>
                            </div>
                        </div>
                        <div class="flex">
                            <i class="fas fa-phone-alt text-purple-500 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium mb-1">24/7 Support</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Our staff is always available to help</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const bookingForm = document.getElementById('bookingForm');
    const checkInDate = document.getElementById('check_in_date');
    const stayDuration = document.getElementById('stay_duration');
    const checkInDisplay = document.getElementById('check_in_display');
    const checkOutDisplay = document.getElementById('check_out_display');
    const checkOutDate = document.getElementById('check_out_date');
    const durationDisplay = document.getElementById('duration-display');
    const totalAmountDisplay = document.getElementById('total-amount');
    const roomPrice = <?php echo $room['price']; ?>;
    
    // Set default check-in date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    checkInDate.valueAsDate = tomorrow;
    
    // Calculate booking details
    function calculateBookingDetails() {
        if (!checkInDate.value) return;
        
        // Get selected duration in months
        const months = parseInt(stayDuration.value);
        
        // Calculate dates
        const startDate = new Date(checkInDate.value);
        const endDate = new Date(startDate);
        endDate.setMonth(startDate.getMonth() + months);
        
        // Format dates for display
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        checkInDisplay.textContent = startDate.toLocaleDateString('en-US', options);
        checkOutDisplay.textContent = endDate.toLocaleDateString('en-US', options);
        
        // Set hidden field values
        checkOutDate.value = endDate.toISOString().split('T')[0];
        
        // Update duration and total displays
        durationDisplay.textContent = `${months} Month${months !== 1 ? 's' : ''}`;
        totalAmountDisplay.textContent = `₱${(roomPrice * months).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`;
    }
    
    // Initialize calculations
    calculateBookingDetails();
    
    // Event listeners for form changes
    [checkInDate, stayDuration].forEach(el => {
        el.addEventListener('change', calculateBookingDetails);
    });
    
    // Form validation
    function validateForm() {
        let isValid = true;
        
        // Validate check-in date
        if (!checkInDate.value) {
            checkInDate.classList.add('is-invalid');
            isValid = false;
        } else {
            checkInDate.classList.remove('is-invalid');
        }
        
        return isValid;
    }

    // Form submission handler - SIMPLIFIED PHP VERSION
bookingForm.addEventListener('submit', function(e) {
    // Validate form
    if (!validateForm()) {
        e.preventDefault();
        return;
    }
    
    // Validate check-out date exists
    if (!checkOutDate.value) {
        alert('Please select valid dates before submitting');
        e.preventDefault();
        return;
    }
    
    // Calculate and set the total amount
    const totalAmountInput = document.createElement('input');
    totalAmountInput.type = 'hidden';
    totalAmountInput.name = 'total_amount';
    totalAmountInput.value = roomPrice * parseInt(stayDuration.value);
    bookingForm.appendChild(totalAmountInput);
});
    // Enhancement: Add hover effects for room thumbnails
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.querySelector('.main-image');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Store current main image
            const mainSrc = mainImage.src;
            // Set thumbnail as main image
            mainImage.src = this.src;
            // Set main image as this thumbnail
            this.src = mainSrc;
            
            // Add a little animation
            mainImage.classList.add('opacity-80');
            setTimeout(() => {
                mainImage.classList.remove('opacity-80');
            }, 300);
        });
        
        // Add hover effect
        thumb.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        thumb.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
<?php include "includes/chatbot.php" ?>
</body>
</html>