<?php

$public = false;
$permissions = [1,2,3];

if (!function_exists('getDistricts')) {
  function getDistricts($id = "")
  {    
    $query = "SELECT * FROM districts";
    if (!isNullOrEmptyString($id)) {
      $query .= " WHERE district_id = " . $id;
    }

    return dbo::getInstance()->query($query)->fetchAll();
  }
}

if (!function_exists('getUsers')) {
  function getUsers($dbo, $options)
  {    
    $query = "SELECT u.*, p.name as permission_name, d.district_name
    FROM uacc u 
    LEFT JOIN permissions p on u.permissions = p.id 
    LEFT JOIN districts d on u.district_id = d.district_id 
    WHERE 1=1";

    if(isset($options["id"]) && !isNullOrEmptyString($options["id"])) {
      $query .= " AND u.id = " . $options["id"];
    }
    if(isset($options["permission"]) && !isNullOrEmptyString($options["permission"])) {
      $query .= " AND u.permissions IN " . $options["permission"];
    }
    if(isset($options["active"]) && !isNullOrEmptyString($options["active"])) {
      $query .= " AND u.active = " . $options["active"];
    }

    return $dbo->query($query)->fetchAll();
  }
}

if (!function_exists('getAssignedUserDetailsForBooking')) {
  function getAssignedUserDetailsForBooking($id)
  {
    $query = "SELECT b.assigned_user_id, u.fname, u.lname, u.email from bookings b INNER JOIN uacc u ON b.assigned_user_id = u.id where booking_id = " . $id;
    return dbo::getInstance()->query($query)->fetchAll();
  }
}

?>