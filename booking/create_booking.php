<?php

$public = false;
$permissions = [1,2];
require_once __DIR__.'/../helpers/generic-helpers.php';
require_once __DIR__.'/../um/current-user-data.php';

// Retrieve form data
$id = $_POST['id'];
$district_id = $_POST['district'];
$createdBy = $_POST['createdBy'];
$place = $_POST['place'];
$date = $_POST['date'];
$time_id = $_POST['time'];
$hours = $_POST['hours'];
$shift_id = $_POST['shift'];
$qualification_id = $_POST['qualification'];
$bi = $_POST['bi'];

// Validate input (you can add more validation as needed)
if(($user_permission == 2 && empty($district_id)) || empty($place) || empty($date) || empty($time_id) || empty($hours) || empty($shift_id) || empty($qualification_id)) {
    // Handle validation errors
    die("Please fill in all required fields.");
}

$db = DB::getInstance();
//$query = $db->query("INSERT INTO Bookings (place, date, hours, shift_id, qualification_id) VALUES (?, ?, ?, ?, ?)", [$place, $date, $hours, $shift_id, $qualification_id]);

if (!isNullOrEmptyString($id)) {

    $query = "UPDATE Bookings SET ";
    if ($user_permission == 2) {
        $query .= "district_id = '$district_id', ";
    }
    $query .= "createdBy = '$createdBy', place = '$place', date = '$date', time_id = $time_id, hours = $hours, shift_id = $shift_id, qualification_id = $qualification_id WHERE booking_id = $id";
    if ($db->query($query)) {
        echo "success";
    } else {
        echo "Error assigning user: " . $db->error;
    }
} else {

    $status_id = 5;
    if ($user_permission == 2) {
        $status_id = 10;
    }

    $fields = [
        'district_id' => $district_id,
        'createdBy' => $createdBy,
        'place' => $place,
        'date' => $date,
        'time_id' => $time_id,
        'hours' => $hours,
        'shift_id' => $shift_id,
        'qualification_id' => $qualification_id,
        'created_by_user_id' => $bi,
        'status_id' => $status_id
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
