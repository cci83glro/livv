<?php

require_once '../users/init.php';

$page = $_GET['page']; // Current page number
$records_per_page = $_GET['records_per_page']; // Number of records per page

$offset = ($page - 1) * $records_per_page;

$db = DB::getInstance();
$query = $db->query("SELECT * FROM Bookings LIMIT $offset, $records_per_page");
//$query = $db->query("SELECT * FROM Bookings LIMIT " . $offset . ", " . $records_per_page);
$results = $query->results();

echo json_encode($results);
?>