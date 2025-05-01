<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding House Management</title>
    <?php include "includes-new/header.php";?>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <?php include "includes-new/sidebar.php" ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

        <?php include "includes-new/topnav.php" ?>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <!-- Dashboard Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Tenant Management</h1>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
   
 
</div>

                </div>

                <!-- Tenant Table Card -->
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
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    include('functions/connection.php');
                                    $query = "SELECT tenant_id, name, email, contact, address, created_at, status, photo FROM tenants";
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
                                                            src="uploads/tenants/<?php echo htmlspecialchars($tenant['photo'], ENT_QUOTES, 'UTF-8'); ?>" 
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
                                                    <?php echo htmlspecialchars($tenant['contact'], ENT_QUOTES, 'UTF-8'); ?>
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
                                                    <button class="editTenantBtn px-3 py-1 bg-green-800 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm flex items-center"
  data-id="<?php echo $tenant['tenant_id']; ?>"
  data-name="<?php echo htmlspecialchars($tenant['name']); ?>"
  data-email="<?php echo htmlspecialchars($tenant['email']); ?>"
  data-contact="<?php echo htmlspecialchars($tenant['contact']); ?>"
  data-address="<?php echo htmlspecialchars($tenant['address']); ?>"
  data-status="<?php echo htmlspecialchars($tenant['status']); ?>"
  data-photo="<?php echo htmlspecialchars($tenant['photo']); ?>">
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

<!-- Edit Tenant Modal -->
<div id="editTenantModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
  <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('editTenantModal')"></div>
  <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
    <div class="flex justify-between items-center px-6 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Edit Tenant</h3>
      <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('editTenantModal')">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form action="functions/edit_tenant.php" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-6">
      <input type="hidden" name="tenant_id" id="tenant_id">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Full Name<input type="text" id="name" name="name" required oninput="validateAlphanumeric(this)" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></label></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Email<input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></label></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone<input type="text" id="contact" name="contact" required oninput="validatePhone(this)" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></label></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Status<select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><option value="active">Active</option><option value="inactive">Inactive</option></select></label></div>
        <div class="col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Address<input type="text" id="address" name="address" required oninput="validateAlphanumeric(this)" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></label></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Current Photo<div class="mt-1"><img id="currentPhotoPreview" src="uploads/tenants/default.png" alt="Photo" class="h-24 w-24 rounded-md object-cover border border-gray-200"></div></label></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Change Photo<input type="file" id="photo" name="photo" accept="image/*" class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><p class="mt-1 text-xs text-gray-500">JPEG, PNG or GIF. Max 2MB.</p></label></div>
      </div>
      <div class="flex justify-end space-x-3 mt-6 px-2">
        <button type="button" onclick="closeModal('editTenantModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
        <button type="submit" name="save_tenant" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Save Changes</button>
      </div>
    </form>
  </div>
</div>


    <?php include "includes-new/footer.php" ?>
    <script>
// Edit button click handlers (should be in your table row generation code)
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.editTenantBtn').forEach(button => {
    button.addEventListener('click', function() {
      // Get data attributes
      const tenantId = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      const email = this.getAttribute('data-email');
      const contact = this.getAttribute('data-contact');
      const address = this.getAttribute('data-address');
      const status = this.getAttribute('data-status');
      const photo = this.getAttribute('data-photo');
      
      // Populate form fields
      document.getElementById('tenant_id').value = tenantId;
      document.getElementById('name').value = name;
      document.getElementById('email').value = email;
      document.getElementById('contact').value = contact;
      document.getElementById('address').value = address;
      document.getElementById('status').value = status;
      
      // Set photo preview
      const photoPreview = document.getElementById('currentPhotoPreview');
      if (photo) {
        photoPreview.src = 'uploads/tenants/' + photo;
      } else {
        photoPreview.src = 'uploads/tenants/default.png';
      }
      
      openModal('editTenantModal');
    });
  });
});

// Modal control functions (should be shared with add modal)
function openModal(modalId) {
  document.getElementById(modalId).classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
  document.getElementById(modalId).classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
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
</script>
    <script>
        // Initialize DataTable with Tailwind styling
        $(document).ready(function() {
         


            // Delete confirmation
            $('.deleteTenantBtn').on('click', function(e) {
                e.preventDefault();
                const tenantId = $(this).data('id');

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

            // Validation functions
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

            function validatePhoneNumber(input) {
                input.value = input.value.replace(/[^0-9]/g, '').slice(0, 11);
            }

            function disableFutureDates(input) {
                const today = new Date().toISOString().split('T')[0];
                if (input.value > today) {
                    input.value = today;
                    alert('Future dates are not allowed.');
                }
            }
        });
    </script></body>
</html>