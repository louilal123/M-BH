<?php
session_start();
require_once 'admin/functions/connection.php';
require_once 'functions/load_tenant_data.php';

if (!isset($_GET['room_id'])) {
    header("Location: rooms.php");
    exit;
}

$roomId = $_GET['room_id'];

// Get room details
$roomQuery = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$roomQuery->bind_param("i", $roomId);
$roomQuery->execute();
$room = $roomQuery->get_result()->fetch_assoc();

if (!$room) {
    header("Location: rooms.php?error=room_not_found");
    exit;
}

// Get tenant data if logged in
$tenantData = null;
if (isset($_SESSION['tenant_id'])) {
    $tenantQuery = $conn->prepare("SELECT tenant_id, name, email, phone FROM tenants WHERE tenant_id = ?");
    $tenantQuery->bind_param("i", $_SESSION['tenant_id']);
    $tenantQuery->execute();
    $tenantResult = $tenantQuery->get_result();
    $tenantData = $tenantResult->fetch_assoc();
}

// Get room images for gallery
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
    <style>
        /* Minimal custom CSS - most styling handled by Tailwind */
        .payment-method {
            transition: all 0.2s ease;
        }
        .payment-method.selected {
            box-shadow: 0 0 0 2px #3b82f6;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-colors duration-300">
    <?php include "includes/topnav.php"; ?>

    <br> <br><br><br>
    <section class="py-12 px-4 pt-200">
        <div class="max-w-6xl mx-auto">
           <!-- Progress Steps -->
           <div class="relative mb-12">
                <div class="flex justify-between">
                    <div class="text-center">
                    <div class="w-10 h-10 bg-blue-600 dark:bg-blue-700 text-gray-600 dark:text-gray-300 rounded-full flex items-center justify-center mx-auto mb-2">
                            1
                        </div>
                        <span class="text-sm font-medium">Booking Details</span>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-gray-600 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            2
                        </div>
                        <span class="text-sm font-medium">Payment</span>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full flex items-center justify-center mx-auto mb-2">
                            3
                        </div>
                        <span class="text-sm font-medium">Confirmation</span>
                    </div>
                </div>
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 -z-10">
                    <div class="h-full bg-blue-600 transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Form -->
                <div class="lg:col-span-2">
                    <div class="card p-8 mb-8">
                        <div class="flex items-center mb-6">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold ml-4">Booking Information</h2>
                        </div>
                        
                        <?php if ($tenantData): ?>
                            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800/50 flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-medium mb-1">Booking as registered tenant</h4>
                                    <p class="font-semibold"><?php echo htmlspecialchars($tenantData['name']); ?></p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400"><?php echo htmlspecialchars($tenantData['email']); ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800/50 flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-medium mb-2">You're not logged in</h4>
                                    <p class="text-sm">Please <a href="login.php?redirect=booking.php?room_id=<?php echo $roomId; ?>" class="text-primary hover:underline font-medium">login</a> or <a href="register.php" class="text-primary hover:underline font-medium">register</a> to book a room.</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form id="bookingForm" method="POST">
                            <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
                            
                            <!-- Check-in Date -->
                            <div class="mb-6">
                                <label for="check_in_date" class="block text-sm font-medium mb-2 flex items-center">
                                    <i class="fas fa-calendar-day mr-2 text-primary"></i>
                                    Check-in Date
                                </label>
                                <input type="date" id="check_in_date" name="check_in_date" 
                                    min="<?php echo date('Y-m-d'); ?>" 
                                    class="form-control" required>
                            </div>
                            
                            <!-- Stay Duration -->
                            <div class="mb-6">
                                <label for="stay_duration" class="block text-sm font-medium mb-2 flex items-center">
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
                            <div class="mb-6 p-4 bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-calendar-week mr-2 text-primary"></i>
                                    <p class="font-medium">Your stay period</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Check-in</p>
                                        <p id="check_in_display" class="font-bold text-primary">-</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Check-out</p>
                                        <p id="check_out_display" class="font-bold text-primary">-</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden check_out_date field that gets submitted -->
                            <input type="hidden" id="check_out_date" name="check_out_date">
                            
                            <!-- Special Requests -->
                            <div class="mb-8">
                                <label for="special_requests" class="block text-sm font-medium mb-2 flex items-center">
                                    <i class="fas fa-comment-dots mr-2 text-primary"></i>
                                    Special Requests
                                </label>
                                <textarea id="special_requests" name="special_requests" rows="3" 
                                        class="form-control"
                                        placeholder="Any special requirements or notes (e.g., preferred floor, accessibility needs)..."></textarea>
                            </div>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-slate-200 dark:border-slate-700">
                                <a href="room_details.php?room_id=<?php echo $roomId; ?>" class="text-primary hover:underline font-medium flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to room details
                                </a>
                                <button type="submit" class="btn-primary" <?php echo !$tenantData ? 'disabled' : ''; ?>>
                                    <i class="fas fa-credit-card mr-2"></i> Continue to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Room Summary -->
                <div class="lg:col-span-1">
                    <div class="card p-6 sticky-summary">
                        <h2 class="text-xl font-bold mb-6 flex items-center">
                            <i class="fas fa-home mr-3 text-primary"></i>
                            Room Summary
                        </h2>
                        
                        <!-- Room Gallery -->
                        <div class="room-gallery mb-6">
                            <img src="uploads/rooms/<?php echo htmlspecialchars($room['photo']); ?>" 
                                 alt="Room <?php echo htmlspecialchars($room['room_number']); ?>" 
                                 class="main-image">
                            <?php foreach ($roomImages as $index => $image): ?>
                                <?php if ($index < 3): ?>
                                    <img src="uploads/rooms/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                         alt="Room <?php echo htmlspecialchars($room['room_number']); ?>" 
                                         class="thumbnail">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-2">Room <?php echo htmlspecialchars($room['room_number']); ?></h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-4"><?php echo htmlspecialchars($room['description']); ?></p>
                        
                        <div class="mb-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                <i class="fas fa-tag mr-2"></i>
                                <?php echo htmlspecialchars($room['room_type']); ?>
                            </span>
                        </div>
                        
                        <div class="divider"></div>
                        
                        <!-- In your booking form (booking.php) -->
            <div class="space-y-4">
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
                
                <div class="flex justify-between items-center font-bold text-lg pt-2">
                    <span class="flex items-center">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Total Due Now
                    </span>
                    <span id="total-amount" class="price-highlight">₱<?php echo number_format($room['price'] * 6, 2); ?></span>
                </div>
            </div>
                       
                    </div>
                </div>
            </div>
            
            <div>
            <div class="mt-6 p-4 bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                            <div class="flex mx-auto items-center justify-center mb-2 mt-8">
                                <i class="fas fa-info-circle text-primary mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-medium mb-1">Need help?</h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Contact us at <a href="tel:+1234567890" class="text-primary hover:underline">(123) 456-7890</a> or <a href="mailto:info@mecmec.com" class="text-primary hover:underline">info@mecmec.com</a></p>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </section>

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
    
    // Form submission handler
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate check-out date exists
        if (!checkOutDate.value) {
            alert('Please select valid dates before submitting');
            return;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        
        // Submit form data
        fetch('functions/process_booking.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = `booking_payment.php?booking_id=${data.booking_id}`;
            } else {
                throw new Error(data.message || 'Unknown error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
});
</script>
</body>
</html>