<?php

require_once '../users/init.php';

// Check if booking ID and user ID are provided
if (!isset($_POST['booking_id']) || !isset($_POST['user_id'])) {
    die("Booking ID or User ID is not provided.");
}

// Retrieve booking ID and user ID from frontend
$booking_id = $_POST['booking_id'];
$user_id = $_POST['user_id'];

// Update assigned_user_id in Bookings table
$db = DB::getInstance();

if ($db->query("UPDATE Bookings SET assigned_user_id = $user_id WHERE booking_id = $booking_id")) {
    // Send email and SMS notifications to admin and user
    // Add code to send notifications here

    echo "User assigned successfully!";
} else {
    echo "Error assigning user: " . $db->error;
}

?>