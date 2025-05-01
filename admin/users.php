<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding House Management</title>
    <?php include "includes-new/header.php";?>
</head>
<body class="bg-gray-50 text-gray-800 ">
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
                        <h1 class="text-2xl font-bold text-gray-800">Admin Management</h1>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <button 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center"
                            onclick="openModal('addAdminModal')"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            <span>Add New</span>
                        </button>
                    </div>
                </div>

                <!-- Admin Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="overflow-x-hidden">
                            <table class="min-w-full divide-y divide-gray-200" id="adminTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    include('functions/connection.php');
                                    $query = "SELECT * FROM admin WHERE role='admin'";
                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0):
                                        $admins = $result->fetch_all(MYSQLI_ASSOC);
                                        foreach ($admins as $admin): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <img src="uploads/<?php echo htmlspecialchars($admin['photo']); ?>" 
                                                         alt="Admin Photo" 
                                                         class="h-10 w-10 rounded-full object-cover">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?php echo htmlspecialchars($admin['fullname']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($admin['email']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($admin['username']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        <?php echo $admin['role'] === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'; ?>">
                                                        <?php echo htmlspecialchars($admin['role']); ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="editAdminBtn px-3 py-1 bg-green-700 text-white rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm flex items-center"
                                                            data-id="<?php echo $admin['admin_id']; ?>"
                                                            data-email="<?php echo htmlspecialchars($admin['email']); ?>"
                                                            data-username="<?php echo htmlspecialchars($admin['username']); ?>"
                                                            data-fullname="<?php echo htmlspecialchars($admin['fullname']); ?>"
                                                            data-role="<?php echo htmlspecialchars($admin['role']); ?>"
                                                            data-photo="<?php echo htmlspecialchars($admin['photo']); ?>">
                                                            <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                                        </button>
                                                        <button class="deleteAdminBtn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm flex items-center"
                                                        data-id="<?php echo htmlspecialchars($admin['admin_id']); ?>"
                                                        >
                                                            <i class="fas fa-trash-alt mr-1 text-xs"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div id="addAdminModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('addAdminModal')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Add New Admin</h3>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('addAdminModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="functions/add_admin.php" enctype="multipart/form-data" class="bg-gray-50 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email
                            <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username
                            <input type="text" name="username" required oninput="validateAlphanumeric(this)" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name
                            <input type="text" name="fullname" required oninput="validateAlphanumeric(this)" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role
                            <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Photo
                            <input type="file" name="photo" required class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password
                            <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 px-2">
                    <button type="button" onclick="closeModal('addAdminModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Add Admin</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editAdminModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('editAdminModal')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Edit Admin</h3>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('editAdminModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="functions/update_admin.php" enctype="multipart/form-data" class="bg-gray-50 p-6">
                <input type="hidden" name="adminId" id="editAdminId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email
                            <input type="email" name="email" id="editEmail" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username
                            <input type="text" name="username" id="editUsername" required oninput="validateAlphanumeric(this)" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name
                            <input type="text" name="fullname" id="editFullname" required oninput="validateAlphanumeric(this)" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role
                            <select name="role" id="editRole" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Photo (Leave empty to keep current)
                            <input type="file" name="photo" id="editPhoto" class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 px-2">
                    <button type="button" onclick="closeModal('editAdminModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Update</button>
                </div>
            </form>
        </div>
    </div>
    <?php include "includes-new/footer.php" ?>
    <script>
    // Modal control functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        $('#adminTable').DataTable({
            responsive: true,
            lengthMenu: [10, 25, 50, 100]
        });

        // Edit admin button handler
        document.querySelectorAll('.editAdminBtn').forEach(button => {
            button.addEventListener('click', function() {
                const data = {
                    id: this.getAttribute('data-id'),
                    email: this.getAttribute('data-email'),
                    username: this.getAttribute('data-username'),
                    fullname: this.getAttribute('data-fullname'),
                    role: this.getAttribute('data-role'),
                    photo: this.getAttribute('data-photo')
                };
                
                // Populate edit form
                document.getElementById('editAdminId').value = data.id;
                document.getElementById('editEmail').value = data.email;
                document.getElementById('editUsername').value = data.username;
                document.getElementById('editFullname').value = data.fullname;
                document.getElementById('editRole').value = data.role;
                
                openModal('editAdminModal');
            });
        });

        // Delete admin button handler
        document.querySelectorAll('.deleteAdminBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const adminId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure you want to delete this admin?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `functions/delete_admin.php?id=${adminId}`;
                    }
                });
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
                document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });
    });

    // Validation functions
    function validateAlphanumeric(input) {
        input.value = input.value.replace(/[^A-Za-z0-9\s]/g, '');
    }
    </script>
</body>
</html>