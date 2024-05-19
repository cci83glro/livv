<?php

require_once __DIR__.'/../users/init.php';

$user_id = 0;
$user_username = '';
$user_permission = 0;
$user_district_id = 0;
if(isset($user) && $user->isLoggedIn()){
 	$user_id = $user->data()->id;
	$user_username = $user->data()->username;
	$user_name = $user->data()->fname . ' ' . $user->data()->lname;
	$user_permission = $user->data()->permissions;
	$user_district_id = $user->data()->district_id;
}

?>
