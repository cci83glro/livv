<?php

$public = false;
$permissions = [1,2,3];
require_once __DIR__.'/../master-pages/master.php';

$dbo = dbo::getInstance();

// $page = $_GET['page']; // Current page number
// $records_per_page = $_GET['records_per_page']; // Number of records per page
// $offset = ($page - 1) * $records_per_page;

$where = " WHERE 1=1";
$order = "";

$bookingsType = Input::get('bookingsType');
if(!isNullOrEmptyString($bookingsType)){
    if ($bookingsType == 'coming') {
        $where .= " AND date > CURRENT_TIMESTAMP()";
    } else if ($bookingsType == 'passed') {
        $where .= " AND date <= CURRENT_TIMESTAMP()";
        $order = "ORDER BY DATE DESC, b.time_id DESC";
    } else if ($bookingsType == 'terminated') {
        $where .= " AND date <= CURRENT_TIMESTAMP()";
        $order = "ORDER BY DATE DESC, b.time_id DESC";
        $where .= " AND status_id = 20";
    } else if ($bookingsType == 'reports') {
        $order = "ORDER BY DATE ASC, b.time_id ASC";
        $where .= " AND status_id = 20";
    }
}

$districtId = Input::get('districtId');
if(!isNullOrEmptyString($districtId)){
    $where .= " AND b.district_id=" . $districtId;
}

$employeeId = Input::get('employeeId');
if(!isNullOrEmptyString($employeeId)){
    $where .= " AND b.assigned_user_id=" . $employeeId;
}

$qualificationId = Input::get('qualificationId');
if(!isNullOrEmptyString($qualificationId)){
    $where .= " AND b.qualification_id=" . $qualificationId;
}

$shiftId = Input::get('shiftId');
if(!isNullOrEmptyString($shiftId)){
    $where .= " AND b.shift_id=" . $shiftId;
}

$fromDate = Input::get('fromDate');
if(!isNullOrEmptyString($fromDate)){
    $where .= " AND b.date >= '" . $fromDate . "'";
}

$toDate = Input::get('toDate');
if(!isNullOrEmptyString($toDate)){
    $where .= " AND b.date <= '" . $toDate . "'";
}

$fromTimeId = Input::get('fromTime');
if(!isNullOrEmptyString($fromTimeId)){
    $where .= " AND b.time_id >= " . $fromTimeId;
}

$toTimeId = Input::get('toTime');
if(!isNullOrEmptyString($toTimeId)){
    $where .= " AND b.time_id <= '" . $toTimeId;
}

$searchText = Input::get('searchText');
if(!isNullOrEmptyString($searchText)){
    $where .= " AND (booking_id LIKE '%" . $searchText . "%' OR place LIKE '%" . $searchText . "%' OR uassigned.fname LIKE '%" . $searchText . "%' OR uassigned.lname LIKE '%" . $searchText . "%')";
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

$query = 
    "SELECT b.*, d.district_name, t.time_id, t.time_value, s.shift_id, s.shift_name, q.qualification_id, q.qualification_name, CONCAT(uassigned.fname, ' ', uassigned.lname) as user_name, CONCAT(ucreated.fname, ' ', ucreated.lname) as created_by_name, ucreated.email as created_by_email
    FROM bookings b
    INNER JOIN districts d ON b.district_id = d.district_id
    INNER JOIN shifts s ON b.shift_id = s.shift_id
    INNER JOIN qualifications q ON b.qualification_id = q.qualification_id
    INNER JOIN times t ON b.time_id = t.time_id
    LEFT JOIN uacc uassigned ON b.assigned_user_id = uassigned.id
    LEFT JOIN uacc ucreated ON b.created_by_user_id = ucreated.id" . $where . " " . $order;
//$query = $db->query("SELECT * FROM Bookings LIMIT $offset, $records_per_page");
//$query = $db->query("SELECT * FROM Bookings LIMIT " . $offset . ", " . $records_per_page);
$results = $dbo->query($query)->fetchAll();

echo json_encode($results);
?>