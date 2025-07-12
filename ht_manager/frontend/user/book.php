<?php session_start(); ?>
<?php require "nav.php" ?>

<?php require "../../backend/database/conectdb.php"; 
$rooms = $db->selectAll("SELECT * FROM chambres WHERE status = 'available'");
?>

<link rel="stylesheet" href="../helper/css/book.css">

<!-- Book Stay Page -->
<div id="book-stay" class="page">
  <main class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <div class="mb-8 slide-in">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Discover Your Perfect Stay</h1>
        <p class="text-gray-600">Choose from our collection of luxury accommodations</p>
      </div>

      <!-- Filter section will be inserted here by JavaScript -->

      <!-- Room Gallery -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($rooms as $room): ?>
        <!-- Room Card -->
        <div class="room-card luxury-card shadow-lg fade-in" data-type="<?php echo $room['type']; ?>" data-superficie="<?php echo $room['superficie']; ?>">
          <div class="room-image">
            <img src="../../rooms/<?php echo $room['image']; ?>"
                alt="Room <?php echo $room['numero']; ?>"  
                class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Room Number <?php echo $room['numero'] ?></h3>
            <div class="flex items-center gap-2 mb-2">
              <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full"><?php echo $room['type'] ?></span>
              <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full"><?php echo $room['superficie'] ?></span>
            </div>
            <p class="text-gray-600 mb-4"><?php echo $room['description'] ?></p>
            <div class="flex items-center justify-between mb-4">
              <span class="text-2xl font-bold text-blue-600">$<?php echo $room['prix']?>/night</span>
            </div>
            <button class="book-now-btn w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition transform hover:scale-105"
                    data-room-id="<?php echo $room['id']; ?>"
                    data-room-number="<?php echo $room['numero']; ?>"
                    data-room-price="<?php echo $room['prix']; ?>"
                    data-room-type="<?php echo $room['type']; ?>">
              Book Now
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>
</div>

<!-- Booking Modal -->
<!-- Booking Modal -->
<div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
    <div class="text-center mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Book Your Stay</h2>
      <p class="text-gray-600" id="modal-room-info">Room Number 101 - Deluxe</p>
    </div>

    <form id="booking-form" method="post" action="../../backend/controller/user/booking.php" class="space-y-6">
      <!-- FIXED: Only one room_id input -->
      <input type="hidden" id="room-id" name="room_id">
      <input type="hidden" id="price-id" name="prix">
      
      <!-- Check-in Date -->
      <div>
        <label for="checkin-date" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
        <input type="date" id="checkin-date" name="checkin_date" 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <div id="checkin-error" class="text-red-500 text-sm mt-1 hidden"></div>
      </div>
    
      <!-- Check-out Date -->
      <div>
        <label for="checkout-date" class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
        <input type="date" id="checkout-date" name="checkout_date" 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <div id="checkout-error" class="text-red-500 text-sm mt-1 hidden"></div>
      </div>
    
      <!-- Number of Guests -->
      <div class="hidden">
        <label for="guests" class="block text-sm font-medium text-gray-700 mb-2">Number of Guests</label>
        <input type="number" id="guests" name="guests" min="1" value="1"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <div id="guests-error" class="text-red-500 text-sm mt-1 hidden"></div>
      </div>

      <!-- Number of Nights (Auto-calculated) -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Nights</label>
        <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg">
          <span id="nights-display" class="text-lg font-semibold text-gray-900">0 nights</span>
        </div>
      </div>

      <!-- Total Price -->
      <div class="bg-blue-50 p-4 rounded-lg">
        <div class="flex justify-between items-center">
          <span class="text-lg font-medium text-gray-700">Total Price:</span>
          <span id="total-price" class="text-2xl font-bold text-blue-600">$0</span>
        </div>
        <p class="text-sm text-gray-600 mt-1">
          <span id="price-breakdown">0 nights × $0/night</span>
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="flex gap-4 pt-4">
        <button type="button" id="cancel-booking" 
                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
          Cancel
        </button>
        <button type="submit" 
                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition transform hover:scale-105">
          Confirm Booking
        </button>
      </div>
    </form>
  </div>
</div>

    <!-- Modal Overlay - Hidden by default -->
    <div id="bookingModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-all duration-300 invisible opacity-0">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity duration-300" onclick="hideBookingModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 translate-y-4">
            <!-- Close Button -->
            <button onclick="hideBookingModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Success Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-4">
                Booking Successful!
            </h2>

            <!-- Message -->
            <p class="text-gray-600 text-center mb-6 leading-relaxed">
                Your booking has been confirmed successfully. You will receive a confirmation email shortly with all the details.
            </p>

            <!-- Success Animation -->
            <div class="flex justify-center mb-6">
                <div class="w-12 h-1 bg-green-500 rounded-full animate-pulse"></div>
            </div>

            <!-- Action Button -->
            <div class="flex justify-center">
                <button onclick="hideBookingModal()" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-8 rounded-xl transition-colors duration-200 shadow-lg hover:shadow-xl">
                    done
                </button>
            </div>
        </div>
    </div>

<script src="../helper/js/user/book.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('booking-modal');
  const modalContent = document.getElementById('modal-content');
  const bookNowButtons = document.querySelectorAll('.book-now-btn');
  const cancelButton = document.getElementById('cancel-booking');
  const bookingForm = document.getElementById('booking-form');
  const checkinInput = document.getElementById('checkin-date');
  const checkoutInput = document.getElementById('checkout-date');
  const roomIdInput = document.getElementById('room-id');
  const priceInput = document.getElementById('price-id');

  let currentRoomPrice = 0;

  // Set minimum date to today
  const today = new Date().toISOString().split('T')[0];
  checkinInput.min = today;
  checkoutInput.min = today;

  // Open modal and set room data
  bookNowButtons.forEach(button => {
    button.addEventListener('click', function() {
      const roomId = this.dataset.roomId;
      const roomNumber = this.dataset.roomNumber;
      const roomPrice = this.dataset.roomPrice;
      const roomType = this.dataset.roomType;

      // FIXED: Clear and set values properly
      roomIdInput.value = roomId;
      priceInput.value = roomPrice;
      currentRoomPrice = parseFloat(roomPrice);

      console.log('Room ID set to:', roomIdInput.value); // Debug
      console.log('Price set to:', priceInput.value); // Debug

      document.getElementById('modal-room-info').textContent = `Room Number ${roomNumber} - ${roomType}`;

      // Reset form but keep the room data
      bookingForm.reset();
      roomIdInput.value = roomId; // Set again after reset
      priceInput.value = roomPrice; // Set again after reset

      updatePriceDisplay();

      modal.classList.remove('hidden');
      setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
      }, 10);
    });
  });

  function closeModal() {
    modalContent.classList.add('scale-95', 'opacity-0');
    modalContent.classList.remove('scale-100', 'opacity-100');
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 300);
  }

  cancelButton.addEventListener('click', closeModal);

  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeModal();
    }
  });

  function clearErrors() {
    const errorElements = ['checkin-error', 'checkout-error'];
    errorElements.forEach(id => {
      const element = document.getElementById(id);
      if (element) {
        element.classList.add('hidden');
        element.textContent = '';
      }
    });

    const inputs = ['checkin-date', 'checkout-date'];
    inputs.forEach(id => {
      const input = document.getElementById(id);
      if (input) {
        input.classList.remove('border-red-500', 'focus:ring-red-500');
        input.classList.add('border-gray-300', 'focus:ring-blue-500');
      }
    });
  }

  function showError(inputId, message) {
    const errorId = inputId + '-error';
    const errorElement = document.getElementById(errorId);
    const inputElement = document.getElementById(inputId);

    if (errorElement && inputElement) {
      errorElement.textContent = message;
      errorElement.classList.remove('hidden');
      inputElement.classList.remove('border-gray-300', 'focus:ring-blue-500');
      inputElement.classList.add('border-red-500', 'focus:ring-red-500');
    }
  }

  function validateForm() {
    clearErrors();
    let isValid = true;

    const checkinDate = checkinInput.value;
    const checkoutDate = checkoutInput.value;

    if (!checkinDate) {
      showError('checkin-date', 'Check-in date is required');
      isValid = false;
    } else {
      const today = new Date().toISOString().split('T')[0];
      if (checkinDate < today) {
        showError('checkin-date', 'Check-in date cannot be in the past');
        isValid = false;
      }
    }

    if (!checkoutDate) {
      showError('checkout-date', 'Check-out date is required');
      isValid = false;
    } else if (checkinDate && checkoutDate <= checkinDate) {
      showError('checkout-date', 'Check-out date must be after check-in date');
      isValid = false;
    }

    return isValid;
  }

  checkinInput.addEventListener('change', function() {
    const checkinDate = new Date(this.value);
    checkinDate.setDate(checkinDate.getDate() + 1);
    checkoutInput.min = checkinDate.toISOString().split('T')[0];

    if (checkoutInput.value && checkoutInput.value <= this.value) {
      checkoutInput.value = '';
    }
    updatePriceDisplay();
  });

  checkoutInput.addEventListener('change', function() {
    updatePriceDisplay();
  });

  function updatePriceDisplay() {
    const checkinDate = checkinInput.value;
    const checkoutDate = checkoutInput.value;

    if (checkinDate && checkoutDate && checkoutDate > checkinDate) {
      const checkin = new Date(checkinDate);
      const checkout = new Date(checkoutDate);
      const timeDifference = checkout.getTime() - checkin.getTime();
      const nights = Math.ceil(timeDifference / (1000 * 3600 * 24));
      const totalPrice = nights * currentRoomPrice;
      
      document.getElementById('nights-display').textContent = `${nights} night${nights > 1 ? 's' : ''}`;
      document.getElementById('total-price').textContent = `$${totalPrice.toFixed(2)}`;
      document.getElementById('price-breakdown').textContent = `${nights} nights × $${currentRoomPrice}/night`;
    } else {
      document.getElementById('nights-display').textContent = '0 nights';
      document.getElementById('total-price').textContent = '$0';
      document.getElementById('price-breakdown').textContent = `0 nights × $${currentRoomPrice}/night`;
    }
  }

  bookingForm.addEventListener('submit', function(e) {
    e.preventDefault();

    // Debug: Check values before submission
    console.log('Before submit - Room ID:', roomIdInput.value);
    console.log('Before submit - Price:', priceInput.value);

    if (!validateForm()) {
      return;
    }

    // Final check
    if (!roomIdInput.value) {
      alert('Error: Room ID is missing');
      return;
    }

    this.submit();
  });
});

        let autoHideTimer;

        // Function to show the booking modal
        function showBookingModal() {
            const modal = document.getElementById('bookingModal');
            const modalContent = modal.querySelector('.relative');
            
            // Show modal
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('visible', 'opacity-100');
            
            // Animate modal content
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'translate-y-4');
                modalContent.classList.add('scale-100', 'translate-y-0');
            }, 10);

            // Auto-hide after 5 seconds
            clearTimeout(autoHideTimer);
            autoHideTimer = setTimeout(() => {
                hideBookingModal();
            }, 5000);
        }

        // Function to hide the booking modal
        function hideBookingModal() {
            const modal = document.getElementById('bookingModal');
            const modalContent = modal.querySelector('.relative');
            
            // Animate modal content out
            modalContent.classList.remove('scale-100', 'translate-y-0');
            modalContent.classList.add('scale-95', 'translate-y-4');
            
            // Hide modal after animation
            setTimeout(() => {
                modal.classList.remove('visible', 'opacity-100');
                modal.classList.add('invisible', 'opacity-0');
            }, 200);


            clearTimeout(autoHideTimer);
        }


        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideBookingModal();
            }
        });


</script>
<?php if (isset($_GET['success'])): ?>
  <script>
    showBookingModal();
  </script>
<?php endif; ?>
