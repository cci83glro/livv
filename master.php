<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

$users_page_url = $us_url_root."um/admin/users.php";
$user_page_url = $us_url_root."um/admin/user.php?id=";

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

if (!$public && !in_array($user_permission, $permissions)) {
    Redirect::to($us_url_root . 'index.php');
}

?>
