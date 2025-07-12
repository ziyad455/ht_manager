
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../core/guist.php");
    exit();
}?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../helper/css/admin.css">
  <style>
    .error-message {
      color: #dc2626;
      font-size: 0.875rem;
      margin-top: 0.25rem;
      display: block;
    }
    
    .input-error {
      border-color: #dc2626 !important;
      box-shadow: 0 0 0 1px #dc2626 !important;
    }
    
    .file-upload-area {
      border: 2px dashed #d1d5db;
      border-radius: 0.5rem;
      padding: 2rem;
      text-align: center;
      cursor: pointer;
      transition: border-color 0.3s;
    }
    
    .file-upload-area:hover {
      border-color: #3b82f6;
    }
    
    .file-upload-area.error {
      border-color: #dc2626;
    }
    
    .room-image-preview {
      max-width: 200px;
      max-height: 150px;
      object-fit: cover;
      border-radius: 0.5rem;
    }
    
    .btn-primary {
      background-color: #3b82f6;
      color: white;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
      font-weight: 500;
    }
    
    .btn-danger {
      background-color: #dc2626;
      color: white;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
      font-weight: 500;
    }
    
    .admin-card {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .page-section {
      padding: 1.5rem;
    }
    
    .admin-card {
      max-width: 1200px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <section id="add-room" class="page-section p-6">
    <div class="admin-card p-6">
      <h3 class="text-xl font-bold text-gray-900 mb-6">Add New Room</h3>
      
      <form id="add-room-form" action="../../backend/controller/admin/addControler.php" class="space-y-6" enctype="multipart/form-data" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Room Number</label>
            <input type="text" id="room-number" name="room-number" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., 101">
            <span id="room-number-error" class="error-message" style="display: none;"></span>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
            <select id="room-type" name="room-type" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">Select Type</option>
              <option value="simple">Simple</option>
              <option value="double">Double</option>
              <option value="suite">Suite</option>
              <option value="familiale">Familiale</option>
            </select>
            <span id="room-type-error" class="error-message" style="display: none;"></span>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Room Size</label>
            <select id="room-size" name="room-size" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">Select Size</option>
              <option value="petite">Petite</option>
              <option value="moyenne">Moyenne</option>
              <option value="grande">Grande</option>
            </select>
            <span id="room-size-error" class="error-message" style="display: none;"></span>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Price per Night ($)</label>
            <input type="number" id="room-price" name="room-price" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="300">
            <span id="room-price-error" class="error-message" style="display: none;"></span>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Room Description</label>
          <textarea id="room-description" name="room-description" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Describe the room features and amenities..."></textarea>
          <span id="room-description-error" class="error-message" style="display: none;"></span>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Room Photo</label>
          <div class="file-upload-area" id="file-upload-area">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
            <p class="text-lg font-medium text-gray-700 mb-2">Drag & drop room photo here</p>
            <p class="text-sm text-gray-500 mb-4">or click to browse files</p>
            <input type="file" id="room-photo" name="room-photo" accept="image/*" class="hidden">
            <button type="button" class="btn-primary" onclick="document.getElementById('room-photo').click()">
              Choose File
            </button>
          </div>
          <span id="room-photo-error" class="error-message" style="display: none;"></span>
          <div id="image-preview" class="mt-4 hidden">
            <img id="preview-img" class="room-image-preview">
            <p id="file-name" class="text-sm text-gray-600 mt-2"></p>
          </div>
        </div>
        
        <div class="flex gap-4">
          <button type="submit" class="btn-primary">
            <i class="fas fa-save mr-2"></i>Add Room
          </button>
          <button type="button" class="btn-danger" onclick="resetForm()">
            <i class="fas fa-times mr-2"></i>Cancel
          </button>
        </div>
      </form>
    </div>
  </section>

  <script>
    // Validation functions
    function showError(fieldId, message) {
      const field = document.getElementById(fieldId);
      const errorElement = document.getElementById(fieldId + '-error');
      
      field.classList.add('input-error');
      errorElement.textContent = message;
      errorElement.style.display = 'block';
      
      if (fieldId === 'room-photo') {
        document.getElementById('file-upload-area').classList.add('error');
      }
    }

    function clearError(fieldId) {
      const field = document.getElementById(fieldId);
      const errorElement = document.getElementById(fieldId + '-error');
      
      field.classList.remove('input-error');
      errorElement.style.display = 'none';
      
      if (fieldId === 'room-photo') {
        document.getElementById('file-upload-area').classList.remove('error');
      }
    }

    function clearAllErrors() {
      const fields = ['room-number', 'room-type', 'room-size', 'room-price', 'room-description', 'room-photo'];
      fields.forEach(fieldId => {
        clearError(fieldId);
      });
    }

    function validateRoomNumber() {
      const roomNumber = document.getElementById('room-number').value.trim();
      
      if (!roomNumber) {
        showError('room-number', 'Room number is required');
        return false;
      }
      
      if (roomNumber.length < 2) {
        showError('room-number', 'Room number must be at least 2 characters');
        return false;
      }
      
      clearError('room-number');
      return true;
    }

    function validateRoomType() {
      const roomType = document.getElementById('room-type').value;
      
      if (!roomType) {
        showError('room-type', 'Please select a room type');
        return false;
      }
      
      clearError('room-type');
      return true;
    }

    function validateRoomSize() {
      const roomSize = document.getElementById('room-size').value;
      
      if (!roomSize) {
        showError('room-size', 'Please select a room size');
        return false;
      }
      
      clearError('room-size');
      return true;
    }

    function validateRoomPrice() {
      const roomPrice = document.getElementById('room-price').value;
      
      if (!roomPrice) {
        showError('room-price', 'Price is required');
        return false;
      }
      
      if (isNaN(roomPrice) || parseFloat(roomPrice) <= 0) {
        showError('room-price', 'Please enter a valid price greater than 0');
        return false;
      }
      
      if (parseFloat(roomPrice) > 10000) {
        showError('room-price', 'Price cannot exceed $10,000 per night');
        return false;
      }
      
      clearError('room-price');
      return true;
    }

    function validateRoomDescription() {
      const roomDescription = document.getElementById('room-description').value.trim();
      
      if (!roomDescription) {
        showError('room-description', 'Room description is required');
        return false;
      }
      
      if (roomDescription.length < 10) {
        showError('room-description', 'Description must be at least 10 characters');
        return false;
      }
      
      if (roomDescription.length > 500) {
        showError('room-description', 'Description cannot exceed 500 characters');
        return false;
      }
      
      clearError('room-description');
      return true;
    }

    function validateRoomPhoto() {
      const roomPhoto = document.getElementById('room-photo').files[0];
      
      if (!roomPhoto) {
        showError('room-photo', 'Room photo is required');
        return false;
      }
      
      // Check file type
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
      if (!allowedTypes.includes(roomPhoto.type)) {
        showError('room-photo', 'Please select a valid image file (JPEG, PNG, GIF, WebP)');
        return false;
      }
      
      // Check file size (5MB limit)
      if (roomPhoto.size > 5 * 1024 * 1024) {
        showError('room-photo', 'Image size cannot exceed 5MB');
        return false;
      }
      
      clearError('room-photo');
      return true;
    }

    // Real-time validation
    document.getElementById('room-number').addEventListener('blur', validateRoomNumber);
    document.getElementById('room-type').addEventListener('change', validateRoomType);
    document.getElementById('room-size').addEventListener('change', validateRoomSize);
    document.getElementById('room-price').addEventListener('blur', validateRoomPrice);
    document.getElementById('room-description').addEventListener('blur', validateRoomDescription);
    document.getElementById('room-photo').addEventListener('change', function() {
      validateRoomPhoto();
      handleImagePreview();
    });

    // Form submission validation
    document.getElementById('add-room-form').addEventListener('submit', function(e) {
      e.preventDefault();
      
      clearAllErrors();
      
      const validations = [
        validateRoomNumber(),
        validateRoomType(),
        validateRoomSize(),
        validateRoomPrice(),
        validateRoomDescription(),
        validateRoomPhoto()
      ];
      
      const isValid = validations.every(validation => validation === true);
      
      if (isValid) {
        // If all validations pass, submit the form
        this.submit();
      } else {
        // Scroll to first error
        const firstError = document.querySelector('.input-error, .error');
        if (firstError) {
          firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    });

    // Image preview functionality
    function handleImagePreview() {
      const fileInput = document.getElementById('room-photo');
      const preview = document.getElementById('image-preview');
      const previewImg = document.getElementById('preview-img');
      const fileName = document.getElementById('file-name');
      
      if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          fileName.textContent = file.name;
          preview.classList.remove('hidden');
        };
        
        reader.readAsDataURL(file);
      }
    }


    const fileUploadArea = document.getElementById('file-upload-area');
    const fileInput = document.getElementById('room-photo');

    fileUploadArea.addEventListener('click', function() {
      fileInput.click();
    });

    fileUploadArea.addEventListener('dragover', function(e) {
      e.preventDefault();
      this.style.borderColor = '#3b82f6';
    });

    fileUploadArea.addEventListener('dragleave', function(e) {
      e.preventDefault();
      this.style.borderColor = '#d1d5db';
    });

    fileUploadArea.addEventListener('drop', function(e) {
      e.preventDefault();
      this.style.borderColor = '#d1d5db';
      
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        fileInput.files = files;
        validateRoomPhoto();
        handleImagePreview();
      }
    });


    function resetForm() {
      document.getElementById('add-room-form').reset();
      clearAllErrors();
      document.getElementById('image-preview').classList.add('hidden');
    }


    document.getElementById('room-number').addEventListener('input', function() {
      if (this.value.trim()) clearError('room-number');
    });

    document.getElementById('room-price').addEventListener('input', function() {
      if (this.value.trim()) clearError('room-price');
    });

    document.getElementById('room-description').addEventListener('input', function() {
      if (this.value.trim()) clearError('room-description');
    });
  </script>
</body>
</html>