<?php

$public = true;
require_once __DIR__.'/../master-pages/master.php';
$user = new User();

if(file_exists(__DIR__.'/../usersc/scripts/just_before_logout.php')){
	require_once __DIR__.'/../usersc/scripts/just_before_logout.php';
}else{
	//Feel free to change where the user goes after logout!
}
$user->logout();
if(file_exists(__DIR__.'/../usersc/scripts/just_after_logout.php')){
	require_once __DIR__.'/../usersc/scripts/just_after_logout.php';
}else{
	Redirect::to(__DIR__.'/../index.php');
}
?>
