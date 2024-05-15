<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

$users_page_url = $us_url_root."um/admin/users.php";
$user_page_url = $us_url_root."um/admin/user.php?id=";

$admin_email_list = [
	// 'kt@livvikar.dk',
	// 'eg@livvikar.dk',
	// 'ik@livvikar.dk',
	// 'dd@livvikar.dk'
	'ciprian_condurachi@yahoo.com',
	'cci83glro@gmail.com'
];

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

//include_once 'config/smtp.php';
include_once 'um/email-helpers.php';

if (!$public && !in_array($user_permission, $permissions)) {
    Redirect::to($us_url_root . 'index.php');
}

?>
