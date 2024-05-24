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
?>