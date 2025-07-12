<?php 

require "../../backend/database/conectdb.php";

$nbr_rooms = $db->selectOne("SELECT COUNT(*) AS total FROM chambres");
$active = $db->selectOne("SELECT COUNT(*) AS active from chambres where status =? ",["not_availble"]) ;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../helper/css/admin.css">
</head>
<body>
    <section id="dashboard" class="page-section active p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="bg-blue-500 p-3 rounded-full mr-4">
                        <i class="fas fa-bed text-white"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Total Rooms</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-rooms"><?= $nbr_rooms['total'] ?></p>
                    </div>
                </div>
            </div>
            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="bg-green-500 p-3 rounded-full mr-4">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Active Bookings</p>
                        <p class="text-2xl font-bold text-gray-900" id="active-bookings"><?= $active['active'] ?></p>
                    </div>
                </div>
            </div>
            <div class="admin-card p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-500 p-3 rounded-full mr-4">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Pending</p>
                        <p class="text-2xl font-bold text-gray-900" id="pending-bookings">3</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Welcome Card -->
<div class="admin-card p-8 flex flex-col items-center justify-center text-center bg-gradient-to-r from-blue-100 to-green-100 shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-blue-700 mb-2">"A well-managed hotel is a guest's second home."</h2>
    <p class="text-gray-700 text-lg mb-4">Keep your rooms updated and monitor bookings to ensure every guest has a smooth stay.</p>
    <div class="flex space-x-4 mt-4">
        <span class="inline-block bg-blue-200 text-blue-800 px-4 py-2 rounded-full font-semibold">Stay Organized</span>
        <span class="inline-block bg-green-200 text-green-800 px-4 py-2 rounded-full font-semibold">Monitor in Real Time</span>
        <span class="inline-block bg-yellow-200 text-yellow-800 px-4 py-2 rounded-full font-semibold">Deliver Excellence</span>
    </div>
</div>

    </section>
</body>
</html>
