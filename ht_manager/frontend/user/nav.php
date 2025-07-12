<?php require "../helper/other/function.php" ;
if (!isset($_SESSION['user'])) {
    header('Location: ../core/guist.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Luxury Stays</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
        body { font-family: 'Inter', sans-serif; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(-30px); }
      to { opacity: 1; transform: translateX(0); }
    }
    .fade-in {
      animation: fadeIn 0.6s ease-out forwards;
    }
    .slide-in {
      animation: slideIn 0.5s ease-out forwards;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    .star-rating {
      direction: rtl;
      unicode-bidi: bidi-override;
    }
    .star-rating input {
      display: none;
    }
    .star-rating label {
      color: #d1d5db;
      font-size: 1.25rem;
      padding: 0 2px;
      cursor: pointer;
      transition: color 0.2s;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color: #f59e0b;
    }
    .room-card {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      transition: all 0.3s ease;
    }
    .room-card:hover {
      transform: scale(1.02);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    .room-image {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 3rem;
    }

    .nav-link {
      transition: all 0.3s ease;
      position: relative;
    }
    .nav-link:hover {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      transform: translateY(-1px);
    }
    .nav-link.active {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .luxury-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <div class="flex-shrink-0 flex items-center">
            <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5m-4 0h4" />
            </svg>
            <span class="ml-2 text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Luxury Stays</span>
          </div>
        </div>
        
        <!-- Navigation Menu -->
        <div class="hidden md:flex items-center space-x-2">
          <a href="Dashboard.php" class="nav-link px-4 py-2 rounded-lg font-medium <?php echo isSelected('Dashboard'); ?>">
            <i class="fas fa-home mr-2"></i>Dashboard
          </a>
          <a href="book.php" class="nav-link px-4 py-2 rounded-lg font-medium <?php echo isSelected('book'); ?>">
            <i class="fas fa-plus-circle mr-2"></i>Book Stay
          </a>
          <a href="upcoming.php"  class="nav-link px-4 py-2 rounded-lg font-medium <?php echo isSelected('upcoming'); ?>">
            <i class="fas fa-calendar-alt mr-2"></i>Upcoming
          </a>
          <a href="pastboking.php" class="nav-link px-4 py-2 rounded-lg font-medium <?php echo isSelected('pastboking'); ?>">
            <i class="fas fa-history mr-2"></i>Past Bookings
          </a>
        </div>

        <div class="flex items-center">
          <a href="../../backend/controller/core/logout.php" class="nav-link px-4 py-2 rounded-lg font-medium text-red-600 hover:bg-red-100 transition">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
          </a>
        </div>
            </div>
          </div>
        </nav>

          <!-- Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg">
          <div class="grid grid-cols-4 py-2">
            <a href="Dashboard.php" class="flex flex-col items-center py-2 px-1 <?php echo isSelected('Dashboard'); ?>">
        <i class="fas fa-home text-lg"></i>
        <span class="text-xs mt-1">Home</span>
            </a>
            <a href="book.php" class="flex flex-col items-center py-2 px-1 <?php echo isSelected('book'); ?>">
        <i class="fas fa-plus-circle text-lg"></i>
        <span class="text-xs mt-1">Book</span>
            </a>
            <a href="upcoming.php" class="flex flex-col items-center py-2 px-1 <?php echo isSelected('upcoming'); ?>">
        <i class="fas fa-calendar-alt text-lg"></i>
        <span class="text-xs mt-1">Upcoming</span>
            </a>
            <a href="pastboking.php" class="flex flex-col items-center py-2 px-1 <?php echo isSelected('pastboking'); ?>">
        <i class="fas fa-history text-lg"></i>
        <span class="text-xs mt-1">History</span>
            </a>
          </div>
        </div>

<script>


    // Star rating interaction
    document.querySelectorAll('.star-rating input').forEach(radio => {
      radio.addEventListener('change', function() {
        const form = this.closest('form');
        const rating = this.value;
        console.log(`Rating submitted: ${rating} stars`);
        
        // Show success message
        const existingMessage = form.parentNode.querySelector('.rating-success');
        if (existingMessage) {
          existingMessage.remove();
        }
        
        const successMessage = document.createElement('div');
        successMessage.className = 'rating-success bg-green-50 border border-green-200 rounded-lg p-3 mt-3';
        successMessage.innerHTML = `
          <p class="text-green-800 text-sm">
            <i class="fas fa-check text-green-500 mr-2"></i>
            Thank you for rating your stay! Your feedback helps us improve.
          </p>
        `;
        form.parentNode.insertBefore(successMessage, form.nextSibling);
        
        // Update the display rating
        const ratingDisplay = form.closest('.luxury-card').querySelector('.flex.items-center.mt-2');
        if (ratingDisplay) {
          const stars = ratingDisplay.querySelectorAll('i');
          stars.forEach((star, index) => {
            if (index < rating) {
              star.className = 'fas fa-star text-yellow-400';
            } else {
              star.className = 'far fa-star text-yellow-400';
            }
          });
          const ratingText = ratingDisplay.querySelector('span');
          if (ratingText) {
            ratingText.textContent = `(Your rating: ${rating}/5)`;
          }
        }
      });
    });

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      showPage('dashboard');
    });
</script>
</body>
</html>