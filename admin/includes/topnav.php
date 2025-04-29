

  <style>
    body {
    display: flex;
    height: 100vh;
    width: 100vw;
    overflow: hidden;
    background: #D3D3D3; /* White background */
}

#sidebar {
    height: 100vh;
    min-width: 300px;
    background-color: #343a40;
    color: white;
}

#navbar {
    width: 100% !important; 
    background-color: rgb(239, 239, 239); /* White background */
}

#content {
    flex-grow: 1;
    overflow-y: auto;
}

.navbar {
    width: 100% !important; 
     
}

.container-fluid {
    width: 100%; 
   
}

 /* Enhanced Dashboard Panels */

 .card {
  background-color: #ffffff; /* White background */
  border-color: white 1px !important;
  border: none !important;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);  /* Stronger shadow on hover */
}

.card h6 {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #333;  /* Slightly darker text color for readability */
}

.card h1 {
  font-size: 2.5rem;  /* Adjusted size for a more balanced design */
  font-weight: bold;
  margin: 0;
}

.card-body {
  padding: 1.5rem;  /* Increased padding for more space around content */
}

.card-icon {
  font-size: 3rem;  /* Adjusted size for the icon */
  color: #000;  /* Matching icon color with the numbers */
}

.card .d-flex {
  display: flex;
  align-items: center;
}

.card .ms-auto {
  margin-left: auto;
}

.row.g-4 {
  gap: 1.5rem;  /* Reduced gap for a more compact layout */
}

.bg-primary, .btn-primary, .primary {
  background-color:rgb(40, 114, 194) !important;
}
.active{
  color:rgb(40, 114, 194) !important;
}
.text-primary{
  color: rgb(40, 114, 194) !important;
}
.nav-link, label{
  color: black;
}

.active>.page-link, .page-link.active{

  background:rgb(40, 114, 194) !important;
}

.bg-success, .btn-success {
  background-color:rgb(70, 134, 85) !important;
}
.text-success{
  color:rgb(70, 134, 85) !important;
}

.bg-warning, .btn-warning  {
  background-color:rgb(131, 101, 12) !important;
}
.text-warning{
  color:rgb(131, 101, 12) !important;
}

.bg-danger, .btn-danger {
  background-color:rgb(161, 43, 55) !important;
}
.text-danger{
  color:rgb(161, 43, 55) !important;
}

    /* Table Styling */
    .table {
      border-collapse: separate;
     
    }
    .table th {
      background: #f8f9fa;
    }
    .table tr {
      background: white;
     
    }
    .table td, .table th {
      vertical-align: middle;
      padding: 15px;
    }
    .badge-success, .badge-danger , .btn{
      padding: 10px !important;
    }

    </style>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark w-full w-100" style="width: 100% !important;">
            <div class="container-fluid"style="width: 100% !important;">
                <button class="btn btn-dark me-3" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                <a class="navbar-brand" href="#">MECMEC BOARDING HOUSE MANAGEMENT SYSTEM</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                              <li class="nav-item dropdown pe-3">

                                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                                  <img src="assets/uploads/<?php echo htmlspecialchars($admin_photo); ?>" alt="Profile" class="rounded-circle" style="width: 40px; height:40px; ">
                                  <span class="d-none d-md-block dropdown-toggle ps-2 text-white"><?php echo htmlspecialchars($admin_fullname); ?></span>
                                </a><!-- End Profile Iamge Icon -->

                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                  <li class="dropdown-header">
                                    <h6><?php echo htmlspecialchars($admin_fullname); ?></h6>
                                    <span><?php echo htmlspecialchars($admin_role); ?></span>
                                  </li>
                                  <li>
                                    <hr class="dropdown-divider">
                                  </li>

                                  <li>
                                    <a class="dropdown-item d-flex align-items-center " href="profile">
                                      <i class="bi bi-person"></i>
                                      <span >My Profile</span>
                                    </a>
                                  </li>
                                  <li>
                                    <hr class="dropdown-divider">
                                  </li>

                                  <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#" id="logoutBtn">
                                      <i class="bi bi-box-arrow-right"></i>
                                      <span id="logoutBtn" >Sign Out</span>
                                    </a>
                                  </li>

                                </ul>
                              </li>
                            </ul>
                        </div>
                    </div>
                </nav>

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
