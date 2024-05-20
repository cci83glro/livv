<?php

require_once __DIR__.'/../users/init.php';

$user_id = 0;
$user_username = '';
$user_fname = '';
$user_lname = '';
$user_fullname = '';
$user_email = '';
$user_permission = 0;
$user_district_id = 0;

if(isset($user) && $user->isLoggedIn()){
 	$user_id = $user->data()->id;
	$user_username = $user->data()->username;
	$user_fname = $user->data()->fname;
	$user_lname = $user->data()->lname;
	$user_fullname = $user_fname . ' ' . $user_lname;
	$user_email = $user->data()->email;
	$user_permission = $user->data()->permissions;
	$user_district_id = $user->data()->district_id;
}

?>
