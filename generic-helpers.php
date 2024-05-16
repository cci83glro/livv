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
?>