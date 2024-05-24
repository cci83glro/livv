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

    return $db->query($query)->results();
  }
}

if (!function_exists('getUsers')) {
  function getUsers($id = "")
  {    
    $query = "SELECT u.*, p.name as permission_name, d.district_name
    FROM users u 
    LEFT JOIN permissions p on u.permissions = p.id 
    LEFT JOIN districts d on u.district_id = d.district_id 
    WHERE 1=1";
    if (!isNullOrEmptyString($id)) {
      $query .= " AND u.id = " . $id;
    }

    return $db->query($query)->results();
  }
}

if (!function_exists('getAssignedUserDetailsForBooking')) {
  function getAssignedUserDetailsForBooking($id)
  {
    $query = "SELECT b.assigned_user_id, u.fname, u.lname, u.email from Bookings b INNER JOIN Users u ON b.assigned_user_id = u.id where booking_id = " . $id;
    return $db->query($query)->results();
  }
}

?>