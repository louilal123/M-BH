<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Payments</title>
    <?php include "includes-new/header.php";?>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #receipt-container, #receipt-container * {
                visibility: visible;
            }
            #receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include "includes-new/sidebar.php" ?>
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include "includes-new/topnav.php" ?>

            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Booking Payments</h1>
                        <p class="text-sm text-gray-600">Record and manage tenant payments</p>
                    </div>
                    <button onclick="openPaymentModal()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Record Payment
                    </button>
                </div>

                <!-- Payment Records -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-5">
                        <div class="overflow-x-hidden">
                            <table class="min-w-full divide-y divide-gray-200" id="paymentTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    include 'functions/connection.php';
                                    $sql = "SELECT p.*, b.booking_id, t.name, t.tenant_id, r.room_number,
                                            (SELECT SUM(amount_paid) FROM payments WHERE booking_id = p.booking_id) as total_paid,
                                            b.total_amount as booking_total
                                            FROM payments p
                                            JOIN bookings b ON p.booking_id = b.booking_id
                                            JOIN tenants t ON b.tenant_id = t.tenant_id
                                            JOIN rooms r ON b.room_id = r.room_id
                                            ORDER BY p.payment_date DESC";
                                    $result = $conn->query($sql);
                                    
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()):
                                            $balance = $row['booking_total'] - $row['total_paid'];
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= $row['payment_id'] ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#<?= $row['booking_id'] ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= $row['name'] ?><br>
                                            <span class="text-gray-500">Room <?= $row['room_number'] ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₱<?= number_format($row['amount_paid'], 2) ?>
                                            <div class="text-xs text-gray-500">
                                                Balance: ₱<?= number_format($balance, 2) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M j, Y', strtotime($row['payment_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                            <?= $row['payment_method'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                <?= $balance <= 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                <?= $balance <= 0 ? 'Paid in Full' : 'Partial Payment' ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="viewPaymentDetails(<?= $row['payment_id'] ?>)" 
                                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="editPayment(<?= $row['payment_id'] ?>)" 
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="printReceipt(<?= $row['payment_id'] ?>)" 
                                                    class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button onclick="confirmDelete(<?= $row['payment_id'] ?>)" 
                                                    class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile;
                                    } else {
                                        echo '<tr><td colspan="8" class="px-6 py-4 text-center text-red-500">Error loading payments: ' . $conn->error . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800" id="paymentModalTitle">Record New Payment</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="paymentForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="payment_id" id="paymentId">
                <div class="p-6 space-y-4">
                    <!-- Booking Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Booking *</label>
                        <select name="booking_id" id="bookingId" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Booking</option>
                            <?php
                            $sql = "SELECT b.booking_id, t.name, t.tenant_id, r.room_number, b.total_amount,
                                    (SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE booking_id = b.booking_id) as paid_amount
                                    FROM bookings b
                                    JOIN tenants t ON b.tenant_id = t.tenant_id
                                    JOIN rooms r ON b.room_id = r.room_id
                                    WHERE b.status = 'confirmed'
                                    ORDER BY t.name";
                            $result = $conn->query($sql);
                            if ($result) {
                                while ($row = $result->fetch_assoc()):
                                    $balance = $row['total_amount'] - $row['paid_amount'];
                                    if ($balance <= 0) continue; // Skip fully paid bookings
                            ?>
                            <option value="<?= $row['booking_id'] ?>" 
                                    data-balance="<?= $balance ?>"
                                    data-tenant="<?= $row['name'] ?>"
                                    data-room="<?= $row['room_number'] ?>">
                                #<?= $row['booking_id'] ?> - <?= $row['name'] ?> (Room <?= $row['room_number'] ?>)
                                - Balance: ₱<?= number_format($balance, 2) ?>
                            </option>
                            <?php endwhile;
                            } else {
                                echo '<option value="" disabled>Error loading bookings</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Payment Details -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount *</label>
                            <input type="number" name="amount" id="amount" step="0.01" min="1" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                            <input type="date" name="payment_date" id="paymentDate" required
                                   value="<?= date('Y-m-d') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Method *</label>
                            <select name="payment_method" id="paymentMethod" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="check">Check</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reference No.</label>
                            <input type="text" name="reference_number" id="referenceNumber"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Payment Proof -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Proof</label>
                        <input type="file" name="proof_photo" id="proofPhoto" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div id="currentProof" class="mt-2 hidden">
                            <p class="text-sm text-gray-500">Current proof:</p>
                            <img id="proofImage" src="" class="h-20 mt-1 border rounded">
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Save Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Receipt Container (Hidden until printing) -->
    <div id="receipt-container" class="hidden p-6 max-w-md mx-auto bg-white">
        <!-- Receipt content will be inserted here by JavaScript -->
    </div>

    <?php include "includes-new/footer.php" ?>
    <script>
    // Initialize DataTable
    $(document).ready(function() {
        $('#paymentTable').DataTable({
            responsive: true,
            order: [[0, 'desc']]
        });
    });

    // Global variables
    let currentAction = 'add';

    // Modal functions
    function openPaymentModal() {
        currentAction = 'add';
        document.getElementById('paymentModalTitle').textContent = 'Record New Payment';
        document.getElementById('paymentForm').reset();
        document.getElementById('paymentId').value = '';
        document.getElementById('currentProof').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    // View payment details
    function viewPaymentDetails(paymentId) {
        fetch(`functions/get_payment_details.php?id=${paymentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: `Payment #${data.payment_id}`,
                        html: `
                            <div class="text-left space-y-2">
                                <p><strong>Booking:</strong> #${data.booking_id}</p>
                                <p><strong>Tenant:</strong> ${data.name} (Room ${data.room_number})</p>
                                <p><strong>Amount:</strong> ₱${parseFloat(data.amount_paid).toFixed(2)}</p>
                                <p><strong>Date:</strong> ${new Date(data.payment_date).toLocaleDateString()}</p>
                                <p><strong>Method:</strong> ${data.payment_method.charAt(0).toUpperCase() + data.payment_method.slice(1)}</p>
                                <p><strong>Reference:</strong> ${data.reference_number || 'N/A'}</p>
                                <p><strong>Remarks:</strong> ${data.remarks || 'None'}</p>
                                ${data.proof_photo ? `<div class="mt-3"><strong>Proof:</strong><br><img src="functions/get_payment_proof.php?id=${data.payment_id}" class="max-w-full h-auto mt-2 border rounded"></div>` : ''}
                            </div>
                        `,
                        confirmButtonText: 'Close',
                        showCancelButton: true,
                        cancelButtonText: 'Print Receipt',
                        cancelButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                            printReceipt(data.payment_id);
                        }
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to load payment details', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to load payment details', 'error');
            });
    }

    // Edit payment
    function editPayment(paymentId) {
        currentAction = 'edit';
        document.getElementById('paymentModalTitle').textContent = 'Edit Payment';
        
        fetch(`functions/get_payment_details.php?id=${paymentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('paymentId').value = data.payment_id;
                    document.getElementById('bookingId').value = data.booking_id;
                    document.getElementById('amount').value = data.amount_paid;
                    document.getElementById('paymentDate').value = data.payment_date.split(' ')[0];
                    document.getElementById('paymentMethod').value = data.payment_method;
                    document.getElementById('referenceNumber').value = data.reference_number || '';
                    document.getElementById('remarks').value = data.remarks || '';
                    
                    if (data.proof_photo) {
                        document.getElementById('currentProof').classList.remove('hidden');
                        document.getElementById('proofImage').src = `functions/get_payment_proof.php?id=${data.payment_id}`;
                    } else {
                        document.getElementById('currentProof').classList.add('hidden');
                    }
                    
                    document.getElementById('paymentModal').classList.remove('hidden');
                } else {
                    Swal.fire('Error', data.message || 'Failed to load payment details', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to load payment details', 'error');
            });
    }

    // Print receipt function
    function printReceipt(paymentId) {
        fetch(`functions/get_payment_details.php?id=${paymentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const receiptContainer = document.getElementById('receipt-container');
                    receiptContainer.innerHTML = `
                        <div class="text-center mb-4">
                            <h2 class="text-xl font-bold">${document.title}</h2>
                            <p class="text-sm text-gray-600">Payment Receipt</p>
                        </div>
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Receipt #:</span>
                                <span>${data.payment_id}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Date:</span>
                                <span>${new Date(data.payment_date).toLocaleDateString()}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Booking #:</span>
                                <span>${data.booking_id}</span>
                            </div>
                        </div>
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <div class="mb-2">
                                <p class="font-medium">Tenant Details:</p>
                                <p>${data.name}</p>
                                <p>Room ${data.room_number}</p>
                            </div>
                        </div>
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Payment Method:</span>
                                <span>${data.payment_method.charAt(0).toUpperCase() + data.payment_method.slice(1)}</span>
                            </div>
                            ${data.reference_number ? `
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Reference #:</span>
                                <span>${data.reference_number}</span>
                            </div>` : ''}
                        </div>
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Amount Paid:</span>
                                <span>₱${parseFloat(data.amount_paid).toFixed(2)}</span>
                            </div>
                        </div>
                        <div class="text-center text-sm text-gray-500 mt-6">
                            <p>Thank you for your payment!</p>
                            <p class="mt-2">${new Date().toLocaleDateString()}</p>
                        </div>
                        <div class="no-print text-center mt-6">
                            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                <i class="fas fa-print mr-2"></i> Print Receipt
                            </button>
                            <button onclick="document.getElementById('receipt-container').classList.add('hidden')" 
                                    class="ml-2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                <i class="fas fa-times mr-2"></i> Close
                            </button>
                        </div>
                    `;
                    receiptContainer.classList.remove('hidden');
                    window.print();
                } else {
                    Swal.fire('Error', data.message || 'Failed to load payment details', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to load payment details', 'error');
            });
    }

    // Delete payment confirmation
    function confirmDelete(paymentId) {
        Swal.fire({
            title: 'Delete Payment?',
            text: "This will permanently remove this payment record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `functions/delete_payment.php?id=${paymentId}`;
            }
        });
    }

    // Form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';
        
        const url = currentAction === 'add' ? 'functions/add_payment.php' : 'functions/update_payment.php';
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while processing your request',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Payment';
        });
    });

    // Update amount field with remaining balance when booking is selected
    document.getElementById('bookingId').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const balance = parseFloat(selectedOption.dataset.balance);
            document.getElementById('amount').value = balance.toFixed(2);
            document.getElementById('amount').max = balance;
        }
    });
    </script>
</body>
</html>