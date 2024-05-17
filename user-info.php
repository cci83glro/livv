<?php

require_once 'users/init.php';

$db = DB::getInstance();

$user_id = 0;
$user_username = '';
$user_permission = 0;
if(isset($user) && $user->isLoggedIn()){
 	$user_id = $user->data()->id;
	$user_username = $user->data()->username;
	$user_name = $user->data()->fname . ' ' . $user->data()->lname;
	$user_permission = $user->data()->permissions;
}

?>
