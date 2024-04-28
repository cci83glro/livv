<?php

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

$user_id = 0;
if(isset($user) && $user->isLoggedIn()){
	$user_id = $user->data()->id;
}

?>
