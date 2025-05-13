<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding House Management</title>
    <?php include "includes-new/header.php";?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <?php include "includes-new/sidebar.php" ?>
        
        <div class="flex-1 flex flex-col overflow-hidden">
        <?php include "includes-new/topnav.php" ?>

            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Tenant Management</h1>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="overflow-x-hidden">
                            <table class="min-w-full divide-y divide-gray-200" id="example">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupation</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    include('functions/connection.php');
                                    $query = "
                                    SELECT t.tenant_id, t.name, t.email, t.phone, t.occupation, t.address, t.photo, t.status, t.created_at,
                                           lh.login_time, lh.ip_address, lh.user_agent
                                    FROM tenants t
                                    LEFT JOIN (
                                      SELECT tenant_id, login_time, ip_address, user_agent
                                      FROM login_history
                                      WHERE login_status = 'success'
                                      ORDER BY login_time DESC
                                    ) lh ON t.tenant_id = lh.tenant_id
                                    GROUP BY t.tenant_id
                                  ";
                                   $result = $conn->query($query);

                                    if ($result->num_rows > 0):
                                        $tenants = $result->fetch_all(MYSQLI_ASSOC);
                                        foreach ($tenants as $tenant): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($tenant['tenant_id'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <?php if (!empty($tenant['photo'])): ?>
                                                        <img 
                                                            src="../uploads/tenants/<?php echo htmlspecialchars($tenant['photo'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                            alt="Tenant Photo" 
                                                            class="h-10 w-10 rounded-full object-cover"
                                                        >
                                                    <?php else: ?>
                                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                            <i class="fas fa-user text-gray-400"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($tenant['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($tenant['address'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($tenant['email'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($tenant['phone'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($tenant['occupation'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <?php 
                                                    $status = htmlspecialchars($tenant['status'], ENT_QUOTES, 'UTF-8'); 
                                                    if ($status === 'active') {
                                                        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>';
                                                    } else {
                                                        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>';
                                                    } 
                                                    ?>
                                                </td>
                                                
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                   
                                                    <button 
    class="editTenantBtn px-3 py-1 bg-indigo-700 text-white  rounded hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm flex items-center"
    data-id="<?php echo $tenant['tenant_id']; ?>"
    data-name="<?php echo htmlspecialchars($tenant['name']); ?>"
    data-email="<?php echo htmlspecialchars($tenant['email']); ?>"
    data-phone="<?php echo htmlspecialchars($tenant['phone']); ?>"
    data-occupation="<?php echo htmlspecialchars($tenant['occupation']); ?>"
    data-address="<?php echo htmlspecialchars($tenant['address']); ?>"
    data-date_created="<?php echo htmlspecialchars($tenant['created_at'] ?? ''); ?>"
    data-status="<?php echo htmlspecialchars($tenant['status']); ?>"
    data-photo="<?php echo htmlspecialchars($tenant['photo']); ?>"
    data-login_time="<?php echo htmlspecialchars($tenant['login_time'] ?? ''); ?>"
    data-login_ip="<?php echo htmlspecialchars($tenant['ip_address'] ?? ''); ?>"
    data-user_agent="<?php echo htmlspecialchars($tenant['user_agent'] ?? ''); ?>"
>
    <i class="fas fa-edit mr-1 text-xs"></i> Edit
</button>

                                                        <button 
                                                            type="button" 
                                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm flex items-center deleteTenantBtn"
                                                            data-id="<?php echo $tenant['tenant_id']; ?>"
                                                        >
                                                            <i class="fas fa-trash-alt mr-1 text-xs"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div id="editTenantModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('editTenantModal')"></div>
    <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl">
        <div class="flex justify-between items-center px-4 py-3 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Tenant Details</h3>
        <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('editTenantModal')">
            <i class="fas fa-times"></i>
        </button>
        </div>
        
        <form action="functions/edit_tenant.php" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-8">
            <input type="hidden" name="tenant_id" id="tenant_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col items-center">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="mt-1 flex justify-center">
                            <img id="currentPhotoPreview" alt="Tenant Photo" class="h-52 w-52 rounded-md object-cover border border-gray-200 shadow">
                        </div>
                    </label>
                    <div class="mt-2 text-sm text-gray-600">
                        Joined: <span class="font-medium" id="date_created"></span>
                    </div>
                </div>
                    
                <!-- First Column of Details -->
                <div class="grid grid-cols-1 gap-4 md:mt-0 mt-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                    Full Name
                    <input type="text" id="name" name="name" required readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 shadow-sm">
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                    Email
                    <input type="email" id="email" name="email" required readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 shadow-sm">
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                    Phone
                    <input type="text" id="phone" name="phone" required readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 shadow-sm">
                    </label>
                </div>
                </div>
                
                <!-- Second row with Occupation and Address across full width -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                    Occupation
                    <input type="text" id="occupation" name="occupation" readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 shadow-sm">
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                    Address
                    <input type="text" id="address" name="address" required readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 shadow-sm">
                    </label>
                </div>
                </div>
            </div>
            <!-- Login Info -->
            <div class="mt-5 pt-4 border-t border-gray-200">
            <h4 class="text-sm font-semibold text-gray-800 mb-3">Last Login Info</h4>
            <table class="min-w-full text-sm text-gray-700">
                <thead>
                <tr>
                    <th class="px-4 py-2 text-left font-medium">Field</th>
                    <th class="px-4 py-2 text-left font-medium">Info</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium">Login Time:</td>
                    <td id="lastLoginTime" class="px-4 py-2 text-gray-600">—</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium">IP Address:</td>
                    <td id="lastLoginIP" class="px-4 py-2 text-gray-600">—</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium">Device Info:</td>
                    <td id="lastLoginDevice" class="px-4 py-2 text-gray-600">—</td>
                </tr>
                </tbody>
            </table>
            </div>
            <!-- Status Update -->
            <div class="mt-5 pt-4 border-t border-gray-200">
            <div class="w-48">
                <label for="status" class="block text-sm font-medium text-gray-700">
                Status
                </label>
                <select id="status" name="status" required
                class="w-full mt-1 px-3 py-2 border border-indigo-500 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                </select>
            </div>
            </div>

            <!-- Buttons at the bottom -->
            <div class="mt-6 flex justify-end space-x-3">
            <button type="button" onclick="closeModal('editTenantModal')"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit" name="save_tenant"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                Update Status
            </button>
            </div>
             </div>
        </form>
  </div>
</div>

    <?php include "includes-new/footer.php" ?>
    <script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.editTenantBtn').forEach(button => {
    button.addEventListener('click', function() {
      const tenantId = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      const email = this.getAttribute('data-email');
      const phone = this.getAttribute('data-phone');
      const occupation = this.getAttribute('data-occupation');
      const address = this.getAttribute('data-address');
      const date_created = this.getAttribute('data-date_created');
      const status = this.getAttribute('data-status');
      const photo = this.getAttribute('data-photo');
      const loginTime = this.getAttribute('data-login_time');
const loginIP = this.getAttribute('data-login_ip');
const userAgent = this.getAttribute('data-user_agent');

let formattedDate = date_created;
if (date_created) {
    const dateObj = new Date(date_created);
    formattedDate = dateObj.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

let formattedLoginTime = loginTime;
if (loginTime) {
    const loginDateObj = new Date(loginTime);
    formattedLoginTime = loginDateObj.toLocaleString('en-US', {
        weekday: 'long', // "Monday"
        year: 'numeric', // "2025"
        month: 'long', // "May"
        day: 'numeric', // "4"
        hour: '2-digit', // "11"
        minute: '2-digit', // "45"
        second: '2-digit', // "30"
        hour12: true // AM/PM format
    });
}

document.getElementById('lastLoginTime').textContent = formattedLoginTime || 'N/A';

      document.getElementById('lastLoginIP').textContent = loginIP || 'N/A';
      document.getElementById('lastLoginDevice').textContent = userAgent || 'N/A';

      document.getElementById('tenant_id').value = tenantId;
      document.getElementById('name').value = name;
      document.getElementById('email').value = email;
      document.getElementById('phone').value = phone;
      document.getElementById('occupation').value = occupation || '';
      document.getElementById('address').value = address;
      document.getElementById('date_created').textContent = formattedDate || 'N/A';
      document.getElementById('status').value = status;
      
      const photoPreview = document.getElementById('currentPhotoPreview');
      if (photo) {
        photoPreview.src = '../uploads/tenants/' + photo;
      } else {
        photoPreview.src = 'uploads/tenants/default.png';
      }
      
      openModal('editTenantModal');
    });
  });

});

function openModal(modalId) {
  document.getElementById(modalId).classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
  document.getElementById(modalId).classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
}

window.addEventListener('click', function(event) {
  if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
    const modals = document.querySelectorAll('.fixed.inset-0.hidden');
    modals.forEach(modal => {
      if (!modal.classList.contains('hidden')) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    });
  }
});

document.querySelectorAll('.deleteTenantBtn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const tenantId = this.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `functions/delete_tenant.php?id=${tenantId}`;
            }
        });
    });
});

function validateName(input) {
    input.value = input.value.replace(/[^A-Za-z\s]/g, '');
}

function validateNumeric(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}

function validateAlphanumeric(input) {
    input.value = input.value.replace(/[^A-Za-z0-9\s]/g, '');
}

function validateSpecialCharacters(input) {
    input.value = input.value.replace(/[A-Za-z0-9]/g, '');
}

function validatePhone(input) {
    input.value = input.value.replace(/[^0-9]/g, '').slice(0, 11);
}

function disableFutureDates(input) {
    const today = new Date().toISOString().split('T')[0];
    if (input.value > today) {
        input.value = today;
        alert('Future dates are not allowed.');
    }
}
</script>
</body>
</html>