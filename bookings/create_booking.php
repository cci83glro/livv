<?php

require_once '../users/init.php';

// Retrieve form data
$place = $_POST['place'];
$date = $_POST['date'];
$time = $_POST['time'];
$hours = $_POST['hours'];
$shift_id = $_POST['shift'];
$qualification_id = $_POST['qualification'];

// Validate input (you can add more validation as needed)
if(empty($place) || empty($date) || empty($hours) || empty($shift_id) || empty($qualification_id)) {
    // Handle validation errors
    die("Please fill in all required fields.");
}

// Insert into database
$db = DB::getInstance();
//$query = $db->query("INSERT INTO Bookings (place, date, hours, shift_id, qualification_id) VALUES (?, ?, ?, ?, ?)", [$place, $date, $hours, $shift_id, $qualification_id]);

$fields = [
    'place' => $place,
    'date' => $date,
    'time_id' => $time,
    'hours' => $hours,
    'shift_id' => $shift_id,
    'qualification_id' => $qualification_id
  ];

//   $db->insert('bookings', $fields);

if($db->insert('bookings', $fields)) {
    // Booking successful
    echo "Booking created successfully!";
} else {
    // Handle errors
    echo "Error creating booking: " . $db->error;
}

// Close statement and database connection
// $stmt->close();
// $mysqli->close();

?>
