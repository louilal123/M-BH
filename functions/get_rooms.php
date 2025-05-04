<?php
// functions/get_rooms.php
require_once '../admin/functions/connection.php';

// Get filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$roomType = isset($_GET['room_type']) ? $_GET['room_type'] : '';
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'room_number_asc';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 9; // 3x3 grid

// Calculate offset for pagination
$offset = ($page - 1) * $perPage;

// Build query - updated to use room_type column directly
$query = "SELECT * FROM rooms WHERE 1=1";

// Add search condition
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (room_number LIKE '%$search%' OR description LIKE '%$search%')";
}

// Add room type filter - now checking the room_type column directly
if (!empty($roomType)) {
    $roomType = $conn->real_escape_string($roomType);
    $query .= " AND room_type = '$roomType'";
}

// Add sorting
switch ($sortBy) {
    case 'price_asc':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY price DESC";
        break;
    case 'room_number_desc':
        $query .= " ORDER BY room_number DESC";
        break;
    case 'room_number_asc':
    default:
        $query .= " ORDER BY room_number ASC";
        break;
}

// Get total count for pagination
$countQuery = preg_replace('/SELECT \*/', 'SELECT COUNT(*)', $query);
$countQuery = preg_replace('/ORDER BY.*$/', '', $countQuery);
$countResult = $conn->query($countQuery);
$totalCount = $countResult->fetch_row()[0];
$totalPages = ceil($totalCount / $perPage);

// Add limit for pagination
$query .= " LIMIT $offset, $perPage";

// Execute query
$result = $conn->query($query);

// Check if rooms were found
if ($result->num_rows > 0) {
    $delay = 100;
    
    // Output rooms
    while ($room = $result->fetch_assoc()) {
        // Get the first image for this room (or default if none)
        $roomId = $room['room_id'];
        $imageQuery = "SELECT image_path FROM room_images WHERE room_id = $roomId LIMIT 1";
        $imageResult = $conn->query($imageQuery);
        
        if ($imageResult && $imageResult->num_rows > 0) {
            $imagePath = $imageResult->fetch_assoc()['image_path'];
        } else {
            // Default image if none found
            $imagePath = "assets/images/rooms/default-room.jpg";
        }
        
        // Determine availability badge color
        $availabilityClass = $room['availability'] == 'available' ? 'bg-accent' : 'bg-gray-500';
        $availabilityText = ucfirst($room['availability']);
        
        // Display room card
        echo '<div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg overflow-hidden room-card" data-aos="fade-up">
                <div class="h-64 overflow-hidden relative">
                    <img src="uploads/rooms/' . htmlspecialchars($imagePath) . '" alt="Room ' . htmlspecialchars($room['room_number']) . '" class="w-full h-full object-cover transition-transform duration-500">
                    <div class="absolute top-4 right-4 ' . $availabilityClass . ' text-white text-sm font-bold px-3 py-1 rounded-full shadow-md">
                        ' . $availabilityText . '
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Room ' . htmlspecialchars($room['room_number']) . '</h3>
                        <span class="text-accent font-bold">₱' . number_format($room['price'], 0) . '/mo</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 mb-4 line-clamp-2">
                        ' . htmlspecialchars($room['description']) . '
                    </p>
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">' . htmlspecialchars($room['room_type']) . '</span>';
                            
        // You can add more features here if needed
        // For example, you might want to add room size or other attributes
        if (!empty($room['size'])) {
            echo '<span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">' . htmlspecialchars($room['size']) . ' m²</span>';
        }
                            
        echo '      </div>
                    <a href="room_details.php?id=' . $room['room_id'] . '" class="block text-center bg-primary hover:bg-primary-dark text-white py-2 rounded-md transition duration-300 transform hover:-translate-y-1">View Details</a>
                </div>
            </div>
        </div>';
        
        $delay += 100;
    }
    
    // Add pagination controls via JavaScript parameters
    echo '<script>
        var totalPages = ' . $totalPages . ';
        var currentPage = ' . $page . ';
    </script>';
} else {
    // No rooms found
    echo '';
}
?>