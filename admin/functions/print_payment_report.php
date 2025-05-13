<?php
include('../connection.php');

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Query to get payments for the selected period
$query = "SELECT p.*, b.tenant_id, b.room_id, t.fullname as tenant_name, r.room_number
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
$payments = $result->fetch_all(MYSQLI_ASSOC);

// Calculate total payments
$total = array_sum(array_column($payments, 'amount_paid'));

// HTML for printable report
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Report - <?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 10px; }
        }
    </style>
</head>
<body>
    <h1>Boarding House Management System</h1>
    <h2>Payment Report for <?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?></h2>
    
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Booking ID</th>
                <th>Tenant</th>
                <th>Room</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Reference</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                    <td><?= htmlspecialchars($payment['booking_id']) ?></td>
                    <td><?= htmlspecialchars($payment['tenant_name']) ?></td>
                    <td>Room <?= htmlspecialchars($payment['room_number']) ?></td>
                    <td>₱<?= number_format($payment['amount_paid'], 2) ?></td>
                    <td><?= htmlspecialchars($payment['payment_method']) ?></td>
                    <td><?= htmlspecialchars($payment['reference_number'] ?: 'N/A') ?></td>
                    <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="4">Total Payments</td>
                <td>₱<?= number_format($total, 2) ?></td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
    
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Print Report</button>
        <button onclick="window.close()">Close</button>
    </div>
    
    <script>
        window.onload = function() {
            // Auto-print when page loads (optional)
            // window.print();
        };
    </script>
</body>
</html>