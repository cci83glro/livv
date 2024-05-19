<?php

$public = false;
$permissions = [1,2,3];
require_once __DIR__.'/../um/current-user-data.php';

// $page = $_GET['page']; // Current page number
// $records_per_page = $_GET['records_per_page']; // Number of records per page

// $offset = ($page - 1) * $records_per_page;

$where = " ";
if ($user_permission == 1) {
    $where = " WHERE b.created_by_user_id = " . $user_id;
} elseif ($user_permission == 3) {
    if (isset($_GET['bi'])) {
        $where = " WHERE b.assigned_user_id = " . $_GET['bi'];
    }
    if (isset($_GET['unassigned'])) {
        $where = " WHERE b.assigned_user_id IS NULL";
    }
}

$db = DB::getInstance();
$query = $db->query(
    "SELECT b.*, d.district_name, t.time_id, t.time_value, s.shift_id, s.shift_name, q.qualification_id, q.qualification_name, CONCAT(u.fname, ' ', u.lname) as user_name 
    FROM Bookings b
    INNER JOIN DIstricts d ON b.district_id = d.district_id
    INNER JOIN Shifts s ON b.shift_id = s.shift_id
    INNER JOIN Qualifications q ON b.qualification_id = q.qualification_id
    INNER JOIN Times t ON b.time_id = t.time_id
    LEFT JOIN Users u ON b.assigned_user_id = u.id" . $where . "
    ORDER BY date desc");
//$query = $db->query("SELECT * FROM Bookings LIMIT $offset, $records_per_page");
//$query = $db->query("SELECT * FROM Bookings LIMIT " . $offset . ", " . $records_per_page);
$results = $query->results();

echo json_encode($results);
?>