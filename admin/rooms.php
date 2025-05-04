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
                <div class="bg-white rounded-lg shadow-sm overflow-x-hidden">
                    <div class="p-4">
                        <div class="overflow-x-hidden">
                            <table class="min-w-full divide-y divide-gray-200" id="example">
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
                                                    <button class="editRoomBtn px-3 py-1 bg-green-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm flex items-center"
                                                        data-id="<?php echo $room['room_id']; ?>"
                                                        data-number="<?php echo htmlspecialchars($room['room_number']); ?>"
                                                        data-description="<?php echo htmlspecialchars($room['description']); ?>"
                                                        data-price="<?php echo htmlspecialchars($room['price']); ?>"
                                                        data-type="<?php echo htmlspecialchars($room['room_type']); ?>"
                                                        data-status="<?php echo $room['availability']; ?>"
                                                        data-photo="<?php echo htmlspecialchars($room['photo']); ?>"
                                                        data-images='<?php 
                                                            $images = [];
                                                            while($image = $imageResult->fetch_assoc()) {
                                                                $images[] = [
                                                                    'image_id' => $image['image_id'],
                                                                    'image_path' => $image['image_path']
                                                                ];
                                                            }
                                                            echo json_encode($images);
                                                        ?>'>
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

    
<div class="fixed inset-0 bg-gray-500/75" id="modalBackdrop" onclick="closeModal('addRoomModal')"></div>
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
  <label for="additionalImages" class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>

  <div class="w-full">
    
    <label for="additionalImages" 
      class="flex flex-col items-center justify-center px-6 py-10 border-2 border-dashed rounded-md cursor-pointer 
             text-gray-400 hover:border-indigo-500 hover:text-indigo-500 transition-colors duration-200">
             <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
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
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500/75" id="modalBackdrop" onclick="closeModal('editRoomModal')"></div>

    <!-- Modal content -->
    <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Edit Room</h3>
            <button class="text-gray-400 hover:text-gray-500" onclick="closeModal('editRoomModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                        <div
                            class="relative dropzone-style border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 transition-colors duration-200 cursor-pointer">
                            <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium text-indigo-600 hover:text-indigo-500">Click to upload</span>
                                    or drag and drop
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                            </div>
                            <input type="file" name="additional_images[]" multiple accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Images Preview</label>
                        <div class="flex flex-wrap gap-2" id="roomImagesPreview">
                            <!-- ALL images (existing + new) will appear here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
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
document.addEventListener('DOMContentLoaded', function() {
    // Handle backdrop click for all modals
document.querySelectorAll('[id^="modalBackdrop"]').forEach(backdrop => {
    backdrop.addEventListener('click', function(e) {
        if (e.target === this) {
            const openModal = this.closest('.fixed.inset-0');
            if (openModal) {
                closeModal(openModal.id);
            }
        }
    });
});

// Prevent clicks inside modal content from closing it
document.querySelectorAll('#addRoomModal > div, #editRoomModal > div').forEach(modalContent => {
    modalContent.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});

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
            
            // Load room images
            const imagesData = this.getAttribute('data-images');
            if (imagesData) {
                const images = JSON.parse(imagesData);
                displayRoomImages(images);
            }
            
            // Handle additional images preview
            const additionalImagesInput = document.querySelector('#editRoomModal input[name="additional_images[]"]');
            additionalImagesInput.addEventListener('change', function() {
                previewMultipleImages(this, 'roomImagesPreview');
            });
            
            openModal('editRoomModal');
        });
    });

    // Delete room button handler (keep your existing code)
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
            previewMultipleImages(this, 'addRoomImagesPreview');
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
    if (addRoomImagesPreview) {
        addRoomImagesPreview.innerHTML = '<p class="text-gray-500 text-sm italic">No images selected</p>';
    }
    
    // Reset previews when modals are closed
    document.querySelectorAll('[onclick^="closeModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            resetImagePreviews(modalId);
        });
    });
});

// Display existing room images in edit modal
function displayRoomImages(images) {
    const previewContainer = document.getElementById('roomImagesPreview');
    previewContainer.innerHTML = '';
    
    if (!images || images.length === 0) {
        previewContainer.innerHTML = '<p class="text-gray-500 text-sm italic">No images</p>';
        return;
    }
    
    images.forEach(image => {
        const imageDiv = document.createElement('div');
        imageDiv.className = 'relative group';
        imageDiv.innerHTML = `
            <img src="../uploads/rooms/${image.image_path}" class="w-24 h-24 object-cover rounded border border-gray-300">
            <input type="hidden" name="existing_images[]" value="${image.image_id}">
            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                    onclick="removeImage(this, ${image.image_id}, true)">
                <i class="fas fa-times text-xs"></i>
            </button>
        `;
        previewContainer.appendChild(imageDiv);
    });
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        // Reset any form inputs if needed
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
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

// Preview single image (for main photo)
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

// Preview multiple images (for additional images)
function previewMultipleImages(input, previewId) {
    const previewContainer = document.getElementById(previewId);
    
    // Clear "no images" message if it exists
    if (previewContainer.querySelector('p.text-gray-500')) {
        previewContainer.innerHTML = '';
    }
    
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
                            onclick="removeImagePreview(this, '${file.name}', '${previewId}')">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                previewContainer.appendChild(imageDiv);
            };
            
            reader.readAsDataURL(file);
        }
    }
}

// Remove image from preview (for newly added files)
function removeImagePreview(button, fileName, previewId) {
    const imageDiv = button.closest('div.relative');
    imageDiv.remove();
    
    // Remove the file from the input's files array
    const input = document.querySelector(`#${previewId === 'addRoomImagesPreview' ? 'addRoomModal' : 'editRoomModal'} input[name="additional_images[]"]`);
    const files = Array.from(input.files);
    const updatedFiles = files.filter(file => file.name !== fileName);
    
    // Create a new DataTransfer object to update the files
    const dataTransfer = new DataTransfer();
    updatedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
    
    // If no images left, show the message
    const previewContainer = document.getElementById(previewId);
    if (previewContainer.children.length === 0) {
        previewContainer.innerHTML = '<p class="text-gray-500 text-sm italic">No images selected</p>';
    }
}

// Remove existing image (marks for deletion)
function removeImage(button, imageId, isExisting) {
    const imageDiv = button.closest('.relative');
    
    if (isExisting) {
        // For existing images, mark for deletion
        const deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'delete_images[]';
        deleteInput.value = imageId;
        document.querySelector('#editRoomModal form').appendChild(deleteInput);
        
        // Visual feedback
        imageDiv.classList.add('opacity-50', 'border-2', 'border-red-500');
    } else {
        // For new images, just remove from preview
        imageDiv.remove();
    }
    
    // Show "no images" message if empty
    const previewContainer = document.getElementById('roomImagesPreview');
    if (previewContainer.children.length === 0) {
        previewContainer.innerHTML = '<p class="text-gray-500 text-sm italic">No images</p>';
    }
}

function clearImageInput(button) {
    const previewContainer = button.closest('.preview-container');
    const inputField = previewContainer.previousElementSibling;
    
    // Clear the file input
    inputField.value = '';
    previewContainer.remove();
}

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
    } else if (modalId === 'editRoomModal') {
        const roomImagesPreview = document.getElementById('roomImagesPreview');
        if (roomImagesPreview) {
            roomImagesPreview.innerHTML = '<p class="text-gray-500 text-sm italic">No images</p>';
        }
        
        // Clear any single image previews
        const previewContainers = document.querySelectorAll('#editRoomModal .preview-container');
        previewContainers.forEach(container => container.remove());
        
        // Reset file inputs
        const fileInputs = document.querySelectorAll('#editRoomModal input[type="file"]');
        fileInputs.forEach(input => input.value = '');
    }
}


</script>
</body>
</html>