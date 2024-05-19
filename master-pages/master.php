<?php

require_once __DIR__.'/../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

$company_address = "Lidsøvej, 2730 Herlev";
$company_address_google_url = "https://maps.app.goo.gl/aJnuGj3JGwNK5J5m9";
$company_phone = "11223344";
$company_phone_display = "1122 3344";
$company_contact_email = "kontakt@livvikar.dk";
$facebook_url = "facebook.com";
$instagram_url = "instagram.com";


$login_page_url = $us_url_root."um/login.php";
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

include_once __DIR__.'/../um/current-user-data.php';
include_once __DIR__.'/../helpers/generic-helpers.php';
include_once __DIR__.'/../helpers/db-helpers.php';
include_once __DIR__.'/../helpers/email-helpers.php';

$url_host = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
$url_uri = $_SERVER['REQUEST_URI'];
$url = $url_host . $url_uri;
if (!$public && !in_array($user_permission, $permissions)) {
    Redirect::to(__DIR__.'/../um/login.php?redirect=' . $url);
}

?>
