<?php

require_once '../generic-helpers.php';
require_once '../users/init.php';

// Retrieve form data
$id = $_POST['id'];
$place = $_POST['place'];
$date = $_POST['date'];
$time_id = $_POST['time'];
$hours = $_POST['hours'];
$shift_id = $_POST['shift'];
$qualification_id = $_POST['qualification'];
$bi = $_POST['bi'];

// Validate input (you can add more validation as needed)
if(empty($place) || empty($date) || empty($time_id) || empty($hours) || empty($shift_id) || empty($qualification_id)) {
    // Handle validation errors
    die("Please fill in all required fields.");
}

// Insert into database
$db = DB::getInstance();
//$query = $db->query("INSERT INTO Bookings (place, date, hours, shift_id, qualification_id) VALUES (?, ?, ?, ?, ?)", [$place, $date, $hours, $shift_id, $qualification_id]);

if (!isNullOrEmptyString($id)) {

    $query = "UPDATE Bookings SET place = '$place', date = '$date', time_id = $time_id, hours = $hours, shift_id = $shift_id, qualification_id = $qualification_id WHERE booking_id = $id";
    if ($db->query($query)) {
        echo "success";
    } else {
        echo "Error assigning user: " . $db->error;
    }
} else {

    $fields = [
        'place' => $place,
        'date' => $date,
        'time_id' => $time_id,
        'hours' => $hours,
        'shift_id' => $shift_id,
        'qualification_id' => $qualification_id,
        'created_by_user_id' => $bi,
        'status_id' => 10
    ];

    if($db->insert('bookings', $fields)) {
        // Booking successful
        echo "success";
    } else {
        // Handle errors
        echo "Error creating booking: " . $db->error;
    }
}
?>
