<?php
// load_tenant_data.php

function loadTenantData($conn, $tenant_id) {
    $stmt = $conn->prepare("SELECT * FROM tenants WHERE tenant_id = ?");
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Store basic tenant info in session for global access
if (isset($_SESSION["loggedin"]) && !isset($_SESSION['tenant_data'])) {
    $tenantData = loadTenantData($conn, $_SESSION['tenant_id']);
    
    $_SESSION['tenant_data'] = [
        'name' => $tenantData['name'],
        'email' => $tenantData['email'],
        'photo' => $tenantData['photo'] ?? 'default.jpg',
        'occupation' => $tenantData['occupation'],
        'phone' => $tenantData['phone']
    ];
}
?>