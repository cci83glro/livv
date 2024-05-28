<?php

$public = false;
$permissions = [2,3];
require_once __DIR__.'/../master-pages/master.php';

$dbo = dbo::getInstance();

$query = $dbo->query("SELECT * FROM (SELECT cm.id, cm.message, cm.user_id, DATE_FORMAT(cm.date_created, '%d.%m.%Y %H:%i') as date_created, CONCAT(u.fname, ' ', u.lname) as author FROM chat_messages cm INNER JOIN uacc u ON cm.user_id = u.id ORDER BY id DESC LIMIT 20) AS s ORDER BY id ASC");
$results = $query->fetchAll();

echo json_encode($results);

?>
