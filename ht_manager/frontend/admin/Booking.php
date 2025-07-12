<?php
require_once '../../backend/database/conectdb.php';
$bookings = $db->selectALL("
  SELECT 
    r.id,
    u.nom AS guest_name,
    u.email AS guest_email,
    c.numero AS room_number,
    r.date_debut AS check_in,
    r.date_fin AS check_out,
    r.statut AS status
  FROM reservations r
  JOIN users u ON r.id_user = u.id
  JOIN chambres c ON r.id_chambre = c.id
  ORDER BY r.date_debut DESC
");
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Management</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background-color: #f5f7fa;
      padding: 20px;
    }

    .page-section {
      max-width: 1200px;
      margin: 0 auto;
    }

    .admin-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
      padding: 24px;
    }

    .text-xl {
      font-size: 1.25rem;
    }

    .font-bold {
      font-weight: 700;
    }

    .text-gray-900 {
      color: #111827;
    }

    .mb-6 {
      margin-bottom: 1.5rem;
    }

    .filter-container {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .filter-btn {
      padding: 8px 16px;
      border: 2px solid #e5e7eb;
      background: white;
      color: #6b7280;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .filter-btn:hover {
      border-color: #3b82f6;
      color: #3b82f6;
    }

    .filter-btn.active {
      background: #3b82f6;
      color: white;
      border-color: #3b82f6;
    }

    .overflow-x-auto {
      overflow-x: auto;
    }

    .w-full {
      width: 100%;
    }

    table {
      border-collapse: collapse;
      min-width: 800px;
    }

    thead tr {
      border-bottom: 2px solid #e5e7eb;
    }

    th, td {
      text-align: left;
      padding: 12px 16px;
      border-bottom: 1px solid #f3f4f6;
    }

    th {
      font-weight: 600;
      color: #374151;
      background: #f9fafb;
    }

    tbody tr:hover {
      background: #f9fafb;
    }

    .status-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-pending {
      background: #fef3c7;
      color: #92400e;
    }

    .status-confirmed {
      background: #d1fae5;
      color: #065f46;
    }

    .status-cancelled {
      background: #fee2e2;
      color: #991b1b;
    }

    .action-btn {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 500;
      cursor: pointer;
      margin-right: 5px;
      transition: all 0.2s ease;
    }

    .btn-confirm {
      background: #10b981;
      color: white;
    }

    .btn-confirm:hover {
      background: #059669;
    }

    .btn-cancel {
      background: #ef4444;
      color: white;
    }

    .btn-cancel:hover {
      background: #dc2626;
    }

    .loading {
      text-align: center;
      padding: 40px;
      color: #6b7280;
    }

    .no-data {
      text-align: center;
      padding: 40px;
      color: #6b7280;
    }

    @media (max-width: 768px) {
      .filter-container {
        justify-content: center;
      }
      
      .admin-card {
        padding: 16px;
      }
      
      th, td {
        padding: 8px 12px;
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <section id="bookings" class="page-section">
    <div class="admin-card">
      <h3 class="text-xl font-bold text-gray-900 mb-6">Booking Status</h3>
      
      <!-- Filter Buttons -->
      <div class="filter-container">
        <button class="filter-btn active" data-status="all">All</button>
        <button class="filter-btn" data-status="En attente">Pending</button>
        <button class="filter-btn" data-status="Confirmée">Confirmed</button>
        <button class="filter-btn" data-status="Annulée">Cancelled</button>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Guest Name</th>
              <th>Guest Email</th>
              <th>Room</th>
              <th>Check-in</th>
              <th>Check-out</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="bookings-table-body">
            <tr>
              <td colspan="8" class="loading">Loading bookings...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <script>
    // Sample data - replace this with actual API call
    const sampleBookings = <?= json_encode($bookings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

    let allBookings = [];
    let currentFilter = 'all';

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
      loadBookings();
      setupEventListeners();
    });

    function setupEventListeners() {
      // Filter buttons
      document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          // Update active filter
          document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          
          currentFilter = this.getAttribute('data-status');
          filterBookings();
        });
      });
    }

    // Load bookings data
    function loadBookings() {
      // Simulate API call - replace with actual fetch
      setTimeout(() => {
        allBookings = sampleBookings;
        renderBookings(allBookings);
      }, 500);
      
      // Uncomment and modify this for real API call:
      /*
      fetch('your-api-endpoint.php')
        .then(response => response.json())
        .then(data => {
          allBookings = data;
          renderBookings(allBookings);
        })
        .catch(error => {
          console.error('Error loading bookings:', error);
          document.getElementById('bookings-table-body').innerHTML = 
            '<tr><td colspan="8" class="no-data">Error loading bookings</td></tr>';
        });
      */
    }

    function filterBookings() {
      let filteredBookings;
      
      if (currentFilter === 'all') {
        filteredBookings = allBookings;
      } else {
        filteredBookings = allBookings.filter(booking => booking.status === currentFilter);
      }
      
      renderBookings(filteredBookings);
    }

    function renderBookings(bookings) {
      const tbody = document.getElementById('bookings-table-body');
      
      if (bookings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="no-data">No bookings found</td></tr>';
        return;
      }

      tbody.innerHTML = bookings.map(booking => {
        const statusClass = getStatusClass(booking.status);
        const actions = getActionsForBooking(booking);
        
        return `
          <tr data-booking-id="${booking.id}">
            <td>#${booking.id}</td>
            <td>${booking.guest_name}</td>
            <td>${booking.guest_email}</td>
            <td>Room ${booking.room_number}</td>
            <td>${formatDate(booking.check_in)}</td>
            <td>${formatDate(booking.check_out)}</td>
            <td>
              <span class="status-badge ${statusClass}">
                ${getStatusText(booking.status)}
              </span>
            </td>
            <td>${actions}</td>
          </tr>
        `;
      }).join('');

      // Add event listeners for action buttons
      addActionListeners();
    }

    function getStatusClass(status) {
      switch(status) {
        case 'En attente': return 'status-pending';
        case 'Confirmée': return 'status-confirmed';
        case 'Annulée': return 'status-cancelled';
        default: return 'status-pending';
      }
    }

    function getStatusText(status) {
      switch(status) {
        case 'En attente': return 'Pending';
        case 'Confirmée': return 'Confirmed';
        case 'Annulée': return 'Cancelled';
        default: return status;
      }
    }

    function getActionsForBooking(booking) {
      if (booking.status === 'En attente') {
        return `
          <button class="action-btn btn-confirm" data-action="confirm" data-id="${booking.id}">
            Confirm
          </button>
          <button class="action-btn btn-cancel" data-action="cancel" data-id="${booking.id}">
            Cancel
          </button>
        `;
      } else {
        return ''; // No actions for confirmed or cancelled bookings
      }
    }

    function addActionListeners() {
      document.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const action = this.getAttribute('data-action');
          const bookingId = this.getAttribute('data-id');
          
          if (confirm(`Are you sure you want to ${action} this booking?`)) {
            updateBookingStatus(bookingId, action);
          }
        });
      });
    }

    function updateBookingStatus(bookingId, action) {
      const newStatus = action === 'confirm' ? 'Confirmée' : 'Annulée';
      
      // Update in memory (for demo)
      const booking = allBookings.find(b => b.id == bookingId);
      if (booking) {
        booking.status = newStatus;
        filterBookings(); // Re-render with current filter
        

        alert(`Booking #${bookingId} has been ${action}ed successfully!`);
      }
      

      fetch('updateBoking.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          booking_id: bookingId,
          status: newStatus
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload bookings
          loadBookings();
          alert(`Booking #${bookingId} has been ${action}ed successfully!`);
        } else {
          alert('Error updating booking status');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error updating booking status');
      });
    }

    function formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString('en-US', options);
    }
  </script>
</body>
</html>