<?php
// Include the database connection
require_once '../admin/functions/connection.php';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $errors = [];
    
    // Check if passwords match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = "Passwords do not match.";
    }
    
    // Check if email already exists
    $email_check = $conn->prepare("SELECT email FROM tenants WHERE email = ?");
    $email_check->bind_param("s", $_POST['email']);
    $email_check->execute();
    $email_check->store_result();
    
    if ($email_check->num_rows > 0) {
        $errors[] = "Email already exists.";
    }
    $email_check->close();
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        // Prepare an insert statement
        $stmt = $conn->prepare("INSERT INTO tenants (name, email, phone, password, occupation, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        
        // Combine first and last name
        $name = $_POST['first_name'] . ' ' . $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $occupation = $_POST['occupation'];
        
        // Hash the password
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $occupation);
        
        if ($stmt->execute()) {
            // Registration successful, start session
            session_start();
            
            // Get the newly created user
            $result = $conn->query("SELECT * FROM tenants WHERE email = '$email'");
            $user = $result->fetch_assoc();
            
            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["tenant_id"] = $user['tenant_id'];
            $_SESSION["name"] = $user['name'];
            $_SESSION["email"] = $user['email'];
            $_SESSION["occupation"] = $user['occupation'];
            
            // Redirect to dashboard or home page
            header("location: ../index.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }
        
        $stmt->close();
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p class='text-red-500'>$error</p>";
        }
    }
    
    $conn->close();
}
?>