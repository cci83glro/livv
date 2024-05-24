<?php

$public = false;
$permissions = [1,2,3];
require_once __DIR__.'/../master-pages/master.php';

// $page = $_GET['page']; // Current page number
// $records_per_page = $_GET['records_per_page']; // Number of records per page
// $offset = ($page - 1) * $records_per_page;

$where = " WHERE 1=1";
if ($user_permission == 1) {
    $where .= " AND b.district_id = " . $user_district_id;
} elseif ($user_permission == 3) {
    $where .= " AND b.status_id <> 5";
    if (isset($_GET['bi'])) {
        $where .= " AND b.assigned_user_id = " . $_GET['bi'];
    }
    if (isset($_GET['unassigned'])) {
        $where .= " AND b.assigned_user_id IS NULL";
    }
}

$bookingId = '';
if (isset($_GET['bookingId'])) {
    $bookingId = $_GET['bookingId'];
    if ($bookingId != '') {
        $where .= " AND b.booking_id = " . $bookingId;
    }
}

$query = $db->query(
    "SELECT b.*, d.district_name, t.time_id, t.time_value, s.shift_id, s.shift_name, q.qualification_id, q.qualification_name, CONCAT(uassigned.fname, ' ', uassigned.lname) as user_name, CONCAT(ucreated.fname, ' ', ucreated.lname) as created_by_name, ucreated.email as created_by_email
    FROM Bookings b
    INNER JOIN DIstricts d ON b.district_id = d.district_id
    INNER JOIN Shifts s ON b.shift_id = s.shift_id
    INNER JOIN Qualifications q ON b.qualification_id = q.qualification_id
    INNER JOIN Times t ON b.time_id = t.time_id
    LEFT JOIN Users uassigned ON b.assigned_user_id = uassigned.id
    LEFT JOIN Users ucreated ON b.created_by_user_id = ucreated.id" . $where . "
    ORDER BY date desc");
//$query = $db->query("SELECT * FROM Bookings LIMIT $offset, $records_per_page");
//$query = $db->query("SELECT * FROM Bookings LIMIT " . $offset . ", " . $records_per_page);
$results = $query->results();

echo json_encode($results);
?>