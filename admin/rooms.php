<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <?php include "includes-new/header.php"; ?>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="flex h-screen overflow-hidden">
        <?php include "includes-new/sidebar.php" ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include "includes-new/topnav.php" ?>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <!-- Dashboard Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Room Management</h1>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <button 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center"
                            onclick="openModal('addRoomModal')"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            <span>Add New Room</span>
                        </button>
                    </div>
                </div>

                <!-- Room Table Card -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="overflow-x-hidden">
                            <table class="min-w-full divide-y divide-gray-200" id="roomTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    include('functions/connection.php');
                                    $query = "SELECT r.room_id, r.room_number, rt.type_name, r.price, r.availability, r.description, r.photo 
                                            FROM rooms r
                                            JOIN room_types rt ON r.room_type = rt.type_id";
                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0):
                                        $rooms = $result->fetch_all(MYSQLI_ASSOC);
                                        foreach ($rooms as $room): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?php echo htmlspecialchars($room['room_number']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($room['type_name']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Php <?php echo number_format($room['price'], 2); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
    <?php 
    $availability = $room['availability'];

    $statusMap = [
        0 => ['label' => 'available', 'class' => 'bg-green-100 text-green-800'],
        1 => ['label' => 'occupied', 'class' => 'bg-yellow-100 text-yellow-800'],
        2 => ['label' => 'under maintenance', 'class' => 'bg-gray-100 text-gray-800'],
        3 => ['label' => 'reserved', 'class' => 'bg-blue-100 text-blue-800']
    ];

    $label = $statusMap[$availability]['label'] ?? 'unknown';
    $badgeClass = $statusMap[$availability]['class'] ?? 'bg-gray-100 text-gray-800';
    ?>
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $badgeClass; ?>">
        <?php echo ucfirst($label); ?>
    </span>
</td>

                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($room['description']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <img src="assets/uploads/<?php echo htmlspecialchars($room['photo']); ?>" 
                                                         alt="Room Photo" 
                                                         class="h-10 w-10 rounded-md object-cover">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="editRoomBtn px-3 py-1 bg-green-800 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm flex items-center"
                                                            data-id="<?php echo $room['room_id']; ?>"
                                                            data-roomnumber="<?php echo htmlspecialchars($room['room_number']); ?>"
                                                            data-roomtype="<?php echo htmlspecialchars($room['type_name']); ?>"
                                                            data-price="<?php echo htmlspecialchars($room['price']); ?>"
                                                            data-description="<?php echo htmlspecialchars($room['description']); ?>"
                                                            data-photo="<?php echo htmlspecialchars($room['photo']); ?>"
                                                            data-availability="<?php echo htmlspecialchars($room['availability']); ?>">
                                                            <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                                        </button>
                                                        <button class="deleteRoomBtn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm flex items-center"
                                                            data-id="<?php echo $room['room_id']; ?>">
                                                            <i class="fas fa-trash-alt mr-1 text-xs"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div id="addRoomModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('addRoomModal')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Add New Room</h3>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('addRoomModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="functions/add_room.php" enctype="multipart/form-data" class="bg-gray-50 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Number
                            <input type="text" name="roomNumber" required oninput="validateNumeric(this)" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Type
                            <select name="roomType" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="" disabled selected>Select Room Type</option>
                                <?php
                                $query = "SELECT * FROM room_types";
                                $result = $conn->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['type_id'] . "'>" . $row['type_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price
                            <input type="number" name="price" required oninput="validateNumeric(this)" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability
                            <select name="availability" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="under maintenance">Under Maintenance</option>
                                <option value="reserved">Reserved</option>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description
                            <textarea name="description" rows="3" required oninput="validateAlphanumeric(this)" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Photo
                            <input type="file" name="photo" accept="image/*" 
                                   class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 px-2">
                    <button type="button" onclick="closeModal('addRoomModal')" 
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" name="save_room" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Save Room
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Room Modal -->
    <div id="editRoomModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('editRoomModal')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Edit Room</h3>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('editRoomModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="functions/update_room.php" enctype="multipart/form-data" class="bg-gray-50 p-6">
                <input type="hidden" name="roomId" id="editRoomId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Number
                            <input type="text" name="roomNumber" id="editRoomNumber" required oninput="validateNumeric(this)" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Type
                            <select name="roomType" id="editRoomType" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="" disabled>Select Room Type</option>
                                <?php
                                $query = "SELECT * FROM room_types";
                                $result = $conn->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['type_id'] . "'>" . $row['type_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price
                            <input type="number" name="price" id="editRoomPrice" required oninput="validateNumeric(this)" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability
                            <select name="availability" id="editRoomAvailability" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="under maintenance">Under Maintenance</option>
                                <option value="reserved">Reserved</option>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description
                            <textarea name="description" id="editRoomDescription" rows="3" required oninput="validateAlphanumeric(this)" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Photo (Leave empty to keep current)
                            <input type="file" name="photo" id="editRoomPhoto" accept="image/*" 
                                   class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <img src="" id="currentRoomPhoto" alt="Current Room Photo" class="mt-3 h-24 rounded-md object-cover">
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 px-2">
                    <button type="button" onclick="closeModal('editRoomModal')" 
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" name="save_room" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include "includes-new/footer.php" ?>
    <script>
    // Modal control functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        $('#roomTable').DataTable({
            responsive: true,
            lengthMenu: [10, 25, 50, 100]
        });

        // Edit room button handler
        document.querySelectorAll('.editRoomBtn').forEach(button => {
            button.addEventListener('click', function() {
                const data = {
                    id: this.getAttribute('data-id'),
                    roomNumber: this.getAttribute('data-roomnumber'),
                    roomType: this.getAttribute('data-roomtype'),
                    price: this.getAttribute('data-price'),
                    description: this.getAttribute('data-description'),
                    photo: this.getAttribute('data-photo'),
                    availability: this.getAttribute('data-availability')
                };
                
                // Populate edit form
                document.getElementById('editRoomId').value = data.id;
                document.getElementById('editRoomNumber').value = data.roomNumber;
                document.getElementById('editRoomPrice').value = data.price;
                document.getElementById('editRoomDescription').value = data.description;
                document.getElementById('editRoomAvailability').value = data.availability;
                document.getElementById('currentRoomPhoto').src = 'assets/uploads/' + data.photo;
                
                // Set the room type in the dropdown by matching type_name
                const typeSelect = document.getElementById('editRoomType');
                for (let i = 0; i < typeSelect.options.length; i++) {
                    if (typeSelect.options[i].text === data.roomType) {
                        typeSelect.selectedIndex = i;
                        break;
                    }
                }
                
                openModal('editRoomModal');
            });
        });

        // Delete room button handler
        document.querySelectorAll('.deleteRoomBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const roomId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `functions/delete_room.php?id=${roomId}`;
                    }
                });
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
                document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });
    });

    // Validation functions
    function validateNumeric(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function validateAlphanumeric(input) {
        input.value = input.value.replace(/[^A-Za-z0-9\s]/g, '');
    }
    </script>
</body>
</html>