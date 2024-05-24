<?php

if (!function_exists('log_error')) {
  function log_error($text = "")
  {
    $db = DB::getInstance();
    
    $fields = [
      'text' => $text
    ];

    $db->insert('errors', $fields);
    $lastId = $db->lastId();

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
    global $user, $dbo;

    if(!isset($user_id) || $user_id == ""){
      if(isset($user) && $user->isLoggedIn()){
        $user_id = $user->data()->id;
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

    $dbo->query('INSERT INTO logs (`user_id`, `logdate`, `logtype`, `lognote`, `ip`, `metadata`)
    VALUES (?,?,?,?,?,?)', $user_id, date('Y-m-d H:i:s'), $logtype, $lognote, ipCheck(), $metadata);

    $lastId = $dbo->lastInsertID();

    return $lastId;
  }
}
?>