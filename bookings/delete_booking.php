<?php
// Include database connection file
require_once "db_connection.php";

// Check if booking ID is provided
if (!isset($_POST['booking_id'])) {
    die("Booking ID is not provided.");
}

// Retrieve booking ID from frontend
$booking_id = $_POST['booking_id'];

// Delete booking from database
$db = DB::getInstance();
$query = "DELETE FROM Bookings WHERE booking_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
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
