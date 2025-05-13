<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reports - Boarding House Management</title>
    <?php include "includes-new/header.php";?>
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
                        <h1 class="text-2xl font-bold text-gray-800">Payment Reports</h1>
                        <p class="text-sm text-gray-600">View and manage payment records</p>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <form id="reportFilterForm" class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Month</label>
                            <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <?php
                                $months = [
                                    '01' => 'January', '02' => 'February', '03' => 'March', 
                                    '04' => 'April', '05' => 'May', '06' => 'June', 
                                    '07' => 'July', '08' => 'August', '09' => 'September', 
                                    '10' => 'October', '11' => 'November', '12' => 'December'
                                ];
                                $currentMonth = date('m');
                                foreach ($months as $num => $name): ?>
                                    <option value="<?= $num ?>" <?= $num == $currentMonth ? 'selected' : '' ?>>
                                        <?= $name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Year</label>
                            <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <?php
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= $currentYear - 5; $year--): ?>
                                    <option value="<?= $year ?>" <?= $year == $currentYear ? 'selected' : '' ?>>
                                        <?= $year ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center">
                                <i class="fas fa-filter mr-2"></i>
                                <span>Filter</span>
                            </button>
                        </div>
                        <div>
                            <button type="button" id="printReportBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center">
                                <i class="fas fa-print mr-2"></i>
                                <span>Print</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment Reports Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="paymentReportsTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    include('functions/connection.php');
                                    
                                    // Default to current month/year
                                    $month = isset($_GET['month']) ? $_GET['month'] : date('m');
                                    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                                    
                                    // Query to get payments with related booking and tenant info
                                    $query = "SELECT p.*, b.tenant_id, b.room_id, b.status as booking_status, 
                                             t.fullname as tenant_name, r.room_number
                                             FROM payments p
                                             JOIN bookings b ON p.booking_id = b.booking_id
                                             JOIN tenants t ON b.tenant_id = t.tenant_id
                                             JOIN rooms r ON b.room_id = r.room_id
                                             WHERE MONTH(p.payment_date) = ? AND YEAR(p.payment_date) = ?
                                             ORDER BY p.payment_date DESC";
                                    
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ss", $month, $year);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    
                                    if ($result->num_rows > 0):
                                        while ($payment = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?= htmlspecialchars($payment['payment_id']) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?= htmlspecialchars($payment['booking_id']) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?= htmlspecialchars($payment['tenant_name']) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Room <?= htmlspecialchars($payment['room_number']) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    ₱<?= number_format($payment['amount_paid'], 2) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?= htmlspecialchars($payment['payment_method']) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?= date('M d, Y', strtotime($payment['payment_date'])) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        <?= $payment['booking_status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                                        <?= htmlspecialchars($payment['booking_status']) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="viewPaymentBtn px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm flex items-center"
                                                            data-id="<?= $payment['payment_id'] ?>"
                                                            data-proof="<?= htmlspecialchars($payment['proof_photo']) ?>">
                                                            <i class="fas fa-eye mr-1 text-xs"></i> View
                                                        </button>
                                                        <button class="editPaymentBtn px-3 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm flex items-center"
                                                            data-id="<?= $payment['payment_id'] ?>">
                                                            <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No payments found for the selected period.
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

    <!-- View Payment Modal -->
    <div id="viewPaymentModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('viewPaymentModal')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Payment Details</h3>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('viewPaymentModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="bg-gray-50 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <h4 class="text-md font-medium text-gray-700 mb-2">Payment Proof</h4>
                        <img id="paymentProofImage" src="" alt="Payment Proof" class="w-full h-auto rounded-lg border border-gray-300">
                    </div>
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-2">Payment Information</h4>
                        <div class="space-y-2">
                            <p><span class="font-medium">Payment ID:</span> <span id="viewPaymentId"></span></p>
                            <p><span class="font-medium">Booking ID:</span> <span id="viewBookingId"></span></p>
                            <p><span class="font-medium">Amount:</span> <span id="viewAmount"></span></p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-2">Additional Details</h4>
                        <div class="space-y-2">
                            <p><span class="font-medium">Method:</span> <span id="viewMethod"></span></p>
                            <p><span class="font-medium">Reference:</span> <span id="viewReference"></span></p>
                            <p><span class="font-medium">Date:</span> <span id="viewDate"></span></p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="closeModal('viewPaymentModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div id="editPaymentModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('editPaymentModal')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Edit Payment</h3>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('editPaymentModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="functions/update_payment.php" enctype="multipart/form-data" class="bg-gray-50 p-6">
                <input type="hidden" name="payment_id" id="editPaymentId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid
                            <input type="number" name="amount_paid" id="editAmountPaid" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method
                            <select name="payment_method" id="editPaymentMethod" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Cash">Cash</option>
                                <option value="GCash">GCash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Check">Check</option>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number
                            <input type="text" name="reference_number" id="editReferenceNumber" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date
                            <input type="date" name="payment_date" id="editPaymentDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarks
                            <textarea name="remarks" id="editRemarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proof of Payment (Leave empty to keep current)
                            <input type="file" name="proof_photo" id="editProofPhoto" class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('editPaymentModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Update Payment</button>
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
        $('#paymentReportsTable').DataTable({
            responsive: true,
            lengthMenu: [10, 25, 50, 100],
            dom: '<"flex justify-between items-center mb-4"<"flex"l><"flex"f>>rt<"flex justify-between items-center mt-4"<"flex"i><"flex"p>>',
            language: {
                search: "",
                searchPlaceholder: "Search payments...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                }
            }
        });

        // View payment button handler
        document.querySelectorAll('.viewPaymentBtn').forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.getAttribute('data-id');
                const proofPhoto = this.getAttribute('data-proof');
                
                // Fetch payment details via AJAX
                fetch(`functions/get_payment_details.php?id=${paymentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate view modal
                            document.getElementById('viewPaymentId').textContent = data.payment_id;
                            document.getElementById('viewBookingId').textContent = data.booking_id;
                            document.getElementById('viewAmount').textContent = '₱' + parseFloat(data.amount_paid).toFixed(2);
                            document.getElementById('viewMethod').textContent = data.payment_method;
                            document.getElementById('viewReference').textContent = data.reference_number || 'N/A';
                            document.getElementById('viewDate').textContent = new Date(data.payment_date).toLocaleDateString('en-US', { 
                                year: 'numeric', month: 'short', day: 'numeric' 
                            });
                            
                            // Set proof image
                            const proofImage = document.getElementById('paymentProofImage');
                            if (proofPhoto) {
                                proofImage.src = `assets/uploads/${proofPhoto}`;
                                proofImage.alt = 'Payment Proof';
                            } else {
                                proofImage.src = 'assets/images/no-image.jpg';
                                proofImage.alt = 'No Proof Available';
                            }
                            
                            openModal('viewPaymentModal');
                        } else {
                            Swal.fire('Error', data.message || 'Failed to fetch payment details', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'An error occurred while fetching payment details', 'error');
                    });
            });
        });

        // Edit payment button handler
        document.querySelectorAll('.editPaymentBtn').forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.getAttribute('data-id');
                
                // Fetch payment details via AJAX
                fetch(`functions/get_payment_details.php?id=${paymentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate edit form
                            document.getElementById('editPaymentId').value = data.payment_id;
                            document.getElementById('editAmountPaid').value = data.amount_paid;
                            document.getElementById('editPaymentMethod').value = data.payment_method;
                            document.getElementById('editReferenceNumber').value = data.reference_number || '';
                            document.getElementById('editPaymentDate').value = data.payment_date.split(' ')[0];
                            document.getElementById('editRemarks').value = data.remarks || '';
                            
                            openModal('editPaymentModal');
                        } else {
                            Swal.fire('Error', data.message || 'Failed to fetch payment details', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'An error occurred while fetching payment details', 'error');
                    });
            });
        });

        // Print report button handler
        document.getElementById('printReportBtn').addEventListener('click', function() {
            const form = document.getElementById('reportFilterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            
            window.open(`functions/print_payment_report.php?${params}`, '_blank');
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