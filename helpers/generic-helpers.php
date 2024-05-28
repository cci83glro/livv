<?php

if (!function_exists('log_error')) {
  function log_error($text = "")
  {
    $dbo = dbo::getInstance();
    
    $fields = [
      'text' => $text
    ];

    $dbo->query("INSERT errors('text') VALUES(?)", $text);
    $lastId = $db->lastInsertID();

    return $lastId;
  }
}

if (!function_exists('isNullOrEmptyString')) {
  function isNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
  }
}

if(!function_exists("tokenHere")){
  function tokenHere(){
    ?>
    <input type="hidden" name="csrf" value="<?=Token::generate();?>">
    <?php
  }
}

if (!function_exists('ipCheck')) {
  function ipCheck()
  {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    return $ip;
  }
}

if (!function_exists('logger')) {
  function logger($user_id = "", $logtype = "", $lognote = "", $metadata = null)
  {
    global $user;
    $dbo = dbo::getInstance();

    if(!isset($user_id) || $user_id == ""){
      if(isset($user) && $user->isLoggedIn()){
        $user_id = $user->data()['id'];
      }else{
        $user_id = 0;
      }
    }
    if (is_array($lognote) || is_object($lognote)) {
      $lognote = json_encode($lognote);
    }
    if (is_array($metadata) || is_object($metadata)) {
      $metadata = json_encode($metadata);
    }

    $dbo->query('INSERT logs (`user_id`, `logdate`, `logtype`, `lognote`, `ip`, `metadata`)
    VALUES (?,?,?,?,?,?)', $user_id, date('Y-m-d H:i:s'), $logtype, $lognote, ipCheck(), $metadata);

    $lastId = $dbo->lastInsertID();

    return $lastId;
  }
}

if (!function_exists('currentPage')) {
  function currentPage()
  {
    $uri = $_SERVER['PHP_SELF'];
    $path = explode('/', $uri);
    $currentPage = end($path);

    return $currentPage;
  }
}

if (!function_exists('sessionValMessages')) {
  function sessionValMessages($valErr = [], $valSuc = [], $genMsg = [])
  {
    $keys = ['valErr', 'valSuc', 'genMsg'];
    foreach ($keys as $key) {
        if(isset($_SESSION[Config::get('session/session_name').$key])
        && is_array($_SESSION[Config::get('session/session_name').$key])
        && $$key != []
        && $$key != null
      ) {
        $_SESSION[Config::get('session/session_name').$key][] = $$key;
      } elseif (
        isset($_SESSION[Config::get('session/session_name').$key])
        && $_SESSION[Config::get('session/session_name').$key] != ''
        && $$key != []
        && $$key != null
      ) {
        $save = $_SESSION[Config::get('session/session_name').$key];
        $_SESSION[Config::get('session/session_name').$key] = [];
        $_SESSION[Config::get('session/session_name').$key][] = $save;
        $_SESSION[Config::get('session/session_name').$key][] = $$key;
      } elseif ($$key != [] && $$key != null) {
        $_SESSION[Config::get('session/session_name').$key] = $$key;
      }
    }
  }
}

if (!function_exists('randomstring')) {
  function randomstring($len)
  {
    $len = $len++;
    $string = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for ($i = 0; $i < $len; ++$i) {
      $string .= substr($chars, rand(0, strlen($chars)), 1);
    }

    return $string;
  }
}

if (!function_exists('random_password')) {
  function random_password($length = 16)
  {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?';
    $password = substr(str_shuffle($chars), 0, $length);

    return $password;
  }
}

if(!function_exists("usError")){
  function usError($msg){
    sessionValMessages($msg);
  }
}

if(!function_exists("usSuccess")){
  function usSuccess($msg){
    sessionValMessages("",$msg);
  }
}

if (!function_exists('display_errors')) {
  function display_errors($errors = [])
  {
    foreach ($errors as $k => $v) {
      if (array_key_exists($errors[$k][1], $errors)) {
        unset($errors[$k][1]);
      }
    }
    sessionValMessages($errors);
  }
}

?>