<?php

$public = true;
require_once __DIR__.'/../master-pages/master.php';
$user = new User();
$user->logout();

Redirect::to($home_page_url);
?>
