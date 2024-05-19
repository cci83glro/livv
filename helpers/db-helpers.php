<?php

if (!function_exists('getDistricts')) {
  function getDistricts($id = "")
  {
    $db = DB::getInstance();
    
    $query = "SELECT * FROM districts";
    if (!isNullOrEmptyString($id)) {
      $query .= " WHERE district_id = " . $id;
    }

    return $db->query($query)->results();
  }
}

?>