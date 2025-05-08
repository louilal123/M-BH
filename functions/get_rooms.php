<?php
session_start();
require_once '../admin/functions/connection.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$roomType = isset($_GET['room_type']) ? $_GET['room_type'] : '';
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'room_number_asc';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 9;
$offset = ($page - 1) * $perPage;

$query = "SELECT * FROM rooms WHERE 1=1 AND availability = '0'";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (room_number LIKE '%$search%' OR description LIKE '%$search%')";
}

if (!empty($roomType)) {
    $roomType = $conn->real_escape_string($roomType);
    $query .= " AND room_type = '$roomType'";
}

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

$countQuery = preg_replace('/SELECT \*/', 'SELECT COUNT(*)', $query);
$countQuery = preg_replace('/ORDER BY.*$/', '', $countQuery);
$countResult = $conn->query($countQuery);
$totalCount = $countResult->fetch_row()[0];
$totalPages = ceil($totalCount / $perPage);

$query .= " LIMIT $offset, $perPage";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $delay = 100;
    while ($room = $result->fetch_assoc()) {
        $roomId = $room['room_id'];

        $imageQuery = "SELECT photo FROM rooms WHERE room_id = $roomId";
        $imageResult = $conn->query($imageQuery);
        $imagePath = ($imageResult && $imageResult->num_rows > 0) ? $imageResult->fetch_assoc()['photo'] : "assets/images/rooms/default-room.jpg";

        $galleryQuery = "SELECT COUNT(*) FROM room_images WHERE room_id = $roomId";
        $galleryResult = $conn->query($galleryQuery);
        $imageCount = $galleryResult->fetch_row()[0];

      echo '<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden room-card transition-all duration-300 hover:shadow-xl">   <div class="h-64 overflow-hidden relative group">
        <img src="uploads/rooms/' . htmlspecialchars($imagePath) . '" alt="Room ' . htmlspecialchars($room['room_number']) . '" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        
        <!-- Status Badge - Now with z-index and ensured visibility -->
        <div class="absolute top-4 right-4 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg z-50">
            Available
        </div>
        
        <!-- Gallery Button Group - Enhanced visibility -->
        <div class="absolute bottom-4 right-4 flex items-center gap-2 z-50">
            <span class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded-full px-3 py-1 shadow-sm text-sm text-gray-900 dark:text-white font-medium">
                ' . ($imageCount > 0 ? $imageCount . ' Photos' : 'No Gallery') . '
            </span>
            <button onclick="showRoomGallery(' . $roomId . ')" class="flex items-center bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded-full px-3 py-1 shadow-sm text-sm text-gray-900 dark:text-white z-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
    </div>

            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Room ' . htmlspecialchars($room['room_number']) . '</h3>
                    <span class="text-accent font-bold">₱' . number_format($room['price'], 0) . '/mo</span>
                </div>

                <div class="flex justify-between items-center mb-2">
                    <span class="bg-green-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-3 py-1.5 rounded-full">'
                        . htmlspecialchars($room['room_type']) . '
                    </span>
                    <div class="flex items-center">
                        <div class="flex mr-1">'
                            . str_repeat('<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>', min(5, round($room['rating'] ?? 4.5))) . '
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">(' . ($room['review_count'] ?? 12) . ')</span>
                    </div>
                </div>

                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2 text-sm leading-relaxed">'
                    . htmlspecialchars($room['description']) . '
                </p>

                <div class="flex flex-wrap items-center gap-2 mb-4">';
                
        if (!empty($room['size'])) {
            echo '<span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-3 py-1.5 rounded-full">'
                . htmlspecialchars($room['size']) . ' m²
            </span>';
        }

        echo '</div>
      <a href="booking.php?room_id=' . $roomId . '" 
         class="block w-full text-center bg-primary hover:bg-primary-dark text-white py-2 rounded-md transition duration-300">
         Book Now
      </a>
   </div>
</div>';

        $delay += 100;
    }

    echo '<script>
        function showRoomGallery(roomId) {
            fetch(`get_room_gallery.php?room_id=${roomId}`)
                .then(response => response.json())
                .then(images => {
                    console.log("Showing gallery for room", roomId, images);
                });
        }
    </script>';
} else {
    echo '';
}
?>
