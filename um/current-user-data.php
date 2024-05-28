<?php

$cookieName = Config::get('remember/cookie_name');
$cookieExists = Cookie::exists($cookieName);
$sessionName = Config::get('session/session_name');
$sessionExists = Session::exists($sessionName);

$user = null;
if($cookieExists && !$sessionExists){
	$hash = Cookie::get($cookieName);
	$sessionUAgentNoVersion = Session::uagent_no_version();	
	$hashCheck = dbo::getInstance()->query("SELECT * FROM users_session WHERE hash = ? AND uagent = ?", $hash,$sessionUAgentNoVersion)->fetchAll();

	if (sizeof($hashCheck) > 0) {
		$user = new User($hashCheck[0]['user_id']);
		$user->login();
	}
}

$user_id = 0;
$user_username = '';
$user_fname = '';
$user_lname = '';
$user_fullname = '';
$user_email = '';
$user_permission = 0;
$user_district_id = 0;

if(isset($user) && $user->isLoggedIn()){
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
