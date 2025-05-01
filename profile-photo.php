<?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: "<?php echo $_SESSION['status_icon']; ?>",
        title: "<?php echo $_SESSION['status']; ?>",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
<?php
unset($_SESSION['status']);
unset($_SESSION['status_icon']);
?>
<?php endif; ?><?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include 'admin/functions/connection.php';

$tenant_id = $_SESSION['tenant_id'];
$stmt = $conn->prepare("SELECT * FROM tenants WHERE tenant_id = ?");
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['photo'])) {
    $target_dir = "uploads/tenants/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . time() . '_' . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if($check === false) {
        $_SESSION['status'] = "File is not an image.";
        $_SESSION['status_icon'] = "error";
        $uploadOk = 0;
    }
    
    if ($_FILES["photo"]["size"] > 5000000) {
        $_SESSION['status'] = "Sorry, your file is too large.";
        $_SESSION['status_icon'] = "error";
        $uploadOk = 0;
    }
    
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $_SESSION['status'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $_SESSION['status_icon'] = "error";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $update_stmt = $conn->prepare("UPDATE tenants SET photo = ? WHERE tenant_id = ?");
            $update_stmt->bind_param("si", $target_file, $tenant_id);
            if ($update_stmt->execute()) {
                $_SESSION['photo'] = $target_file;
                $_SESSION['status'] = "Profile photo updated successfully.";
                $_SESSION['status_icon'] = "success";
            } else {
                $_SESSION['status'] = "Error updating profile photo.";
                $_SESSION['status_icon'] = "error";
            }
            $update_stmt->close();
        } else {
            $_SESSION['status'] = "Sorry, there was an error uploading your file.";
            $_SESSION['status_icon'] = "error";
        }
    }
    header("Location: profile.php");
    exit();
}


?>