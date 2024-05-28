<?php

$public = false;
$permissions = [1,2,3];
require_once __DIR__.'/../master-pages/master.php';

$dbo = dbo::getInstance();

// $page = $_GET['page']; // Current page number
// $records_per_page = $_GET['records_per_page']; // Number of records per page
// $offset = ($page - 1) * $records_per_page;

$where = " WHERE 1=1";

$search = Input::get('search');
if(!isNullOrEmptyString($search)){
    $where .= " AND (booking_id LIKE '%" . $search . "%' OR place LIKE '%" . $search . "%' OR district_name LIKE '%" . $search . "%' OR uassigned.fname LIKE '%" . $search . "%' OR uassigned.lname LIKE '%" . $search . "%')";
  }

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

$query = $dbo->query(
    "SELECT b.*, d.district_name, t.time_id, t.time_value, s.shift_id, s.shift_name, q.qualification_id, q.qualification_name, CONCAT(uassigned.fname, ' ', uassigned.lname) as user_name, CONCAT(ucreated.fname, ' ', ucreated.lname) as created_by_name, ucreated.email as created_by_email
    FROM bookings b
    INNER JOIN districts d ON b.district_id = d.district_id
    INNER JOIN shifts s ON b.shift_id = s.shift_id
    INNER JOIN qualifications q ON b.qualification_id = q.qualification_id
    INNER JOIN times t ON b.time_id = t.time_id
    LEFT JOIN uacc uassigned ON b.assigned_user_id = uassigned.id
    LEFT JOIN uacc ucreated ON b.created_by_user_id = ucreated.id" . $where . "
    ORDER BY date desc");
//$query = $db->query("SELECT * FROM Bookings LIMIT $offset, $records_per_page");
//$query = $db->query("SELECT * FROM Bookings LIMIT " . $offset . ", " . $records_per_page);
$results = $query->fetchAll();

echo json_encode($results);
?>