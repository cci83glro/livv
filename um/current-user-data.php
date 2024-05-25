<?php

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = dbo::getInstance()->query("SELECT * FROM users_session WHERE hash = ? AND uagent = ?",array($hash,Session::uagent_no_version()))->fetchAll();

	if (sizeof($hashCheck) > 0) {
		$user = new User($hashCheck[0]['user_id']);
		$user->login();
	}
}

$user = new User();

$user_id = 0;
$user_username = '';
$user_fname = '';
$user_lname = '';
$user_fullname = '';
$user_email = '';
$user_permission = 0;
$user_district_id = 0;

if(isset($user) && $user->isLoggedIn()){
 	// $user_id = $user->data()->id;
	// $user_username = $user->data()->username;
	// $user_fname = $user->data()->fname;
	// $user_lname = $user->data()->lname;
	// $user_fullname = $user_fname . ' ' . $user_lname;
	// $user_email = $user->data()->email;
	// $user_permission = $user->data()->permissions;
	// $user_district_id = $user->data()->district_id;

	$user_id = $user->data()['id'];
	$user_username = $user->data()['username'];
	$user_fname = $user->data()['fname'];
	$user_lname = $user->data()['lname'];
	$user_fullname = $user_fname . ' ' . $user_lname;
	$user_email = $user->data()['email'];
	$user_permission = $user->data()['permissions'];
	$user_district_id = $user->data()['district_id'];
}

?>
