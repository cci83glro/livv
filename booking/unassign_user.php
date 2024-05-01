<?php

require_once '../users/init.php';

// Check if booking ID and user ID are provided
if (!isset($_POST['booking_id'])) {
    die("Booking ID is not provided.");
}

// Retrieve booking ID and user ID from frontend
$booking_id = $_POST['booking_id'];

// Update assigned_user_id in Bookings table
$db = DB::getInstance();

if ($db->query("UPDATE Bookings SET assigned_user_id = NULL WHERE booking_id = $booking_id")) {
    // Send email and SMS notifications to admin and user
    // Add code to send notifications here

    echo "User unassigned successfully!";
} else {
    echo "Error unassigning user: " . $db->error;
}

?>