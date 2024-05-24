<?php

//require_once __DIR__.'/../users/init.php';

$abs_us_root=$_SERVER['DOCUMENT_ROOT'];

$self_path=explode("/", $_SERVER['PHP_SELF']);
$self_path_length=count($self_path);
$file_found=FALSE;

for($i = 1; $i < $self_path_length; $i++){
	array_splice($self_path, $self_path_length-$i, $i);
	$us_url_root=implode("/",$self_path)."/";

	if (file_exists($abs_us_root.$us_url_root.'z_us_root.php')){
		$file_found=TRUE;
		break;
	}else{
		$file_found=FALSE;
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
