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
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include "includes-new/topnav.php" ?>

            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Bookings Management</h1>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="lg:overflow-x-hidden">
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
                                    $query = "SELECT b.*, t.name as tenant_name, t.email as tenant_email, t.phone as tenant_phone, t.photo as tenant_photo,
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
                                                            <img class="h-10 w-10 rounded-full" src="../uploads/tenants/<?php echo htmlspecialchars($booking['tenant_photo'] ?? 'default-tenant.jpg'); ?>" alt="">
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
                                                        <button class="viewBookingBtn px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm flex items-center"
                                                            data-id="<?php echo $booking['booking_id']; ?>"
                                                            data-tenant="<?php echo htmlspecialchars($booking['tenant_name']); ?>"
                                                            data-email="<?php echo htmlspecialchars($booking['tenant_email']); ?>"
                                                            data-phone="<?php echo htmlspecialchars($booking['tenant_phone']); ?>"
                                                            data-photo="<?php echo htmlspecialchars($booking['tenant_photo'] ?? 'default-tenant.jpg'); ?>"
                                                            data-room="<?php echo htmlspecialchars($booking['room_number']); ?>"
                                                            data-roomtype="<?php echo htmlspecialchars($booking['room_type']); ?>"
                                                            data-roomprice="<?php echo number_format($booking['room_price'], 2); ?>"
                                                            data-checkin="<?php echo date('M d, Y', strtotime($booking['check_in_date'])); ?>"
                                                            data-checkout="<?php echo date('M d, Y', strtotime($booking['check_out_date'])); ?>"
                                                            data-duration="<?php echo ceil((strtotime($booking['check_out_date']) - strtotime($booking['check_in_date'])) / (60 * 60 * 24)); ?> days"
                                                            data-status="<?php echo $booking['status']; ?>"
                                                            data-amount="<?php echo number_format($booking['total_amount'], 2); ?>"
                                                            data-requests="<?php echo htmlspecialchars($booking['special_requests'] ?? 'None'); ?>"
                                                            data-date="<?php echo date('M d, Y', strtotime($booking['booking_date'])); ?>">
                                                            <i class="fas fa-eye mr-1 text-xs"></i> View
                                                        </button>
                                                        <button class="editStatusBtn px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm flex items-center"
                                                            data-id="<?php echo $booking['booking_id']; ?>"
                                                            data-tenant="<?php echo htmlspecialchars($booking['tenant_name']); ?>"
                                                            data-room="<?php echo htmlspecialchars($booking['room_number']); ?>"
                                                            data-status="<?php echo $booking['status']; ?>">
                                                            <i class="fas fa-edit mr-1 text-xs"></i> Status
                                                        </button>
                                                        <button class="deleteBookingBtn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm flex items-center"
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

<div id="viewBookingModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('viewBookingModal')"></div>
    <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Booking Details #<span id="viewBookingId"></span></h3>
            <div class="flex space-x-2">
                <button onclick="printBookingDetails()" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm flex items-center">
                    <i class="fas fa-print mr-1 text-xs"></i> Print Receipt
                </button>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('viewBookingModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6 bg-gray-50" id="printableBookingContent">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Tenant Information</h4>
                    <div class="bg-white p-4 rounded-md shadow-sm">
                        <div class="flex items-center mb-3">
                            <img id="viewTenantPhoto" class="h-12 w-12 rounded-full mr-3" src="" alt="Tenant Photo">
                            <div>
                                <div id="viewTenantName" class="font-medium"></div>
                                <div id="viewTenantEmail" class="text-sm text-gray-500"></div>
                                <div id="viewTenantPhone" class="text-sm text-gray-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Room Information</h4>
                    <div class="bg-white p-4 rounded-md shadow-sm">
                        <div id="viewRoomNumber" class="font-medium"></div>
                        <div id="viewRoomType" class="text-sm text-gray-500"></div>
                        <div id="viewRoomPrice" class="text-sm text-gray-500"></div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-md shadow-sm">
                    <h4 class="font-medium text-gray-700 mb-1">Booking Dates</h4>
                    <div class="flex justify-between">
                        <div>
                            <div class="text-sm text-gray-500">Check-In</div>
                            <div id="viewCheckIn" class="font-medium"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Check-Out</div>
                            <div id="viewCheckOut" class="font-medium"></div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="text-sm text-gray-500">Duration</div>
                        <div id="viewDuration" class="font-medium"></div>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-md shadow-sm">
                    <h4 class="font-medium text-gray-700 mb-1">Payment</h4>
                    <div class="flex justify-between mb-1">
                        <div class="text-sm text-gray-500">Total Amount</div>
                        <div id="viewTotalAmount" class="font-medium"></div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-sm text-gray-500">Status</div>
                        <span id="viewStatus" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"></span>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-md shadow-sm">
                    <h4 class="font-medium text-gray-700 mb-1">Booking Info</h4>
                    <div class="text-sm text-gray-500 mb-1">Booking Date</div>
                    <div id="viewBookingDate" class="font-medium text-sm mb-2"></div>
                    <div class="text-sm text-gray-500 mb-1">Special Requests</div>
                    <div id="viewSpecialRequests" class="font-medium text-sm"></div>
                </div>
            </div>
        </div>
        <div class="flex justify-end space-x-3 p-4 border-t">
            <button type="button" onclick="openStatusModal()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Update Status
            </button>
            <button type="button" onclick="closeModal('viewBookingModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Close
            </button>
        </div>
    </div>
</div>

<div id="statusUpdateModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('statusUpdateModal')"></div>
    <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Update Booking Status</h3>
            <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('statusUpdateModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="functions/update_booking_status.php" class="bg-gray-50 p-6">
            <input type="hidden" name="booking_id" id="statusBookingId">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Booking ID</label>
                <div id="displayBookingId" class="w-full px-3 py-2 bg-gray-100 rounded-md"></div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tenant</label>
                <div id="displayTenant" class="w-full px-3 py-2 bg-gray-100 rounded-md"></div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                <div id="displayRoom" class="w-full px-3 py-2 bg-gray-100 rounded-md"></div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="updateStatus" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                   
                </select>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal('statusUpdateModal')" 
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

    <?php include "includes-new/footer.php" ?>
    <script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function printBookingDetails() {
        const printContent = document.getElementById('printableBookingContent').innerHTML;
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = `
            <div class="p-6 max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h1 class="text-2xl font-bold">Booking Receipt</h1>
                    <div class="text-sm text-gray-500">Printed on ${new Date().toLocaleDateString()}</div>
                </div>
                ${printContent}
            </div>
        `;
        
        window.print();
        document.body.innerHTML = originalContent;
        document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
            modal.classList.remove('hidden');
        });
    }

    function openStatusModal() {
        const bookingId = document.getElementById('viewBookingId').textContent;
        const tenantName = document.getElementById('viewTenantName').textContent;
        const roomNumber = document.getElementById('viewRoomNumber').textContent;
        const status = document.getElementById('viewStatus').textContent.toLowerCase();
        
        document.getElementById('statusBookingId').value = bookingId;
        document.getElementById('displayBookingId').textContent = bookingId;
        document.getElementById('displayTenant').textContent = tenantName;
        document.getElementById('displayRoom').textContent = roomNumber;
        document.getElementById('updateStatus').value = status;
        
        closeModal('viewBookingModal');
        openModal('statusUpdateModal');
    }

    document.addEventListener('DOMContentLoaded', function() {
        $('#bookingsTable').DataTable({
            responsive: true,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']]
        });

        document.querySelectorAll('.viewBookingBtn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('viewBookingId').textContent = this.getAttribute('data-id');
                // src="../uploads/tenants/
                document.getElementById('viewTenantPhoto').src = '../uploads/tenants/' + this.getAttribute('data-photo');
                document.getElementById('viewTenantName').textContent = this.getAttribute('data-tenant');
                document.getElementById('viewTenantEmail').textContent = this.getAttribute('data-email');
                document.getElementById('viewTenantPhone').textContent = this.getAttribute('data-phone');
                document.getElementById('viewRoomNumber').textContent = 'Room #' + this.getAttribute('data-room');
                document.getElementById('viewRoomType').textContent = this.getAttribute('data-roomtype');
                document.getElementById('viewRoomPrice').textContent = '₱' + this.getAttribute('data-roomprice') + ' per night';
                document.getElementById('viewCheckIn').textContent = this.getAttribute('data-checkin');
                document.getElementById('viewCheckOut').textContent = this.getAttribute('data-checkout');
                document.getElementById('viewDuration').textContent = this.getAttribute('data-duration');
                document.getElementById('viewTotalAmount').textContent = '₱' + this.getAttribute('data-amount');
                document.getElementById('viewBookingDate').textContent = this.getAttribute('data-date');
                document.getElementById('viewSpecialRequests').textContent = this.getAttribute('data-requests');
                
                const status = this.getAttribute('data-status');
                const statusElement = document.getElementById('viewStatus');
                statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                
                statusElement.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ';
                switch(status) {
                    case 'confirmed': statusElement.className += 'bg-green-100 text-green-800'; break;
                    case 'pending': statusElement.className += 'bg-yellow-100 text-yellow-800'; break;
                    case 'cancelled': statusElement.className += 'bg-red-100 text-red-800'; break;
                    case 'completed': statusElement.className += 'bg-blue-100 text-blue-800'; break;
                    default: statusElement.className += 'bg-gray-100 text-gray-800';
                }
                
                openModal('viewBookingModal');
            });
        });

        document.querySelectorAll('.editStatusBtn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('statusBookingId').value = this.getAttribute('data-id');
                document.getElementById('displayBookingId').textContent = this.getAttribute('data-id');
                document.getElementById('displayTenant').textContent = this.getAttribute('data-tenant');
                document.getElementById('displayRoom').textContent = 'Room #' + this.getAttribute('data-room');
                document.getElementById('updateStatus').value = this.getAttribute('data-status');
                
                openModal('statusUpdateModal');
            });
        });

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

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
                document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });
    });
    </script>
    <script>
           // Add this new form submission handler
    document.getElementById('statusUpdateModal').querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                
                // Update the status in the table without reload
                const bookingId = formData.get('booking_id');
                const newStatus = formData.get('status');
                
                // Find and update the status in the DataTable
                const table = $('#bookingsTable').DataTable();
                table.rows().every(function() {
                    const rowData = this.data();
                    if (rowData[0] == bookingId || rowData[0].includes(bookingId)) {
                        // Update status cell (adjust the index based on your table structure)
                        const statusCell = this.node().cells[4]; // Typically status is in column 5
                        statusCell.innerHTML = `
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                newStatus === 'confirmed' ? 'bg-green-100 text-green-800' :
                                newStatus === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800'
                            }">
                                ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}
                            </span>
                        `;
                        
                        // Update the data-status attribute for any action buttons
                        const rowNode = this.node();
                        rowNode.querySelectorAll('[data-status]').forEach(el => {
                            el.setAttribute('data-status', newStatus);
                        });
                        
                        return false; // Exit the loop
                    }
                });
                
                closeModal('statusUpdateModal');
            } else {
                throw new Error(data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: error.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
    </script>
</body>
</html>