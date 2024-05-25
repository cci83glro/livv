<?php

$public = false;
$permissions = [1,2];
require_once __DIR__.'/../master-pages/master.php';

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

if (!isNullOrEmptyString($id)) {

    $query = "UPDATE Bookings SET ";
    if ($user_permission == 2) {
        $query .= "district_id = '$district_id', ";
    }
    $query .= "createdBy = '$createdBy', place = '$place', date = '$date', time_id = $time_id, hours = $hours, shift_id = $shift_id, qualification_id = $qualification_id WHERE booking_id = $id";
    if ($dbo->query($query)) {
        echo "success";
    } else {
        echo "Error assigning user: " . $dbo->error;
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

    $query = 'INSERT INTO bookings(district_id, createdBy, place, date, time_id, hours, shift_id, qualification_id, created_by_user_id, status_id) VALUES(?,?,?,?,?,?,?,?,?,?)';

    if($dbo->query($query, $district_id, $createdBy, $place, $date, $time_id, $hours, $shift_id, $qualification_id, $bi, $status_id)) {
        // Booking successful
        echo "success";
    } else {
        // Handle errors
        echo "Error creating booking: " . $dbo->error;
    }
}
?>
