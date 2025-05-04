 <!-- Mobile Sidebar Overlay -->
 <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="includes-new/dashboard.js"></script>
<script>
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault(); 

        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to log out!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Logout',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'functions/logout.php';
            }
        });
    });
    </script>

    
 <!-- SweetAlert display script -->
 <?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
        <script>
            Swal.fire({
                icon: "<?php echo $_SESSION['status_icon']; ?>",
                title: "<?php echo $_SESSION['status']; ?>",
                confirmButtonText: "Ok"
            });
        </script>
        <?php
        unset($_SESSION['status']);
        unset($_SESSION['status_icon']);
        ?>
    <?php endif; ?>

   
<script>
new DataTable('#example');</script>
