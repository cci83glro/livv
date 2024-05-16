<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

$company_address = "Lidsøvej, 2730 Herlev";
$company_address_google_url = "https://maps.app.goo.gl/aJnuGj3JGwNK5J5m9";
$company_phone = "11223344";
$company_phone_display = "1122 3344";
$company_contact_email = "kontakt@livvikar.dk";
$facebook_url = "facebook.com";
$instagram_url = "instagram.com";


$users_page_url = $us_url_root."um/admin/users.php";
$user_page_url = $us_url_root."um/admin/user.php?id=";
$bookings_page_url = $us_url_root."bookings.php";

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

include_once 'generic-helpers.php';
include_once 'um/email-helpers.php';

$url_host = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
$url_uri = $_SERVER['REQUEST_URI'];
$url = $url_host . $url_uri;
if (!$public && !in_array($user_permission, $permissions)) {
    Redirect::to($us_url_root . 'um/login.php?redirect=' . $url);
}

?>
