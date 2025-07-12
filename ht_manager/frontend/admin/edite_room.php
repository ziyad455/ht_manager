<?php
require "../../backend/database/conectdb.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../core/guist.php");
    exit();
}

// Get room ID from URL
$roomId = $_GET['id'] ?? null;
if (!$roomId || !is_numeric($roomId)) {
    die("Invalid room ID");
}

// Fetch room data
$room = $db->selectOne("SELECT * FROM chambres WHERE id = ?", [$roomId]);
if (!$room) {
    die("Room not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room #<?= $roomId ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #3b82f6;
            --primary-blue-dark: #1d4ed8;
            --secondary-blue: #1e40af;
            --accent-green: #10b981;
            --accent-green-dark: #059669;
            --luxury-gold: #f59e0b;
            --dark-bg: #1f2937;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --white: #ffffff;
            --red-500: #ef4444;
            --red-600: #dc2626;
            --blue-50: #eff6ff;
            --blue-100: #dbeafe;
            --green-50: #f0fdf4;
            --green-100: #dcfce7;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--blue-50) 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            color: var(--primary-blue);
            background-color: var(--blue-50);
        }
        
        .form-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06),
                0 20px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            padding: 30px;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }
        
        .header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }
        
        .header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .form-content {
            padding: 40px;
        }
        
        .error-message {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: var(--red-600);
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 16px;
            background-color: var(--gray-50);
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
            background-color: var(--white);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }
        
        select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            appearance: none;
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .image-section {
            background: var(--gray-50);
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
        }
        
        .image-section h3 {
            margin: 0 0 16px 0;
            color: var(--gray-700);
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .image-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .image-preview {
            width: 180px;
            height: 180px;
            border-radius: 16px;
            object-fit: cover;
            border: 3px solid var(--white);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        
        .image-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            cursor: pointer;
            background: linear-gradient(135deg, var(--blue-100) 0%, var(--green-100) 100%);
            border: 2px dashed var(--primary-blue);
            border-radius: 16px;
            padding: 20px 30px;
            text-align: center;
            transition: all 0.3s ease;
            color: var(--primary-blue);
            font-weight: 600;
        }
        
        .file-upload:hover {
            background: linear-gradient(135deg, var(--blue-50) 0%, var(--green-50) 100%);
            border-color: var(--accent-green);
            color: var(--accent-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }
        
        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            top: 0;
            left: 0;
        }
        
        .upload-icon {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-available {
            background-color: var(--green-100);
            color: var(--accent-green-dark);
        }
        
        .status-unavailable {
            background-color: #fee2e2;
            color: var(--red-600);
        }
        
        .buttons {
            display: flex;
            gap: 16px;
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }
        
        .btn {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary {
            background: var(--white);
            color: var(--gray-600);
            border: 2px solid var(--gray-300);
        }
        
        .btn-secondary:hover {
            background-color: var(--gray-50);
            border-color: var(--gray-400);
            color: var(--gray-700);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            .image-container {
                justify-content: center;
            }
            
            .form-content {
                padding: 24px;
            }
            
            .header {
                padding: 24px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">        
        <div class="form-container">
            <div class="header">
                <h1>
                    <i class="fas fa-edit"></i>
                    Edit Room #<?= htmlspecialchars($room['numero']) ?>
                </h1>
                <p>Update room details and amenities</p>
            </div>
            
            <div class="form-content">
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Error: <?= htmlspecialchars($_GET['error']) ?></span>
                    </div>
                <?php endif; ?>
                
                <form action="../../backend/controller/admin/edite_room.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="room-id" value="<?= $room['id'] ?>">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="room-number">
                                <i class="fas fa-hashtag"></i> Room Number
                            </label>
                            <div class="input-wrapper">
                                <input type="text" id="room-number" name="room-number" value="<?= htmlspecialchars($room['numero']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="room-type">
                                <i class="fas fa-bed"></i> Room Type
                            </label>
                            <select id="room-type" name="room-type" required>
                                <option value="simple" <?= $room['type'] === 'simple' ? 'selected' : '' ?>>Simple Room</option>
                                <option value="double" <?= $room['type'] === 'double' ? 'selected' : '' ?>>Double Room</option>
                                <option value="suite" <?= $room['type'] === 'suite' ? 'selected' : '' ?>>Luxury Suite</option>
                                <option value="familiale" <?= $room['type'] === 'familiale' ? 'selected' : '' ?>>Family Room</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="room-size">
                                <i class="fas fa-expand-arrows-alt"></i> Room Size
                            </label>
                            <select id="room-size" name="room-size" required>
                                <option value="petite" <?= $room['superficie'] === 'petite' ? 'selected' : '' ?>>Petite (20-30 m²)</option>
                                <option value="moyenne" <?= $room['superficie'] === 'moyenne' ? 'selected' : '' ?>>Moyenne (30-45 m²)</option>
                                <option value="grande" <?= $room['superficie'] === 'grande' ? 'selected' : '' ?>>Grande (45+ m²)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="room-price">
                                <i class="fas fa-dollar-sign"></i> Price per Night (DH)
                            </label>
                            <div class="input-wrapper">
                                <input type="number" id="room-price" name="room-price" step="0.01" value="<?= htmlspecialchars($room['prix']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="room-description">
                                <i class="fas fa-align-left"></i> Room Description
                            </label>
                            <textarea id="room-description" name="room-description" required placeholder="Describe the room amenities, features, and what makes it special..."><?= htmlspecialchars($room['description']) ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="room-status">
                                <i class="fas fa-toggle-on"></i> Room Status
                            </label>
                            <select id="room-status" name="room-status" required>
                                <option value="available" <?= $room['status'] === 'availble' ? 'selected' : '' ?>>✅ Available</option>
                                <option value="not available" <?= $room['status'] === 'not_availble' ? 'selected' : '' ?>>❌ Not Available</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="image-section">
                        <h3>
                            <i class="fas fa-camera"></i>
                            Room Photography
                        </h3>
                        <div class="image-container">
                            <img id="current-image" src="../../rooms/<?= htmlspecialchars($room['image']) ?>" class="image-preview" alt="Current room image">
                            <img id="new-image-preview" class="image-preview" style="display: none;" alt="New room image preview">
                        </div>
                        
                        <label for="room-photo" class="file-upload">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <span id="upload-text">Click to change photo</span>
                            <div style="font-size: 12px; margin-top: 4px; opacity: 0.7;">
                                Maximum 5MB • JPG, PNG, WebP
                            </div>
                            <input type="file" id="room-photo" name="room-photo" accept="image/*">
                        </label>
                    </div>
                    
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Room
                        </button>
                        <a href="rooms.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('room-photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select a valid image file.');
                    e.target.value = '';
                    return;
                }
                
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB.');
                    e.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    // Hide current image and show new preview
                    document.getElementById('current-image').style.display = 'none';
                    const newPreview = document.getElementById('new-image-preview');
                    newPreview.src = event.target.result;
                    newPreview.style.display = 'block';
                    
                    // Update the upload text
                    document.getElementById('upload-text').innerHTML = '<i class="fas fa-check"></i> Selected: ' + file.name;
                }
                
                reader.readAsDataURL(file);
            } else {
                // Reset if no file selected
                document.getElementById('current-image').style.display = 'block';
                document.getElementById('new-image-preview').style.display = 'none';
                document.getElementById('upload-text').innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Click to change photo';
            }
        });

        // Add smooth transitions and animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate form elements on focus
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>