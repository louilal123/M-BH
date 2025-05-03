<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding House Management - Rooms</title>
    <?php include "includes-new/header.php";?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <style>
        .image-thumbnail {
            position: relative;
            margin: 5px;
            display: inline-block;
        }
        .image-thumbnail img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .image-actions {
            position: absolute;
            top: 5px;
            right: 5px;
            display: none;
        }
        .image-thumbnail:hover .image-actions {
            display: block;
        }
        .dropzone {
            border: 2px dashed #ccc;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .dz-preview {
            margin: 5px;
        }
        .dz-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
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
                        <h1 class="text-2xl font-bold text-gray-800">Room Management</h1>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <button onclick="openModal('addRoomModal')" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i> Add Room
                        </button>
                    </div>
                </div>

                <!-- Room Table Card -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="roomsTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room #</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    include('functions/connection.php');
                                    $query = "SELECT * FROM rooms";
                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0):
                                        while($room = $result->fetch_assoc()): 
                                            // Get images for this room
                                            $imageQuery = "SELECT * FROM room_images WHERE room_id = ?";
                                            $imageStmt = $conn->prepare($imageQuery);
                                            $imageStmt->bind_param("i", $room['room_id']);
                                            $imageStmt->execute();
                                            $imageResult = $imageStmt->get_result();
                                            $hasImages = $imageResult->num_rows > 0;
                                            $mainImage = $room['photo'] ?: ($hasImages ? $imageResult->fetch_assoc()['image_path'] : 'default-room.jpg');
                                        ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($room['room_number'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <img src="../uploads/rooms/<?php echo $mainImage; ?>" 
                                                         alt="Room Image" 
                                                         class="h-10 w-10 rounded-full object-cover">
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($room['description'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    ₱<?php echo number_format($room['price'], 2); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($room['room_type'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
    <?php 
        $status = $room['availability'];
        switch ($status) {
            case 0:
                $badgeClass = 'bg-green-100 text-green-800';
                $statusText = 'Available';
                break;
            case 1:
                $badgeClass = 'bg-yellow-100 text-yellow-800';
                $statusText = 'Occupied';
                break;
            case 2:
                $badgeClass = 'bg-red-100 text-red-800';
                $statusText = 'Under Maintenance';
                break;
            default:
                $badgeClass = 'bg-gray-100 text-gray-800';
                $statusText = 'Unknown';
                break;
        }

        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full '.$badgeClass.'">'.$statusText.'</span>';
    ?>
</td>

                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="editRoomBtn px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm flex items-center"
                                                            data-id="<?php echo $room['room_id']; ?>"
                                                            data-number="<?php echo htmlspecialchars($room['room_number']); ?>"
                                                            data-description="<?php echo htmlspecialchars($room['description']); ?>"
                                                            data-price="<?php echo htmlspecialchars($room['price']); ?>"
                                                            data-type="<?php echo htmlspecialchars($room['room_type']); ?>"
                                                            data-status="<?php echo $room['availability']; ?>"
                                                            data-photo="<?php echo htmlspecialchars($room['photo']); ?>">
                                                            <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                                        </button>
                                                      
                                                        <button class="deleteRoomBtn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm flex items-center"
                                                            data-id="<?php echo $room['room_id']; ?>">
                                                            <i class="fas fa-trash-alt mr-1 text-xs"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No rooms found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

<!-- Add Room Modal -->
<div id="addRoomModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('addRoomModal')"></div>
    <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Add New Room</h3>
            <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('addRoomModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="functions/add_room.php" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column - Room Details -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Number</label>
                        <input type="text" name="room_number" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" name="price" step="0.01" min="0" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                        <input type="text" name="room_type" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="availability" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="0">Available</option>
                            <option value="1">Occupied</option>
                            <option value="2">Maintenance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Main Photo</label>
                        <input type="file" name="photo" accept="image/*" 
                               class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <!-- Right Column - Room Images -->
                <div>
                    <!-- File Input styled like Dropzone -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                        
                        <div class="w-full">
                            <label for="additionalImages" 
                                class="flex items-center justify-center px-6 py-10 border-2 border-dashed rounded-md cursor-pointer 
                                    text-gray-400 hover:border-indigo-500 hover:text-indigo-500 transition-colors duration-200">
                                <span class="text-sm">Click or drag images to upload</span>
                                <input 
                                    id="additionalImages"
                                    type="file"
                                    name="additional_images[]" 
                                    multiple 
                                    accept="image/*" 
                                    class="hidden" />
                            </label>
                        </div>
                    </div>

                    <!-- Image Previews -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image Preview <span class="font-italic italic text-gray-500 text-sm">(selected images will appear here)</span></label>
                        <div class="flex flex-wrap gap-2" id="addRoomImagesPreview">
                            <!-- Preview will appear here -->
                        </div>
                    </div>
                </div>


            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal('addRoomModal')" 
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <button type="submit" name="add_room" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Add Room
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Edit Room Modal -->
    <div id="editRoomModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('editRoomModal')"></div>
        <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Edit Room</h3>
                <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('editRoomModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="functions/edit_room.php" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-6">
                <input type="hidden" name="room_id" id="edit_room_id">
                <input type="hidden" name="current_photo" id="edit_current_photo">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column - Room Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Number</label>
                            <input type="text" name="room_number" id="edit_room_number" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="edit_room_description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <input type="number" name="price" id="edit_room_price" step="0.01" min="0" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                            <input type="text" name="room_type" id="edit_room_type" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="availability" id="edit_room_status" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="0">Available</option>
                                <option value="1">Occupied</option>
                                <option value="2">Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Main Photo</label>
                            <img id="current_room_photo" src="" class="h-20 w-20 object-cover mb-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Change Main Photo</label>
                            <input type="file" name="photo" accept="image/*" 
                                   class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    
                    <!-- Right Column - Room Images -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Additional Images</label>
                            <div class="dropzone border-2 border-dashed border-gray-300 rounded-lg" id="roomImagesDropzone">
                                <div class="dz-message text-gray-500">
                                    Drop files here or click to upload<br>
                                    <span class="text-xs">(Max 5MB per image)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                            <div class="flex flex-wrap gap-2" id="roomImagesContainer">
                                <!-- Images will be loaded here via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('editRoomModal')" 
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" name="edit_room" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include "includes-new/footer.php" ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
   <script>

// Initialize Dropzone
Dropzone.autoDiscover = false;
let roomImagesDropzone;

document.addEventListener('DOMContentLoaded', function() {
    // Edit button click handler
    document.querySelectorAll('.editRoomBtn').forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-id');
            document.getElementById('edit_room_id').value = roomId;
            document.getElementById('edit_room_number').value = this.getAttribute('data-number');
            document.getElementById('edit_room_description').value = this.getAttribute('data-description');
            document.getElementById('edit_room_price').value = this.getAttribute('data-price');
            document.getElementById('edit_room_type').value = this.getAttribute('data-type');
            document.getElementById('edit_room_status').value = this.getAttribute('data-status');
            
            // Set current photo preview
            const photoPath = this.getAttribute('data-photo');
            const photoPreview = document.getElementById('current_room_photo');
            document.getElementById('edit_current_photo').value = photoPath;
            
            if (photoPath) {
                photoPreview.src = '../uploads/rooms/' + photoPath;
            } else {
                photoPreview.src = '../uploads/rooms/default-room.jpg';
            }
            
            // Load room images and initialize dropzone
            loadRoomImages(roomId);
            initializeDropzone(roomId);
            
            openModal('editRoomModal');
        });
    });

    // Delete button click handler
    document.querySelectorAll('.deleteRoomBtn').forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This will also delete all images associated with this room!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `functions/delete_room.php?id=${roomId}`;
                }
            });
        });
    });
    
    // Handle main photo preview in Add Room modal
    const mainPhotoInput = document.querySelector('#addRoomModal input[name="photo"]');
    if (mainPhotoInput) {
        mainPhotoInput.addEventListener('change', function() {
            previewSingleImage(this);
        });
    }
    
    // Handle additional images preview in Add Room modal
    const additionalImagesInput = document.querySelector('#addRoomModal input[name="additional_images[]"]');
    if (additionalImagesInput) {
        additionalImagesInput.addEventListener('change', function() {
            previewMultipleImages(this);
        });
    }
    
    // Handle file inputs in the Edit Room modal
    const editMainPhotoInput = document.querySelector('#editRoomModal input[name="photo"]');
    if (editMainPhotoInput) {
        editMainPhotoInput.addEventListener('change', function() {
            previewSingleImage(this);
        });
    }
    
    // Initialize the Add Room modal image preview as empty
    const addRoomImagesPreview = document.getElementById('addRoomImagesPreview');
 
    
    // Reset previews when modals are closed
    document.querySelectorAll('[onclick^="closeModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            resetImagePreviews(modalId);
        });
    });
});

function loadRoomImages(roomId) {
    fetch(`functions/get_room_images.php?room_id=${roomId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('roomImagesContainer');
            container.innerHTML = '';
            
            if (data.error) {
                container.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                return;
            }
            
            if (data.length === 0) {
                container.innerHTML = '<p class="text-gray-500">No additional images found for this room.</p>';
                return;
            }
            
            data.forEach(image => {
                const imageElement = `
                    <div class="image-thumbnail relative">
                        <img src="../uploads/rooms/${image.image_path}" alt="Room Image" class="w-24 h-24 object-cover rounded">
                        <div class="image-actions">
                            <button onclick="deleteImage(${image.image_id}, ${roomId})" 
                                    class="p-1 bg-red-600 text-white rounded-full text-xs hover:bg-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', imageElement);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            const container = document.getElementById('roomImagesContainer');
            container.innerHTML = '<p class="text-red-500">Error loading images</p>';
        });
}

function initializeDropzone(roomId) {
    // Destroy previous instance if exists
    if (roomImagesDropzone) {
        roomImagesDropzone.destroy();
    }
    
    roomImagesDropzone = new Dropzone("#roomImagesDropzone", {
        url: "functions/upload_room_images.php",
        paramName: "file",
        maxFilesize: 5, // MB
        acceptedFiles: "image/*",
        addRemoveLinks: false,
        autoProcessQueue: true,
        parallelUploads: 5,
        params: {
            room_id: roomId
        },
        init: function() {
            this.on("success", function(file, response) {
                // Reload images after upload
                loadRoomImages(roomId);
                // Remove the preview
                this.removeFile(file);
            });
            this.on("error", function(file, message) {
                if (message.error) {
                    Swal.fire('Error', message.error, 'error');
                } else {
                    Swal.fire('Error', 'Error uploading image', 'error');
                }
            });
        }
    });
}

function deleteImage(imageId, roomId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('functions/delete_room_image.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `image_id=${imageId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadRoomImages(roomId);
                    Swal.fire('Deleted!', 'Image has been deleted.', 'success');
                } else {
                    Swal.fire('Error', data.error || 'Error deleting image', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error deleting image', 'error');
            });
        }
    });
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    
    // Reset dropzone when closing modal
    if (modalId === 'editRoomModal' && roomImagesDropzone) {
        roomImagesDropzone.removeAllFiles(true);
    }
}

// Function to preview single image (main photo)
function previewSingleImage(input) {
    const previewContainer = document.createElement('div');
    previewContainer.classList.add('mt-2');
    
    // Remove any existing preview
    const existingPreview = input.parentElement.querySelector('.preview-container');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewContainer.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" alt="Preview" class="w-32 h-32 object-cover rounded border border-gray-300">
                    <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center" 
                            onclick="clearImageInput(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            previewContainer.classList.add('preview-container');
            input.parentElement.appendChild(previewContainer);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Update the previewMultipleImages function in your JS
function previewMultipleImages(input) {
    const previewContainer = document.getElementById('addRoomImagesPreview');
    
  
    
    if (input.files && input.files.length > 0) {
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imageDiv = document.createElement('div');
                imageDiv.className = 'relative group';
                imageDiv.innerHTML = `
                    <img src="${e.target.result}" alt="Image ${i+1}" class="w-24 h-24 object-cover rounded border border-gray-300">
                    <span class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs px-1 truncate">
                        ${file.name}
                    </span>
                    <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                            onclick="removeImagePreview(this, '${file.name}')">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                previewContainer.appendChild(imageDiv);
            };
            
            reader.readAsDataURL(file);
        }
    }
    
  
}



// Function to reset image previews when modal is closed
function resetImagePreviews(modalId) {
    if (modalId === 'addRoomModal') {
        const addRoomImagesPreview = document.getElementById('addRoomImagesPreview');
        if (addRoomImagesPreview) {
            addRoomImagesPreview.innerHTML = '<p class="text-gray-500 text-sm italic">No images selected</p>';
        }
        
        // Clear any single image previews
        const previewContainers = document.querySelectorAll('#addRoomModal .preview-container');
        previewContainers.forEach(container => container.remove());
        
        // Reset file inputs
        const fileInputs = document.querySelectorAll('#addRoomModal input[type="file"]');
        fileInputs.forEach(input => input.value = '');
    }
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
        const modals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
        modals.forEach(modal => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    }
});
// Add this to your existing JS
function removeImagePreview(button, fileName) {
    const imageDiv = button.closest('div.relative');
    imageDiv.remove();
    
    // Remove the file from the input's files array
    const input = document.querySelector('#addRoomModal input[name="additional_images[]"]');
    const files = Array.from(input.files);
    const updatedFiles = files.filter(file => file.name !== fileName);
    
    // Create a new DataTransfer object to update the files
    const dataTransfer = new DataTransfer();
    updatedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
    
   
}
   </script>
</body>
</html>