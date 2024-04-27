<?php

require_once '../users/init.php';

// Check if booking ID is provided
if (!isset($_POST['booking_id'])) {
    die("Booking ID is not provided.");
}

// Retrieve booking ID from frontend
$booking_id = $_POST['booking_id'];

// Delete booking from database
$db = DB::getInstance();

if ($db->query("DELETE FROM Bookings WHERE booking_id = $booking_id")) {
    // Deletion successful
    echo "Booking deleted successfully!";
} else {
    // Handle errors
    echo "Error deleting booking: " . $db->error;
}

// Close statement and database connection
// $stmt->close();
// $mysqli->close();

?>
