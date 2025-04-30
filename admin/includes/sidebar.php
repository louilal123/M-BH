<style>
  /* Sidebar styles */
  
  #sidebar {
    
    min-height: 100vh;
    width: 250px;
    color: white;
    padding: 1rem 0;
  }

  #sidebar .nav-link {
    color: #adb5bd;
    padding: 0.5rem 1rem;
    
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  #sidebar .nav-link:hover {
    background-color: #495057;
    color: #ffffff;
  }

  #sidebar .nav-link .fas {
    margin-right: 10px;
    font-size: 1.2rem;
    margin-left: 0.5rem;
  }

  #sidebar .nav-item.active .nav-link {
    background-color: #007bff;
    color: white;
  }

  #sidebar h4 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
  }

  #sidebar .nav-item {
    margin-bottom: 0.5rem;
  }
  

  /* Responsive adjustments */
  @media (max-width: 768px) {
    #sidebar {
      width: 100%;
    }
  }
</style>
<?php
$currentPage = basename($_SERVER['PHP_SELF'], ".php");
?>
<nav id="sidebar" class="d-flex flex-column" role="navigation">
  <div class="p-4 text-center mb-2">
    <h2 class="text-white fw-bold">MBHMS</h2>
  </div>
  <ul class="nav flex-column">
    <!-- Dashboard -->
    <li class="nav-item <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
      <a class="nav-link <?php echo $currentPage === 'dashboard' ? 'bg-primary' : ''; ?>" href="dashboard">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
    </li>

       <!-- Tenants -->
       <li class="nav-item <?php echo $currentPage === 'tenants' ? 'active' : ''; ?>">
      <a class="nav-link <?php echo $currentPage === 'tenants' ? 'bg-primary' : ''; ?>" href="tenants">
        <i class="fas fa-users"></i> Tenants
      </a>
    </li>
    <!-- Rooms -->
    <li class="nav-item <?php echo $currentPage === 'rooms' ? 'active' : ''; ?>">
      <a class="nav-link <?php echo $currentPage === 'rooms' ? 'bg-primary' : ''; ?>" href="rooms">
        <i class="fas fa-home"></i> Rooms
      </a>
    </li>
    <!-- Room Types -->
    <li class="nav-item <?php echo $currentPage === 'room_types' ? 'active' : ''; ?>">
      <a class="nav-link <?php echo $currentPage === 'room_types' ? 'bg-primary' : ''; ?>" href="room_types">
        <i class="fas fa-cube"></i> Room Types
      </a>
    </li>
 
    <!-- Bookings -->
    <li class="nav-item <?php echo $currentPage === 'room_assignment' ? 'active' : ''; ?>">
      <a class="nav-link <?php echo $currentPage === 'room_assignment' ? 'bg-primary' : ''; ?>" href="room_assignment">
        <i class="fas fa-book"></i> Room Assignments
      </a>
    </li>
    <!-- Payments -->
    <li class="nav-item <?php echo $currentPage === 'payments' ? 'active' : ''; ?>">
      <a class="nav-link <?php echo $currentPage === 'payments' ? 'bg-primary' : ''; ?>" href="payments">
        <i class="fas fa-edit"></i> Payments
      </a>
    </li>
    <!-- Reports -->
    <li class="nav-item <?php echo $currentPage === 'reports' ? 'active' : ''; ?>">
      <a class="nav-link <?php echo $currentPage === 'reports' ? 'bg-primary' : ''; ?>" href="reports">
        <i class="fas fa-chart-bar"></i> Reports
      </a>
    </li>
    <!-- System Users -->
   

    <li class="nav-item <?php echo $currentPage === 'users' ? 'active' : ''; ?>">
      <a class="nav-link <?php echo $currentPage === 'users' ? 'bg-primary' : ''; ?>" href="users">
        <i class="fas fa-user-cog"></i> System Users
      </a>
    </li>

  
  </ul>
</nav>
