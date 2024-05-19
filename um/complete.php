<?php

$public = true;
require_once __DIR__.'/../master-pages/header.php';

$action = Input::get('action');
if($action == "thank_you_verify"){
    require $abs_us_root.$us_url_root.'um/views/_joinThankYou_verify.php';
}elseif($action == "thank_you_join"){
    require_once $abs_us_root.$us_url_root.'um/views/_joinThankYou.php';
}elseif($action == "thank_you"){
    require $abs_us_root.$us_url_root.'um/views/_joinThankYou.php';
}

require_once __DIR__.'/../master-pages/footer.php';
