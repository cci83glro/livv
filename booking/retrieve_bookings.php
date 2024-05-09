<?php

require_once '../users/init.php';

// $page = $_GET['page']; // Current page number
// $records_per_page = $_GET['records_per_page']; // Number of records per page

// $offset = ($page - 1) * $records_per_page;

$db = DB::getInstance();
$query = $db->query(
    "SELECT b.*, t.time_id, t.time_value, s.shift_id, s.shift_name, q.qualification_id, q.qualification_name, CONCAT(u.fname, ' ', u.lname) as user_name 
    FROM Bookings b
    INNER JOIN Shifts s ON b.shift_id = s.shift_id 
    INNER JOIN Qualifications q ON b.qualification_id = q.qualification_id 
    INNER JOIN Times t ON b.time_id = t.time_id
    LEFT JOIN Users u ON b.assigned_user_id = u.id
    ORDER BY booking_id desc");
//$query = $db->query("SELECT * FROM Bookings LIMIT $offset, $records_per_page");
//$query = $db->query("SELECT * FROM Bookings LIMIT " . $offset . ", " . $records_per_page);
$results = $query->results();

echo json_encode($results);
?>