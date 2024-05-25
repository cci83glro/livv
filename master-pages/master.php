<?php

$admin_email_list = [
	'kt@livvikar.dk',
	'eg@livvikar.dk',
	'ik@livvikar.dk',
	'dd@livvikar.dk',
	//'ciprian_condurachi@yahoo.com',
	//'cci83glro@gmail.com'
];

$company_name = "Liv-Vikar ApS";
$company_cvr = "44803003";
$company_address = "Lidsøvej, 2730 Herlev";
$company_address_google_url = "https://maps.app.goo.gl/aJnuGj3JGwNK5J5m9";
$company_phone = "11223344";
$company_phone_display = "1122 3344";
$company_contact_email = "kontakt@livvikar.dk";
$facebook_url = "facebook.com";
$instagram_url = "instagram.com";

$GLOBALS['config'] = array(
	'remember'        => array(
	'cookie_name'   => 'pEF9bfPrPfPSSDeIf2kK',
	'cookie_expiry' => 604800  //One week
	),
	'session' => array(
	'session_name' => 'XgE8It8SrULg6WAoRLGm',
	'token_name' => 'token',
	)
);

require_once __DIR__.'/../helpers/classes/Hash.php';
require_once __DIR__.'/../helpers/classes/Config.php';
require_once __DIR__.'/../helpers/roots.php';
require_once __DIR__.'/../helpers/classes/Redirect.php';
require_once __DIR__.'/../helpers/classes/Input.php';
require_once __DIR__.'/../helpers/classes/Session.php';
require_once __DIR__.'/../helpers/classes/Cookie.php';
require_once __DIR__.'/../helpers/classes/Token.php';
require_once __DIR__.'/../helpers/classes/User.php';
require_once __DIR__.'/../um/current-user-data.php';

if (!$public && !in_array($user_permission, $permissions)) {
    Redirect::to($us_url_root.'/um/login.php?redirect=' . $url);
}

$home_page_url = $us_url_root;
$login_page_url = $us_url_root."um/login.php";
$users_page_url = $us_url_root."um/admin/users.php";
$application_page_url = $us_url_root."applications/application.php?id=";
$user_page_url = $us_url_root."um/admin/user.php?id=";
$bookings_page_url = $us_url_root."bookings.php";


include_once __DIR__.'/../helpers/dbo.php';
require_once __DIR__.'/../helpers/classes/Validator.php';
include_once __DIR__.'/../helpers/db-helpers.php';
include_once __DIR__.'/../helpers/generic-helpers.php';
include_once __DIR__.'/../helpers/email-helpers.php';
$currentPage = currentPage();

$url_host = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
$url_uri = $_SERVER['REQUEST_URI'];
$url = $url_host . $url_uri;

?>
