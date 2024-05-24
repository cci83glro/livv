<?php

require_once __DIR__.'/../master-pages/master.php';

$query = $dbo->query(
    "SELECT u.id, CONCAT(u.fname, ' ', u.lname) as name
    FROM livv.users u INNER JOIN user_permission_matches up ON u.id=up.user_id
    WHERE up.permission_id = 3");
$results = $query->results();

echo json_encode($results);
?>