<?php
session_start();
if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../core/guist.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../helper/css/admin.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Sidebar -->
    <?php require "sideBar.php" ?>

    <!-- Mobile Menu Button -->
    <button class="md:hidden fixed top-4 left-4 z-50 bg-white p-3 rounded-full shadow-lg" id="mobile-menu-btn">
        <i class="fas fa-bars text-gray-700"></i>
    </button>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900" id="page-title">Dashboard</h2>
                <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i>
                        <span id="current-time"></span>
                    </div>
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-full">
                        <i class="fas fa-user mr-2"></i>
                        Admin
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Section -->
        <?php require "dashboard.php"; ?>

        <!-- Room Management Section -->
        <?php require "Rooms.php";  ?>
        
        <!-- Booking Status Section -->
        <?php require "Booking.php";  ?>
        
        <!-- Add Room Section -->
        <?php require "addRoom.php";  ?>
        
    </div>

    <!-- Edit Room Modal -->
    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="admin-card p-6 m-4 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Edit Room</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="edit-room-form" class="space-y-4">
                <input type="hidden" id="edit-room-id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Number</label>
                        <input type="text" id="edit-room-number" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Name</label>
                        <input type="text" id="edit-room-name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                        <select id="edit-room-type" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="simple">Simple</option>
                            <option value="double">Double</option>
                            <option value="suite">Suite</option>
                            <option value="familiale">Familiale</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Size</label>
                        <select id="edit-room-size" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="petite">Petite</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="grande">Grande</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price per Night ($)</label>
                        <input type="number" id="edit-room-price" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <input type="number" id="edit-room-rating" min="1" max="5" step="0.1" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Description</label>
                    <textarea id="edit-room-description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Update Room
                    </button>
                    <button type="button" class="btn-danger" onclick="closeEditModal()">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sample data - In real application, this would come from your database
        let rooms = [
            {
                id: 1,
                number: '101',
                name: 'Deluxe Ocean View',
                type: 'double',
                size: 'moyenne',
                price: 300,
                rating: 4.9,
                description: 'Experience breathtaking ocean views from your private balcony with premium amenities and elegant furnishings.',
                image: 'https://via.placeholder.com/100x80/667eea/ffffff?text=Room'
            },
            {
                id: 2,
                number: '102',
                name: 'Executive Suite',
                type: 'suite',
                size: 'grande',
                price: 450,
                rating: 4.8,
                description: 'Spacious suite with separate living area, premium amenities, and personalized concierge service.',
                image: 'https://via.placeholder.com/100x80/ffecd2/333333?text=Suite'
            },
            {
                id: 3,
                number: '201',
                name: 'Presidential Villa',
                type: 'suite',
                size: 'grande',
                price: 800,
                rating: 5.0,
                description: 'Ultimate luxury with private pool, butler service, and panoramic views. Perfect for special occasions.',
                image: 'https://via.placeholder.com/100x80/a8edea/333333?text=Villa'
            },
            {
                id: 4,
                number: '103',
                name: 'Romantic Getaway',
                type: 'double',
                size: 'moyenne',
                price: 380,
                rating: 4.9,
                description: 'Intimate suite designed for couples with private jacuzzi, rose petal service, and champagne welcome.',
                image: 'https://via.placeholder.com/100x80/ff9a9e/ffffff?text=Romance'
            },
            {
                id: 5,
                number: '104',
                name: 'Garden Paradise',
                type: 'simple',
                size: 'petite',
                price: 320,
                rating: 4.7,
                description: 'Eco-friendly luxury with organic amenities, private garden access, and sustainable design elements.',
                image: 'https://via.placeholder.com/100x80/84fab0/333333?text=Garden'
            },
            {
                id: 6,
                number: '202',
                name: 'Wellness Retreat',
                type: 'familiale',
                size: 'grande',
                price: 420,
                rating: 4.8,
                description: 'Rejuvenating experience with in-room spa services, meditation corner, and healthy dining options.',
                image: 'https://via.placeholder.com/100x80/fad0c4/333333?text=Wellness'
            }
        ];

        let bookings = [
            {
                id: 'BK001',
                guestName: 'John Doe',
                guestId: 'ID123456',
                roomNumber: '101',
                checkIn: '2024-06-15',
                checkOut: '2024-06-18',
                status: 'confirmed'
            },
            {
                id: 'BK002',
                guestName: 'Jane Smith',
                guestId: 'ID789012',
                roomNumber: '102',
                checkIn: '2024-06-16',
                checkOut: '2024-06-20',
                status: 'pending'
            },
            {
                id: 'BK003',
                guestName: 'Bob Johnson',
                guestId: 'ID345678',
                roomNumber: '201',
                checkIn: '2024-06-17',
                checkOut: '2024-06-22',
                status: 'confirmed'
            },
            {
                id: 'BK004',
                guestName: 'Alice Brown',
                guestId: 'ID901234',
                roomNumber: '103',
                checkIn: '2024-06-14',
                checkOut: '2024-06-16',
                status: 'cancelled'
            },
            {
                id: 'BK005',
                guestName: 'Charlie Wilson',
                guestId: 'ID567890',
                roomNumber: '104',
                checkIn: '2024-06-18',
                checkOut: '2024-06-21',
                status: 'pending'
            }
        ];

        // Initialize the admin panel
        document.addEventListener('DOMContentLoaded', function() {
            initializeAdmin();
            updateClock();
            setInterval(updateClock, 1000);
        });

        function initializeAdmin() {
            setupSidebarNavigation();
            setupFileUpload();
            // populateRoomsTable();
            populateBookingsTable();
            setupForms();
            setupMobileMenu();
        }


        // ... (previous code continues)

        function setupSidebarNavigation() {
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            
            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    sidebarItems.forEach(i => i.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Hide all sections
                    document.querySelectorAll('.page-section').forEach(section => {
                        section.classList.remove('active');
                    });
                    
                    // Show selected section
                    const pageId = this.getAttribute('data-page');
                    document.getElementById(pageId).classList.add('active');
                    
                    // Update page title
                    document.getElementById('page-title').textContent = this.textContent.trim();
                    
                    // Close mobile menu if open
                    document.querySelector('.sidebar').classList.remove('mobile-open');
                });
            });
        }

        function setupFileUpload() {
            const fileUploadArea = document.getElementById('file-upload-area');
            const fileInput = document.getElementById('room-photo');
            const previewImg = document.getElementById('preview-img');
            const fileName = document.getElementById('file-name');
            const imagePreview = document.getElementById('image-preview');

            // Handle drag and drop
            fileUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileUploadArea.classList.add('dragover');
            });

            fileUploadArea.addEventListener('dragleave', () => {
                fileUploadArea.classList.remove('dragover');
            });

            fileUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                fileUploadArea.classList.remove('dragover');
                
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileUpload(fileInput.files[0]);
                }
            });

            // Handle file selection
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    handleFileUpload(fileInput.files[0]);
                }
            });

            function handleFileUpload(file) {
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        fileName.textContent = file.name;
                        imagePreview.classList.remove('hidden');
                    };
                    
                    reader.readAsDataURL(file);
                } else {
                    showNotification('Please select an image file', 'error');
                }
            }
        }

        

        function populateBookingsTable() {
            const tbody = document.getElementById('bookings-table-body');
            tbody.innerHTML = '';
            
            bookings.forEach(booking => {
                let statusClass = '';
                let statusText = '';
                
                switch(booking.status) {
                    case 'confirmed':
                        statusClass = 'status-confirmed';
                        statusText = 'Confirmed';
                        break;
                    case 'pending':
                        statusClass = 'status-pending';
                        statusText = 'Pending';
                        break;
                    case 'cancelled':
                        statusClass = 'status-cancelled';
                        statusText = 'Cancelled';
                        break;
                }
                
                const tr = document.createElement('tr');
                tr.className = 'border-b border-gray-200 hover:bg-gray-50';
                tr.innerHTML = `
                    <td class="py-4 px-4">${booking.id}</td>
                    <td class="py-4 px-4">${booking.guestName}</td>
                    <td class="py-4 px-4">${booking.guestId}</td>
                    <td class="py-4 px-4">${booking.roomNumber}</td>
                    <td class="py-4 px-4">${formatDate(booking.checkIn)}</td>
                    <td class="py-4 px-4">${formatDate(booking.checkOut)}</td>
                    <td class="py-4 px-4">
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td class="py-4 px-4">
                        <button class="btn-edit mr-2" onclick="editBooking('${booking.id}')">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        <button class="btn-danger" onclick="deleteBooking('${booking.id}')">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function setupForms() {
            // Add room form

            
            // Edit room form
            document.getElementById('edit-room-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const roomId = parseInt(document.getElementById('edit-room-id').value);
                const roomIndex = rooms.findIndex(r => r.id === roomId);
                
                if (roomIndex !== -1) {
                    rooms[roomIndex] = {
                        ...rooms[roomIndex],
                        number: document.getElementById('edit-room-number').value,
                        name: document.getElementById('edit-room-name').value,
                        type: document.getElementById('edit-room-type').value,
                        size: document.getElementById('edit-room-size').value,
                        price: parseFloat(document.getElementById('edit-room-price').value),
                        rating: parseFloat(document.getElementById('edit-room-rating').value),
                        description: document.getElementById('edit-room-description').value
                    };
                    
                    // populateRoomsTable();
                    closeEditModal();
                    showNotification('Room updated successfully!', 'success');
                }
            });
        }

        function setupMobileMenu() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.querySelector('.sidebar');
            
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-open');
            });
        }

        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            document.getElementById('current-time').textContent = timeString;
        }

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        function showPage(pageId) {
            document.querySelectorAll('.page-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(pageId).classList.add('active');
            
            // Update active sidebar item
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('data-page') === pageId) {
                    item.classList.add('active');
                }
            });
            
            document.getElementById('page-title').textContent = 
                document.querySelector(`.sidebar-item[data-page="${pageId}"]`).textContent.trim();
        }

        function showAddRoom() {
            showPage('add-room');
        }

        function editRoom(roomId) {
            const room = rooms.find(r => r.id === roomId);
            if (room) {
                document.getElementById('edit-room-id').value = room.id;
                document.getElementById('edit-room-number').value = room.number;
                document.getElementById('edit-room-name').value = room.name;
                document.getElementById('edit-room-type').value = room.type;
                document.getElementById('edit-room-size').value = room.size;
                document.getElementById('edit-room-price').value = room.price;
                document.getElementById('edit-room-rating').value = room.rating;
                document.getElementById('edit-room-description').value = room.description;
                
                document.getElementById('edit-modal').classList.remove('hidden');
            }
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        function deleteRoom(roomId) {
            if (confirm('Are you sure you want to delete this room?')) {
                rooms = rooms.filter(r => r.id !== roomId);
                // populateRoomsTable();
                showNotification('Room deleted successfully!', 'success');
            }
        }

        function editBooking(bookingId) {
            const booking = bookings.find(b => b.id === bookingId);
            if (booking) {
                // In a real app, you would open a modal to edit the booking
                alert(`Editing booking ${bookingId}`);
            }
        }

        function deleteBooking(bookingId) {
            if (confirm('Are you sure you want to delete this booking?')) {
                bookings = bookings.filter(b => b.id !== bookingId);
                populateBookingsTable();
                showNotification('Booking deleted successfully!', 'success');
            }
        }

        function resetForm() {
            document.getElementById('add-room-form').reset();
            document.getElementById('image-preview').classList.add('hidden');
            showPage('rooms');
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>