<?php

$public = false;
$permissions = [2];
require_once __DIR__.'/../master-pages/master.php';

// Check if booking ID is provided
if (!isset($_POST['booking_id'])) {
    die("Booking ID is not provided.");
}

$dbo = dbo::getInstance();

// Retrieve booking ID from frontend
$booking_id = $_POST['booking_id'];

if ($dbo->query("DELETE FROM Bookings WHERE booking_id = $booking_id")) {
    // Deletion successful
    echo "Booking deleted successfully!";
} else {
    // Handle errors
    echo "Error deleting booking: " . $dbo->error;
}

// Close statement and database connection
// $stmt->close();
// $mysqli->close();

?>
