<?php

class Redirect {

  public static function to($location = null, $args=''){
    global $us_url_root,$settings,$user;
    if ($location) {

      if($settings != "" && $settings->debug > 0){
        if($settings->debug == 2 || ($settings->debug == 1 && isset($user) && $user->isLoggedIn() && $user->data()->id == 1)){
          $cp = currentPage();
          $line = debug_backtrace();
          $line = $line[0]["line"];
          if(!isset($user) || !$user->isLoggedIn()){
            $loggingUserId = 0;
          }else{
            $loggingUserId = $user->data()->id;
          }
          $loc = Input::sanitize($location);
          logger($loggingUserId,"Redirect Diag","From $cp on line $line to $loc");
        }
        }


      if ($args) $location .= $args; // allows 'login.php?err=Error+Message' or the like
      if (!headers_sent()){
        header('Location: '.$location);
        exit();
      } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$location.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$location.'" />';
        echo '</noscript>'; exit;
      }
    }
  }
}
