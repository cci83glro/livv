<?php

$public = false;
$permissions = [2,3];
require_once __DIR__.'/../master-pages/master.php';

$dbo = dbo::getInstance();

$query = $dbo->query("SELECT TOP 10 * FROM chat_messages ORDER BY date_created desc");
$results = $query->fetchAll();

echo json_encode($results);

?>
