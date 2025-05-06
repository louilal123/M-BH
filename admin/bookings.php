<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management</title>
    <?php include "includes-new/header.php"; ?>
</head>
<body class="bg-gray-50 text-gray-800">
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
                        <h1 class="text-2xl font-bold text-gray-800">Bookings Management</h1>
                    </div>
                   
                </div>

                <!-- Bookings Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="bookingsTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-In</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-Out</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    include('functions/connection.php');
                                    $query = "SELECT b.*, t.name as tenant_name, t.email as tenant_email, 
                                              r.room_number, r.price as room_price, r.room_type
                                              FROM bookings b
                                              JOIN tenants t ON b.tenant_id = t.tenant_id
                                              JOIN rooms r ON b.room_id = r.room_id
                                              ORDER BY b.booking_date DESC";
                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0):
                                        while ($booking = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?php echo htmlspecialchars($booking['booking_id']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img class="h-10 w-10 rounded-full" src="assets/uploads/<?php echo htmlspecialchars($booking['photo'] ?? 'default-tenant.jpg'); ?>" alt="">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($booking['tenant_name']); ?></div>
                                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($booking['tenant_email']); ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">Room #<?php echo htmlspecialchars($booking['room_number']); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($booking['room_type']); ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo date('M d, Y', strtotime($booking['check_in_date'])); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo date('M d, Y', strtotime($booking['check_out_date'])); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ₱<?php echo number_format($booking['total_amount'], 2); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        <?php 
                                                        switch($booking['status']) {
                                                            case 'confirmed': echo 'bg-green-100 text-green-800'; break;
                                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                            case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                                            case 'completed': echo 'bg-blue-100 text-blue-800'; break;
                                                            default: echo 'bg-gray-100 text-gray-800';
                                                        }
                                                        ?>">
                                                        <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="editBookingBtn px-3 py-1 bg-green-700 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm flex items-center"
                                                            data-id="<?php echo $booking['booking_id']; ?>"
                                                            data-tenant="<?php echo $booking['tenant_id']; ?>"
                                                            data-room="<?php echo $booking['room_id']; ?>"
                                                            data-checkin="<?php echo $booking['check_in_date']; ?>"
                                                            data-checkout="<?php echo $booking['check_out_date']; ?>"
                                                            data-status="<?php echo $booking['status']; ?>"
                                                            data-requests="<?php echo htmlspecialchars($booking['special_requests']); ?>"
                                                            data-amount="<?php echo $booking['total_amount']; ?>">
                                                            <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                                        </button>
                                                        <button class="deleteBookingBtn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm flex items-center"
                                                            data-id="<?php echo $booking['booking_id']; ?>">
                                                            <i class="fas fa-trash-alt mr-1 text-xs"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No bookings found
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    // Edit booking button handler
        document.querySelectorAll('.editBookingBtn').forEach(button => {
            button.addEventListener('click', function() {
                const data = {
                    id: this.getAttribute('data-id'),
                    tenant: this.getAttribute('data-tenant'),
                    room: this.getAttribute('data-room'),
                    checkin: this.getAttribute('data-checkin'),
                    checkout: this.getAttribute('data-checkout'),
                    status: this.getAttribute('data-status'),
                    requests: this.getAttribute('data-requests'),
                    amount: this.getAttribute('data-amount')
                };
                
                // Populate edit form
                document.getElementById('editBookingId').value = data.id;
                document.getElementById('editTenantId').value = data.tenant;
                document.getElementById('editRoomId').value = data.room;
                document.getElementById('editCheckInDate').value = data.checkin;
                document.getElementById('editCheckOutDate').value = data.checkout;
                document.getElementById('editStatus').value = data.status;
                document.getElementById('editSpecialRequests').value = data.requests;
                document.getElementById('editTotalAmount').value = data.amount;
                
                openModal('editBookingModal');
            });
        });
    <!-- Edit Booking Modal -->
    <div id="editBookingModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('editBookingModal')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Edit Booking</h3>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('editBookingModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="functions/update_booking.php" class="bg-gray-50 p-6">
                <input type="hidden" name="booking_id" id="editBookingId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tenant
                            <select name="tenant_id" id="editTenantId" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <?php
                                $tenants = $conn->query("SELECT tenant_id, name, email FROM tenants ORDER BY name");
                                while ($tenant = $tenants->fetch_assoc()): ?>
                                    <option value="<?php echo $tenant['tenant_id']; ?>">
                                        <?php echo htmlspecialchars($tenant['name'] . " (" . $tenant['email'] . ")"); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room
                            <select name="room_id" id="editRoomId" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <?php
                                $rooms = $conn->query("SELECT room_id, room_number, room_type FROM rooms ORDER BY room_number");
                                while ($room = $rooms->fetch_assoc()): ?>
                                    <option value="<?php echo $room['room_id']; ?>">
                                        <?php echo htmlspecialchars("Room #" . $room['room_number'] . " (" . $room['room_type'] . ")"); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Check-In Date
                            <input type="date" name="check_in_date" id="editCheckInDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Check-Out Date
                            <input type="date" name="check_out_date" id="editCheckOutDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status
                            <select name="status" id="editStatus" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount
                            <input type="number" name="total_amount" id="editTotalAmount" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Special Requests
                            <textarea name="special_requests" id="editSpecialRequests" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 px-2">
                    <button type="button" onclick="closeModal('editBookingModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Update Booking</button>
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
        $('#bookingsTable').DataTable({
            responsive: true,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']]
        });

        // Calculate total amount when room or dates change
        document.querySelector('[name="room_id"]').addEventListener('change', calculateTotal);
        document.getElementById('checkInDate').addEventListener('change', calculateTotal);
        document.getElementById('checkOutDate').addEventListener('change', calculateTotal);

        function calculateTotal() {
            const roomSelect = document.querySelector('[name="room_id"]');
            const checkInDate = document.getElementById('checkInDate').value;
            const checkOutDate = document.getElementById('checkOutDate').value;
            const totalAmount = document.getElementById('totalAmount');

            if (roomSelect.value && checkInDate && checkOutDate) {
                const price = roomSelect.options[roomSelect.selectedIndex].getAttribute('data-price');
                const days = Math.ceil((new Date(checkOutDate) - new Date(checkInDate)) / (1000 * 60 * 60 * 24));
                totalAmount.value = (price * days).toFixed(2);
            }
        }

        // Edit booking button handler
        document.querySelectorAll('.editBookingBtn').forEach(button => {
            button.addEventListener('click', function() {
                const data = {
                    id: this.getAttribute('data-id'),
                    tenant: this.getAttribute('data-tenant'),
                    room: this.getAttribute('data-room'),
                    checkin: this.getAttribute('data-checkin'),
                    checkout: this.getAttribute('data-checkout'),
                    status: this.getAttribute('data-status'),
                    requests: this.getAttribute('data-requests'),
                    amount: this.getAttribute('data-amount')
                };
                
                // Populate edit form
                document.getElementById('editBookingId').value = data.id;
                document.getElementById('editTenantId').value = data.tenant;
                document.getElementById('editRoomId').value = data.room;
                document.getElementById('editCheckInDate').value = data.checkin;
                document.getElementById('editCheckOutDate').value = data.checkout;
                document.getElementById('editStatus').value = data.status;
                document.getElementById('editSpecialRequests').value = data.requests;
                document.getElementById('editTotalAmount').value = data.amount;
                
                openModal('editBookingModal');
            });
        });

        // Delete booking button handler
        document.querySelectorAll('.deleteBookingBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const bookingId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure you want to delete this booking?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `functions/delete_booking.php?id=${bookingId}`;
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
    </script>
</body>
</html>