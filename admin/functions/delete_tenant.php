<?php
session_start();
include('connection.php'); // Include the database connection

if (isset($_GET['id'])) {
    // Sanitize the tenant ID to prevent SQL injection
    $tenantId = mysqli_real_escape_string($conn, $_GET['id']);

    // Delete all payments related to the tenant (if any)
    $deletePaymentsQuery = "DELETE p FROM payments p
                            JOIN bookings b ON p.booking_id = b.booking_id
                            WHERE b.tenant_id = '$tenantId'";
    mysqli_query($conn, $deletePaymentsQuery);

    // Now, delete the tenant record
    $query = "DELETE FROM tenants WHERE tenant_id = '$tenantId'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['status'] = "Tenant deleted successfully!";
        $_SESSION['status_icon'] = "success";
    } else {
        $_SESSION['status'] = "Failed to delete tenant.";
        $_SESSION['status_icon'] = "error";
    }

    // Redirect back to the tenant list page
    header("Location: ../tenants.php");
    exit();
}
?>
